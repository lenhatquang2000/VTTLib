<?php

namespace App\Jobs;

use App\Models\ExportHistory;
use App\Models\BibliographicRecord;
use App\Http\Controllers\Admin\MarcReportController;
use App\Exports\DynamicMarcReportExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExportMarcReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $historyId;
    protected $requestData;
    protected $reportType;
    protected $format;

    /**
     * Create a new job instance.
     */
    public function __construct(int $historyId, array $requestData, string $reportType, string $format)
    {
        $this->historyId   = $historyId;
        $this->requestData = $requestData;
        $this->reportType  = $reportType;
        $this->format      = $format;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $history = ExportHistory::find($this->historyId);
        if (!$history) {
            return;
        }

        try {
            $history->update(['status' => 'processing']);

            // 1. Tạo request ảo từ requestData
            $request = Request::create('/admin/marc-reports/generate', 'POST', $this->requestData);

            // 2. Dựng lại base query tương ứng
            $itemBasedReports = ['inventory_report', 'accession_book', 'spine_label', 'barcode_list', 'inventory_status', 'generated_barcodes'];
            
            $controller = app(MarcReportController::class);

            if (in_array($this->reportType, $itemBasedReports)) {
                $query = \App\Models\BookItem::with(['bibliographicRecord.fields.subfields', 'branch', 'storageLocation']);
                $query = $controller->applyAdvancedFilters($query, $request, $this->reportType, $itemBasedReports);
                $query->latest();
            } else {
                $query = BibliographicRecord::with(['fields.subfields'])
                    ->where('status', BibliographicRecord::STATUS_APPROVED);

                if (in_array($this->reportType, ['book_stats', 'book_id_list', 'book_title_qty', 'cataloging_subsystem', 'article_index'])) {
                    $query->withCount('items');
                }

                if (in_array($this->reportType, ['book_stats', 'book_id_list', 'book_title_qty', 'cataloging_subsystem'])) {
                    $query->where(function($q) {
                        $q->where('record_type', 'resource')
                          ->orWhereHas('items');
                    });
                }

                $query = $controller->applyAdvancedFilters($query, $request, $this->reportType, $itemBasedReports);
                $query->latest();
            }

            // Lấy meta
            $meta = $controller->getReportMeta($this->reportType);

            // 3. Đường dẫn lưu trữ
            $fileName = $meta['file_prefix'] . '_' . now()->format('Ymd_His');
            $extension = $this->format === 'csv' ? 'csv' : 'xlsx';
            $relativeFilePath = 'exports/' . $fileName . '.' . $extension;

            // Đảm bảo thư mục exports tồn tại
            if (!Storage::disk('local')->exists('exports')) {
                Storage::disk('local')->makeDirectory('exports');
            }

            // 4. Thực hiện render và lưu trữ file
            if ($this->format === 'excel') {
                if (in_array($this->reportType, ['barcode_list', 'generated_barcodes'])) {
                    $records = $query->get();
                    Excel::store(
                        new \App\Exports\BarcodeExport($records, $meta['title']),
                        $relativeFilePath,
                        'local'
                    );
                } else {
                    Excel::store(
                        new DynamicMarcReportExport($meta['headers'], $meta['title'], $query, $this->reportType),
                        $relativeFilePath,
                        'local'
                    );
                }
            } elseif ($this->format === 'csv') {
                Excel::store(
                    new DynamicMarcReportExport($meta['headers'], $meta['title'], $query, $this->reportType),
                    $relativeFilePath,
                    'local',
                    \Maatwebsite\Excel\Excel::CSV
                );
            }

            // 5. Cập nhật trạng thái thành công
            $history->update([
                'status' => 'completed',
                'file_path' => $relativeFilePath
            ]);

        } catch (\Throwable $e) {
            Log::error('Lỗi khi chạy background export: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            $history->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
