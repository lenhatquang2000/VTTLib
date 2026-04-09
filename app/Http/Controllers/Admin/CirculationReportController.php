<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\LoanTransaction;
use App\Models\BookItem;
use App\Models\PatronDetail;
use App\Models\LibraryEntry;
use App\Models\WebsiteAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Circulation\MultiReportExport;

class CirculationReportController extends Controller
{
    /**
     * Export selected reports to Excel
     */
    public function export(Request $request)
    {
        $selectedReports = $request->input('reports', []);
        
        if (empty($selectedReports)) {
            return back()->with('error', __('Please select at least one report to export.'));
        }

        $fileName = 'Circulation_Reports_' . now()->format('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new MultiReportExport($selectedReports), $fileName);
    }

    /**
     * Display reports dashboard
     */
    public function index()
    {
        return view('admin.circulation.reports.index');
    }

    /**
     * Danh sách tài liệu đang mượn
     */
    public function currentlyBorrowed()
    {
        $loans = LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])
            ->where('status', 'borrowed')
            ->orderBy('loan_date', 'desc')
            ->paginate(50);

        return view('admin.circulation.reports.currently-borrowed', compact('loans'));
    }

    /**
     * Báo cáo phục vụ bạn đọc (Patron service statistics)
     */
    public function patronService()
    {
        $stats = PatronDetail::select('patron_groups.name as group_name', DB::raw('count(*) as total'))
            ->join('patron_groups', 'patron_details.patron_group_id', '=', 'patron_groups.id')
            ->groupBy('patron_groups.name')
            ->get();

        $loanStats = LoanTransaction::select(DB::raw('DATE(loan_date) as date'), DB::raw('count(*) as count'))
            ->where('loan_date', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->get();

        return view('admin.circulation.reports.patron-service', compact('stats', 'loanStats'));
    }

    /**
     * Danh sách tài liệu đang mượn quá hạn
     */
    public function overdue()
    {
        $loans = LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->paginate(50);

        return view('admin.circulation.reports.overdue', compact('loans'));
    }

    /**
     * Danh sách bạn đọc mượn tài liệu nhiều nhất
     */
    public function topPatrons()
    {
        $topPatrons = PatronDetail::with('user')
            ->withCount('loanTransactions')
            ->orderBy('loan_transactions_count', 'desc')
            ->limit(20)
            ->get();

        return view('admin.circulation.reports.top-patrons', compact('topPatrons'));
    }

    /**
     * Danh sách chi tiết lược sử giao dịch sách
     */
    public function transactionHistory()
    {
        $transactions = LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        return view('admin.circulation.reports.transaction-history', compact('transactions'));
    }

    /**
     * Danh sách tài liệu chưa mượn lần nào
     */
    public function neverBorrowed()
    {
        $items = BookItem::with('bibliographicRecord')
            ->whereDoesntHave('loanTransactions')
            ->paginate(100);

        return view('admin.circulation.reports.never-borrowed', compact('items'));
    }

    /**
     * Số lượng bạn đọc vào thư viện
     */
    public function libraryEntries()
    {
        $startDate = request()->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = request()->get('end_date', Carbon::now()->format('Y-m-d'));

        // Get entries with relationships
        $entries = LibraryEntry::with(['patron.user', 'branch'])
            ->forDateRange($startDate, $endDate)
            ->orderBy('entry_time', 'desc')
            ->paginate(50);

        // Get statistics
        $stats = [
            'total_entries' => LibraryEntry::forDateRange($startDate, $endDate)->count(),
            'unique_patrons' => LibraryEntry::forDateRange($startDate, $endDate)
                ->whereNotNull('patron_detail_id')
                ->distinct('patron_detail_id')
                ->count(),
            'average_duration' => LibraryEntry::forDateRange($startDate, $endDate)
                ->whereNotNull('exit_time')
                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, entry_time, exit_time)) as avg_duration')
                ->value('avg_duration'),
            'by_purpose' => LibraryEntry::forDateRange($startDate, $endDate)
                ->selectRaw('purpose, COUNT(*) as count')
                ->whereNotNull('purpose')
                ->groupBy('purpose')
                ->get(),
            'by_branch' => LibraryEntry::forDateRange($startDate, $endDate)
                ->selectRaw('branches.name, COUNT(*) as count')
                ->join('branches', 'library_entries.branch_id', '=', 'branches.id')
                ->groupBy('branches.id', 'branches.name')
                ->get(),
            'daily_stats' => LibraryEntry::forDateRange($startDate, $endDate)
                ->selectRaw('DATE(entry_time) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];

        return view('admin.circulation.reports.library-entries', compact('entries', 'stats', 'startDate', 'endDate'));
    }

    /**
     * Thống kê lượt truy cập website
     */
    public function websiteAccess()
    {
        $startDate = request()->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = request()->get('end_date', Carbon::now()->format('Y-m-d'));

        // Get accesses with relationships
        $accesses = WebsiteAccess::with('user')
            ->forDateRange($startDate, $endDate)
            ->orderBy('access_time', 'desc')
            ->paginate(100);

        // Get statistics
        $stats = [
            'total_visits' => WebsiteAccess::forDateRange($startDate, $endDate)->count(),
            'unique_sessions' => WebsiteAccess::forDateRange($startDate, $endDate)
                ->distinct('session_id')
                ->count('session_id'),
            'unique_visitors' => WebsiteAccess::forDateRange($startDate, $endDate)
                ->distinct('ip_address')
                ->count('ip_address'),
            'registered_users' => WebsiteAccess::forDateRange($startDate, $endDate)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id'),
            'by_device_type' => WebsiteAccess::forDateRange($startDate, $endDate)
                ->selectRaw('device_type, COUNT(*) as count')
                ->whereNotNull('device_type')
                ->groupBy('device_type')
                ->get(),
            'by_access_type' => WebsiteAccess::forDateRange($startDate, $endDate)
                ->selectRaw('access_type, COUNT(*) as count')
                ->groupBy('access_type')
                ->get(),
            'by_browser' => WebsiteAccess::forDateRange($startDate, $endDate)
                ->selectRaw('browser, COUNT(*) as count')
                ->whereNotNull('browser')
                ->groupBy('browser')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
            'daily_stats' => WebsiteAccess::forDateRange($startDate, $endDate)
                ->selectRaw('DATE(access_time) as date, COUNT(*) as visits, COUNT(DISTINCT session_id) as sessions')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_pages' => WebsiteAccess::forDateRange($startDate, $endDate)
                ->selectRaw('page_url, COUNT(*) as count')
                ->whereNotNull('page_url')
                ->groupBy('page_url')
                ->orderByDesc('count')
                ->limit(10)
                ->get()
        ];

        return view('admin.circulation.reports.website-access', compact('accesses', 'stats', 'startDate', 'endDate'));
    }
}
