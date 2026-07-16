<?php

namespace App\Jobs;

use App\Models\ExportHistory;
use App\Models\PatronDetail;
use App\Http\Controllers\Admin\PatronReportController;
use App\Exports\DynamicPatronReportExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExportPatronReportJob implements ShouldQueue
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

        $startTime = microtime(true);

        try {
            $history->update(['status' => 'processing']);

            // 1. Create fake request
            $request = Request::create('/admin/patron-reports/generate', 'POST', $this->requestData);

            // 2. Build the query
            $controller = app(PatronReportController::class);
            $query = $controller->buildQuery($request);

            // Clear default heavy eager loads to optimize speed and memory
            $query->setEagerLoads([]);

            // Eager load only the relationships required for the specific report type
            if ($this->reportType === 'patron_list' || $this->reportType === 'viewer_patron_list') {
                $query->with(['addresses']);
            } elseif ($this->reportType === 'renew_list' || $this->reportType === 'renew_by_period') {
                $query->with(['patronGroup']);
            }

            // Limit results
            $limit = intval($request->input('result_limit', 0));
            if ($limit > 0) {
                $query->limit($limit);
            }

            $patrons = $query->latest('registration_date')->get();

            // Prepare export rows based on report_type
            $title = '';
            $prefix = 'patrons';
            $headers = [];
            $rows = [];

            switch ($this->reportType) {
                case 'print_cards':
                case 'viewer_print_cards':
                    $title = __('Báo cáo in thẻ độc giả');
                    $prefix = 'in_the_doc_gia';
                    $headers = [__('STT'), __('Mã bạn đọc'), __('Họ tên'), __('Ngày sinh'), __('Giới tính'), __('Đơn vị / Lớp'), __('Ngày hết hạn')];
                    foreach ($patrons as $index => $patron) {
                        $rows[] = [
                            $index + 1,
                            $patron->patron_code,
                            $patron->display_name,
                            $patron->dob ? \Carbon\Carbon::parse($patron->dob)->format('d/m/Y') : '',
                            $patron->gender === 'male' ? __('Nam') : ($patron->gender === 'female' ? __('Nữ') : ''),
                            $patron->department ?: ($patron->position_class ?: ''),
                            $patron->expiry_date ? \Carbon\Carbon::parse($patron->expiry_date)->format('d/m/Y') : ''
                        ];
                    }
                    break;

                case 'renew_list':
                case 'renew_by_period':
                    $title = __('Báo cáo gia hạn thẻ độc giả');
                    $prefix = 'gia_han_the';
                    $headers = [__('STT'), __('Mã bạn đọc'), __('Họ tên'), __('Nhóm bạn đọc'), __('Số điện thoại'), __('Ngày hết hạn'), __('Trạng thái')];
                    foreach ($patrons as $index => $patron) {
                        $rows[] = [
                            $index + 1,
                            $patron->patron_code,
                            $patron->display_name,
                            optional($patron->patronGroup)->name,
                            $patron->phone ?: ($patron->phone_contact ?: ''),
                            $patron->expiry_date ? \Carbon\Carbon::parse($patron->expiry_date)->format('d/m/Y') : '',
                            $patron->card_status === 'normal' ? __('Hoạt động') : __('Bị khóa')
                        ];
                    }
                    break;

                case 'patron_list':
                case 'viewer_patron_list':
                default:
                    $title = __('Danh sách độc giả trong thư viện');
                    $prefix = 'danh_sach_doc_gia';
                    $headers = [
                        __('STT'),
                        __('Mã số'),
                        __('Họ và tên'),
                        __('Ngày sinh'),
                        __('Giới tính'),
                        __('CMND'),
                        __('Bộ phận (Đơn vị)'),
                        __('Địa chỉ'),
                        __('Ngày cấp thẻ'),
                        __('Hạn thẻ')
                    ];
                    foreach ($patrons as $index => $patron) {
                        $rows[] = [
                            $index + 1,
                            $patron->patron_code,
                            $patron->display_name,
                            $patron->dob ? \Carbon\Carbon::parse($patron->dob)->format('n/j/Y') : '',
                            $patron->gender === 'male' ? __('Nam') : ($patron->gender === 'female' ? __('Nữ') : ''),
                            $patron->id_card,
                            $patron->department ?: ($patron->position_class ?: ''),
                            $patron->addresses->where('is_primary', true)->first()?->address_line ?? $patron->addresses->first()?->address_line ?? '',
                            $patron->registration_date ? \Carbon\Carbon::parse($patron->registration_date)->format('n/j/Y') : '',
                            $patron->expiry_date ? \Carbon\Carbon::parse($patron->expiry_date)->format('n/j/Y') : ''
                        ];
                    }
                    break;
            }

            // 3. Storage path
            $fileName = $prefix . '_' . now()->format('Ymd_His');
            $extension = $this->format === 'csv' ? 'csv' : 'xlsx';
            $relativeFilePath = 'exports/' . $fileName . '.' . $extension;

            // Ensure exports dir exists
            if (!Storage::disk('local')->exists('exports')) {
                Storage::disk('local')->makeDirectory('exports');
            }

            // 4. Store the file using Excel::store
            if ($this->format === 'excel') {
                Excel::store(
                    new DynamicPatronReportExport($headers, $rows, $title, $this->reportType),
                    $relativeFilePath,
                    'local'
                );
            } elseif ($this->format === 'csv') {
                Excel::store(
                    new DynamicPatronReportExport($headers, $rows, $title, $this->reportType),
                    $relativeFilePath,
                    'local',
                    \Maatwebsite\Excel\Excel::CSV
                );
            }

            $executionTimeMs = round((microtime(true) - $startTime) * 1000);

            // 5. Update history status
            $history->update([
                'status' => 'completed',
                'file_path' => $relativeFilePath,
                'execution_time_ms' => $executionTimeMs
            ]);

        } catch (\Throwable $e) {
            Log::error('Lỗi khi chạy background export độc giả: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            $executionTimeMs = round((microtime(true) - $startTime) * 1000);

            $history->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'execution_time_ms' => $executionTimeMs
            ]);
        }
    }
}
