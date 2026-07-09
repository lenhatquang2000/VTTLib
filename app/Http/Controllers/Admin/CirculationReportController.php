<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanTransaction;
use App\Models\BookItem;
use App\Models\PatronDetail;
use App\Models\PatronGroup;
use App\Models\Branch;
use App\Models\LibraryEntry;
use App\Models\WebsiteAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DynamicCirculationReportExport;

class CirculationReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $reportType = $request->query('report_type', 'currently_borrowed');
        
        $patronGroups = PatronGroup::all();
        $branches = Branch::where('is_active', true)->get();
        
        $reportsList = [
            'currently_borrowed' => [
                'title' => __('Danh sách tài liệu đang mượn'),
                'desc' => __('Danh sách chi tiết các tài liệu đang được bạn đọc mượn và chưa hoàn trả.')
            ],
            'patron_service' => [
                'title' => __('Báo cáo phục vụ bạn đọc'),
                'desc' => __('Thống kê tổng hợp số lượng bạn đọc theo nhóm và hiệu quả phục vụ bạn đọc tại thư viện.')
            ],
            'library_entries' => [
                'title' => __('Số lượng bạn đọc vào thư viện'),
                'desc' => __('Thống kê chi tiết nhật ký và lượt ra vào thư viện của bạn đọc.')
            ],
            'overdue' => [
                'title' => __('Danh sách tài liệu đang mượn quá hạn'),
                'desc' => __('Danh sách các tài liệu đã quá hạn trả nhưng bạn đọc chưa làm thủ tục hoàn trả cho thư viện.')
            ],
            'top_patrons' => [
                'title' => __('Danh sách bạn đọc mượn tài liệu nhiều nhất'),
                'desc' => __('Bảng xếp hạng độc giả có số lượng giao dịch mượn sách nhiều nhất trong hệ thống.')
            ],
            'transaction_history' => [
                'title' => __('Danh sách chi tiết lược sử giao dịch sách'),
                'desc' => __('Nhật ký chi tiết các giao dịch mượn, trả sách của tất cả độc giả trong thư viện.')
            ],
            'website_access' => [
                'title' => __('Thống kê lượt truy cập website'),
                'desc' => __('Báo cáo chi tiết nhật ký truy cập cổng thông tin thư viện điện tử OPAC.')
            ],
            'never_borrowed' => [
                'title' => __('Danh sách tài liệu chưa mượn lần nào'),
                'desc' => __('Danh sách các ấn phẩm có trong kho nhưng chưa từng phát sinh giao dịch mượn trả.')
            ]
        ];

        if (!array_key_exists($reportType, $reportsList)) {
            $reportType = 'currently_borrowed';
        }

        $activeReport = $reportsList[$reportType];
        
        return view('admin.circulation.reports.index', compact(
            'patronGroups', 
            'branches', 
            'reportType',
            'activeReport',
            'reportsList'
        ));
    }

    /**
     * Export selected reports to Excel or CSV
     */
    public function export(Request $request)
    {
        $reportType = $request->input('report_type', 'currently_borrowed');
        $format = $request->input('format', 'excel');
        
        $title = '';
        $prefix = 'circulation_report';
        $headers = [];
        $rows = [];

        // Apply filters based on report type query targets
        switch ($reportType) {
            case 'patron_service':
                $title = __('Báo cáo phục vụ bạn đọc');
                $prefix = 'phuc_vu_ban_doc';
                $headers = [__('STT'), __('Nhóm độc giả'), __('Số lượng tài khoản'), __('Tổng lượt mượn sách')];
                
                $stats = PatronGroup::withCount('patrons')->get();
                foreach ($stats as $index => $g) {
                    $totalLoans = LoanTransaction::whereHas('patron', function($p) use ($g) {
                        $p->where('patron_group_id', $g->id);
                    })->count();

                    $rows[] = [
                        $index + 1,
                        $g->name,
                        $g->patrons_count,
                        $totalLoans
                    ];
                }
                break;

            case 'library_entries':
                $title = __('Số lượng bạn đọc vào thư viện');
                $prefix = 'luot_vao_thu_vien';
                $headers = [__('STT'), __('Mã số thẻ'), __('Họ tên'), __('Đơn vị / Chi nhánh'), __('Thời gian vào'), __('Thời gian ra'), __('Mục đích')];
                
                $query = LibraryEntry::with(['patron.user', 'branch']);
                
                // Simple search
                if ($request->filled('search')) {
                    $search = $request->input('search');
                    $query->whereHas('patron', function($p) use ($search) {
                        $p->where('patron_code', 'LIKE', "%{$search}%")
                          ->orWhere('display_name', 'LIKE', "%{$search}%");
                    });
                }
                
                // Branch filter
                if ($request->filled('branch_id')) {
                    $query->where('branch_id', $request->input('branch_id'));
                }
                
                // Date range
                if ($request->filled('date_from')) {
                    $query->whereDate('entry_time', '>=', $request->input('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->whereDate('entry_time', '<=', $request->input('date_to'));
                }
                
                $limit = intval($request->input('result_limit', 0));
                if ($limit > 0) $query->limit($limit);
                
                $entries = $query->orderBy('entry_time', 'desc')->get();
                foreach ($entries as $index => $e) {
                    $rows[] = [
                        $index + 1,
                        optional($e->patron)->patron_code,
                        optional($e->patron)->display_name,
                        optional($e->branch)->name,
                        $e->entry_time ? Carbon::parse($e->entry_time)->format('d/m/Y H:i:s') : '',
                        $e->exit_time ? Carbon::parse($e->exit_time)->format('d/m/Y H:i:s') : '',
                        $e->purpose
                    ];
                }
                break;

            case 'top_patrons':
                $title = __('Danh sách bạn đọc mượn tài liệu nhiều nhất');
                $prefix = 'doc_gia_muon_nhieu_nhat';
                $headers = [__('STT'), __('Mã độc giả'), __('Họ tên'), __('Nhóm bạn đọc'), __('Số điện thoại'), __('Tổng lượt mượn')];
                
                $query = PatronDetail::with(['user', 'patronGroup'])->withCount('loanTransactions');
                
                // Patron Group
                $group_ids = $request->input('patron_group_ids', []);
                if (!empty($group_ids)) {
                    $query->whereIn('patron_group_id', $group_ids);
                }
                
                $limit = intval($request->input('result_limit', 20));
                if ($limit <= 0) $limit = 20;
                
                $patrons = $query->orderBy('loan_transactions_count', 'desc')->limit($limit)->get();
                foreach ($patrons as $index => $p) {
                    $rows[] = [
                        $index + 1,
                        $p->patron_code,
                        $p->display_name,
                        optional($p->patronGroup)->name,
                        $p->phone ?: ($p->phone_contact ?: ''),
                        $p->loan_transactions_count
                    ];
                }
                break;

            case 'website_access':
                $title = __('Thống kê lượt truy cập website');
                $prefix = 'truy_cap_website';
                $headers = [__('STT'), __('Người dùng'), __('Địa chỉ IP'), __('Trình duyệt'), __('Thiết bị'), __('Thời gian truy cập'), __('Đường dẫn (URL)')];
                
                $query = WebsiteAccess::with('user');
                
                if ($request->filled('date_from')) {
                    $query->whereDate('access_time', '>=', $request->input('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->whereDate('access_time', '<=', $request->input('date_to'));
                }
                
                $limit = intval($request->input('result_limit', 100));
                if ($limit > 0) $query->limit($limit);
                
                $accesses = $query->orderBy('access_time', 'desc')->get();
                foreach ($accesses as $index => $a) {
                    $rows[] = [
                        $index + 1,
                        optional($a->user)->name ?: __('Khách'),
                        $a->ip_address,
                        $a->browser,
                        $a->device_type,
                        $a->access_time ? Carbon::parse($a->access_time)->format('d/m/Y H:i:s') : '',
                        $a->page_url
                    ];
                }
                break;

            case 'never_borrowed':
                $title = __('Danh sách tài liệu chưa mượn lần nào');
                $prefix = 'tai_lieu_chua_muon';
                $headers = [__('STT'), __('Mã vạch'), __('Số ĐKCB'), __('Nhan đề'), __('Tác giả'), __('Nhà xuất bản'), __('Kho/Phòng')];
                
                $query = BookItem::with(['bibliographicRecord', 'branch'])->whereDoesntHave('loanTransactions');
                
                if ($request->filled('branch_id')) {
                    $query->where('branch_id', $request->input('branch_id'));
                }
                
                $limit = intval($request->input('result_limit', 100));
                if ($limit > 0) $query->limit($limit);
                
                $items = $query->get();
                foreach ($items as $index => $item) {
                    $rows[] = [
                        $index + 1,
                        $item->barcode,
                        $item->accession_number,
                        optional($item->bibliographicRecord)->getMarcValue('245', 'a'),
                        optional($item->bibliographicRecord)->getMarcValue('100', 'a'),
                        optional($item->bibliographicRecord)->getMarcValue('260', 'b') ?: optional($item->bibliographicRecord)->getMarcValue('264', 'b'),
                        optional($item->branch)->name
                    ];
                }
                break;

            case 'overdue':
                $title = __('Danh sách tài liệu đang mượn quá hạn');
                $prefix = 'tai_lieu_qua_han';
                $headers = [__('STT'), __('Mã độc giả'), __('Họ tên'), __('Nhan đề tài liệu'), __('Mã vạch'), __('Ngày mượn'), __('Hạn trả'), __('Quá hạn (ngày)')];
                
                $query = LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])
                    ->where('status', 'borrowed')
                    ->where('due_date', '<', Carbon::now());

                // Simple search
                if ($request->filled('search')) {
                    $search = $request->input('search');
                    $query->whereHas('patron', function($p) use ($search) {
                        $p->where('patron_code', 'LIKE', "%{$search}%")
                          ->orWhere('display_name', 'LIKE', "%{$search}%");
                    });
                }

                // Patron Group
                $group_ids = $request->input('patron_group_ids', []);
                if (!empty($group_ids)) {
                    $query->whereHas('patron', function($p) use ($group_ids) {
                        $p->whereIn('patron_group_id', $group_ids);
                    });
                }

                if ($request->filled('date_from')) {
                    $query->whereDate('loan_date', '>=', $request->input('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->whereDate('loan_date', '<=', $request->input('date_to'));
                }

                $limit = intval($request->input('result_limit', 0));
                if ($limit > 0) $query->limit($limit);

                $loans = $query->orderBy('due_date', 'asc')->get();
                foreach ($loans as $index => $loan) {
                    $dueDate = Carbon::parse($loan->due_date);
                    $daysOverdue = intval($dueDate->diffInDays(Carbon::now(), false));
                    if ($daysOverdue < 0) $daysOverdue = 0;

                    $rows[] = [
                        $index + 1,
                        optional($loan->patron)->patron_code,
                        optional($loan->patron)->display_name,
                        optional($loan->bookItem->bibliographicRecord)->getMarcValue('245', 'a'),
                        optional($loan->bookItem)->barcode,
                        $loan->loan_date ? Carbon::parse($loan->loan_date)->format('d/m/Y') : '',
                        $loan->due_date ? Carbon::parse($loan->due_date)->format('d/m/Y') : '',
                        $daysOverdue
                    ];
                }
                break;

            case 'transaction_history':
                $title = __('Lược sử giao dịch sách');
                $prefix = 'luoc_su_giao_dich';
                $headers = [__('STT'), __('Mã độc giả'), __('Họ tên'), __('Nhan đề tài liệu'), __('Mã vạch'), __('Ngày mượn'), __('Ngày trả'), __('Trạng thái')];
                
                $query = LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord']);

                if ($request->filled('search')) {
                    $search = $request->input('search');
                    $query->where(function($q) use ($search) {
                        $q->whereHas('patron', function($p) use ($search) {
                            $p->where('patron_code', 'LIKE', "%{$search}%")
                              ->orWhere('display_name', 'LIKE', "%{$search}%");
                        })->orWhereHas('bookItem', function($bi) use ($search) {
                            $bi->where('barcode', 'LIKE', "%{$search}%");
                        });
                    });
                }

                if ($request->filled('date_from')) {
                    $query->whereDate('loan_date', '>=', $request->input('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->whereDate('loan_date', '<=', $request->input('date_to'));
                }

                $limit = intval($request->input('result_limit', 0));
                if ($limit > 0) $query->limit($limit);

                $loans = $query->orderBy('created_at', 'desc')->get();
                foreach ($loans as $index => $loan) {
                    $rows[] = [
                        $index + 1,
                        optional($loan->patron)->patron_code,
                        optional($loan->patron)->display_name,
                        optional($loan->bookItem->bibliographicRecord)->getMarcValue('245', 'a'),
                        optional($loan->bookItem)->barcode,
                        $loan->loan_date ? Carbon::parse($loan->loan_date)->format('d/m/Y') : '',
                        $loan->return_date ? Carbon::parse($loan->return_date)->format('d/m/Y') : '',
                        $loan->status === 'returned' ? __('Đã trả') : __('Đang mượn')
                    ];
                }
                break;

            case 'currently_borrowed':
            default:
                $title = __('Danh sách tài liệu đang mượn');
                $prefix = 'tai_lieu_dang_muon';
                $headers = [__('STT'), __('Mã độc giả'), __('Họ tên'), __('Nhan đề tài liệu'), __('Mã vạch'), __('Ngày mượn'), __('Hạn trả')];
                
                $query = LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])->where('status', 'borrowed');

                if ($request->filled('search')) {
                    $search = $request->input('search');
                    $query->whereHas('patron', function($p) use ($search) {
                        $p->where('patron_code', 'LIKE', "%{$search}%")
                          ->orWhere('display_name', 'LIKE', "%{$search}%");
                    });
                }

                $group_ids = $request->input('patron_group_ids', []);
                if (!empty($group_ids)) {
                    $query->whereHas('patron', function($p) use ($group_ids) {
                        $p->whereIn('patron_group_id', $group_ids);
                    });
                }

                if ($request->filled('date_from')) {
                    $query->whereDate('loan_date', '>=', $request->input('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->whereDate('loan_date', '<=', $request->input('date_to'));
                }

                $limit = intval($request->input('result_limit', 0));
                if ($limit > 0) $query->limit($limit);

                $loans = $query->orderBy('loan_date', 'desc')->get();
                foreach ($loans as $index => $loan) {
                    $rows[] = [
                        $index + 1,
                        optional($loan->patron)->patron_code,
                        optional($loan->patron)->display_name,
                        optional($loan->bookItem->bibliographicRecord)->getMarcValue('245', 'a'),
                        optional($loan->bookItem)->barcode,
                        $loan->loan_date ? Carbon::parse($loan->loan_date)->format('d/m/Y') : '',
                        $loan->due_date ? Carbon::parse($loan->due_date)->format('d/m/Y') : ''
                    ];
                }
                break;
        }

        if (empty($rows)) {
            return back()->with('error', __('Không tìm thấy dữ liệu phù hợp với bộ lọc đã chọn.'));
        }

        $fileName = $prefix . '_' . now()->format('Ymd_His');

        if ($format === 'excel') {
            return Excel::download(
                new DynamicCirculationReportExport($headers, $rows, $title), 
                $fileName . '.xlsx'
            );
        }

        // CSV format
        return Excel::download(
            new DynamicCirculationReportExport($headers, $rows, $title), 
            $fileName . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    // Keep individual routes for legacy compatibility
    public function currentlyBorrowed() { return redirect()->route('admin.circulation.reports.index', ['report_type' => 'currently_borrowed']); }
    public function patronService() { return redirect()->route('admin.circulation.reports.index', ['report_type' => 'patron_service']); }
    public function overdue() { return redirect()->route('admin.circulation.reports.index', ['report_type' => 'overdue']); }
    public function topPatrons() { return redirect()->route('admin.circulation.reports.index', ['report_type' => 'top_patrons']); }
    public function transactionHistory() { return redirect()->route('admin.circulation.reports.index', ['report_type' => 'transaction_history']); }
    public function neverBorrowed() { return redirect()->route('admin.circulation.reports.index', ['report_type' => 'never_borrowed']); }
    public function libraryEntries() { return redirect()->route('admin.circulation.reports.index', ['report_type' => 'library_entries']); }
    public function websiteAccess() { return redirect()->route('admin.circulation.reports.index', ['report_type' => 'website_access']); }
}
