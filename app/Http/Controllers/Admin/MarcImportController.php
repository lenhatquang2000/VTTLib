<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BibliographicRecord;
use App\Models\MarcFramework;
use App\Models\MarcTagDefinition;
use App\Models\MarcSubfieldDefinition;
use App\Models\DocumentType;
use App\Models\StorageLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Imports\MarcRecordsImport;
use App\Exports\MarcTemplateExport;

class MarcImportController extends Controller
{
    /**
     * Display import form
     */
    public function index()
    {
        $frameworks = MarcFramework::where('is_active', true)->get();
        $documentTypes = DocumentType::where('is_active', true)->get();
        $storageLocations = StorageLocation::where('is_active', true)->get();

        return view('admin.marc_import.index', compact(
            'frameworks',
            'documentTypes',
            'storageLocations'
        ));
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate(Request $request)
    {
        $frameworkId = $request->get('framework_id');
        $framework = MarcFramework::find($frameworkId);

        return Excel::download(
            new MarcTemplateExport($framework),
            'marc_import_template_' . $framework->code . '.xlsx'
        );
    }

    /**
     * Upload and validate Excel file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
            'framework_id' => 'required|exists:marc_frameworks,id',
            'action_type' => 'required|in:create,update'
        ]);

        try {
            $file = $request->file('excel_file');
            $framework = MarcFramework::find($request->framework_id);

            // Store file temporarily
            $filePath = $file->store('temp/imports', 'local');

            // Import and validate
            $import = new MarcRecordsImport($framework, $request->action_type);
            Excel::import($import, $filePath, 'local');

            // Get results
            $results = $import->getResults();

            // Clean up temp file
            Storage::disk('local')->delete($filePath);

            return response()->json([
                'success' => true,
                'message' => __('File processed successfully'),
                'data' => [
                    'total_rows' => $results['total_rows'],
                    'valid_rows' => $results['valid_rows'],
                    'invalid_rows' => $results['invalid_rows'],
                    'errors' => $results['errors'],
                    'preview' => $results['preview'],
                    'valid_data' => $results['valid_data']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Error processing file: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process confirmed import
     */
    public function process(Request $request)
    {
        $request->validate([
            'framework_id' => 'required|exists:marc_frameworks,id',
            'action_type' => 'required|in:create,update',
            'validated_data' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $framework = MarcFramework::find($request->framework_id);
            $results = [];

            foreach ($request->validated_data as $rowIndex => $data) {
                try {
                    if ($request->action_type === 'create') {
                        $record = $this->createMarcRecord($data, $framework);
                    } else {
                        $record = $this->updateMarcRecord($data, $framework);
                    }

                    $results[] = [
                        'row_index' => $rowIndex,
                        'success' => true,
                        'record_id' => $record->id,
                        'title' => $this->extractTitle($record)
                    ];
                } catch (\Exception $e) {
                    $results[] = [
                        'row_index' => $rowIndex,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('Import completed successfully'),
                'data' => $results
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => __('Import failed: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new MARC record from Excel data
     */
    protected function createMarcRecord(array $data, MarcFramework $framework)
    {
        $record = BibliographicRecord::create([
            'framework' => $framework->code,
            'leader' => $this->generateLeader($data, $framework),
            'status' => 'pending',
            'record_type' => $data['record_type'] ?? 'book',
            'subject_category' => $data['subject_category'] ?? 'General',
            'serial_frequency' => $data['serial_frequency'] ?? 'unknown',
            'date_type' => $data['date_type'] ?? 'bc',
            'acquisition_method' => $data['acquisition_method'] ?? 'untraced',
            'document_format' => $data['document_format'] ?? 'none',
            'cataloging_standard' => $data['cataloging_standard'] ?? 'AACR2'
        ]);

        // Create MARC fields and subfields
        $this->createMarcFields($record, $data, $framework);

        return $record;
    }

    /**
     * Update existing MARC record
     */
    protected function updateMarcRecord(array $data, MarcFramework $framework)
    {
        // Find existing record by identifier (title + author + ISBN)
        $record = $this->findExistingRecord($data);

        if (!$record) {
            throw new \Exception(__('Record not found for update'));
        }

        // Update record metadata
        $record->update([
            'leader' => $this->generateLeader($data, $framework),
            'status' => 'pending'
        ]);

        // Update MARC fields
        $record->fields()->delete();
        $this->createMarcFields($record, $data, $framework);

        return $record;
    }

    /**
     * Create MARC fields and subfields
     */
    protected function createMarcFields(BibliographicRecord $record, array $data, MarcFramework $framework)
    {
        $fieldMappings = $this->getFieldMappings($framework);

        foreach ($fieldMappings as $excelColumn => $marcTag) {
            if (empty($data[$excelColumn])) {
                continue;
            }

            // Create field
            $field = $record->fields()->create([
                'tag' => $marcTag,
                'indicator1' => $this->getIndicator($data, $marcTag, 1),
                'indicator2' => $this->getIndicator($data, $marcTag, 2)
            ]);

            // Create subfields
            $subfields = $this->parseSubfields($data[$excelColumn], $marcTag);
            foreach ($subfields as $code => $value) {
                $field->subfields()->create([
                    'code' => $code,
                    'value' => $value
                ]);
            }
        }
    }

    /**
     * Get field mappings for framework
     */
    protected function getFieldMappings(MarcFramework $framework): array
    {
        // Default MARC21 mappings - can be customized per framework
        return [
            'title' => '245',
            'author' => '100',
            'publisher' => '260',
            'publication_year' => '260',
            'isbn' => '020',
            'issn' => '022',
            'subject' => '650',
            'classification' => '082',
            'location' => '852',
            'notes' => '500',
            'language' => '008',
            'description' => '520'
        ];
    }

    /**
     * Parse subfields from Excel data
     */
    protected function parseSubfields(string $value, string $marcTag): array
    {
        $subfields = [];

        switch ($marcTag) {
            case '245': // Title
                $subfields['a'] = $value;
                break;
            case '100': // Author
                $subfields['a'] = $value;
                break;
            case '260': // Publication
                $parts = explode('|', $value);
                if (isset($parts[0])) $subfields['a'] = trim($parts[0]); // Place
                if (isset($parts[1])) $subfields['b'] = trim($parts[1]); // Publisher
                if (isset($parts[2])) $subfields['c'] = trim($parts[2]); // Date
                break;
            case '020': // ISBN
                $subfields['a'] = $value;
                break;
            case '650': // Subject
                $subfields['a'] = $value;
                break;
            case '082': // Classification
                $subfields['a'] = $value;
                break;
            case '852': // Location
                $parts = explode('|', $value);
                if (isset($parts[0])) $subfields['a'] = trim($parts[0]); // Location
                if (isset($parts[1])) $subfields['b'] = trim($parts[1]); // Sublocation
                break;
            default:
                $subfields['a'] = $value;
        }

        return $subfields;
    }

    /**
     * Generate leader field
     */
    protected function generateLeader(array $data, MarcFramework $framework): string
    {
        // Default leader for books
        $leader = '01234nam a2200000 a 4500';

        // Customize based on data
        if (isset($data['record_type'])) {
            switch ($data['record_type']) {
                case 'serial':
                    $leader = '01234nas a2200000 a 4500';
                    break;
                case 'article':
                    $leader = '01234na  a2200000 a 4500';
                    break;
            }
        }

        return $leader;
    }

    /**
     * Find existing record for update
     */
    protected function findExistingRecord(array $data): ?BibliographicRecord
    {
        $query = BibliographicRecord::query();

        // Try to find by ISBN first
        if (!empty($data['isbn'])) {
            $query->whereHas('fields.subfields', function ($q) use ($data) {
                $q->where('code', 'a')->where('value', $data['isbn']);
            })->whereHas('fields', function ($q) {
                $q->where('tag', '020');
            });
        }

        // Try by title + author
        if (!empty($data['title']) && !empty($data['author'])) {
            $query->orWhere(function ($q) use ($data) {
                $q->whereHas('fields.subfields', function ($q) use ($data) {
                    $q->where('code', 'a')->where('value', 'like', '%' . $data['title'] . '%');
                })->whereHas('fields', function ($q) {
                    $q->where('tag', '245');
                })->whereHas('fields.subfields', function ($q) use ($data) {
                    $q->where('code', 'a')->where('value', 'like', '%' . $data['author'] . '%');
                })->whereHas('fields', function ($q) {
                    $q->where('tag', '100');
                });
            });
        }

        return $query->first();
    }

    /**
     * Create a new framework based on the headers of an Excel file
     */
    public function createFrameworkFromFile(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
            'framework_name' => 'required|string|max:255',
            'framework_code' => 'required|string|max:20|unique:marc_frameworks,code'
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $filePath = $file->store('temp/imports', 'local');
            $fullPath = Storage::disk('local')->path($filePath);

            // Get headers
            $headings = (new \Maatwebsite\Excel\HeadingRowImport)->toArray($fullPath);
            $headers = $headings[0][0] ?? [];

            if (empty($headers)) {
                throw new \Exception(__('Could not find any headers in the file.'));
            }

            // Create framework
            $framework = MarcFramework::create([
                'name' => $request->framework_name,
                'code' => $request->framework_code,
                'is_active' => true
            ]);

            // Map headers to MARC tags
            $order = 1;
            $customTagCounter = 900;

            foreach ($headers as $header) {
                if (empty($header)) continue;

                $tagCode = $this->guessMarcTag($header);

                if ($tagCode === '9XX') {
                    $tagCode = (string)$customTagCounter++;
                }

                // Create or find tag definition
                $tag = MarcTagDefinition::firstOrCreate(
                    ['tag' => $tagCode],
                    ['label' => ucwords(str_replace(['_', '-'], ' ', $header))]
                );

                // Attach to framework
                $framework->tags()->attach($tag->id, [
                    'is_visible' => true,
                    'order' => $order++
                ]);

                // Ensure subfield 'a' exists
                if ($tag->subfields()->where('code', 'a')->count() === 0) {
                    $tag->subfields()->create([
                        'code' => 'a',
                        'label' => 'Standard subfield',
                        'is_visible' => true
                    ]);
                }
            }

            DB::commit();
            Storage::disk('local')->delete($filePath);

            return response()->json([
                'success' => true,
                'message' => __('Framework created successfully based on file headers.'),
                'data' => [
                    'framework_id' => $framework->id,
                    'framework_name' => $framework->name
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($filePath)) Storage::disk('local')->delete($filePath);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guess MARC tag based on header name
     */
    protected function guessMarcTag($header)
    {
        $header = strtolower(trim($header));
        $mappings = [
            'title' => '245',
            'author' => '100',
            'publisher' => '260',
            'publication_year' => '260',
            'isbn' => '020',
            'issn' => '022',
            'subject' => '650',
            'classification' => '082',
            'location' => '852',
            'notes' => '500',
            'language' => '008',
            'description' => '520',
            'barcode' => '952',
        ];

        return $mappings[$header] ?? '9XX';
    }

    /**
     * Get field indicator
     */
    protected function getIndicator(array $data, string $tag, int $indicatorNum): string
    {
        // Default indicators - can be customized
        return ' ';
    }

    /**
     * Extract title from record
     */
    protected function extractTitle(BibliographicRecord $record): string
    {
        $titleField = $record->fields()->where('tag', '245')->first();
        if ($titleField) {
            $titleSubfield = $titleField->subfields()->where('code', 'a')->first();
            return $titleSubfield ? $titleSubfield->value : 'Untitled';
        }

        return 'Untitled';
    }
}
