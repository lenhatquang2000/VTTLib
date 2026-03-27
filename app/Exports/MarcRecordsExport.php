<?php

namespace App\Exports;

use App\Models\BibliographicRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MarcRecordsExport implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles
{
    protected $records;
    protected $includeItems;

    public function __construct($records = null, $includeItems = false)
    {
        $this->records = $records;
        $this->includeItems = $includeItems;
    }

    public function collection()
    {
        if ($this->records) {
            return $this->records;
        }

        return BibliographicRecord::with(['items', 'fields.subfields'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function title(): string
    {
        return 'MARC Records Export';
    }

    public function headings(): array
    {
        $baseHeadings = [
            'ID',
            'Title',
            'Author',
            'ISBN',
            'Publisher',
            'Publication Year',
            'Document Type',
            'Language',
            'Framework',
            'Status',
            'Created At',
            'Updated At'
        ];

        if ($this->includeItems) {
            $baseHeadings = array_merge($baseHeadings, [
                'Items Count',
                'Barcodes',
                'Storage Locations',
                'Item Statuses'
            ]);
        }

        return $baseHeadings;
    }

    public function map($record): array
    {
        // Extract title from 245 field
        $title = '';
        $author = '';
        $isbn = '';
        $publisher = '';
        $pubYear = '';
        $language = '';

        foreach ($record->fields as $field) {
            switch ($field->tag) {
                case '245':
                    $subfields = $field->subfields->pluck('value')->toArray();
                    $title = implode(' ', $subfields);
                    break;
                case '100':
                case '110':
                case '111':
                    $subfields = $field->subfields->pluck('value')->toArray();
                    $author = implode(' ', $subfields);
                    break;
                case '020':
                    $isbnField = $field->subfields->where('code', 'a')->first();
                    $isbn = $isbnField ? $isbnField->value : '';
                    break;
                case '260':
                case '264':
                    $subfieldB = $field->subfields->where('code', 'b')->first();
                    $subfieldC = $field->subfields->where('code', 'c')->first();
                    $publisher = $subfieldB ? $subfieldB->value : '';
                    $pubYear = $subfieldC ? $subfieldC->value : '';
                    break;
                case '008':
                    $value = $field->subfields->first()->value ?? '';
                    $language = substr($value, 35, 3);
                    break;
            }
        }

        $baseData = [
            $record->id,
            $title,
            $author,
            $isbn,
            $publisher,
            $pubYear,
            $record->document_type?->name ?? '',
            $language,
            $record->framework?->code ?? '',
            $record->status,
            $record->created_at->format('Y-m-d H:i:s'),
            $record->updated_at->format('Y-m-d H:i:s')
        ];

        if ($this->includeItems) {
            $items = $record->items;
            $barcodes = $items->pluck('barcode')->filter()->implode(', ');
            $locations = $items->map(function($item) {
                return $item->storageLocation?->name ?? '';
            })->filter()->implode(', ');
            $statuses = $items->pluck('status')->implode(', ');

            $baseData = array_merge($baseData, [
                $items->count(),
                $barcodes,
                $locations,
                $statuses
            ]);
        }

        return $baseData;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E3F2FD'
                    ]
                ]
            ],
        ];
    }
}
