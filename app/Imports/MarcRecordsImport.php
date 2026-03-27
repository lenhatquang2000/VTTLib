<?php

namespace App\Imports;

use App\Models\MarcFramework;
use App\Models\MarcTagDefinition;
use App\Models\MarcSubfieldDefinition;
use App\Models\DocumentType;
use App\Models\StorageLocation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class MarcRecordsImport implements ToCollection, WithHeadingRow
{
    protected $framework;
    protected $actionType;
    protected $results = [];
    protected $errors = [];
    protected $preview = [];

    public function __construct(MarcFramework $framework, string $actionType)
    {
        $this->framework = $framework;
        $this->actionType = $actionType;
    }

    public function collection(Collection $rows)
    {
        $totalRows = $rows->count();
        $validRows = 0;
        $invalidRows = 0;
        $errors = [];
        $preview = [];
        $validData = [];

        foreach ($rows as $index => $row) {
            $rowIndex = $index + 2; // Excel row number (1-based + header)

            try {
                $validation = $this->validateRow($row, $rowIndex);

                if ($validation['valid']) {
                    $validRows++;
                    $validData[$rowIndex] = $row->toArray();

                    // Add to preview (max 5 records)
                    if (count($preview) < 5) {
                        $preview[] = [
                            'row_index' => $rowIndex,
                            'title' => $row['title'] ?? 'Untitled',
                            'author' => $row['author'] ?? 'Unknown',
                            'isbn' => $row['isbn'] ?? '',
                            'status' => 'valid',
                            'raw_marc' => $this->formatRowToRawMarc($row->toArray())
                        ];
                    }
                } else {
                    $invalidRows++;
                    $errors[] = [
                        'row_index' => $rowIndex,
                        'errors' => $validation['errors']
                    ];
                }
            } catch (\Exception $e) {
                $invalidRows++;
                $errors[] = [
                    'row_index' => $rowIndex,
                    'errors' => [$e->getMessage()]
                ];
            }
        }

        $this->results = [
            'total_rows' => $totalRows,
            'valid_rows' => $validRows,
            'invalid_rows' => $invalidRows,
            'errors' => $errors,
            'preview' => $preview,
            'valid_data' => $validData
        ];
    }

    /**
     * Format an excel row to a raw MARC string for preview
     */
    protected function formatRowToRawMarc(array $data): string
    {
        $lines = [];

        // 1. Leader
        $leader = '00472nam a2200169 a 4500';
        $lines[] = "LDR " . $leader;

        // 2. Control Fields (Simulated)
        $lines[] = "001 " . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        $lines[] = "005 " . date('YmdHis') . ".0";
        $lines[] = "008 " . date('ymd') . "s" . date('Y') . "    vn            000 0 vi d";

        // 3. Data Fields based on mappings
        $mappings = [
            'isbn' => ['tag' => '020', 'ind' => '  '],
            'classification' => ['tag' => '082', 'ind' => '04'],
            'author' => ['tag' => '100', 'ind' => '1 '],
            'title' => ['tag' => '245', 'ind' => '10'],
            'publisher' => ['tag' => '260', 'ind' => '  '],
            'location' => ['tag' => '852', 'ind' => '  '],
        ];

        foreach ($mappings as $key => $info) {
            if (!empty($data[$key])) {
                $tag = $info['tag'];
                $ind = $info['ind'];
                $value = $data[$key];

                // Simple subfield formatting
                if ($tag == '260' && strpos($value, '|') === false) {
                    // Try to be smart with publisher if no pipe exists
                    $lines[] = "{$tag} {$ind} |a [N.x.b] |b {$value} |c " . ($data['publication_year'] ?? '');
                } else {
                    $formattedValue = (strpos($value, '|') === 0) ? $value : "|a " . $value;
                    $lines[] = "{$tag} {$ind} {$formattedValue}";
                }
            }
        }

        return implode("\n", $lines);
    }

    protected function validateRow($row, $rowIndex): array
    {
        $errors = [];

        // Required fields
        $requiredFields = ['title'];
        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                $errors[] = __('Field :field is required', ['field' => $field]);
            }
        }

        // Validate ISBN format if provided
        if (!empty($row['isbn'])) {
            if (!$this->isValidISBN($row['isbn'])) {
                $errors[] = __('Invalid ISBN format');
            }
        }

        // Validate publication year if provided
        if (!empty($row['publication_year'])) {
            if (
                !is_numeric($row['publication_year']) ||
                $row['publication_year'] < 1000 ||
                $row['publication_year'] > date('Y') + 1
            ) {
                $errors[] = __('Invalid publication year');
            }
        }

        // Validate record type
        if (!empty($row['record_type'])) {
            $validTypes = ['book', 'serial', 'article', 'thesis'];
            if (!in_array($row['record_type'], $validTypes)) {
                $errors[] = __('Invalid record type. Valid types: :types', ['types' => implode(', ', $validTypes)]);
            }
        }

        // Validate document type if provided
        if (!empty($row['document_type'])) {
            $docType = DocumentType::where('name', $row['document_type'])->first();
            if (!$docType) {
                $errors[] = __('Document type not found: :type', ['type' => $row['document_type']]);
            }
        }

        // Validate storage location if provided
        if (!empty($row['storage_location'])) {
            $location = StorageLocation::where('name', $row['storage_location'])->first();
            if (!$location) {
                $errors[] = __('Storage location not found: :location', ['location' => $row['storage_location']]);
            }
        }

        // Check for duplicates if updating
        if ($this->actionType === 'update') {
            if (empty($row['isbn']) && empty($row['title'])) {
                $errors[] = __('ISBN or Title is required for update operation');
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $row->toArray()
        ];
    }

    protected function isValidISBN($isbn): bool
    {
        // Remove hyphens and spaces
        $isbn = preg_replace('/[\s-]/', '', $isbn);

        // Just check if it's 10 or 13 digits
        return (strlen($isbn) === 10 || strlen($isbn) === 13) && ctype_digit($isbn);
    }

    protected function validateISBN10($isbn): bool
    {
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (10 - $i) * intval($isbn[$i]);
        }

        $checksum = 11 - ($sum % 11);
        if ($checksum === 10) {
            $checksum = 'X';
        } elseif ($checksum === 11) {
            $checksum = '0';
        }

        return strtoupper($isbn[9]) === $checksum;
    }

    protected function validateISBN13($isbn): bool
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += intval($isbn[$i]) * ($i % 2 === 0 ? 1 : 3);
        }

        $checksum = (10 - ($sum % 10)) % 10;

        return intval($isbn[12]) === $checksum;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
