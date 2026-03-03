<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BibliographicRecord;
use App\Models\MarcTagDefinition;
use App\Models\MarcFramework;
use App\Models\DocumentType;
use App\Models\StorageLocation;
use App\Models\BookItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcBookController extends Controller
{
    protected $barcodeService;

    public function __construct(\App\Services\BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    public function index()
    {
        $records = BibliographicRecord::with('fields.subfields')->paginate(10);
        return view('admin.marc_books.index', compact('records'));
    }

    public function create(Request $request)
    {
        $frameworks = MarcFramework::where('is_active', true)->get();
        $frameworkId = $request->query('framework_id');

        if (!$frameworkId && $frameworks->isNotEmpty()) {
            // Default to STANDARD if available, else first
            $standard = $frameworks->where('code', 'STANDARD')->first();
            $frameworkId = $standard ? $standard->id : $frameworks->first()->id;
        }

        $currentFramework = MarcFramework::find($frameworkId);
        
        // Fetch visible tags for the selected framework via pivot table
        $definitions = $currentFramework ? $currentFramework->tags()
            ->with(['subfields' => function ($q) {
                $q->where('is_visible', true)->orderBy('code');
            }])
            ->wherePivot('is_visible', true)
            ->get() : collect();

        $documentTypes = DocumentType::active()->ordered()->get();
        $locations = StorageLocation::where('is_active', true)->with('branch')->get();

        return view('admin.marc_books.create', compact('definitions', 'documentTypes', 'locations', 'frameworks', 'frameworkId'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Handle cover image upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('covers', 'public');
            }

            // Create bibliographic record with metadata
            $record = BibliographicRecord::create([
                'leader' => '00000nam a2200000 i 4500',
                'cover_image' => $coverImagePath,
                'record_type' => $request->input('record_type', 'book'),
                'status' => $request->input('status', BibliographicRecord::STATUS_PENDING),
                'framework' => $request->input('framework', 'STANDARD'),
                'subject_category' => $request->input('subject_category'),
                'serial_frequency' => $request->input('serial_frequency'),
                'date_type' => $request->input('date_type'),
                'acquisition_method' => $request->input('acquisition_method'),
                'document_format' => $request->input('document_format'),
                'cataloging_standard' => $request->input('cataloging_standard'),
            ]);

            // Process MARC fields
            $fields = $request->input('fields', []);
            $sequence = 0;

            foreach ($fields as $tag => $data) {
                $subfieldEntries = $data['subfields'] ?? [];
                $hasData = false;
                foreach($subfieldEntries as $entry) {
                    if(!empty($entry['code']) && !empty($entry['value'])) {
                        $hasData = true;
                        break;
                    }
                }

                if (!$hasData) continue;

                $marcField = $record->fields()->create([
                    'tag' => $tag,
                    'indicator1' => $data['ind1'] ?? ' ',
                    'indicator2' => $data['ind2'] ?? ' ',
                    'sequence' => $sequence++
                ]);

                foreach ($subfieldEntries as $entry) {
                    if (!empty($entry['code']) && !empty($entry['value'])) {
                        $marcField->subfields()->create([
                            'code' => $entry['code'],
                            'value' => $entry['value']
                        ]);
                    }
                }
            }

            // Process distribution items
            $items = $request->input('items', []);
            foreach ($items as $itemData) {
                if (!empty($itemData['document_type_id']) && !empty($itemData['storage_location_id'])) {
                    $quantity = (int) ($itemData['quantity'] ?? 1);
                    
                    for ($i = 0; $i < $quantity; $i++) {
                        BookItem::create([
                            'bibliographic_record_id' => $record->id,
                            'document_type_id' => $itemData['document_type_id'],
                            'storage_location_id' => $itemData['storage_location_id'],
                            'barcode' => $this->barcodeService->getNextCode('item'),
                            'accession_number' => $this->generateAccessionNumber(),
                            'quantity' => 1,
                            'status' => 'available'
                        ]);
                        
                        // Increment counter if service is used
                        $this->barcodeService->incrementCounter('item', $itemData['barcode'] ?? '');
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.marc.book')->with('success', __('Record_Created_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error while cataloging: ' . $e->getMessage())->withInput();
        }
    }

    private function generateBarcode()
    {
        return $this->barcodeService->getNextCode('item');
    }

    private function generateAccessionNumber()
    {
        return 'ACC' . date('Y') . str_pad(BookItem::count() + 1, 6, '0', STR_PAD_LEFT);
    }

    public function show(BibliographicRecord $record)
    {
        $record->load('fields.subfields');
        // Fetch definitions to show human-readable labels in the review page
        $definitions = MarcTagDefinition::with('subfields')->get()->keyBy('tag');
        
        return view('admin.marc_books.show', compact('record', 'definitions'));
    }

    public function updateStatus(Request $request, BibliographicRecord $record)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved',
            'framework' => 'nullable|string',
            'subject_category' => 'nullable|string',
            'record_type' => 'nullable|string',
            'serial_frequency' => 'nullable|string',
            'date_type' => 'nullable|string',
            'acquisition_method' => 'nullable|string',
            'document_format' => 'nullable|string',
            'cataloging_standard' => 'nullable|string',
        ]);

        $record->update($validated);

        return back()->with('success', __('Status_Updated_Successfully'));
    }

    public function edit(BibliographicRecord $record)
    {
        $record->load('fields.subfields');
        $framework = MarcFramework::where('code', $record->framework)->first();
        
        $definitions = $framework ? $framework->tags()
            ->with(['subfields' => function($q) {
                $q->where('is_visible', true)->orderBy('code');
            }])
            ->wherePivot('is_visible', true)
            ->get() : collect();
        return view('admin.marc_books.edit', compact('record', 'definitions'));
    }

    public function update(Request $request, BibliographicRecord $record)
    {
        DB::beginTransaction();
        try {
            $leader = $record->leader;
            if (isset($leader[5]) && $leader[5] === 'n') {
                $leader[5] = 'c';
            }
            $record->update(['leader' => $leader]);

            $fields = $request->input('fields', []);
            $sequence = 0;

            // Track IDs to delete later
            $submittedFieldTags = [];
            $submittedSubfieldIds = [];

            foreach ($fields as $tag => $data) {
                $subfieldEntries = $data['subfields'] ?? [];
                
                // Check if the tag has any valid content
                $hasValidSubfields = false;
                foreach ($subfieldEntries as $entry) {
                    if (!empty($entry['code']) && !empty($entry['value'])) {
                        $hasValidSubfields = true;
                        break;
                    }
                }

                if (!$hasValidSubfields) continue;

                $submittedFieldTags[] = $tag;

                // 1. Update or Create MarcField
                $marcField = $record->fields()->updateOrCreate(
                    ['tag' => $tag],
                    [
                        'indicator1' => $data['ind1'] ?? ' ',
                        'indicator2' => $data['ind2'] ?? ' ',
                        'sequence' => $sequence++
                    ]
                );

                // 2. Handle Subfields of this field
                foreach ($subfieldEntries as $entry) {
                    if (!empty($entry['code']) && !empty($entry['value'])) {
                        $subfield = $marcField->subfields()->updateOrCreate(
                            ['id' => $entry['id'] ?? null],
                            [
                                'code' => $entry['code'],
                                'value' => $entry['value']
                            ]
                        );
                        $submittedSubfieldIds[] = $subfield->id;
                    }
                }

                // Delete subfields of this field that were NOT submitted
                $marcField->subfields()->whereNotIn('id', $submittedSubfieldIds)->delete();
            }

            // Delete fields (Tags) that were NOT submitted or are now empty
            $record->fields()->whereNotIn('tag', $submittedFieldTags)->delete();

            DB::commit();
            return redirect()->route('admin.marc.book')->with('success', __('Record_Updated_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error while updating: ' . $e->getMessage())->withInput();
        }
    }
}
