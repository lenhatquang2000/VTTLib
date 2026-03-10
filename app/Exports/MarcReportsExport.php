<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MarcReportsExport implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    protected $data;
    protected $reportType;

    public function __construct(array $data, string $reportType)
    {
        $this->data = $data;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        switch ($this->reportType) {
            case 'summary':
                return $this->summaryCollection();
            case 'productivity':
                return $this->productivityCollection();
            case 'quality':
                return $this->qualityCollection();
            case 'detailed':
                return $this->detailedCollection();
            default:
                return collect([]);
        }
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'summary':
                return [
                    ['MARC Cataloging Summary Report'],
                    ['Date Range: ' . $this->data['date_range']['from']->format('Y-m-d') . ' to ' . $this->data['date_range']['to']->format('Y-m-d')],
                    ['Framework: ' . ($this->data['framework'] ? $this->data['framework']->name : 'All Frameworks')],
                    [],
                    ['Metric', 'Value'],
                    ['Total Records', $this->data['statistics']['total_records']],
                    ['Approved Records', $this->data['statistics']['by_status']['approved'] ?? 0],
                    ['Pending Records', $this->data['statistics']['by_status']['pending'] ?? 0],
                    ['Approval Rate (%)', round($this->data['statistics']['approved_rate'], 2)],
                    ['Average Records Per Day', $this->data['productivity']['avg_per_day']],
                    ['Completeness Rate (%)', $this->data['quality']['completeness_rate']],
                    ['Average Fields Per Record', $this->data['quality']['avg_fields_per_record']],
                    ['ISBN Rate (%)', $this->data['quality']['isbn_rate']]
                ];
                
            case 'productivity':
                $headings = [
                    ['User Productivity Report'],
                    ['Date Range: ' . $this->data['date_range']['from']->format('Y-m-d') . ' to ' . $this->data['date_range']['to']->format('Y-m-d')],
                    ['Framework: ' . ($this->data['framework'] ? $this->data['framework']->name : 'All Frameworks')],
                    [],
                    ['User Name', 'Email', 'Total Records', 'Active Days', 'Average Daily']
                ];
                
                foreach ($this->data['user_productivity'] as $user) {
                    $headings[] = [
                        $user->name,
                        $user->email,
                        $user->total_records,
                        $user->active_days,
                        round($user->avg_daily, 2)
                    ];
                }
                
                return $headings;
                
            case 'quality':
                $headings = [
                    ['Quality Metrics Report'],
                    ['Date Range: ' . $this->data['date_range']['from']->format('Y-m-d') . ' to ' . $this->data['date_range']['to']->format('Y-m-d')],
                    ['Framework: ' . ($this->data['framework'] ? $this->data['framework']->name : 'All Frameworks')],
                    [],
                    ['Field Tag', 'Total Records', 'Records With Field', 'Completion Rate (%)']
                ];
                
                foreach ($this->data['field_completion'] as $field) {
                    $headings[] = [
                        $field->tag,
                        $field->total_records,
                        $field->records_with_field,
                        $field->completion_rate
                    ];
                }
                
                return $headings;
                
            case 'detailed':
                $headings = [
                    ['Detailed MARC Records Report'],
                    ['Date Range: ' . $this->data['date_range']['from']->format('Y-m-d') . ' to ' . $this->data['date_range']['to']->format('Y-m-d')],
                    ['Framework: ' . ($this->data['framework'] ? $this->data['framework']->name : 'All Frameworks')],
                    [],
                    ['ID', 'Title', 'Author', 'ISBN', 'Publisher', 'Framework', 'Record Type', 'Status', 'Fields Count', 'Created At', 'Updated At']
                ];
                
                foreach ($this->data['records'] as $record) {
                    $headings[] = [
                        $record['id'],
                        $record['title'],
                        $record['author'],
                        $record['isbn'],
                        $record['publisher'],
                        $record['framework'],
                        $record['record_type'],
                        $record['status'],
                        $record['fields_count'],
                        $record['created_at'],
                        $record['updated_at']
                    ];
                }
                
                return $headings;
                
            default:
                return [];
        }
    }

    public function title(): string
    {
        return 'MARC Report - ' . ucfirst($this->reportType);
    }

    public function styles(Worksheet $sheet)
    {
        // Style header rows
        $sheet->getStyle('1:4')->getFont()->setBold(true);
        $sheet->getStyle('1:4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE5E5E5');
            
        // Style column headers
        $highestRow = $sheet->getHighestRow();
        if ($this->reportType === 'summary') {
            $sheet->getStyle('5:6')->getFont()->setBold(true);
            $sheet->getStyle('5:6')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF0F0F0');
        } else {
            $sheet->getStyle('5:5')->getFont()->setBold(true);
            $sheet->getStyle('5:5')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF0F0F0');
        }
        
        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        return [];
    }

    protected function summaryCollection()
    {
        return collect([
            [
                'Metric' => 'Total Records',
                'Value' => $this->data['statistics']['total_records']
            ],
            [
                'Metric' => 'Approved Records',
                'Value' => $this->data['statistics']['by_status']['approved'] ?? 0
            ],
            [
                'Metric' => 'Pending Records',
                'Value' => $this->data['statistics']['by_status']['pending'] ?? 0
            ],
            [
                'Metric' => 'Approval Rate (%)',
                'Value' => round($this->data['statistics']['approved_rate'], 2)
            ],
            [
                'Metric' => 'Average Records Per Day',
                'Value' => $this->data['productivity']['avg_per_day']
            ],
            [
                'Metric' => 'Completeness Rate (%)',
                'Value' => $this->data['quality']['completeness_rate']
            ],
            [
                'Metric' => 'Average Fields Per Record',
                'Value' => $this->data['quality']['avg_fields_per_record']
            ],
            [
                'Metric' => 'ISBN Rate (%)',
                'Value' => $this->data['quality']['isbn_rate']
            ]
        ]);
    }

    protected function productivityCollection()
    {
        return collect($this->data['user_productivity'])->map(function ($user) {
            return [
                'User Name' => $user->name,
                'Email' => $user->email,
                'Total Records' => $user->total_records,
                'Active Days' => $user->active_days,
                'Average Daily' => round($user->avg_daily, 2)
            ];
        });
    }

    protected function qualityCollection()
    {
        return collect($this->data['field_completion'])->map(function ($field) {
            return [
                'Field Tag' => $field->tag,
                'Total Records' => $field->total_records,
                'Records With Field' => $field->records_with_field,
                'Completion Rate (%)' => $field->completion_rate
            ];
        });
    }

    protected function detailedCollection()
    {
        return collect($this->data['records'])->map(function ($record) {
            return [
                'ID' => $record['id'],
                'Title' => $record['title'],
                'Author' => $record['author'],
                'ISBN' => $record['isbn'],
                'Publisher' => $record['publisher'],
                'Framework' => $record['framework'],
                'Record Type' => $record['record_type'],
                'Status' => $record['status'],
                'Fields Count' => $record['fields_count'],
                'Created At' => $record['created_at'],
                'Updated At' => $record['updated_at']
            ];
        });
    }
}
