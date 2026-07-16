<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Log;

/**
 * Excel export using FromQuery + WithChunkReading + WithMapping.
 *
 * WHY NOT lazy() / FromGenerator+chunk()?
 *   - lazy() uses a raw PDO cursor -> strips eager-loaded relations -> N+1 queries.
 *   - yield inside a closure doesn't propagate to the outer generator function.
 *
 * WHY FromQuery + WithChunkReading?
 *   - Maatwebsite reads rows in batches (chunkSize=500), calling map() per row.
 *   - The base query already has with(['fields.subfields', 'items']) so each
 *     chunk fires only 2-3 extra queries (IN (...) instead of one per record).
 */
class DynamicMarcReportExport implements
    FromQuery,
    WithChunkReading,
    WithHeadings,
    WithTitle,
    WithStyles,
    ShouldAutoSize,
    WithCustomStartCell,
    WithMapping
{
    protected array  $headers;
    protected string $title;
    protected        $baseQuery;
    protected string $reportType;
    protected int    $rowCounter = 0;
    protected ?int   $historyId = null;
    protected int    $totalRows = 0;
    protected float  $lastProgressUpdateAt = 0.0;

    /** @var float Thời điểm bắt đầu export (microtime) */
    protected float $exportStartTime;

    /** @var float Thời điểm chunk hiện tại bắt đầu */
    protected float $chunkStartTime;

    /** @var int Số chunk đã xử lý */
    protected int $chunkIndex = 0;

    /** @var int Số row trong chunk hiện tại */
    protected int $chunkRowCount = 0;

    public function __construct(array $headers, string $title, $baseQuery, string $reportType, ?int $historyId = null)
    {
        $this->exportStartTime = microtime(true);
        $this->chunkStartTime  = microtime(true);

        $this->headers    = $headers;
        $this->title      = $title;
        $this->baseQuery  = $baseQuery;
        $this->reportType = $reportType;
        $this->historyId  = $historyId;

        // Query total rows count for progress tracking
        try {
            $this->totalRows = $this->baseQuery ? $this->baseQuery->clone()->count() : 0;
        } catch (\Exception $e) {
            $this->totalRows = 0;
        }
        $this->lastProgressUpdateAt = microtime(true);

        Log::channel('single')->info('[EXPORT] ====== BẮT ĐẦU EXPORT ======', [
            'report_type' => $reportType,
            'title'       => $title,
            'time'        => now()->toDateTimeString(),
        ]);
    }

    // FromQuery: return the builder; Maatwebsite will paginate it via chunkSize.
    public function query()
    {
        $t = microtime(true);
        Log::channel('single')->info('[EXPORT] query() được gọi - chuẩn bị builder', [
            'elapsed_ms' => round(($t - $this->exportStartTime) * 1000, 2),
        ]);
        if (in_array($this->reportType, ['book_stats', 'spine_label'])) {
            return $this->baseQuery->clone()->whereRaw('1 = 0');
        }
        return $this->baseQuery;
    }

    // 1000 records per chunk = 3 DB queries per chunk (SELECT + fields IN + subfields IN).
    // Chunk lớn hơn = ít lần PhpSpreadsheet flush buffer hơn = nhanh hơn tổng thể.
    public function chunkSize(): int
    {
        return 1000;
    }

    // WithMapping: called once per row after eager loading resolves.
    // Zero extra DB queries here - all relations are already in memory.
    public function map($record): array
    {
        $this->rowCounter++;
        $this->chunkRowCount++;

        if ($this->historyId && $this->totalRows > 0) {
            $currentTime = microtime(true);
            $percentage = (int)(($this->rowCounter / $this->totalRows) * 100);
            if ($currentTime - $this->lastProgressUpdateAt >= 0.3 || $this->rowCounter === $this->totalRows) {
                // Check if history record has been deleted by user (aborted)
                $historyExists = \App\Models\ExportHistory::where('id', $this->historyId)->exists();
                if (!$historyExists) {
                    throw new \Exception('Export aborted by user.');
                }

                $calculatedProgress = min(95, max(15, $percentage));
                \App\Models\ExportHistory::where('id', $this->historyId)->update(['progress' => $calculatedProgress]);
                $this->lastProgressUpdateAt = $currentTime;
            }
        }

        if ($this->chunkRowCount === 1) {
            $this->chunkIndex++;
        }

        if ($this->chunkRowCount >= $this->chunkSize()) {
            $this->chunkRowCount  = 0;
            $this->chunkStartTime = microtime(true);
        }

        $itemBasedReports = [
            'inventory_report', 'accession_book', 'spine_label',
            'barcode_list', 'inventory_status', 'generated_barcodes',
        ];
        $isItemBased = in_array($this->reportType, $itemBasedReports);
        return $this->mapRow($record, $this->rowCounter, $isItemBased);
    }

    protected function mapRow($record, int $counter, bool $isItemBased): array
    {
        switch ($this->reportType) {
            case 'cataloging_subsystem':
                return [
                    $counter, $record->id,
                    $this->marc($record, '245', 'a'),
                    $this->marc($record, '100', 'a') ?: $this->marc($record, '700', 'a'),
                    optional($record->created_at)->format('d/m/Y'),
                    $record->status,
                ];
            case 'book_stats':
                $year  = $this->marc($record, '260', 'c') ?: $this->marc($record, '264', 'c');
                $pub   = $this->marc($record, '260', 'b') ?: $this->marc($record, '264', 'b');
                $ddc   = $this->marc($record, '082', 'a') ?: $this->marc($record, '090', 'a');
                $auth  = $this->marc($record, '082', 'b')
                       ?: ($this->marc($record, '090', 'b')
                       ?: ($this->marc($record, '100', 'a') ? mb_substr($this->marc($record, '100', 'a'), 0, 3, 'UTF-8') : ''));
                $price = $this->marc($record, '952', 'g') ?: ($this->marc($record, '020', 'c') ?: '0');
                return [
                    $counter, $this->marc($record, '245', 'a') ?: 'Khong co nhan de',
                    $pub ?: '...', $year ?: '...', $ddc ?: '...', $auth ?: '...',
                    $record->items_count ?? 0, $price,
                ];
            case 'accession_book':
                $bib   = $record->bibliographicRecord;
                $year  = $this->marc($bib, '260', 'c') ?: $this->marc($bib, '264', 'c');
                $place = $this->marc($bib, '260', 'a') ?: $this->marc($bib, '264', 'a');
                return [
                    $counter, $record->barcode,
                    $this->marc($bib, '245', 'a'),
                    $this->marc($bib, '100', 'a') ?: $this->marc($bib, '700', 'a'),
                    $year, $place,
                    $this->marc($bib, '952', 'g') ?: '...',
                    $record->location,
                ];
            case 'spine_label':
                $bib      = $record->bibliographicRecord;
                $ddc      = $this->marc($bib, '082', 'a');
                $authCode = $this->marc($bib, '082', 'b')
                          ?: ($this->marc($bib, '090', 'b')
                          ?: mb_substr((string)($this->marc($bib, '100', 'a') ?? ''), 0, 3, 'UTF-8'));
                return [
                    $counter, $record->barcode,
                    $this->marc($bib, '245', 'a'),
                    $ddc, $authCode, trim($ddc . ' ' . $authCode),
                ];
            case 'inventory_report':
                $bib = $record->bibliographicRecord;
                return [
                    $counter, $record->barcode,
                    $this->marc($bib, '245', 'a'),
                    $record->location, $record->status,
                    optional($record->created_at)->format('d/m/Y'),
                ];
            case 'article_index':
                return [
                    $counter,
                    $this->marc($record, '245', 'a'), $this->marc($record, '100', 'a'),
                    $this->marc($record, '773', 't'), $this->marc($record, '773', 'g'),
                    $this->marc($record, '773', 'q'),
                ];
            case 'book_id_list':
                $bib = $record->bibliographicRecord;
                $ddc = $this->marc($bib, '082', 'a') ?: $this->marc($bib, '090', 'a');
                return [
                    $counter,
                    $record->accession_number ?: $record->barcode,
                    $this->marc($bib, '245', 'a'),
                    $this->marc($bib, '100', 'a') ?: $this->marc($bib, '700', 'a'),
                    $ddc ?: '...',
                    $record->storageLocation?->code ?: ($record->location ?: '...'),
                    '',
                    '',
                    $record->notes ?: '',
                ];
            case 'inventory_status':
                $bib = $record->bibliographicRecord;
                return [
                    $counter, $record->barcode,
                    $this->marc($bib, '245', 'a'),
                    optional($record->branch)->name ?: $record->location,
                    optional($record->storageLocation)->name ?: '...',
                    $record->status,
                    optional($record->created_at)->format('d/m/Y'),
                ];
            case 'generated_barcodes':
                $bib = $record->bibliographicRecord;
                return [
                    $counter, $record->barcode,
                    $this->marc($bib, '245', 'a'),
                    optional($record->created_at)->format('d/m/Y H:i'),
                ];
            case 'book_title_qty':
                $titleA = $this->marc($record, '245', 'a');
                $titleB = $this->marc($record, '245', 'b');
                $titleC = $this->marc($record, '245', 'c');
                $fullTitle = $titleA;
                if ($titleB) $fullTitle .= ' : ' . $titleB;
                if ($titleC) $fullTitle .= ' / ' . $titleC;

                $publisher = $this->marc($record, '260', 'b') ?: $this->marc($record, '264', 'b');
                $year = $this->marc($record, '260', 'c') ?: $this->marc($record, '264', 'c');
                if ($year) $year = preg_replace('/[^0-9]/', '', $year);

                $ddc = $this->marc($record, '082', 'a') ?: $this->marc($record, '090', 'a');
                $authorCode = $this->marc($record, '082', 'b') ?: ($this->marc($record, '090', 'b') ?: ($this->marc($record, '100', 'a') ? mb_substr($this->marc($record, '100', 'a'), 0, 3, 'UTF-8') : ''));
                
                $qty = $record->items_count ?? $record->items->count();
                
                $priceVal = $this->marc($record, '020', 'c') ?: $this->marc($record, '020', 'd');
                $price = '';
                if ($priceVal) {
                    $digits = preg_replace('/[^0-9]/', '', $priceVal);
                    if ($digits !== '') {
                        $price = (int)$digits;
                    } else {
                        $price = $priceVal;
                    }
                }

                $author = $this->marc($record, '100', 'a') ?: ($this->marc($record, '700', 'a') ?: '');
                $barcodes = $record->items ? $record->items->pluck('barcode')->implode(', ') : '';

                return [
                    $counter,
                    $barcodes,
                    $fullTitle,
                    $author,
                    $publisher,
                    $year,
                    $ddc,
                    $authorCode,
                    $qty,
                    $price
                ];
            default:
                return [
                    $counter, $record->id,
                    $this->marc($record, '245', 'a'), $this->marc($record, '100', 'a'),
                    $this->marc($record, '260', 'c'),
                    $record->items_count ?? $record->items->count(),
                ];
        }
    }

    /**
     * Extract MARC subfield value from in-memory eager-loaded collection.
     * Gọi trực tiếp qua Model getMarcValue() đã được tối ưu hóa O(1).
     */
    protected function marc($record, string $tag, string $code): ?string
    {
        return $record ? $record->getMarcValue($tag, $code) : null;
    }

    public function headings(): array   
    { 
        if (in_array($this->reportType, ['book_stats', 'spine_label'])) {
            return [];
        }
        return $this->headers; 
    }

    public function title(): string     { return $this->title; }
    
    public function startCell(): string 
    { 
        if ($this->reportType === 'book_id_list') {
            return 'A7'; 
        }
        if ($this->reportType === 'book_title_qty') {
            return 'A3'; 
        }
        return 'A4'; 
    }

    public function styles(Worksheet $sheet)
    {
        $stylesStart = microtime(true);

        // --- Log tổng kết mapping trước khi apply styles ---
        $totalElapsed = round((microtime(true) - $this->exportStartTime) * 1000, 2);
        Log::channel('single')->info('[EXPORT] styles() - Mapping hoàn tất, bắt đầu apply styles', [
            'total_rows_mapped' => $this->rowCounter,
            'total_chunks'      => $this->chunkIndex,
            'elapsed_ms'        => $totalElapsed,
        ]);

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->headers));
        
        $styleResult = [];

        if ($this->reportType === 'spine_label') {
            // Set column widths
            $widths = [
                'A' => 1.81640625,
                'B' => 17.54296875,
                'C' => 7.90625,
                'D' => 17.54296875,
                'E' => 7.90625,
                'F' => 17.54296875,
                'G' => 7.90625,
                'H' => 17.54296875,
                'I' => 3,
                'J' => 20.90625,
                'K' => 9
            ];
            foreach ($widths as $col => $w) {
                $sheet->getColumnDimension($col)->setWidth($w);
            }

            // Set top margin height
            $sheet->getRowDimension(1)->setRowHeight(16);

            // Fetch records using baseQuery
            $records = $this->baseQuery->get();

            $cols = ['B', 'D', 'F', 'H'];
            
            foreach ($records as $index => $item) {
                $colLetter = $cols[$index % 4];
                $rowIndex = floor($index / 4);
                
                $r1 = 2 + $rowIndex * 4;
                $r2 = 3 + $rowIndex * 4;
                $r3 = 4 + $rowIndex * 4;
                $r4 = 5 + $rowIndex * 4; // separator row

                // Set row heights
                $sheet->getRowDimension($r1)->setRowHeight(23.5);
                $sheet->getRowDimension($r2)->setRowHeight(23.5);
                $sheet->getRowDimension($r3)->setRowHeight(23.5);
                $sheet->getRowDimension($r4)->setRowHeight(19.75);

                $record = $item->bibliographicRecord;
                $ddc = $record->getMarcValue('082', 'a') ?: $record->getMarcValue('090', 'a');
                $authorCode = $record->getMarcValue('082', 'b') ?: ($record->getMarcValue('090', 'b') ?: ($record->getMarcValue('100', 'a') ? mb_substr($record->getMarcValue('100', 'a'), 0, 3, 'UTF-8') : ''));

                // Write Cell 1: Library Prefix
                $sheet->setCellValue("{$colLetter}{$r1}", 'TV ĐH VTT');
                $style1 = $sheet->getStyle("{$colLetter}{$r1}");
                $style1->getFont()->setName('Times New Roman')->setSize(12)->setBold(false);
                $style1->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $style1->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $style1->getBorders()->applyFromArray([
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ]);

                // Write Cell 2: DDC
                $sheet->setCellValue("{$colLetter}{$r2}", $ddc ?: '');
                $style2 = $sheet->getStyle("{$colLetter}{$r2}");
                $style2->getFont()->setName('Times New Roman')->setSize(12)->setBold(true);
                $style2->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $style2->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $style2->getBorders()->applyFromArray([
                    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED, 'color' => ['rgb' => '000000']],
                    'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                ]);

                // Write Cell 3: Author Code
                $sheet->setCellValue("{$colLetter}{$r3}", $authorCode ?: '');
                $style3 = $sheet->getStyle("{$colLetter}{$r3}");
                $style3->getFont()->setName('Times New Roman')->setSize(12)->setBold(true);
                $style3->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $style3->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $style3->getBorders()->applyFromArray([
                    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE],
                    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                ]);
            }
        } elseif ($this->reportType === 'book_stats') {
            // Write school headers in row 1, 2, 3
            $sheet->setCellValue('A1', 'THƯ VIỆN - TRƯỜNG ĐẠI HỌC VÕ TRƯỜNG TOẢN');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11);
            
            $sheet->setCellValue('A2', 'Địa chỉ: Quốc lộ 1A, xã Thạnh Xuân, Thành phố Cần Thơ');
            $sheet->getStyle('A2')->getFont()->setSize(10);
            
            $sheet->setCellValue('A3', 'Website: http://library.vttu.edu.vn/');
            $sheet->getStyle('A3')->getFont()->setSize(10);

            // Write report title in row 5
            $sheet->mergeCells("A5:C5");
            $sheet->setCellValue('A5', 'THỐNG KÊ SỐ LƯỢNG ĐẦU SÁCH TRONG THƯ VIỆN');
            $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Fetch records using baseQuery
            $records = $this->baseQuery->get();
            $storageLocations = \App\Models\StorageLocation::with('branch')->where('is_active', true)->get();
            $documentTypes = \App\Models\DocumentType::where('is_active', true)->orderBy('order', 'asc')->get();
            
            $counts = [];
            foreach ($storageLocations as $loc) {
                foreach ($documentTypes as $dt) {
                    $counts[$loc->id][$dt->id] = 0;
                }
            }

            foreach ($records as $record) {
                $dtId = $record->document_type_id;
                if (!$dtId) continue;
                
                foreach ($record->items as $item) {
                    $locId = $item->storage_location_id;
                    if ($locId && isset($counts[$locId][$dtId])) {
                        $counts[$locId][$dtId]++;
                    }
                }
            }

            $currentRow = 7;
            foreach ($storageLocations as $loc) {
                $locCounts = $counts[$loc->id] ?? [];
                $totalLocSum = array_sum($locCounts);

                // Write Storage Location Code and Name
                $sheet->setCellValue("A{$currentRow}", $loc->code);
                $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
                $sheet->setCellValue("B{$currentRow}", $loc->name);
                $sheet->getStyle("B{$currentRow}")->getFont()->setBold(true);
                
                $currentRow++;

                // Table headers: STT, THỂ LOẠI TÀI LIỆU, SỐ LƯỢNG
                $sheet->setCellValue("A{$currentRow}", 'STT');
                $sheet->setCellValue("B{$currentRow}", 'THỂ LOẠI TÀI LIỆU');
                $sheet->setCellValue("C{$currentRow}", 'SỐ LƯỢNG');
                
                // Style table headers
                $headerStyle = [
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'B4C6E7'] // Medium slate blue-grey
                    ],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ];
                $sheet->getStyle("A{$currentRow}:C{$currentRow}")->applyFromArray($headerStyle);
                
                $tableStartRow = $currentRow;
                $currentRow++;

                $stt = 1;
                foreach ($documentTypes as $dt) {
                    $qty = $locCounts[$dt->id] ?? 0;
                    
                    $sheet->setCellValue("A{$currentRow}", $stt++);
                    $sheet->setCellValue("B{$currentRow}", $dt->name);
                    $sheet->setCellValue("C{$currentRow}", $qty);
                    
                    $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    
                    $currentRow++;
                }

                // Total row
                $sheet->setCellValue("B{$currentRow}", 'Tổng số:');
                $sheet->getStyle("B{$currentRow}")->getFont()->setBold(true);
                $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                
                $sheet->setCellValue("C{$currentRow}", $totalLocSum);
                $sheet->getStyle("C{$currentRow}")->getFont()->setBold(true);
                $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
                $tableEndRow = $currentRow;
                
                // Apply borders
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
                $sheet->getStyle("A{$tableStartRow}:C{$tableEndRow}")->applyFromArray($borderStyle);
                
                $currentRow += 3;
            }
        } elseif ($this->reportType === 'book_id_list') {
            // Write school headers in row 1, 2, 3
            $sheet->setCellValue('A1', 'THƯ VIỆN - TRƯỜNG ĐẠI HỌC VÕ TRƯỜNG TOẢN');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11);
            
            $sheet->setCellValue('A2', 'Địa chỉ: Quốc lộ 1A, xã Thạnh Xuân, Thành phố Cần Thơ');
            $sheet->getStyle('A2')->getFont()->setSize(10);
            
            $sheet->setCellValue('A3', 'Website: http://library.vttu.edu.vn/');
            $sheet->getStyle('A3')->getFont()->setSize(10);

            // Write report title in row 5
            $sheet->mergeCells("A5:{$lastCol}5");
            $sheet->setCellValue('A5', 'DANH SÁCH TÀI LIỆU TRONG THƯ VIỆN');
            $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Header style on row 7
            $sheet->getStyle("A7:{$lastCol}7")->getFont()->setBold(true);
            $sheet->getStyle("A7:{$lastCol}7")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            // Add grid borders to the entire table
            $highestRow = $sheet->getHighestRow();
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];
            $sheet->getStyle("A7:{$lastCol}{$highestRow}")->applyFromArray($styleArray);
        } elseif ($this->reportType === 'book_title_qty') {
            // Write report title in row 1
            $sheet->mergeCells("A1:J1");
            $sheet->setCellValue('A1', 'DANH SÁCH NHAN ĐỀ VÀ SỐ LƯỢNG');
            
            // Set row heights
            $sheet->getRowDimension(1)->setRowHeight(35);
            $sheet->getRowDimension(3)->setRowHeight(25);

            // Set column widths
            $widths = [
                'A' => 6,   // STT
                'B' => 18,  // Mã vạch
                'C' => 50,  // Nhan đề
                'D' => 20,  // Tác giả
                'E' => 25,  // Nhà xuất bản
                'F' => 10,  // Năm XB
                'G' => 15,  // Số phân loại
                'H' => 12,  // Mã hóa
                'I' => 10,  // Số bản
                'J' => 15   // Giá tiền
            ];
            foreach ($widths as $col => $w) {
                $sheet->getColumnDimension($col)->setWidth($w);
            }

            $highestRow = $sheet->getHighestRow();

            // Set Times New Roman for the entire sheet area
            $sheet->getStyle("A1:J{$highestRow}")->getFont()->setName('Times New Roman');

            // Style Title
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // Header style on row 3
            $sheet->getStyle("A3:J3")->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle("A3:J3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A3:J3")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            
            // Add grid borders to the entire table (from row 3 to highestRow)
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];
            $sheet->getStyle("A3:J{$highestRow}")->applyFromArray($styleArray);

            // Set default row height for the sheet, and override specific headers
            $sheet->getDefaultRowDimension()->setRowHeight(20);
            $sheet->getRowDimension(1)->setRowHeight(35);
            $sheet->getRowDimension(3)->setRowHeight(25);

            // Alignments, wrap text, and number formats using range-based styling (3000x faster than cell-by-cell loop)
            $sheet->getStyle("A4:A{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A4:A{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // Mã vạch
            $sheet->getStyle("B4:B{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B4:B{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle("B4:B{$highestRow}")->getAlignment()->setWrapText(true);

            // Nhan đề
            $sheet->getStyle("C4:C{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C4:C{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle("C4:C{$highestRow}")->getAlignment()->setWrapText(true);

            // Tác giả
            $sheet->getStyle("D4:D{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("D4:D{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle("D4:D{$highestRow}")->getAlignment()->setWrapText(true);

            // Nhà xuất bản
            $sheet->getStyle("E4:E{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("E4:E{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle("E4:E{$highestRow}")->getAlignment()->setWrapText(true);

            // Năm XB
            $sheet->getStyle("F4:F{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F4:F{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // Số phân loại
            $sheet->getStyle("G4:G{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("G4:G{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // Mã hóa
            $sheet->getStyle("H4:H{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("H4:H{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // Số bản
            $sheet->getStyle("I4:I{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I4:I{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // Giá tiền
            $sheet->getStyle("J4:J{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("J4:J{$highestRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle("J4:J{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');

        } else {
            $sheet->mergeCells("A1:{$lastCol}1");
            $sheet->setCellValue('A1', $this->title);
            $sheet->mergeCells("A2:{$lastCol}2");
            $sheet->setCellValue('A2', 'Ngay xuat bao cao: ' . now()->format('d/m/Y H:i'));

            $styleResult = [
                1 => [
                    'font'      => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ],
                2 => [
                    'font'      => ['italic' => true, 'size' => 11],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ],
                4 => [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '680102'],
                    ],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ],
            ];
        }

        $styleMs      = round((microtime(true) - $stylesStart) * 1000, 2);
        $finalElapsed = round((microtime(true) - $this->exportStartTime) * 1000, 2);
        Log::channel('single')->info('[EXPORT] ====== EXPORT HOÀN TẤT ======', [
            'total_rows'     => $this->rowCounter,
            'total_chunks'   => $this->chunkIndex,
            'styles_ms'      => $styleMs,
            'total_time_ms'  => $finalElapsed,
            'total_time_sec' => round($finalElapsed / 1000, 2),
        ]);

        return $styleResult;
    }
}
