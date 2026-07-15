<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BibliographicRecord;
use App\Models\DocumentType;
use App\Models\MarcFramework;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DynamicMarcReportExport;
use Carbon\Carbon;

class MarcReportController extends Controller
{
    /**
     * Hiển thị trang xuất báo cáo
     */
    public function index(Request $request)
    {
        $reportType = $request->query('report_type', 'cataloging_subsystem');
        
        $frameworks = MarcFramework::where('is_active', true)->get();
        $documentTypes = DocumentType::active()->ordered()->get();
        $branches = \App\Models\Branch::where('is_active', true)->get();
        $storageLocations = \App\Models\StorageLocation::where('is_active', true)->get();
        
        $reportsList = [
            'cataloging_subsystem' => [
                'title' => __('Báo cáo phân hệ biên mục'),
                'desc' => __('Xuất dữ liệu chi sách các bản ghi thư tịch đã biên mục trong hệ thống dưới dạng tệp dữ liệu.')
            ],
            'article_index' => [
                'title' => __('Thư mục bài trích'),
                'desc' => __('Xuất danh mục các bài báo, bài trích tạp chí đã được phân tích biên mục trong hệ thống.')
            ],
            'book_stats' => [
                'title' => __('Thống kê số lượng đầu sách'),
                'desc' => __('Báo cáo tổng hợp số lượng đầu sách cùng với số lượng bản ấn (bản sao cá biệt) tương ứng hiện có trong thư viện.')
            ],
            'book_id_list' => [
                'title' => __('Danh sách tài liệu theo mã sách'),
                'desc' => __('Báo cáo chi tiết các đầu sách trong thư viện, sắp xếp và lọc theo mã sách.')
            ],
            'inventory_status' => [
                'title' => __('Tình hình kho tài liệu'),
                'desc' => __('Báo cáo hiện trạng phân bộ kho tài liệu, vị trí lưu trữ và trạng thái hiện tại của từng bản sách.')
            ],
            'spine_label' => [
                'title' => __('In Nhãn gáy'),
                'desc' => __('Xuất dữ liệu phục vụ việc in nhãn gáy sách (bao gồm mã vạch, số phân loại DDC, mã tác giả và ký hiệu xếp giá).')
            ],
            'barcode_list' => [
                'title' => __('In mã vạch'),
                'desc' => __('Xuất danh sách mã vạch (Barcode) và ký hiệu xếp giá để in nhãn dán lên gáy/bìa sách.')
            ],
            'book_title_qty' => [
                'title' => __('Danh sách nhan đề và số lượng'),
                'desc' => __('Báo cáo thống kê tổng hợp danh sách các nhan đề sách, tác giả, năm xuất bản cùng số lượng bản ấn hiện có trong thư viện.')
            ],
            'accession_book' => [
                'title' => __('Sổ đăng ký cá biệt'),
                'desc' => __('Báo cáo sổ đăng ký cá biệt (ĐKCB) chi tiết từng cuốn sách, giá tiền, vị trí lưu trữ phục vụ cho công tác kiểm kê.')
            ],
            'generated_barcodes' => [
                'title' => __('In mã vạch phát sinh'),
                'desc' => __('Xuất danh sách mã vạch phát sinh mới trong hệ thống theo thời gian.')
            ]
        ];

        if (!array_key_exists($reportType, $reportsList)) {
            $reportType = 'cataloging_subsystem';
        }

        $activeReport = $reportsList[$reportType];
        
        return view('admin.marc_books.export', compact(
            'frameworks', 
            'documentTypes', 
            'branches', 
            'storageLocations',
            'reportType',
            'activeReport',
            'reportsList'
        ));
    }

    /**
     * Xem trước báo cáo dưới dạng bảng trực tiếp trên trang (AJAX)
     */
    public function preview(Request $request)
    {
        $reportType = $request->input('report_type');
        $itemBasedReports = ['inventory_report', 'accession_book', 'spine_label', 'barcode_list', 'inventory_status', 'generated_barcodes', 'book_id_list'];
        $perPage = $request->input('per_page', 10);
        
        if ($reportType === 'book_stats') {
            $query = BibliographicRecord::with(['items.storageLocation', 'documentType'])
                ->where('status', BibliographicRecord::STATUS_APPROVED);
            $query = $this->applyAdvancedFilters($query, $request, $reportType, $itemBasedReports);
            $records = $query->get();
            
            $storageLocations = \App\Models\StorageLocation::with('branch')->where('is_active', true)->get();
            $documentTypes = \App\Models\DocumentType::where('is_active', true)->orderBy('order', 'asc')->get();
            
            $counts = [];
            foreach ($storageLocations as $loc) {
                foreach ($documentTypes as $dt) {
                    $counts[$loc->id][$dt->id] = 0;
                }
            }
            
            $totalCount = 0;
            foreach ($records as $record) {
                $dtId = $record->document_type_id;
                if (!$dtId) continue;
                
                foreach ($record->items as $item) {
                    $locId = $item->storage_location_id;
                    if ($locId && isset($counts[$locId][$dtId])) {
                        $counts[$locId][$dtId]++;
                        $totalCount++;
                    }
                }
            }
            
            return view('admin.marc_books.partials.book_stats_preview', [
                'storageLocations' => $storageLocations,
                'documentTypes' => $documentTypes,
                'counts' => $counts,
                'totalCount' => $totalCount
            ]);
        }
        
        if (in_array($reportType, $itemBasedReports)) {
            $query = \App\Models\BookItem::with(['bibliographicRecord.fields.subfields', 'branch', 'storageLocation']);
            $query = $this->applyAdvancedFilters($query, $request, $reportType, $itemBasedReports);
            $paginated = $query->latest()->paginate($perPage);
            $records = $paginated->items();
        } else {
            $query = BibliographicRecord::with(['fields.subfields', 'items'])
                ->where('status', BibliographicRecord::STATUS_APPROVED);
            if (in_array($reportType, ['book_stats', 'book_title_qty', 'cataloging_subsystem'])) {
                $query->where(function($q) {
                    $q->where('record_type', 'resource')
                      ->orWhereHas('items');
                });
            }
            $query = $this->applyAdvancedFilters($query, $request, $reportType, $itemBasedReports);
            $paginated = $query->latest()->paginate($perPage);
            $records = $paginated->items();
        }

        $reportData = $this->prepareDataByReportType($reportType, $records);
        
        // Cập nhật lại cột STT theo chỉ số phân trang hiện tại
        $startIndex = ($paginated->currentPage() - 1) * $paginated->perPage() + 1;
        foreach ($reportData['rows'] as $index => &$row) {
            $row[0] = $startIndex + $index;
        }

        if ($reportType === 'spine_label') {
            return view('admin.marc_books.partials.spine_label_preview', [
                'records' => $records,
                'paginated' => $paginated,
                'totalCount' => $paginated->total()
            ]);
        }

        if ($reportType === 'barcode_list') {
            return view('admin.marc_books.partials.barcode_list_preview', [
                'records' => $records,
                'paginated' => $paginated,
                'totalCount' => $paginated->total()
            ]);
        }

        $viewName = $reportType === 'book_id_list' 
            ? 'admin.marc_books.partials.book_id_list_preview' 
            : 'admin.marc_books.partials.report_preview';

        return view($viewName, [
            'headers' => $reportData['headers'],
            'rows' => $reportData['rows'],
            'paginated' => $paginated,
            'totalCount' => $paginated->total(),
        ]);
    }

    /**
     * Xử lý tạo báo cáo với bộ lọc linh hoạt
     * Optimized: uses streaming generator (chunk-based) to avoid timeout on large datasets.
     */
    public function generate(Request $request)
    {
        // Tăng giới hạn thời gian và bộ nhớ cho xuất dữ liệu lớn
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $reportType = $request->input('report_type');
        $format     = $request->input('format', 'excel');

        // Các báo cáo về kho/mã vạch/nhãn gáy nên bắt đầu từ BookItem
        $itemBasedReports = ['inventory_report', 'accession_book', 'spine_label', 'barcode_list', 'inventory_status', 'generated_barcodes', 'book_id_list'];

        if (in_array($reportType, $itemBasedReports)) {
            $query = \App\Models\BookItem::with(['bibliographicRecord.fields.subfields', 'branch', 'storageLocation']);
            $query = $this->applyAdvancedFilters($query, $request, $reportType, $itemBasedReports);
            $query->latest();

            // Kiểm tra nhanh có dữ liệu không (tránh xử lý vô ích)
            if (!$query->exists()) {
                return back()->with('error', __('Không tìm thấy dữ liệu phù hợp với bộ lọc đã chọn.'));
            }
        } else {
            // Chỉ eager-load fields.subfields; withCount đủ để đếm bản ấn.
            // Không load with('items') để tránh kéo toàn bộ collection vào RAM mỗi chunk.
            $query = BibliographicRecord::with(['fields.subfields'])
                ->where('status', BibliographicRecord::STATUS_APPROVED);

            // withCount để tránh N+1 khi đếm số bản ấn
            if (in_array($reportType, ['book_stats', 'book_title_qty', 'cataloging_subsystem', 'article_index'])) {
                $query->withCount('items');
            }

            if (in_array($reportType, ['book_stats', 'book_title_qty', 'cataloging_subsystem'])) {
                $query->where(function($q) {
                    $q->where('record_type', 'resource')
                      ->orWhereHas('items');
                });
            }

            $query = $this->applyAdvancedFilters($query, $request, $reportType, $itemBasedReports);
            $query->latest();

            if (!$query->exists()) {
                return back()->with('error', __('Không tìm thấy dữ liệu phù hợp với bộ lọc đã chọn.'));
            }
        }

        // Lấy metadata (headers, title, prefix) từ loại báo cáo – không load dữ liệu thực
        $meta = $this->getReportMeta($reportType);

        // Định dạng tên file và xuất
        $fileName = $meta['file_prefix'] . '_' . now()->format('Ymd_His');
        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $fullFileName = $fileName . '.' . $extension;

        // Tạo lịch sử xuất dữ liệu với trạng thái pending
        $history = \App\Models\ExportHistory::create([
            'user_id'     => auth()->id(),
            'report_type' => $reportType,
            'title'       => $meta['title'],
            'filename'    => $fullFileName,
            'format'      => $format,
            'status'      => 'pending',
        ]);

        // Đẩy job xử lý xuất dữ liệu đồng bộ (sync) để tự động thực thi ngay mà không cần bật Worker
        \App\Jobs\ExportMarcReportJob::dispatch(
            $history->id,
            $request->all(),
            $reportType,
            $format
        )->onConnection('sync');

        return response()->json([
            'success' => true,
            'message' => __('Yêu cầu xuất file của bạn đã được nhận và đang được xử lý dưới nền. Bạn sẽ nhận được thông báo sau khi hoàn tất.'),
            'history' => $history
        ]);
    }

    /**
     * Trả về metadata (headers, title, file_prefix) theo loại báo cáo,
     * không cần load bất kỳ dữ liệu nào từ DB.
     */
    public function getReportMeta(string $reportType): array
    {
        $map = [
            'cataloging_subsystem' => [
                'title'       => __('Báo cáo phân hệ biên mục'),
                'file_prefix' => 'bien_muc',
                'headers'     => [__('STT'), __('Mã bản ghi'), __('Nhan đề'), __('Tác giả'), __('Ngày biên mục'), __('Trạng thái')],
            ],
            'book_stats' => [
                'title'       => __('Thống kê số lượng đầu sách'),
                'file_prefix' => 'thong_ke_dau_sach',
                'headers'     => [__('STT'), __('Nhan đề'), __('Nhà xuất bản'), __('Năm XB'), __('Số phân loại'), __('Mã hóa'), __('Số bản'), __('Giá tiền')],
            ],
            'accession_book' => [
                'title'       => __('Sổ đăng ký cá biệt'),
                'file_prefix' => 'so_dkcb',
                'headers'     => [__('STT'), __('Mã ĐKCB (Barcode)'), __('Nhan đề'), __('Tác giả'), __('Năm XB'), __('Nơi XB'), __('Giá tiền'), __('Vị trí')],
            ],
            'spine_label' => [
                'title'       => __('Danh sách dữ liệu in nhãn gáy'),
                'file_prefix' => 'nhan_gay',
                'headers'     => [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Số phân loại (DDC)'), __('Mã tác giả'), __('Ký hiệu xếp giá')],
            ],
            'inventory_report' => [
                'title'       => __('Báo cáo tình hình kho tài liệu'),
                'file_prefix' => 'kho_tai_lieu',
                'headers'     => [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Vị trí kho'), __('Trạng thái'), __('Ngày nhập kho')],
            ],
            'article_index' => [
                'title'       => __('Thư mục bài trích tạp chí'),
                'file_prefix' => 'bai_trich',
                'headers'     => [__('STT'), __('Tên bài trích'), __('Tác giả bài trích'), __('Tên tạp chí/nguồn'), __('Tập/Số'), __('Trang trích dẫn')],
            ],
            'barcode_list' => [
                'title'       => __('Danh sách dữ liệu in mã vạch'),
                'file_prefix' => 'ma_vach',
                'headers'     => [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Ký hiệu xếp giá (Call Number)'), __('Vị trí')],
            ],
            'book_id_list' => [
                'title'       => __('Danh sách tài liệu theo mã sách'),
                'file_prefix' => 'theo_ma_sach',
                'headers'     => [__('STT'), __('Mã số'), __('Tựa đề'), __('Tác giả'), __('Dewey'), __('Vị trí'), __('Ký N'), __('Ký X'), __('Ghi chú')],
            ],
            'inventory_status' => [
                'title'       => __('Báo cáo chi tiết tình hình kho'),
                'file_prefix' => 'tinh_hinh_kho',
                'headers'     => [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Kho/Phòng'), __('Loại lưu kho'), __('Trạng thái'), __('Ngày nhập')],
            ],
            'generated_barcodes' => [
                'title'       => __('Danh sách mã vạch phát sinh'),
                'file_prefix' => 'ma_vach_phat_sinh',
                'headers'     => [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Ngày tạo')],
            ],
            'book_title_qty' => [
                'title'       => __('Danh sách nhan đề và số lượng'),
                'file_prefix' => 'nhan_de_so_luong',
                'headers'     => [__('STT'), __('Mã bản ghi'), __('Nhan đề'), __('Tác giả'), __('Năm XB'), __('Số lượng bản ấn')],
            ],
        ];

        return $map[$reportType] ?? [
            'title'       => __('Báo cáo chi tiết tài liệu'),
            'file_prefix' => 'tai_lieu',
            'headers'     => [__('STT'), __('Mã bản ghi'), __('Nhan đề'), __('Tác giả'), __('Năm XB'), __('Số lượng bản ấn')],
        ];
    }

    /**
     * Logic bóc tách dữ liệu MARC cho từng loại báo cáo cụ thể
     */
    private function prepareDataByReportType($type, $records)
    {
        $headers = [];
        $rows = [];
        $title = '';
        $prefix = 'report';

        switch ($type) {
            case 'cataloging_subsystem': // Báo cáo phân hệ biên mục
                $title = __('Báo cáo phân hệ biên mục');
                $prefix = 'bien_muc';
                $headers = [__('STT'), __('Mã bản ghi'), __('Nhan đề'), __('Tác giả'), __('Ngày biên mục'), __('Trạng thái')];
                foreach ($records as $index => $record) {
                    $rows[] = [
                        $index + 1,
                        $record->id,
                        $record->getMarcValue('245', 'a'),
                        $record->getMarcValue('100', 'a') ?: $record->getMarcValue('700', 'a'),
                        $record->created_at->format('d/m/Y'),
                        $record->status
                    ];
                }
                break;

            case 'book_stats': // Thống kê số lượng đầu sách
                $title = __('Thống kê số lượng đầu sách');
                $prefix = 'thong_ke_dau_sach';
                $headers = [
                    __('STT'), 
                    __('Nhan đề'), 
                    __('Nhà xuất bản'), 
                    __('Năm XB'), 
                    __('Số phân loại'), 
                    __('Mã hóa'), 
                    __('Số bản'), 
                    __('Giá tiền')
                ];
                foreach ($records as $index => $record) {
                    $year = $record->getMarcValue('260', 'c') ?: $record->getMarcValue('264', 'c');
                    $pub = $record->getMarcValue('260', 'b') ?: $record->getMarcValue('264', 'b');
                    $ddc = $record->getMarcValue('082', 'a') ?: $record->getMarcValue('090', 'a');
                    $authorCode = $record->getMarcValue('082', 'b') ?: ($record->getMarcValue('090', 'b') ?: ($record->getMarcValue('100', 'a') ? mb_substr($record->getMarcValue('100', 'a'), 0, 3, 'UTF-8') : ''));
                    $price = $record->getMarcValue('952', 'g') ?: ($record->getMarcValue('020', 'c') ?: '0');
                    $rows[] = [
                        $index + 1,
                        $record->getMarcValue('245', 'a') ?: __('Không có nhan đề'),
                        $pub ?: '...',
                        $year ?: '...',
                        $ddc ?: '...',
                        $authorCode ?: '...',
                        $record->items->count(),
                        $price
                    ];
                }
                break;

            case 'accession_book': // Số đăng ký cá biệt
                $title = __('Sổ đăng ký cá biệt');
                $prefix = 'so_dkcb';
                $headers = [__('STT'), __('Mã ĐKCB (Barcode)'), __('Nhan đề'), __('Tác giả'), __('Năm XB'), __('Nơi XB'), __('Giá tiền'), __('Vị trí')];
                $count = 1;
                foreach ($records as $item) {
                    $record = $item->bibliographicRecord;
                    $year = $record->getMarcValue('260', 'c') ?: $record->getMarcValue('264', 'c');
                    $place = $record->getMarcValue('260', 'a') ?: $record->getMarcValue('264', 'a');
                    $price = $record->getMarcValue('952', 'g') ?: '...';
                    $rows[] = [
                        $count++,
                        $item->barcode,
                        $record->getMarcValue('245', 'a'),
                        $record->getMarcValue('100', 'a') ?: $record->getMarcValue('700', 'a'),
                        $year,
                        $place,
                        $price,
                        $item->location
                    ];
                }
                break;

            case 'spine_label': // In nhãn gáy
                $title = __('Danh sách dữ liệu in nhãn gáy');
                $prefix = 'nhan_gay';
                $headers = [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Số phân loại (DDC)'), __('Mã tác giả'), __('Ký hiệu xếp giá')];
                $count = 1;
                foreach ($records as $item) {
                    $record = $item->bibliographicRecord;
                    $ddc = $record->getMarcValue('082', 'a');
                    $authorCode = $record->getMarcValue('082', 'b') ?: ($record->getMarcValue('090', 'b') ?: mb_substr($record->getMarcValue('100', 'a'), 0, 3, 'UTF-8'));
                    $rows[] = [
                        $count++,
                        $item->barcode,
                        $record->getMarcValue('245', 'a'),
                        $ddc,
                        $authorCode,
                        $ddc . ' ' . $authorCode
                    ];
                }
                break;

            case 'inventory_report': // Tình hình kho tài liệu
                $title = __('Báo cáo tình hình kho tài liệu');
                $prefix = 'kho_tai_lieu';
                $headers = [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Vị trí kho'), __('Trạng thái'), __('Ngày nhập kho')];
                $count = 1;
                foreach ($records as $item) {
                    $record = $item->bibliographicRecord;
                    $rows[] = [
                        $count++,
                        $item->barcode,
                        $record->getMarcValue('245', 'a'),
                        $item->location,
                        $item->status,
                        $item->created_at->format('d/m/Y')
                    ];
                }
                break;

            case 'article_index': // Thư mục bài trích
                $title = __('Thư mục bài trích tạp chí');
                $prefix = 'bai_trich';
                $headers = [__('STT'), __('Tên bài trích'), __('Tác giả bài trích'), __('Tên tạp chí/nguồn'), __('Tập/Số'), __('Trang trích dẫn')];
                foreach ($records as $index => $record) {
                    $rows[] = [
                        $index + 1,
                        $record->getMarcValue('245', 'a'),
                        $record->getMarcValue('100', 'a'),
                        $record->getMarcValue('773', 't'),
                        $record->getMarcValue('773', 'g'),
                        $record->getMarcValue('773', 'q')
                    ];
                }
                break;

            case 'barcode_list': // Dữ liệu in mã vạch
                $title = __('Danh sách dữ liệu in mã vạch');
                $prefix = 'ma_vach';
                $headers = [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Ký hiệu xếp giá (Call Number)'), __('Vị trí')];
                $count = 1;
                foreach ($records as $item) {
                    $record = $item->bibliographicRecord;
                    $callNumber = $record->getMarcValue('082', 'a') . ' ' . ($record->getMarcValue('082', 'b') ?: ($record->getMarcValue('090', 'b') ?: mb_substr($record->getMarcValue('100', 'a'), 0, 3, 'UTF-8')));
                    $rows[] = [
                        $count++,
                        $item->barcode,
                        $record->getMarcValue('245', 'a'),
                        $callNumber,
                        $item->location
                    ];
                }
                break;

            case 'book_id_list': // Danh sách tài liệu theo mã sách
                $title = __('Danh sách tài liệu theo mã sách');
                $prefix = 'theo_ma_sach';
                $headers = [__('STT'), __('Mã số'), __('Tựa đề'), __('Tác giả'), __('Dewey'), __('Vị trí'), __('Ký N'), __('Ký X'), __('Ghi chú')];
                $count = 1;
                foreach ($records as $item) {
                    $record = $item->bibliographicRecord;
                    $ddc = $record->getMarcValue('082', 'a') ?: $record->getMarcValue('090', 'a');
                    $rows[] = [
                        $count++,
                        $item->accession_number ?: $item->barcode,
                        $record->getMarcValue('245', 'a'),
                        $record->getMarcValue('100', 'a') ?: $record->getMarcValue('700', 'a'),
                        $ddc ?: '...',
                        $item->storageLocation?->code ?: ($item->location ?: '...'),
                        '',
                        '',
                        $item->notes ?: ''
                    ];
                }
                break;

            case 'inventory_status': // Tình hình kho tài liệu (chi tiết)
                $title = __('Báo cáo chi tiết tình hình kho');
                $prefix = 'tinh_hinh_kho';
                $headers = [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Kho/Phòng'), __('Loại lưu kho'), __('Trạng thái'), __('Ngày nhập')];
                $count = 1;
                foreach ($records as $item) {
                    $record = $item->bibliographicRecord;
                    $rows[] = [
                        $count++,
                        $item->barcode,
                        $record->getMarcValue('245', 'a'),
                        $item->branch?->name ?: $item->location,
                        $item->storageLocation?->name ?: '...',
                        $item->status,
                        $item->created_at->format('d/m/Y')
                    ];
                }
                break;

            case 'generated_barcodes': // In mã vạch phát sinh
                $title = __('Danh sách mã vạch phát sinh');
                $prefix = 'ma_vach_phat_sinh';
                $headers = [__('STT'), __('Mã vạch'), __('Nhan đề'), __('Ngày tạo')];
                $count = 1;
                foreach ($records as $item) {
                    $record = $item->bibliographicRecord;
                    $rows[] = [
                        $count++,
                        $item->barcode,
                        $record->getMarcValue('245', 'a'),
                        $item->created_at->format('d/m/Y H:i')
                    ];
                }
                break;

            case 'book_title_qty': // Danh sách nhan đề và số lượng
                $title = __('Danh sách nhan đề và số lượng');
                $prefix = 'nhan_de_so_luong';
                $headers = [__('STT'), __('Mã bản ghi'), __('Nhan đề'), __('Tác giả'), __('Năm XB'), __('Số lượng bản ấn')];
                foreach ($records as $index => $record) {
                    $rows[] = [
                        $index + 1,
                        $record->id,
                        $record->getMarcValue('245', 'a'),
                        $record->getMarcValue('100', 'a') ?: $record->getMarcValue('700', 'a'),
                        $record->getMarcValue('260', 'c') ?: $record->getMarcValue('264', 'c'),
                        $record->items->count()
                    ];
                }
                break;

            default:
                $title = __('Báo cáo chi tiết tài liệu');
                $prefix = 'tai_lieu';
                $headers = [__('STT'), __('Mã bản ghi'), __('Nhan đề'), __('Tác giả'), __('Năm XB'), __('Số lượng bản ấn')];
                foreach ($records as $index => $record) {
                    $rows[] = [
                        $index + 1,
                        $record->id,
                        $record->getMarcValue('245', 'a'),
                        $record->getMarcValue('100', 'a'),
                        $record->getMarcValue('260', 'c'),
                        $record->items->count()
                    ];
                }
                break;
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
            'title' => $title,
            'file_prefix' => $prefix
        ];
    }

    public function applyAdvancedFilters($query, Request $request, $reportType, $itemBasedReports)
    {
        $isItemBased = in_array($reportType, $itemBasedReports);

        // A. Basic search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            if ($isItemBased) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('barcode', 'like', '%' . $searchTerm . '%')
                      ->orWhere('accession_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('order_code', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('bibliographicRecord', function($br) use ($searchTerm) {
                          $br->whereHas('fields', function($f) use ($searchTerm) {
                              $f->whereHas('subfields', function($sf) use ($searchTerm) {
                                  $sf->where('value', 'like', '%' . $searchTerm . '%');
                              });
                          });
                      });
                });
            } else {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('id', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('fields', function($f) use ($searchTerm) {
                          $f->whereHas('subfields', function($sf) use ($searchTerm) {
                              $sf->where('value', 'like', '%' . $searchTerm . '%');
                          });
                      })
                      ->orWhereHas('items', function($it) use ($searchTerm) {
                          $it->where('barcode', 'like', '%' . $searchTerm . '%')
                            ->orWhere('accession_number', 'like', '%' . $searchTerm . '%');
                      });
                });
            }
        }

        // B. Tab 1: Thông tin (4 conditions builder)
        $infoFields = $request->input('info_fields', []);
        $infoOps = $request->input('info_ops', []);
        $infoVals = $request->input('info_vals', []);

        if (is_array($infoFields) && is_array($infoVals)) {
            $query->where(function($mainQuery) use ($infoFields, $infoOps, $infoVals, $isItemBased) {
                for ($i = 0; $i < count($infoFields); $i++) {
                    $field = $infoFields[$i] ?? null;
                    $op = $infoOps[$i] ?? 'AND'; // 'AND', 'OR', 'NOT'
                    $val = $infoVals[$i] ?? null;

                    if (empty($field) || is_null($val) || $val === '') {
                        continue;
                    }

                    $conditionQuery = function($q) use ($field, $val, $isItemBased) {
                        if ($isItemBased) {
                            if ($field === 'id') {
                                $q->where('bibliographic_record_id', $val);
                            } elseif ($field === 'barcode') {
                                $q->where('barcode', 'like', '%' . $val . '%');
                            } elseif ($field === 'accession_number') {
                                $q->where('accession_number', 'like', '%' . $val . '%');
                            } elseif ($field === 'order_code') {
                                $q->where('order_code', 'like', '%' . $val . '%');
                            } else {
                                $q->whereHas('bibliographicRecord', function($br) use ($field, $val) {
                                    $this->applyMarcTagFilter($br, $field, $val);
                                });
                            }
                        } else {
                            if ($field === 'id') {
                                $q->where('id', $val);
                            } elseif ($field === 'barcode') {
                                $q->whereHas('items', function($it) use ($val) {
                                    $it->where('barcode', 'like', '%' . $val . '%');
                                });
                            } elseif ($field === 'accession_number') {
                                $q->whereHas('items', function($it) use ($val) {
                                    $it->where('accession_number', 'like', '%' . $val . '%');
                                });
                            } elseif ($field === 'order_code') {
                                $q->whereHas('items', function($it) use ($val) {
                                    $it->where('order_code', 'like', '%' . $val . '%');
                                });
                            } else {
                                $this->applyMarcTagFilter($q, $field, $val);
                            }
                        }
                    };

                    if ($i === 0 || $op === 'AND') {
                        $mainQuery->where($conditionQuery);
                    } elseif ($op === 'OR') {
                        $mainQuery->orWhere($conditionQuery);
                    } elseif ($op === 'NOT') {
                        $mainQuery->whereNot($conditionQuery);
                      }
                  }
              });
          }

          // C. Tab 2: Phân phối (circulation_statuses, storage_types, distribution_statuses)
          if ($request->filled('circulation_statuses')) {
              $statuses = (array)$request->input('circulation_statuses');
              if ($isItemBased) {
                  $query->whereIn('status', $statuses);
              } else {
                  $query->whereHas('items', function($it) use ($statuses) {
                      $it->whereIn('status', $statuses);
                  });
              }
          }

          if ($request->filled('storage_types')) {
              $types = (array)$request->input('storage_types');
              if ($isItemBased) {
                  $query->whereIn('storage_type', $types);
              } else {
                  $query->whereHas('items', function($it) use ($types) {
                      $it->whereIn('storage_type', $types);
                  });
              }
          }

          if ($request->filled('distribution_statuses')) {
              $distStatuses = (array)$request->input('distribution_statuses');
              if (count($distStatuses) === 1) {
                  $status = $distStatuses[0];
                  if ($isItemBased) {
                      if ($status === 'distributed') {
                          $query->whereNotNull('branch_id');
                      } else {
                          $query->whereNull('branch_id');
                      }
                  } else {
                      if ($status === 'distributed') {
                          $query->whereHas('items', function($it) { $it->whereNotNull('branch_id'); });
                      } else {
                          $query->whereHas('items', function($it) { $it->whereNull('branch_id'); });
                      }
                  }
              }
          }

          // D. Tab 3: Giới hạn (created date, updated date, size, limits)
          $tableName = $isItemBased ? 'book_items' : 'bibliographic_records';

          if ($request->filled('date_from')) {
              $query->whereDate('created_at', '>=', $request->date_from);
          }
          if ($request->filled('date_to')) {
              $query->whereDate('created_at', '<=', $request->date_to);
          }

          if ($request->filled('updated_date_from')) {
              $query->whereDate('updated_at', '>=', $request->updated_date_from);
          }
          if ($request->filled('updated_date_to')) {
              $query->whereDate('updated_at', '<=', $request->updated_date_to);
          }

          if ($request->filled('created_by') && \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'created_by')) {
              $query->where('created_by', $request->created_by);
          }
          if ($request->filled('updated_by') && \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'updated_by')) {
              $query->where('updated_by', $request->updated_by);
          }
          if ($request->filled('size_code') && \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'size_code')) {
              $query->where('size_code', $request->size_code);
          }
          if ($request->filled('branch_code') && \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'branch_code')) {
              $query->where('branch_code', $request->branch_code);
          }
          if ($request->filled('usage_level') && \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'usage_level')) {
              $query->where('usage_level', $request->usage_level);
          }

          if ($request->filled('waits_for_print')) {
              if ($isItemBased) {
                  $query->where('waits_for_print', true);
              } else {
                  $query->whereHas('items', function($it) {
                      $it->where('waits_for_print', true);
                  });
              }
          }

          // E. Tab 4: Vị trí (branch_id, storage_location_id)
          if ($request->filled('branch_id')) {
              if ($isItemBased) {
                  $query->where('branch_id', $request->branch_id);
              } else {
                  $query->whereHas('items', function($it) use ($request) {
                      $it->where('branch_id', $request->branch_id);
                  });
              }
          }

          if ($request->filled('storage_location_id')) {
              if ($isItemBased) {
                  $query->where('storage_location_id', $request->storage_location_id);
              } else {
                  $query->whereHas('items', function($it) use ($request) {
                      $it->where('storage_location_id', $request->storage_location_id);
                  });
              }
          }

          // F. Tab 5: Checkbox Categories (frameworks, document_types, statuses)
          if ($request->filled('frameworks')) {
              $fCodes = (array)$request->input('frameworks');
              if ($isItemBased) {
                  $query->whereHas('bibliographicRecord', function($br) use ($fCodes) {
                      $br->whereIn('framework', $fCodes);
                  });
              } else {
                  $query->whereIn('framework', $fCodes);
              }
          }

          if ($request->filled('document_types')) {
              $dTypes = (array)$request->input('document_types');
              if ($isItemBased) {
                  $query->whereHas('bibliographicRecord', function($br) use ($dTypes) {
                      $br->whereIn('document_format', $dTypes);
                  });
              } else {
                  $query->whereIn('document_format', $dTypes);
              }
          }

          if ($request->filled('statuses')) {
              $rStatuses = (array)$request->input('statuses');
              if ($isItemBased) {
                  $query->whereHas('bibliographicRecord', function($br) use ($rStatuses) {
                      $br->whereIn('status', $rStatuses);
                  });
              } else {
                  $query->whereIn('status', $rStatuses);
              }
          }

          // Apply result limit if numeric
          if ($request->filled('result_limit') && is_numeric($request->result_limit)) {
              $query->limit(intval($request->result_limit));
          }

          return $query;
      }

      private function applyMarcTagFilter($q, $field, $val)
      {
          $tagMap = [
              'title' => ['tag' => '245', 'sub' => 'a'],
              'author' => ['tag' => '100', 'sub' => 'a'],
              'publisher_place' => ['tag' => '260', 'sub' => 'a'],
              'publisher' => ['tag' => '260', 'sub' => 'b'],
              'publisher_year' => ['tag' => '260', 'sub' => 'c'],
              'isbn' => ['tag' => '020', 'sub' => 'a'],
              'issn' => ['tag' => '022', 'sub' => 'a'],
              'subject' => ['tag' => '650', 'sub' => 'a'],
              'dewey' => ['tag' => '082', 'sub' => 'a'],
              'language_code' => ['tag' => '041', 'sub' => 'a'],
              'lc_call_number' => ['tag' => '050', 'sub' => 'a'],
              'summary' => ['tag' => '520', 'sub' => 'a'],
              'notes' => ['tag' => '500', 'sub' => 'a'],
              'genre' => ['tag' => '655', 'sub' => 'a'],
          ];

          if (isset($tagMap[$field])) {
              $tData = $tagMap[$field];
              $q->whereHas('fields', function($fq) use ($tData, $val, $field) {
                  if ($field === 'author') {
                      $fq->whereIn('tag', ['100', '700']);
                  } elseif (in_array($field, ['publisher_place', 'publisher', 'publisher_year'])) {
                      $fq->whereIn('tag', ['260', '264']);
                  } else {
                      $fq->where('tag', $tData['tag']);
                  }
                  $fq->whereHas('subfields', function($sfq) use ($tData, $val) {
                      $sfq->where('code', $tData['sub'])
                          ->where('value', 'like', '%' . $val . '%');
                  });
              });
           } elseif ($field === 'any' || $field === 'fulltext') {
               $q->whereHas('fields', function($fq) use ($val) {
                   $fq->whereHas('subfields', function($sfq) use ($val) {
                       $sfq->where('value', 'like', '%' . $val . '%');
                   });
               });
           }
       }

       /**
        * Giao diện lịch sử xuất file
        */
       public function exportHistories()
       {
           $histories = \App\Models\ExportHistory::where('user_id', auth()->id())
               ->orderBy('created_at', 'desc')
               ->paginate(15);

           return view('admin.marc_reports.histories', compact('histories'));
       }

       /**
        * API trả về danh sách lịch sử xuất gần nhất (dùng cho bell dropdown)
        */
       public function exportHistoriesList()
       {
           $histories = \App\Models\ExportHistory::where('user_id', auth()->id())
               ->orderBy('created_at', 'desc')
               ->limit(10)
               ->get();

           $unreadCount = \App\Models\ExportHistory::where('user_id', auth()->id())
               ->where('is_read', false)
               ->count();

           return response()->json([
               'histories' => $histories,
               'unread_count' => $unreadCount
           ]);
       }

       /**
        * Tải file đã xuất thành công
        */
       public function exportHistoriesDownload($id)
       {
           $history = \App\Models\ExportHistory::where('user_id', auth()->id())
               ->where('id', $id)
               ->firstOrFail();

           if ($history->status !== 'completed' || !$history->file_path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($history->file_path)) {
               return abort(404, __('File không tồn tại hoặc chưa tạo xong.'));
           }

           return \Illuminate\Support\Facades\Storage::disk('local')->download($history->file_path, $history->filename);
       }

       /**
        * Đánh dấu toàn bộ thông báo xuất file là đã đọc
        */
       public function exportHistoriesMarkAllRead()
       {
           \App\Models\ExportHistory::where('user_id', auth()->id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

           return response()->json(['success' => true]);
       }

       /**
        * Xóa một bản ghi lịch sử xuất dữ liệu và file tương ứng
        */
       public function exportHistoriesDestroy($id)
       {
           $history = \App\Models\ExportHistory::where('user_id', auth()->id())
               ->where('id', $id)
               ->firstOrFail();

           if ($history->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($history->file_path)) {
               \Illuminate\Support\Facades\Storage::disk('local')->delete($history->file_path);
           }

           $history->delete();

           return response()->json(['success' => true]);
       }
}