<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BibliographicRecord;
use App\Models\MarcTagDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcBookController extends Controller
{
    public function index()
    {
        $records = BibliographicRecord::with('fields.subfields')->paginate(10);
        return view('admin.marc_books.index', compact('records'));
    }

    public function create()
    {
        // Fetch visible tags with their subfields for the form
        $definitions = MarcTagDefinition::with([
            'subfields' => function ($q) {
                $q->where('is_visible', true)->orderBy('code');
            }
        ])
            ->where('is_visible', true)
            ->orderBy('tag')
            ->get();

        return view('admin.marc_books.create', compact('definitions'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = BibliographicRecord::create([
                'leader' => '00000nam a2200000 i 4500', // Default MARC21 leader
                'record_type' => 'book',
                'status' => BibliographicRecord::STATUS_PENDING
            ]);

            $fields = $request->input('fields', []);
            $sequence = 0;

            foreach ($fields as $tag => $data) {
                // Determine if there's any active subfield data before creating the field
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

            DB::commit();
            return redirect()->route('admin.marc.book')->with('success', __('Record_Created_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error while cataloging: ' . $e->getMessage())->withInput();
        }
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
        $definitions = MarcTagDefinition::with(['subfields' => function($q) {
            $q->where('is_visible', true)->orderBy('code');
        }])
        ->where('is_visible', true)
        ->orderBy('tag')
        ->get();
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
