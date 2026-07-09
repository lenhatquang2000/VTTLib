<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DynamicPatronReportExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithCustomStartCell
{
    protected $headers;
    protected $rows;
    protected $title;

    public function __construct(array $headers, array $rows, string $title)
    {
        $this->headers = $headers;
        $this->rows = collect($rows);
        $this->title = $title;
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function styles(Worksheet $sheet)
    {
        // Set main title
        $sheet->mergeCells('A1:' . $this->getColumnLetter(count($this->headers)) . '1');
        $sheet->setCellValue('A1', $this->title);
        
        // Set date generated line
        $sheet->mergeCells('A2:' . $this->getColumnLetter(count($this->headers)) . '2');
        $sheet->setCellValue('A2', 'Ngày xuất báo cáo: ' . now()->format('d/m/Y H:i'));

        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            2 => [
                'font' => ['italic' => true, 'size' => 11],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1d4ed8'] // Primary blue color for VTTLib
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
        ];

        // Apply borders
        $lastRow = count($this->rows) + 4;
        $lastCol = $this->getColumnLetter(count($this->headers));
        $sheet->getStyle("A4:{$lastCol}{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return $styles;
    }

    protected function getColumnLetter($index)
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
    }
}
