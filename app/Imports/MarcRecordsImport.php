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
        $this->results['total_rows'] = $rows->count();
        $this->results['valid_rows'] = 0;
        $this->results['invalid_rows'] = 0;
        $this->results['errors'] = [];
        $this->results['preview'] = [];

        foreach ($rows as $index => $row) {
            $rowIndex = $index + 2; // Excel row number (1-based + header)
            
            try {
                $validation = $this->validateRow($row, $rowIndex);
                
                if ($validation['valid']) {
                    $this->results['valid_rows']++;
                    
                    // Add to preview (max 5 records)
                    if (count($this->results['preview']) < 5) {
                        $this->results['preview'][] = [
                            'row_index' => $rowIndex,
                            'title' => $row['title'] ?? 'Untitled',
                            'author' => $row['author'] ?? 'Unknown',
                            'isbn' => $row['isbn'] ?? '',
                            'status' => 'valid'
                        ];
                    }
                } else {
                    $this->results['invalid_rows']++;
                    $this->results['errors'][] = [
                        'row_index' => $rowIndex,
                        'errors' => $validation['errors']
                    ];
                }
                
            } catch (\Exception $e) {
                $this->results['invalid_rows']++;
                $this->results['errors'][] = [
                    'row_index' => $rowIndex,
                    'errors' => [$e->getMessage()]
                ];
            }
        }
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
            if (!is_numeric($row['publication_year']) || 
                $row['publication_year'] < 1000 || 
                $row['publication_year'] > date('Y') + 1) {
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
        
        // Check ISBN-10 or ISBN-13
        if (strlen($isbn) === 10) {
            return $this->validateISBN10($isbn);
        } elseif (strlen($isbn) === 13) {
            return $this->validateISBN13($isbn);
        }
        
        return false;
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
