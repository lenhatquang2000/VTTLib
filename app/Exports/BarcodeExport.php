<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class BarcodeExport implements FromCollection, WithTitle, WithStyles, ShouldAutoSize
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
        // Chúng ta không dùng collection trực tiếp để render data trong ô, 
        // mà sẽ dùng hàm styles để vẽ lưới label
        return collect([]);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        $itemsPerRow = 4; // 4 tem trên 1 hàng ngang như hình
        $row = 1;
        $col = 1;

        // Định dạng chung cho trang
        $sheet->getDefaultRowDimension()->setRowHeight(25);
        
        foreach ($this->items as $index => $item) {
            $currentColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            
            // Một con tem gồm 4 dòng thông tin
            // Dòng 1: Tên trường
            // Dòng 2: THƯ VIỆN
            // Dòng 3: Mã vạch chính (To)
            // Dòng 4: Mã số phụ (Nhỏ)

            $startRow = ($MathFloor = floor($index / $itemsPerRow)) * 6 + 1;
            $startCol = (($index % $itemsPerRow) * 2) + 1; // Mỗi tem cách nhau 1 cột trống hoặc dùng 1 cột rộng
            
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            
            // Ghi nội dung vào các ô
            $sheet->setCellValue($colLetter . ($startRow), 'ĐẠI HỌC VÕ TRƯỜNG TOẢN');
            $sheet->setCellValue($colLetter . ($startRow + 1), 'THƯ VIỆN');
            
            // Mã vạch cho Font Code 39 cần có ký tự * ở đầu và cuối để máy quét nhận diện
            $barcodeText = '*' . $item->barcode . '*';
            $sheet->setCellValue($colLetter . ($startRow + 2), $barcodeText);
            
            $sheet->setCellValue($colLetter . ($startRow + 3), $item->accession_number ?: substr($item->barcode, 0, 9));

            // Style cho từng tem
            $cellRange = $colLetter . $startRow . ':' . $colLetter . ($startRow + 3);
            
            // Căn giữa toàn bộ
            $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cellRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // Font chữ
            $sheet->getStyle($colLetter . $startRow)->getFont()->setSize(8);
            $sheet->getStyle($colLetter . ($startRow + 1))->getFont()->setSize(9)->setBold(true);
            
            // Áp dụng Font mã vạch cho dòng này
            // Lưu ý: Người dùng cần cài font 'Libre Barcode 39' hoặc 'Code39' trên máy tính
            $sheet->getStyle($colLetter . ($startRow + 2))->getFont()->setName('Libre Barcode 39')->setSize(24);
            
            $sheet->getStyle($colLetter . ($startRow + 3))->getFont()->setSize(9);

            // Border xung quanh con tem
            $sheet->getStyle($cellRange)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            // Kẻ dòng ngăn cách trong tem (nếu cần giống hình)
            $sheet->getStyle($colLetter . $startRow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED);
            $sheet->getStyle($colLetter . ($startRow + 1))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($colLetter . ($startRow + 2))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Độ rộng cột cho tem
            $sheet->getColumnDimension($colLetter)->setWidth(30);
        }

        return [];
    }
}
