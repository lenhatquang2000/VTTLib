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

    /** @var float Thời điểm bắt đầu export (microtime) */
    protected float $exportStartTime;

    /** @var float Thời điểm chunk hiện tại bắt đầu */
    protected float $chunkStartTime;

    /** @var int Số chunk đã xử lý */
    protected int $chunkIndex = 0;

    /** @var int Số row trong chunk hiện tại */
    protected int $chunkRowCount = 0;

    public function __construct(array $headers, string $title, $baseQuery, string $reportType)
    {
        $this->exportStartTime = microtime(true);
        $this->chunkStartTime  = microtime(true);

        $this->headers    = $headers;
        $this->title      = $title;
        $this->baseQuery  = $baseQuery;
        $this->reportType = $reportType;

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

        // --- Log khi bắt đầu chunk mới (mỗi chunkSize rows) ---
        if ($this->chunkRowCount === 1) {
            $this->chunkIndex++;
            $elapsed = round((microtime(true) - $this->exportStartTime) * 1000, 2);
            Log::channel('single')->info("[EXPORT] >>> Bắt đầu CHUNK #{$this->chunkIndex}", [
                'row_start'  => $this->rowCounter,
                'elapsed_ms' => $elapsed,
            ]);
        }

        // --- Log mỗi 100 rows để theo dõi tiến độ ---
        if ($this->rowCounter % 100 === 0) {
            $elapsed    = round((microtime(true) - $this->exportStartTime) * 1000, 2);
            $chunkMs    = round((microtime(true) - $this->chunkStartTime) * 1000, 2);
            Log::channel('single')->info("[EXPORT] Đã map {$this->rowCounter} rows", [
                'chunk'         => $this->chunkIndex,
                'total_elapsed_ms' => $elapsed,
                'chunk_elapsed_ms' => $chunkMs,
                'rows_this_chunk'  => $this->chunkRowCount,
            ]);
        }

        // --- Reset chunk counter khi đủ chunkSize ---
        if ($this->chunkRowCount >= $this->chunkSize()) {
            $chunkMs = round((microtime(true) - $this->chunkStartTime) * 1000, 2);
            Log::channel('single')->info("[EXPORT] <<< Hoàn thành CHUNK #{$this->chunkIndex}", [
                'rows_processed'   => $this->chunkRowCount,
                'chunk_elapsed_ms' => $chunkMs,
                'total_rows_done'  => $this->rowCounter,
            ]);
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
                return [
                    $counter, $record->id,
                    $this->marc($record, '245', 'a'),
                    $this->marc($record, '100', 'a') ?: $this->marc($record, '700', 'a'),
                    $record->items_count ?? 0,
                    $this->marc($record, '260', 'c') ?: $this->marc($record, '264', 'c'),
                    $this->marc($record, '082', 'a'),
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
                return [
                    $counter, $record->id,
                    $this->marc($record, '245', 'a'),
                    $this->marc($record, '100', 'a') ?: $this->marc($record, '700', 'a'),
                    $this->marc($record, '260', 'c') ?: $this->marc($record, '264', 'c'),
                    $record->items_count ?? $record->items->count(),
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

    public function headings(): array   { return $this->headers; }
    public function title(): string     { return $this->title; }
    public function startCell(): string { return 'A4'; }

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
