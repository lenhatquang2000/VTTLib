<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarcodeExport implements FromCollection, WithTitle, WithStyles
{
    protected $items;
    protected $title;

    public function __construct($items, string $title = 'DANH SÁCH MÃ VẠCH')
    {
        $this->items = $items;
        $this->title = $title;
    }

    public function collection()
    {
        // We do not use collection to render grid rows automatically,
        // instead we write them manually inside the styles() method.
        return collect([]);
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
     * Compute Code 93 Checksum characters (C and K)
     */
    protected function getCode93Checksum(string $text): string
    {
        $alphabet = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-. $/+%";
        $alphabet .= "<=>?";
        
        $valMap = [];
        $charMap = [];
        for ($i = 0; $i < strlen($alphabet); $i++) {
            $valMap[$alphabet[$i]] = $i;
            $charMap[$i] = $alphabet[$i];
        }
        
        // Calculate C checksum
        $sumC = 0;
        $len = strlen($text);
        for ($i = 0; $i < $len; $i++) {
            $char = $text[$len - 1 - $i];
            $val = isset($valMap[$char]) ? $valMap[$char] : 0;
            $weight = ($i % 20) + 1;
            $sumC += $val * $weight;
        }
        $cVal = $sumC % 47;
        $cChar = $charMap[$cVal] ?? '';
        
        // Calculate K checksum
        $textWithC = $text . $cChar;
        $sumK = 0;
        $lenK = strlen($textWithC);
        for ($i = 0; $i < $lenK; $i++) {
            $char = $textWithC[$lenK - 1 - $i];
            $val = isset($valMap[$char]) ? $valMap[$char] : 0;
            $weight = ($i % 15) + 1;
            $sumK += $val * $weight;
        }
        $kVal = $sumK % 47;
        $kChar = $charMap[$kVal] ?? '';
        
        return $cChar . $kChar;
    }

    public function styles(Worksheet $sheet)
    {
        $itemsPerRow = 4; // 4 labels per row
        
        // Set column widths
        $widths = [
            'A' => 25.08984375,
            'B' => 1.453125,
            'C' => 25.08984375,
            'D' => 0.81640625,
            'E' => 25.08984375,
            'F' => 1.1796875,
            'G' => 25.08984375,
            'H' => 9,
            'I' => 9
        ];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $cols = ['A', 'C', 'E', 'G'];
        
        foreach ($this->items as $index => $item) {
            $colLetter = $cols[$index % $itemsPerRow];
            $rowIndex = floor($index / $itemsPerRow);
            
            $r1 = 1 + $rowIndex * 5;
            $r2 = 2 + $rowIndex * 5;
            $r3 = 3 + $rowIndex * 5;
            $r4 = 4 + $rowIndex * 5;
            $r5 = 5 + $rowIndex * 5; // Separator row

            // Set row heights
            $sheet->getRowDimension($r1)->setRowHeight(14.25);
            $sheet->getRowDimension($r2)->setRowHeight(14.25);
            $sheet->getRowDimension($r3)->setRowHeight(28.25);
            $sheet->getRowDimension($r4)->setRowHeight(14);
            $sheet->getRowDimension($r5)->setRowHeight(10.5);

            // Write Cell 1: School Title (VNI-Revue, TCVN3 encoding)
            $sheet->setCellValue("{$colLetter}{$r1}", 'ÑAÏI HOÏC VOÕ TRÖÔØNG TOAÛN');
            $style1 = $sheet->getStyle("{$colLetter}{$r1}");
            $style1->getFont()->setName('VNI-Revue')->setSize(7)->setBold(false);
            $style1->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $style1->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $style1->getBorders()->applyFromArray([
                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED, 'color' => ['rgb' => '000000']],
                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
            ]);

            // Write Cell 2: Subtitle
            $sheet->setCellValue("{$colLetter}{$r2}", 'THƯ VIỆN');
            $style2 = $sheet->getStyle("{$colLetter}{$r2}");
            $style2->getFont()->setName('Times New Roman')->setSize(9)->setBold(false);
            $style2->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $style2->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $style2->getBorders()->applyFromArray([
                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED, 'color' => ['rgb' => '000000']],
                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
            ]);

            // Write Cell 3: Barcode in Code 93 d
            $barcodeCode = $item->barcode;
            $barcodeVal = '(' . $barcodeCode . $this->getCode93Checksum($barcodeCode) . ')';
            $sheet->setCellValue("{$colLetter}{$r3}", $barcodeVal);
            $style3 = $sheet->getStyle("{$colLetter}{$r3}");
            $style3->getFont()->setName('Code 93 d')->setSize(20)->setBold(false);
            $style3->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $style3->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $style3->getBorders()->applyFromArray([
                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED, 'color' => ['rgb' => '000000']],
                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
            ]);

            // Write Cell 4: Barcode raw string (Times New Roman)
            $sheet->setCellValue("{$colLetter}{$r4}", $barcodeCode);
            $style4 = $sheet->getStyle("{$colLetter}{$r4}");
            $style4->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
            $style4->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $style4->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $style4->getBorders()->applyFromArray([
                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE],
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
            ]);
        }

        return [];
    }
}
