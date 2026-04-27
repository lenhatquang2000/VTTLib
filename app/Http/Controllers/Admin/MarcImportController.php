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
use Illuminate\Support\Facades\Log;
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

    // ========================================================================
    // MARC FILE (.mrc / .txt) IMPORT
    // ========================================================================

    /**
     * Upload and parse a raw MARC file (.mrc or .txt)
     */
    public function uploadMarcFile(Request $request)
    {
        $request->validate([
            'marc_file' => 'required|file|max:10240',
            'action_type' => 'required|in:create,update'
        ]);

        try {
            $file = $request->file('marc_file');
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, ['mrc', 'txt'])) {
                return response()->json([
                    'success' => false,
                    'message' => __('Chỉ hỗ trợ file .mrc hoặc .txt')
                ], 422);
            }

            $rawContent = file_get_contents($file->getRealPath());

            // Split into individual MARC records
            $rawRecords = $this->splitMarcRecords($rawContent);

            if (empty($rawRecords)) {
                return response()->json([
                    'success' => false,
                    'message' => __('Không tìm thấy bản ghi MARC hợp lệ trong file')
                ], 422);
            }

            $parsedRecords = [];
            $errors = [];
            $allTags = []; // Collect unique tags for framework extraction

            foreach ($rawRecords as $index => $rawRecord) {
                $parsed = $this->parseRawMarcRecord($rawRecord);
                if ($parsed) {
                    $parsed['row_index'] = $index + 1;
                    $parsedRecords[] = $parsed;

                    // Collect tags for framework
                    foreach ($parsed['fields'] as $tag => $fieldDataArr) {
                        if (!isset($allTags[$tag])) {
                            $allTags[$tag] = [
                                'tag' => $tag,
                                'label' => $this->getMarcTagLabel($tag),
                                'subfields' => []
                            ];
                        }
                        // Collect subfield codes
                        foreach ($fieldDataArr as $fieldData) {
                            if (isset($fieldData['subfields'])) {
                                foreach ($fieldData['subfields'] as $sf) {
                                    $allTags[$tag]['subfields'][$sf['code']] = $sf['code'];
                                }
                            }
                        }
                    }
                } else {
                    $errors[] = [
                        'row_index' => $index + 1,
                        'errors' => [__('Không thể phân tích bản ghi MARC')]
                    ];
                }
            }

            // Sort tags
            ksort($allTags);

            // Build preview (first 5 records)
            $preview = [];
            foreach (array_slice($parsedRecords, 0, 5) as $rec) {
                $preview[] = [
                    'row_index' => $rec['row_index'],
                    'title' => $rec['title'] ?? 'N/A',
                    'author' => $rec['author'] ?? 'N/A',
                    'isbn' => $rec['isbn'] ?? 'N/A',
                    'publisher' => $rec['publisher'] ?? 'N/A',
                    'year' => $rec['year'] ?? 'N/A',
                    'fields_summary' => $this->buildFieldsSummary($rec['fields'])
                ];
            }

            // Store parsed data in session for later processing
            session(['marc_import_data' => $parsedRecords]);
            session(['marc_import_action' => $request->action_type]);

            // Auto-detect matching frameworks
            $extractedTagCodes = array_keys($allTags);
            $matchingFrameworks = [];
            $allFrameworks = MarcFramework::where('is_active', true)->with('tags')->get();

            foreach ($allFrameworks as $fw) {
                $fwTags = $fw->tags->pluck('tag')->toArray();
                if (empty($fwTags)) continue;

                // Count how many extracted tags exist in this framework
                $matchedTags = array_intersect($extractedTagCodes, $fwTags);
                $matchRatio = count($matchedTags) / count($extractedTagCodes);

                $matchingFrameworks[] = [
                    'id' => $fw->id,
                    'name' => $fw->name,
                    'code' => $fw->code,
                    'matched_tags' => count($matchedTags),
                    'total_file_tags' => count($extractedTagCodes),
                    'total_fw_tags' => count($fwTags),
                    'match_ratio' => round($matchRatio * 100),
                    'is_compatible' => $matchRatio >= 0.5, // >=50% tags match
                ];
            }

            // Sort: compatible first, then by match ratio descending
            usort($matchingFrameworks, function ($a, $b) {
                if ($a['is_compatible'] !== $b['is_compatible']) {
                    return $b['is_compatible'] <=> $a['is_compatible'];
                }
                return $b['match_ratio'] <=> $a['match_ratio'];
            });

            return response()->json([
                'success' => true,
                'message' => __('File MARC đã được phân tích thành công'),
                'data' => [
                    'total_records' => count($rawRecords),
                    'valid_records' => count($parsedRecords),
                    'invalid_records' => count($errors),
                    'errors' => $errors,
                    'preview' => $preview,
                    'extracted_framework' => array_values($allTags),
                    'matching_frameworks' => $matchingFrameworks
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('MARC file upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Lỗi xử lý file: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save extracted framework from MARC file with user-provided name
     */
    public function saveFrameworkFromMarc(Request $request)
    {
        $request->validate([
            'framework_name' => 'required|string|max:255',
            'framework_code' => 'required|string|max:20|unique:marc_frameworks,code',
            'tags' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $framework = MarcFramework::create([
                'name' => $request->framework_name,
                'code' => strtoupper($request->framework_code),
                'is_active' => true
            ]);

            $order = 1;
            foreach ($request->tags as $tagData) {
                $tag = MarcTagDefinition::firstOrCreate(
                    ['tag' => $tagData['tag']],
                    ['label' => $tagData['label'] ?? $this->getMarcTagLabel($tagData['tag'])]
                );

                $framework->tags()->attach($tag->id, [
                    'is_visible' => true,
                    'order' => $order++
                ]);

                // Ensure subfields exist
                $subfieldCodes = $tagData['subfields'] ?? ['a'];
                foreach ($subfieldCodes as $code) {
                    if ($tag->subfields()->where('code', $code)->count() === 0) {
                        $tag->subfields()->create([
                            'code' => $code,
                            'label' => $this->getMarcSubfieldLabel($tagData['tag'], $code),
                            'is_visible' => true
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('Khung biên mục đã được lưu thành công'),
                'data' => [
                    'framework_id' => $framework->id,
                    'framework_name' => $framework->name,
                    'framework_code' => $framework->code
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Save framework from MARC error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process and import parsed MARC records into the database
     */
    public function processMarcFile(Request $request)
    {
        $request->validate([
            'framework_id' => 'required|exists:marc_frameworks,id',
            'action_type' => 'required|in:create,update'
        ]);

        $parsedRecords = session('marc_import_data', []);

        if (empty($parsedRecords)) {
            return response()->json([
                'success' => false,
                'message' => __('Không tìm thấy dữ liệu đã phân tích. Vui lòng upload file lại.')
            ], 422);
        }

        try {
            DB::beginTransaction();

            $framework = MarcFramework::find($request->framework_id);
            $results = [];

            // Ensure MarcTagDefinition & MarcSubfieldDefinition exist for all tags/subfields
            // and link them to the chosen framework
            $this->ensureDefinitionsForParsedRecords($framework, $parsedRecords);

            foreach ($parsedRecords as $parsed) {
                try {
                    if ($request->action_type === 'update') {
                        $record = $this->findExistingRecordFromMarc($parsed);
                        if ($record) {
                            $record->update([
                                'leader' => $parsed['leader'],
                                'status' => 'pending'
                            ]);
                            $record->fields()->delete();
                        } else {
                            // Fallback to create if not found
                            $record = BibliographicRecord::create([
                                'framework' => $framework->code,
                                'leader' => $parsed['leader'],
                                'status' => 'pending',
                                'record_type' => 'book',
                                'subject_category' => 'General',
                                'serial_frequency' => 'unknown',
                                'date_type' => 'bc',
                                'acquisition_method' => 'untraced',
                                'document_format' => 'none',
                                'cataloging_standard' => 'AACR2'
                            ]);
                        }
                    } else {
                        $record = BibliographicRecord::create([
                            'framework' => $framework->code,
                            'leader' => $parsed['leader'],
                            'status' => 'pending',
                            'record_type' => 'book',
                            'subject_category' => 'General',
                            'serial_frequency' => 'unknown',
                            'date_type' => 'bc',
                            'acquisition_method' => 'untraced',
                            'document_format' => 'none',
                            'cataloging_standard' => 'AACR2'
                        ]);
                    }

                    // Create MARC fields from parsed data
                    $this->createMarcFieldsFromParsed($record, $parsed['fields']);

                    $results[] = [
                        'row_index' => $parsed['row_index'] ?? 0,
                        'success' => true,
                        'record_id' => $record->id,
                        'title' => $parsed['title'] ?? 'Untitled'
                    ];
                } catch (\Exception $e) {
                    $results[] = [
                        'row_index' => $parsed['row_index'] ?? 0,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();
            session()->forget(['marc_import_data', 'marc_import_action']);

            return response()->json([
                'success' => true,
                'message' => __('Import hoàn tất'),
                'data' => $results
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MARC file process error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Import thất bại: ') . $e->getMessage()
            ], 500);
        }
    }

    // ========================================================================
    // MARC PARSING HELPERS
    // ========================================================================

    /**
     * Split raw content into individual MARC records
     * Handles both ISO 2709 binary and text-based MARC
     */
    protected function splitMarcRecords(string $content): array
    {
        $records = [];

        // Try ISO 2709 format first (records terminated by 0x1D)
        $rt = chr(0x1D); // Record terminator
        if (strpos($content, $rt) !== false) {
            $parts = explode($rt, $content);
            foreach ($parts as $part) {
                $part = trim($part);
                if (strlen($part) >= 24) {
                    $records[] = $part . $rt;
                }
            }
        }

        // If no ISO 2709 records found, try text-based MARC
        if (empty($records)) {
            // Text MARC: records might be separated by blank lines or specific delimiters
            // Try splitting by double newline or by detecting leader patterns
            $lines = preg_split('/\r?\n/', $content);
            $currentRecord = '';

            foreach ($lines as $line) {
                // Check if line starts with a leader pattern (5 digits at start)
                if (preg_match('/^\d{5}[a-z]/', $line) && strlen($currentRecord) > 0) {
                    $records[] = $currentRecord;
                    $currentRecord = $line;
                } else {
                    $currentRecord .= ($currentRecord ? "\n" : '') . $line;
                }
            }

            if (strlen($currentRecord) >= 24) {
                $records[] = $currentRecord;
            }
        }

        return $records;
    }

    /**
     * Parse a single raw MARC record (ISO 2709 or text)
     */
    protected function parseRawMarcRecord(string $raw): ?array
    {
        try {
            $raw = trim($raw);
            if (strlen($raw) < 24) {
                return null;
            }

            $leader = substr($raw, 0, 24);
            $baseAddress = (int)substr($leader, 12, 5);

            // Validate leader
            if ($baseAddress <= 24 || $baseAddress > strlen($raw)) {
                return null;
            }

            $ft = chr(0x1E); // Field terminator
            $sf = chr(0x1F); // Subfield delimiter
            $rt = chr(0x1D); // Record terminator

            $directoryStr = substr($raw, 24, $baseAddress - 25);
            $dataSection = substr($raw, $baseAddress);

            // Remove record terminator from data if present
            $dataSection = rtrim($dataSection, $rt);

            $fields = [];
            $title = '';
            $author = '';
            $isbn = '';
            $publisher = '';
            $year = '';
            $subjects = [];

            // Parse directory entries (12 bytes each)
            for ($i = 0; $i + 11 < strlen($directoryStr); $i += 12) {
                $tag = substr($directoryStr, $i, 3);
                $length = (int)substr($directoryStr, $i + 3, 4);
                $start = (int)substr($directoryStr, $i + 7, 5);

                if ($start + $length > strlen($dataSection) + 1) {
                    continue;
                }

                $fieldData = substr($dataSection, $start, $length);
                // Remove field terminator
                $fieldData = rtrim($fieldData, $ft);

                // Parse subfields
                $subfields = [];
                $indicators = ['', ''];

                if (intval($tag) >= 10) {
                    // Variable fields have indicators
                    if (strlen($fieldData) >= 2) {
                        $indicators[0] = $fieldData[0] ?? ' ';
                        $indicators[1] = $fieldData[1] ?? ' ';
                        $subfieldStr = substr($fieldData, 2);
                    } else {
                        $subfieldStr = $fieldData;
                    }

                    // Split by subfield delimiter (0x1F or $)
                    $sfParts = [];
                    if (strpos($subfieldStr, $sf) !== false) {
                        $sfParts = explode($sf, $subfieldStr);
                    } elseif (strpos($subfieldStr, '$') !== false) {
                        $sfParts = explode('$', $subfieldStr);
                    } else {
                        // Single value, treat as subfield 'a'
                        $subfields[] = ['code' => 'a', 'value' => trim($subfieldStr)];
                    }

                    foreach ($sfParts as $sfPart) {
                        $sfPart = trim($sfPart);
                        if (strlen($sfPart) >= 1) {
                            $code = $sfPart[0];
                            $value = trim(substr($sfPart, 1));
                            if ($value !== '') {
                                $subfields[] = ['code' => $code, 'value' => $value];
                            }
                        }
                    }
                } else {
                    // Control fields (00X) - no subfields
                    $subfields[] = ['code' => '_', 'value' => trim($fieldData)];
                }

                $fields[$tag][] = [
                    'indicators' => $indicators,
                    'subfields' => $subfields,
                    'raw' => $fieldData
                ];

                // Extract common data
                $this->extractCommonData($tag, $subfields, $title, $author, $isbn, $publisher, $year, $subjects);
            }

            return [
                'leader' => $leader,
                'title' => rtrim($title, ' /:.'),
                'author' => rtrim($author, ',. '),
                'isbn' => preg_replace('/[^0-9X-]/', '', $isbn),
                'publisher' => rtrim($publisher, ',. '),
                'year' => preg_replace('/[^0-9]/', '', $year),
                'subjects' => $subjects,
                'fields' => $fields,
                'raw_base64' => base64_encode($raw)
            ];
        } catch (\Exception $e) {
            Log::error('MARC record parse error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract common bibliographic data from subfields
     */
    protected function extractCommonData(string $tag, array $subfields, string &$title, string &$author, string &$isbn, string &$publisher, string &$year, array &$subjects): void
    {
        foreach ($subfields as $sf) {
            $code = $sf['code'];
            $val = $sf['value'];

            switch ($tag) {
                case '020':
                    if ($code === 'a') $isbn = $val;
                    break;
                case '100':
                case '110':
                case '111':
                    if ($code === 'a') $author = $val;
                    break;
                case '245':
                    if ($code === 'a') $title = $val;
                    if ($code === 'b') $title .= ' ' . $val;
                    break;
                case '260':
                case '264':
                    if ($code === 'b') $publisher = $val;
                    if ($code === 'c') $year = $val;
                    break;
                case '650':
                case '651':
                    if ($code === 'a') $subjects[] = $val;
                    break;
            }
        }
    }

    /**
     * Ensure MarcTagDefinition & MarcSubfieldDefinition exist for all tags/subfield codes
     * found across parsed records, and link them to the given framework.
     */
    protected function ensureDefinitionsForParsedRecords(MarcFramework $framework, array $parsedRecords): void
    {
        // Collect all unique tag => subfield codes from parsed records
        $tagSubfields = [];
        foreach ($parsedRecords as $parsed) {
            if (!isset($parsed['fields'])) continue;
            foreach ($parsed['fields'] as $tag => $instances) {
                if (!isset($tagSubfields[$tag])) {
                    $tagSubfields[$tag] = [];
                }
                foreach ($instances as $instance) {
                    if (isset($instance['subfields'])) {
                        foreach ($instance['subfields'] as $sf) {
                            $code = strtolower(trim($sf['code'] ?? ''));
                            if ($code !== '') {
                                $tagSubfields[$tag][$code] = true;
                            }
                        }
                    }
                }
            }
        }

        // Existing framework tag IDs to avoid duplicate attach
        $existingFrameworkTagIds = $framework->tags()->pluck('marc_tag_definitions.id')->toArray();
        $order = $framework->tags()->max('order') ?? 0;

        foreach ($tagSubfields as $tag => $codes) {
            // 1. Ensure MarcTagDefinition exists
            $tagDef = MarcTagDefinition::firstOrCreate(
                ['tag' => $tag],
                ['label' => $this->getMarcTagLabel($tag)]
            );

            // 2. Link tag to framework if not already linked
            if (!in_array($tagDef->id, $existingFrameworkTagIds)) {
                $framework->tags()->attach($tagDef->id, [
                    'is_visible' => true,
                    'order' => ++$order
                ]);
                $existingFrameworkTagIds[] = $tagDef->id;
            }

            // 3. Ensure MarcSubfieldDefinition exists (and fix bad labels) for each code
            foreach (array_keys($codes) as $code) {
                $correctLabel = $this->getMarcSubfieldLabel($tag, $code);
                $sfDef = $tagDef->subfields()->where('code', $code)->first();

                if (!$sfDef) {
                    $tagDef->subfields()->create([
                        'code' => $code,
                        'label' => $correctLabel,
                        'is_visible' => true
                    ]);
                } elseif (
                    str_starts_with($sfDef->label, 'Subfield') ||
                    $sfDef->label === 'Saved' ||
                    $sfDef->label === '' ||
                    $sfDef->label === 'Primary data' ||
                    $sfDef->label === 'Secondary data' ||
                    $sfDef->label === 'Tertiary data'
                ) {
                    $sfDef->update(['label' => $correctLabel]);
                }
            }
        }
    }

    /**
     * Create MARC fields from parsed field data
     */
    protected function createMarcFieldsFromParsed(BibliographicRecord $record, array $fields): void
    {
        foreach ($fields as $tag => $fieldInstances) {
            foreach ($fieldInstances as $seq => $fieldData) {
                $field = $record->fields()->create([
                    'tag' => $tag,
                    'indicator1' => $fieldData['indicators'][0] ?? ' ',
                    'indicator2' => $fieldData['indicators'][1] ?? ' ',
                    'sequence' => $seq
                ]);

                if (isset($fieldData['subfields'])) {
                    foreach ($fieldData['subfields'] as $sf) {
                        $field->subfields()->create([
                            'code' => $sf['code'],
                            'value' => $sf['value']
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Find existing record by ISBN or title+author from parsed MARC data
     */
    protected function findExistingRecordFromMarc(array $parsed): ?BibliographicRecord
    {
        if (!empty($parsed['isbn'])) {
            $record = BibliographicRecord::whereHas('fields', function ($q) {
                $q->where('tag', '020');
            })->whereHas('fields.subfields', function ($q) use ($parsed) {
                $q->where('code', 'a')->where('value', 'like', '%' . $parsed['isbn'] . '%');
            })->first();

            if ($record) return $record;
        }

        if (!empty($parsed['title'])) {
            return BibliographicRecord::whereHas('fields', function ($q) {
                $q->where('tag', '245');
            })->whereHas('fields.subfields', function ($q) use ($parsed) {
                $q->where('code', 'a')->where('value', 'like', '%' . $parsed['title'] . '%');
            })->first();
        }

        return null;
    }

    /**
     * Build human-readable fields summary for preview
     */
    protected function buildFieldsSummary(array $fields): array
    {
        $summary = [];
        foreach ($fields as $tag => $instances) {
            foreach ($instances as $instance) {
                $parts = [];
                if (isset($instance['subfields'])) {
                    foreach ($instance['subfields'] as $sf) {
                        $parts[] = '$' . $sf['code'] . ' ' . $sf['value'];
                    }
                }
                $summary[] = [
                    'tag' => $tag,
                    'label' => $this->getMarcTagLabel($tag),
                    'value' => implode(' ', $parts)
                ];
            }
        }
        return $summary;
    }

    /**
     * Get human-readable label for a MARC tag
     */
    protected function getMarcTagLabel(string $tag): string
    {
        $labels = [
            '001' => 'Control Number',
            '003' => 'Control Number Identifier',
            '005' => 'Date/Time of Latest Transaction',
            '008' => 'Fixed-Length Data Elements',
            '010' => 'Library of Congress Control Number',
            '020' => 'ISBN',
            '022' => 'ISSN',
            '035' => 'System Control Number',
            '040' => 'Cataloging Source',
            '041' => 'Language Code',
            '043' => 'Geographic Area Code',
            '050' => 'LC Call Number',
            '082' => 'Dewey Decimal Classification',
            '084' => 'Other Classification Number',
            '100' => 'Main Entry - Personal Name',
            '110' => 'Main Entry - Corporate Name',
            '111' => 'Main Entry - Meeting Name',
            '130' => 'Main Entry - Uniform Title',
            '210' => 'Abbreviated Title',
            '222' => 'Key Title',
            '240' => 'Uniform Title',
            '245' => 'Title Statement',
            '246' => 'Varying Form of Title',
            '250' => 'Edition Statement',
            '260' => 'Publication, Distribution',
            '264' => 'Production, Publication, Distribution',
            '300' => 'Physical Description',
            '336' => 'Content Type',
            '337' => 'Media Type',
            '338' => 'Carrier Type',
            '490' => 'Series Statement',
            '500' => 'General Note',
            '504' => 'Bibliography Note',
            '505' => 'Formatted Contents Note',
            '520' => 'Summary',
            '600' => 'Subject - Personal Name',
            '610' => 'Subject - Corporate Name',
            '650' => 'Subject - Topical Term',
            '651' => 'Subject - Geographic Name',
            '653' => 'Index Term - Uncontrolled',
            '655' => 'Genre/Form',
            '700' => 'Added Entry - Personal Name',
            '710' => 'Added Entry - Corporate Name',
            '711' => 'Added Entry - Meeting Name',
            '776' => 'Additional Physical Form',
            '800' => 'Series Added Entry - Personal Name',
            '830' => 'Series Added Entry - Uniform Title',
            '852' => 'Location',
            '856' => 'Electronic Location and Access',
            '900' => 'Local Data (900)',
            '910' => 'Local Data (910)',
            '920' => 'Local Data (920)',
            '942' => 'Added Entry Elements (Koha)',
            '952' => 'Holdings (Koha)',
            '999' => 'Local Data (999)',
        ];

        // Check DB first
        $dbTag = MarcTagDefinition::where('tag', $tag)->first();
        if ($dbTag) {
            return $dbTag->label;
        }

        return $labels[$tag] ?? "Tag $tag";
    }

    /**
     * Get human-readable label for a MARC subfield
     */
    protected function getMarcSubfieldLabel(string $tag, string $code): string
    {
        // Check DB first
        $dbDef = MarcSubfieldDefinition::whereHas('tagDefinition', function ($q) use ($tag) {
            $q->where('tag', $tag);
        })->where('code', $code)->first();
        if ($dbDef && $dbDef->label && $dbDef->label !== '' && !str_starts_with($dbDef->label, 'Subfield')) {
            return $dbDef->label;
        }

        // Tag-specific subfield labels (MARC21 standard)
        $specific = [
            '001' => ['_' => 'Control Number'],
            '003' => ['_' => 'Control Number Identifier'],
            '005' => ['_' => 'Date and Time of Latest Transaction'],
            '008' => ['_' => 'Fixed-Length Data Elements'],
            '010' => ['a' => 'LC Control Number', 'z' => 'Canceled/Invalid'],
            '020' => ['a' => 'ISBN', 'c' => 'Terms of Availability', 'z' => 'Canceled/Invalid ISBN', 'q' => 'Qualifying Information'],
            '022' => ['a' => 'ISSN', 'y' => 'Incorrect ISSN', 'z' => 'Canceled ISSN'],
            '035' => ['a' => 'System Control Number', 'z' => 'Canceled/Invalid Number'],
            '040' => ['a' => 'Original Cataloging Agency', 'b' => 'Language of Cataloging', 'c' => 'Transcribing Agency', 'd' => 'Modifying Agency', 'e' => 'Description Conventions'],
            '041' => ['a' => 'Language Code of Text', 'b' => 'Language Code of Summary', 'h' => 'Language Code of Original'],
            '050' => ['a' => 'Classification Number', 'b' => 'Item Number'],
            '082' => ['a' => 'Classification Number', 'b' => 'Item Number', '2' => 'Edition Number'],
            '084' => ['a' => 'Classification Number', 'b' => 'Item Number', '2' => 'Number Source'],
            '100' => ['a' => 'Personal Name', 'b' => 'Numeration', 'c' => 'Titles', 'd' => 'Dates', 'e' => 'Relator Term', 'q' => 'Fuller Form of Name', '4' => 'Relator Code'],
            '110' => ['a' => 'Corporate Name', 'b' => 'Subordinate Unit', 'c' => 'Location', 'd' => 'Date', '4' => 'Relator Code'],
            '111' => ['a' => 'Meeting Name', 'c' => 'Location', 'd' => 'Date', 'n' => 'Number of Part', '4' => 'Relator Code'],
            '130' => ['a' => 'Uniform Title', 'd' => 'Date of Treaty', 'f' => 'Date of Work', 'l' => 'Language'],
            '240' => ['a' => 'Uniform Title', 'f' => 'Date of Work', 'l' => 'Language'],
            '245' => ['a' => 'Title', 'b' => 'Remainder of Title', 'c' => 'Statement of Responsibility', 'h' => 'Medium', 'n' => 'Number of Part', 'p' => 'Name of Part'],
            '246' => ['a' => 'Title Proper/Short Title', 'b' => 'Remainder of Title', 'i' => 'Display Text'],
            '250' => ['a' => 'Edition Statement', 'b' => 'Remainder of Edition'],
            '260' => ['a' => 'Place of Publication', 'b' => 'Publisher Name', 'c' => 'Date of Publication'],
            '264' => ['a' => 'Place of Production/Publication', 'b' => 'Producer/Publisher Name', 'c' => 'Date of Production/Publication'],
            '300' => ['a' => 'Extent', 'b' => 'Other Physical Details', 'c' => 'Dimensions', 'e' => 'Accompanying Material'],
            '336' => ['a' => 'Content Type Term', 'b' => 'Content Type Code', '2' => 'Source'],
            '337' => ['a' => 'Media Type Term', 'b' => 'Media Type Code', '2' => 'Source'],
            '338' => ['a' => 'Carrier Type Term', 'b' => 'Carrier Type Code', '2' => 'Source'],
            '490' => ['a' => 'Series Statement', 'v' => 'Volume/Sequential Designation', 'x' => 'ISSN'],
            '500' => ['a' => 'General Note'],
            '504' => ['a' => 'Bibliography Note'],
            '505' => ['a' => 'Formatted Contents Note', 'g' => 'Miscellaneous Information', 'r' => 'Statement of Responsibility', 't' => 'Title'],
            '520' => ['a' => 'Summary', 'b' => 'Expansion of Summary'],
            '600' => ['a' => 'Personal Name', 'b' => 'Numeration', 'd' => 'Dates', 'v' => 'Form Subdivision', 'x' => 'General Subdivision', 'y' => 'Chronological Subdivision', 'z' => 'Geographic Subdivision', '2' => 'Subject Heading Source'],
            '610' => ['a' => 'Corporate Name', 'b' => 'Subordinate Unit', 'v' => 'Form Subdivision', 'x' => 'General Subdivision', '2' => 'Subject Heading Source'],
            '650' => ['a' => 'Topical Term', 'v' => 'Form Subdivision', 'x' => 'General Subdivision', 'y' => 'Chronological Subdivision', 'z' => 'Geographic Subdivision', '2' => 'Subject Heading Source'],
            '651' => ['a' => 'Geographic Name', 'v' => 'Form Subdivision', 'x' => 'General Subdivision', '2' => 'Subject Heading Source'],
            '653' => ['a' => 'Uncontrolled Term'],
            '655' => ['a' => 'Genre/Form Data', 'v' => 'Form Subdivision', 'x' => 'General Subdivision', '2' => 'Source of Term'],
            '700' => ['a' => 'Personal Name', 'b' => 'Numeration', 'c' => 'Titles', 'd' => 'Dates', 'e' => 'Relator Term', 'q' => 'Fuller Form of Name', 't' => 'Title of Work', '4' => 'Relator Code'],
            '710' => ['a' => 'Corporate Name', 'b' => 'Subordinate Unit', 'e' => 'Relator Term', '4' => 'Relator Code'],
            '711' => ['a' => 'Meeting Name', 'c' => 'Location', 'd' => 'Date', 'n' => 'Number of Part'],
            '776' => ['a' => 'Main Entry Heading', 't' => 'Title', 'w' => 'Record Control Number', 'z' => 'ISBN'],
            '800' => ['a' => 'Personal Name', 't' => 'Title of Work', 'v' => 'Volume'],
            '830' => ['a' => 'Uniform Title', 'v' => 'Volume', 'x' => 'ISSN'],
            '852' => ['a' => 'Location', 'b' => 'Sublocation', 'c' => 'Shelving Location', 'h' => 'Classification Part', 'i' => 'Item Part', 'p' => 'Piece Designation'],
            '856' => ['u' => 'URI', 'y' => 'Link Text', 'z' => 'Public Note', '3' => 'Materials Specified'],
            '942' => ['a' => 'Institution', 'c' => 'Item Type', 'h' => 'Classification Part', '2' => 'Classification Source'],
            '952' => ['a' => 'Home Branch', 'b' => 'Current Branch', 'c' => 'Shelving Location', 'd' => 'Date Acquired', 'o' => 'Call Number', 'p' => 'Barcode', 't' => 'Copy Number', 'y' => 'Item Type'],
        ];

        if (isset($specific[$tag][$code])) {
            return $specific[$tag][$code];
        }

        // Generic fallback for common subfield codes
        $generic = [
            'a' => 'Primary Data',
            'b' => 'Secondary Data',
            'c' => 'Qualifier/Additional',
            'd' => 'Date',
            'e' => 'Relator/Additional',
            'f' => 'Date of Work',
            'g' => 'Miscellaneous',
            'h' => 'Medium/Additional',
            'i' => 'Display Text',
            'k' => 'Form Subheading',
            'l' => 'Language',
            'n' => 'Number of Part',
            'p' => 'Name of Part',
            'q' => 'Qualifying Information',
            'r' => 'Key for Music',
            's' => 'Version',
            't' => 'Title',
            'u' => 'URI',
            'v' => 'Volume Designation',
            'w' => 'Control Number',
            'x' => 'ISSN',
            'y' => 'Link Text',
            'z' => 'ISBN/Note',
            '_' => 'Control Data',
            '2' => 'Source',
            '3' => 'Materials Specified',
            '4' => 'Relator Code',
            '5' => 'Institution',
            '6' => 'Linkage',
            '8' => 'Field Link',
        ];

        return $generic[$code] ?? "Subfield \$$code";
    }
}
