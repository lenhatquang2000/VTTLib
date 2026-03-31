<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\LoanTransaction;
use App\Models\BookItem;
use App\Models\PatronDetail;
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
}
