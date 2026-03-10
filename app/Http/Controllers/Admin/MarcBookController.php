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

    public function index(Request $request)
    {
        $query = BibliographicRecord::with('fields.subfields');
        
        // Advanced search filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Search in title (245$a)
                $q->whereHas('fields', function ($fieldQuery) use ($searchTerm) {
                    $fieldQuery->where('tag', '245')
                        ->whereHas('subfields', function ($subfieldQuery) use ($searchTerm) {
                            $subfieldQuery->where('code', 'a')
                                ->where('value', 'like', '%' . $searchTerm . '%');
                        });
                })
                // Search in author (100$a)
                ->orWhereHas('fields', function ($fieldQuery) use ($searchTerm) {
                    $fieldQuery->where('tag', '100')
                        ->whereHas('subfields', function ($subfieldQuery) use ($searchTerm) {
                            $subfieldQuery->where('code', 'a')
                                ->where('value', 'like', '%' . $searchTerm . '%');
                        });
                })
                // Search in ISBN (020$a)
                ->orWhereHas('fields', function ($fieldQuery) use ($searchTerm) {
                    $fieldQuery->where('tag', '020')
                        ->whereHas('subfields', function ($subfieldQuery) use ($searchTerm) {
                            $subfieldQuery->where('code', 'a')
                                ->where('value', 'like', '%' . $searchTerm . '%');
                        });
                })
                // Search in publisher (260$b)
                ->orWhereHas('fields', function ($fieldQuery) use ($searchTerm) {
                    $fieldQuery->where('tag', '260')
                        ->whereHas('subfields', function ($subfieldQuery) use ($searchTerm) {
                            $subfieldQuery->where('code', 'b')
                                ->where('value', 'like', '%' . $searchTerm . '%');
                        });
                })
                // Search in subject (650$a)
                ->orWhereHas('fields', function ($fieldQuery) use ($searchTerm) {
                    $fieldQuery->where('tag', '650')
                        ->whereHas('subfields', function ($subfieldQuery) use ($searchTerm) {
                            $subfieldQuery->where('code', 'a')
                                ->where('value', 'like', '%' . $searchTerm . '%');
                        });
                })
                // Search in notes (500$a)
                ->orWhereHas('fields', function ($fieldQuery) use ($searchTerm) {
                    $fieldQuery->where('tag', '500')
                        ->whereHas('subfields', function ($subfieldQuery) use ($searchTerm) {
                            $subfieldQuery->where('code', 'a')
                                ->where('value', 'like', '%' . $searchTerm . '%');
                        });
                });
            });
        }
        
        // Filter by framework
        if ($request->filled('framework')) {
            $query->where('framework', $request->framework);
        }
        
        // Filter by record type
        if ($request->filled('record_type')) {
            $query->where('record_type', $request->record_type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by subject category
        if ($request->filled('subject_category')) {
            $query->where('subject_category', $request->subject_category);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by specific MARC tag
        if ($request->filled('marc_tag') && $request->filled('marc_value')) {
            $query->whereHas('fields', function ($fieldQuery) use ($request) {
                $fieldQuery->where('tag', $request->marc_tag)
                    ->whereHas('subfields', function ($subfieldQuery) use ($request) {
                        $subfieldQuery->where('value', 'like', '%' . $request->marc_value . '%');
                    });
            });
        }
        
        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'title':
                $query->orderBy(function ($q) {
                    $q->selectRaw('COALESCE(
                        (SELECT value FROM marc_subfields 
                         JOIN marc_fields ON marc_subfields.marc_field_id = marc_fields.id 
                         WHERE marc_fields.bibliographic_record_id = bibliographic_records.id 
                         AND marc_fields.tag = "245" AND marc_subfields.code = "a" 
                         LIMIT 1), "") as title');
                }, $sortOrder);
                break;
            case 'author':
                $query->orderBy(function ($q) {
                    $q->selectRaw('COALESCE(
                        (SELECT value FROM marc_subfields 
                         JOIN marc_fields ON marc_subfields.marc_field_id = marc_fields.id 
                         WHERE marc_fields.bibliographic_record_id = bibliographic_records.id 
                         AND marc_fields.tag = "100" AND marc_subfields.code = "a" 
                         LIMIT 1), "") as author');
                }, $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }
        
        $records = $query->paginate(10)->withQueryString();
        
        // Get search filters data
        $frameworks = MarcFramework::where('is_active', true)->pluck('code', 'code');
        $recordTypes = BibliographicRecord::distinct()->pluck('record_type');
        $subjectCategories = BibliographicRecord::distinct()->pluck('subject_category');
        $commonMarcTags = ['245', '100', '020', '260', '650', '500', '082', '852'];
        
        return view('admin.marc_books.index', compact(
            'records', 
            'frameworks', 
            'recordTypes', 
            'subjectCategories',
            'commonMarcTags'
        ));
    }

    public function form(Request $request, ?BibliographicRecord $record = null)
    {
        if ($record) {
            $record->load('fields.subfields', 'items');
        }

        $frameworks = MarcFramework::where('is_active', true)->get();
        $frameworkId = $record
            ? optional($frameworks->firstWhere('code', $record->framework))->id
            : $request->query('framework_id');

        if (!$frameworkId && !$record && $frameworks->isNotEmpty()) {
            // Default to STANDARD if available, else first
            $standard = $frameworks->where('code', 'STANDARD')->first();
            $frameworkId = $standard ? $standard->id : $frameworks->first()->id;
        }

        $currentFramework = MarcFramework::find($frameworkId);

        if ($record) {
            // In edit mode: keep framework-visible tags, but always include exact tag/subfield
            // definitions already used by the record so labels resolve correctly from DB.
            $frameworkDefinitions = $currentFramework ? $currentFramework->tags()
                ->with(['subfields' => function ($q) {
                    $q->orderBy('code');
                }])
                ->wherePivot('is_visible', true)
                ->get() : collect();

            $recordTags = $record->fields->pluck('tag')->unique()->values();
            $recordDefinitions = MarcTagDefinition::whereIn('tag', $recordTags)
                ->with(['subfields' => function ($q) {
                    $q->orderBy('code');
                }])
                ->get();

            $definitionsByTag = $frameworkDefinitions->keyBy('tag');
            foreach ($recordDefinitions as $recordDefinition) {
                if ($definitionsByTag->has($recordDefinition->tag)) {
                    $existing = $definitionsByTag->get($recordDefinition->tag);
                    $mergedSubfields = $existing->subfields
                        ->keyBy(fn ($s) => strtolower(trim((string) $s->code)))
                        ->union($recordDefinition->subfields->keyBy(fn ($s) => strtolower(trim((string) $s->code))))
                        ->values();
                    $existing->setRelation('subfields', $mergedSubfields);
                    $definitionsByTag->put($recordDefinition->tag, $existing);
                } else {
                    $definitionsByTag->put($recordDefinition->tag, $recordDefinition);
                }
            }
            $definitions = $definitionsByTag->values();
        } else {
            // Create mode: only show visible tags/subfields configured for the selected framework.
            $definitions = $currentFramework ? $currentFramework->tags()
                ->with(['subfields' => function ($q) {
                    $q->where('is_visible', true)->orderBy('code');
                }])
                ->wherePivot('is_visible', true)
                ->get() : collect();
        }

        $documentTypes = DocumentType::active()->ordered()->get();
        $locations = StorageLocation::where('is_active', true)->with('branch')->get();
        $branches = \App\Models\Branch::with('storageLocations')->where('is_active', true)->get();
        $nextBarcode = $this->barcodeService->previewNextBarcode('item');

        if ($record) {
            $record->load('items.branch', 'items.storageLocation');
            return view('admin.marc_books.edit', compact('record', 'definitions', 'frameworks', 'documentTypes', 'locations', 'frameworkId', 'branches', 'nextBarcode'));
        }

        return view('admin.marc_books.create', compact('definitions', 'documentTypes', 'locations', 'frameworks', 'frameworkId', 'branches', 'nextBarcode'));
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
                if (!empty($itemData['storage_location_id'])) {
                    $itemPayload = [
                        'bibliographic_record_id' => $record->id,
                        'branch_id' => $itemData['branch_id'] ?? null,
                        'storage_location_id' => $itemData['storage_location_id'],
                        'barcode' => $itemData['barcode'] ?? null,
                        'accession_number' => $itemData['accession_number'] ?? $this->generateAccessionNumber(),
                        'storage_type' => $itemData['storage_type'] ?? 'Book',
                        'quantity' => $itemData['quantity'] ?? 1,
                        'status' => $itemData['status'] ?? 'available',
                        'order_code' => $itemData['order_code'] ?? null,
                        'waits_for_print' => isset($itemData['waits_for_print']) ? (bool)$itemData['waits_for_print'] : false,
                        'notes' => $itemData['notes'] ?? null,
                        'volume_issue' => $itemData['volume_issue'] ?? null,
                        'day' => $itemData['day'] ?? null,
                        'month_season' => $itemData['month_season'] ?? null,
                        'year' => $itemData['year'] ?? null,
                        'shelf' => $itemData['shelf'] ?? null,
                        'shelf_position' => $itemData['shelf_position'] ?? null,
                        'location' => $itemData['location'] ?? null,
                        'temporary_location' => $itemData['temporary_location'] ?? null,
                    ];

                    if (empty($itemPayload['barcode'])) {
                        $itemPayload['barcode'] = $this->barcodeService->getNextCode('item');
                        $this->barcodeService->incrementCounter('item', $itemPayload['barcode']);
                    }

                    BookItem::create($itemPayload);
                }
            }

            DB::commit();
            $tab = $request->input('tab', 0);
            return redirect()->route('admin.marc.book.form', ['record' => $record->id, 'tab' => $tab])->with('success', __('Record_Created_Successfully'));

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
                    if (!empty($entry['code']) && (!empty($entry['value']) || $entry['value'] === '0')) {
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

                $fieldSubfieldIds = [];
                // 2. Handle Subfields of this field
                foreach ($subfieldEntries as $entry) {
                    if (!empty($entry['code']) && (!empty($entry['value']) || $entry['value'] === '0')) {
                        $subfield = $marcField->subfields()->updateOrCreate(
                            ['id' => $entry['id'] ?? null],
                            [
                                'code' => $entry['code'],
                                'value' => $entry['value']
                            ]
                        );
                        $fieldSubfieldIds[] = $subfield->id;
                    }
                }

                // Delete subfields of this field that were NOT submitted
                $marcField->subfields()->whereNotIn('id', $fieldSubfieldIds)->delete();
            }

            // Delete fields (Tags) that were NOT submitted or are now empty
            $record->fields()->whereNotIn('tag', $submittedFieldTags)->delete();

            // Process distribution items (Add, Update, Delete)
            $items = $request->input('items', []);
            $submittedItemIds = [];
            
            foreach ($items as $itemData) {
                if (!empty($itemData['storage_location_id'])) {
                    $itemPayload = [
                        'branch_id' => $itemData['branch_id'] ?? null,
                        'storage_location_id' => $itemData['storage_location_id'],
                        'barcode' => $itemData['barcode'] ?? null,
                        'accession_number' => $itemData['accession_number'] ?? $this->generateAccessionNumber(),
                        'storage_type' => $itemData['storage_type'] ?? 'Book',
                        'quantity' => $itemData['quantity'] ?? 1,
                        'status' => $itemData['status'] ?? 'available',
                        'order_code' => $itemData['order_code'] ?? null,
                        'waits_for_print' => isset($itemData['waits_for_print']) ? (bool)$itemData['waits_for_print'] : false,
                        'notes' => $itemData['notes'] ?? null,
                        'volume_issue' => $itemData['volume_issue'] ?? null,
                        'day' => $itemData['day'] ?? null,
                        'month_season' => $itemData['month_season'] ?? null,
                        'year' => $itemData['year'] ?? null,
                        'shelf' => $itemData['shelf'] ?? null,
                        'shelf_position' => $itemData['shelf_position'] ?? null,
                        'location' => $itemData['location'] ?? null,
                        'temporary_location' => $itemData['temporary_location'] ?? null,
                    ];

                    // Update existing
                    if (!empty($itemData['id'])) {
                        $bookItem = BookItem::find($itemData['id']);
                        if ($bookItem && $bookItem->bibliographic_record_id == $record->id) {
                            $bookItem->update($itemPayload);
                            $submittedItemIds[] = $bookItem->id;
                        }
                    } else {
                        // Create new
                        $itemPayload['bibliographic_record_id'] = $record->id;
                        if (empty($itemPayload['barcode'])) {
                            $itemPayload['barcode'] = $this->barcodeService->getNextCode('item');
                            $this->barcodeService->incrementCounter('item', $itemPayload['barcode']);
                        }
                        
                        $newItem = BookItem::create($itemPayload);
                        $submittedItemIds[] = $newItem->id;
                    }
                }
            }
            
            // Note: In typical library systems, deleting cataloged items might be restricted 
            // if they are on loan. We assume deletion is allowed here for items removed from UI.
            // Be careful with cascading deletes.
            $record->items()->whereNotIn('id', $submittedItemIds)->delete();

            DB::commit();
            $tab = $request->input('tab', 0);
            return redirect()->route('admin.marc.book.form', ['record' => $record->id, 'tab' => $tab])->with('success', __('Record_Updated_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error while updating: ' . $e->getMessage())->withInput();
        }
    }
}
