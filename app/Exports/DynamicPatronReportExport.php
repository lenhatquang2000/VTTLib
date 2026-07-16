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
    protected $reportType;

    public function __construct(array $headers, array $rows, string $title, string $reportType = 'patron_list')
    {
        $this->headers = $headers;
        $this->rows = collect($rows);
        $this->title = $title;
        $this->reportType = $reportType;
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
        if ($this->reportType === 'patron_list') {
            return 'A7';
        }
        return 'A4';
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->reportType === 'patron_list') {
            // Set library name line
            $sheet->setCellValue('A1', 'THƯ VIỆN - TRƯỜNG ĐẠI HỌC VÕ TRƯỜNG TOẢN');
            
            // Set address line
            $sheet->setCellValue('A2', 'Địa chỉ: Quốc Lộ 1A, xã Thạnh Xuân, Thành phố Cần Thơ');
            
            // Set website line
            $sheet->setCellValue('A3', 'Website: http://library.vttu.edu.vn/');
            
            // Set main title on A5 merged
            $lastColLetter = $this->getColumnLetter(count($this->headers));
            $sheet->mergeCells("A5:{$lastColLetter}5");
            $sheet->setCellValue('A5', 'DANH SÁCH ĐỘC GIẢ THƯ VIỆN');

            // Apply font styles for upper header block
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
            $sheet->getStyle('A3')->getFont()->setSize(10);
            
            $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(18);
            $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Table headers style (A7 to lastCol7)
            $headerRange = "A7:{$lastColLetter}7";
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => '000000']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2'] // Light blue/gray as in image
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);

            // Set height of header row
            $sheet->getRowDimension(7)->setRowHeight(30);

            // Apply borders to table
            $lastRow = count($this->rows) + 7;
            $tableRange = "A7:{$lastColLetter}{$lastRow}";
            $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Alignment style for data cells
            // Center align STT (Col A), Ngay Sinh (Col D), Gioi Tinh (Col E), Ngay Cap (Col I), Han The (Col J)
            $sheet->getStyle("A8:A{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D8:D{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E8:E{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I8:I{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J8:J{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Let's also format numbers for code to avoid scientific notations (Col B)
            $sheet->getStyle("B8:B{$lastRow}")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            return [];
        }

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
