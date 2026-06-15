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
    public function index()
    {
        $frameworks = MarcFramework::where('is_active', true)->get();
        $documentTypes = DocumentType::active()->ordered()->get();
        
        return view('admin.marc_books.export', compact('frameworks', 'documentTypes'));
    }

    /**
     * Xử lý tạo báo cáo với bộ lọc linh hoạt
     */
    public function generate(Request $request)
    {
        $reportType = $request->input('report_type');
        $format = $request->input('format', 'excel');
        $rows = []; // Initialize empty array

        // 1. Build Query dựa trên loại báo cáo
        // Các báo cáo về kho/mã vạch/nhãn gáy nên bắt đầu từ BookItem để chính xác theo từng cuốn sách
        $itemBasedReports = ['inventory_report', 'accession_book', 'spine_label', 'barcode_list', 'inventory_status', 'generated_barcodes'];
        
        if (in_array($reportType, $itemBasedReports)) {
            // Lưu ý: BookItem belongsTo bibliographicRecord (không phải record)
            $query = \App\Models\BookItem::with(['bibliographicRecord.fields.subfields', 'branch', 'storageLocation']);
            
            // Áp dụng bộ lọc cho BookItem (thông qua relationship)
            if ($request->filled('framework_id')) {
                $query->whereHas('bibliographicRecord', function($q) use ($request) {
                    $q->where('framework', $request->framework_id); // BibliographicRecord dùng cột 'framework' thay vì 'framework_id'
                });
            }
            if ($request->filled('document_type_id')) {
                $query->whereHas('bibliographicRecord', function($q) use ($request) {
                    $q->where('document_format', $request->document_type_id); // BibliographicRecord dùng 'document_format'
                });
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $records = $query->latest()->get();
        } else {
            // BibliographicRecord không có relation framework (nó là một cột chuỗi/ID trực tiếp)
            $query = BibliographicRecord::with(['fields.subfields', 'items']);

            // Áp dụng bộ lọc cho BibliographicRecord
            if ($request->filled('framework_id')) {
                $query->where('framework', $request->framework_id);
            }
            if ($request->filled('document_type_id')) {
                $query->where('document_format', $request->document_type_id);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $records = $query->latest()->get();
        }

        if ($records->isEmpty()) {
            return back()->with('error', __('Không tìm thấy dữ liệu phù hợp với bộ lọc đã chọn.'));
        }

        // 3. Chuẩn bị dữ liệu theo loại báo cáo đã chọn
        $reportData = $this->prepareDataByReportType($reportType, $records);

        // 4. Định dạng tên file và xuất bản
        $fileName = $reportData['file_prefix'] . '_' . now()->format('Ymd_His');
        
        if ($format === 'excel') {
            // Đặc biệt cho in mã vạch, dùng class Export riêng để vẽ lưới nhãn
            if (in_array($reportType, ['barcode_list', 'generated_barcodes'])) {
                return Excel::download(
                    new \App\Exports\BarcodeExport($records, $reportData['title']), 
                    $fileName . '.xlsx'
                );
            }

            return Excel::download(
                new DynamicMarcReportExport($reportData['headers'], $reportData['rows'], $reportData['title']), 
                $fileName . '.xlsx'
            );
        }

        return back()->with('error', __('Định dạng xuất này hiện chưa được hỗ trợ.'));
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
                $headers = [__('STT'), __('Mã bản ghi'), __('Nhan đề'), __('Số lượng bản ấn'), __('Ngày tạo')];
                foreach ($records as $index => $record) {
                    $rows[] = [
                        $index + 1,
                        $record->id,
                        $record->getMarcValue('245', 'a'),
                        $record->items->count(),
                        $record->created_at->format('d/m/Y')
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
                    $authorCode = $record->getMarcValue('090', 'b') ?: substr($record->getMarcValue('100', 'a'), 0, 3);
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
                    $callNumber = $record->getMarcValue('082', 'a') . ' ' . ($record->getMarcValue('090', 'b') ?: substr($record->getMarcValue('100', 'a'), 0, 3));
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
                $headers = [__('STT'), __('Mã bản ghi'), __('Nhan đề'), __('Tác giả'), __('Số lượng bản ấn'), __('Năm XB'), __('DDC')];
                foreach ($records as $index => $record) {
                    $rows[] = [
                        $index + 1,
                        $record->id,
                        $record->getMarcValue('245', 'a'),
                        $record->getMarcValue('100', 'a') ?: $record->getMarcValue('700', 'a'),
                        $record->items->count(),
                        $record->getMarcValue('260', 'c') ?: $record->getMarcValue('264', 'c'),
                        $record->getMarcValue('082', 'a')
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
}