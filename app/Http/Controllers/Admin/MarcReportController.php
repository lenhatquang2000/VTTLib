<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BibliographicRecord;
use App\Models\MarcFramework;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\StorageLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MarcReportsExport;

class MarcReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        $frameworkId = $request->get('framework_id');
        
        // Get frameworks for filter
        $frameworks = MarcFramework::where('is_active', true)->get();
        
        // Get statistics
        $stats = $this->getCatalogingStatistics($dateRange, $frameworkId);
        
        // Get recent activity
        $recentActivity = $this->getRecentCatalogingActivity($dateRange, $frameworkId);
        
        // Get productivity metrics
        $productivity = $this->getProductivityMetrics($dateRange, $frameworkId);
        
        // Get quality metrics
        $quality = $this->getQualityMetrics($dateRange, $frameworkId);
        
        return view('admin.marc_reports.index', compact(
            'frameworks',
            'stats',
            'recentActivity',
            'productivity',
            'quality',
            'dateRange'
        ));
    }

    /**
     * Generate detailed reports
     */
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:summary,productivity,quality,detailed,framework,document_type,department,comprehensive,statistics,catalog,category,new,published,year',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'framework_id' => 'nullable|exists:marc_frameworks,id',
            'format' => 'required|in:web,excel,pdf'
        ]);

        // Handle empty dates
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from) : Carbon::now()->subDays(30);
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : Carbon::now()->endOfDay();
        
        $dateRange = [
            'from' => $dateFrom,
            'to' => $dateTo
        ];

        $frameworkId = $request->framework_id;

        // Route to appropriate report generator
        switch ($request->report_type) {
            case 'summary':
            case 'productivity':
            case 'quality':
            case 'detailed':
                $data = $this->generateMarcReport($request->report_type, $dateRange, $frameworkId);
                break;
                
            case 'framework':
            case 'document_type':
            case 'department':
            case 'comprehensive':
                $data = $this->generateSubsystemReport($request->report_type, $dateRange, $frameworkId);
                break;
                
            case 'statistics':
            case 'catalog':
            case 'category':
            case 'new':
            case 'published':
            case 'year':
                $data = $this->generateDocumentReport($request->report_type, $dateRange);
                break;
        }

        if ($request->format === 'excel') {
            return Excel::download(new MarcReportsExport($data, $request->report_type), 
                'marc_report_' . $request->report_type . '_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.xlsx');
        }

        if ($request->format === 'pdf') {
            // For now, return Excel for PDF too - you can implement PDF generation later
            return Excel::download(new MarcReportsExport($data, $request->report_type), 
                'marc_report_' . $request->report_type . '_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.xlsx');
        }

        return view('admin.marc_reports.' . $request->report_type, [
            'data' => $data,
            'dateRange' => $dateRange,
            'framework' => $frameworkId ? MarcFramework::find($frameworkId) : null
        ]);
    }

    /**
     * Generate MARC reports (existing functionality)
     */
    protected function generateMarcReport($reportType, array $dateRange, ?int $frameworkId): array
    {
        switch ($reportType) {
            case 'summary':
                return $this->generateSummaryReport($dateRange, $frameworkId);
            case 'productivity':
                return $this->generateProductivityReport($dateRange, $frameworkId);
            case 'quality':
                return $this->generateQualityReport($dateRange, $frameworkId);
            case 'detailed':
                return $this->generateDetailedReport($dateRange, $frameworkId);
        }
    }

    /**
     * Generate subsystem reports
     */
    protected function generateSubsystemReport($reportType, array $dateRange, ?int $frameworkId): array
    {
        $query = BibliographicRecord::whereBetween('created_at', [$dateRange['from'], $dateRange['to']]);
        
        if ($frameworkId) {
            $framework = MarcFramework::find($frameworkId);
            $query->where('framework', $framework->code);
        }

        switch ($reportType) {
            case 'framework':
                return $this->generateFrameworkReport($query, $dateRange);
            case 'document_type':
                return $this->generateDocumentTypeReport($query, $dateRange);
            case 'department':
                return $this->generateDepartmentReport($query, $dateRange);
            case 'comprehensive':
                return $this->generateComprehensiveReport($query, $dateRange);
        }
    }

    /**
     * Generate document reports
     */
    protected function generateDocumentReport($reportType, array $dateRange): array
    {
        $query = BibliographicRecord::whereBetween('created_at', [$dateRange['from'], $dateRange['to']]);

        switch ($reportType) {
            case 'statistics':
                return $this->generateDocumentStatistics($query, $dateRange);
            case 'catalog':
                return $this->generateDocumentCatalog($query, $dateRange);
            case 'category':
                return $this->generateDocumentCategory($query, $dateRange);
            case 'new':
                return $this->generateNewDocuments($query, $dateRange);
            case 'published':
                return $this->generatePublishedDocuments($query, $dateRange);
            case 'year':
                return $this->generateDocumentYearReport($query, $dateRange);
        }
    }

    /**
     * Generate framework report
     */
    protected function generateFrameworkReport($query, array $dateRange): array
    {
        $byFramework = $query->selectRaw('framework, COUNT(*) as count')
            ->groupBy('framework')
            ->get();

        return [
            'title' => 'Báo cáo theo Framework',
            'data' => $byFramework,
            'date_range' => $dateRange,
            'total' => $byFramework->sum('count')
        ];
    }

    /**
     * Generate document type report
     */
    protected function generateDocumentTypeReport($query, array $dateRange): array
    {
        $byType = $query->selectRaw('document_type_id, COUNT(*) as count')
            ->with('documentType')
            ->groupBy('document_type_id')
            ->get();

        return [
            'title' => 'Báo cáo theo Loại Tài liệu',
            'data' => $byType,
            'date_range' => $dateRange,
            'total' => $byType->sum('count')
        ];
    }

    /**
     * Generate department report
     */
    protected function generateDepartmentReport($query, array $dateRange): array
    {
        $byUser = $query->selectRaw('user_id, COUNT(*) as count')
            ->with('user')
            ->groupBy('user_id')
            ->get();

        return [
            'title' => 'Báo cáo Theo Phòng Ban',
            'data' => $byUser,
            'date_range' => $dateRange,
            'total' => $byUser->sum('count')
        ];
    }

    /**
     * Generate comprehensive report
     */
    protected function generateComprehensiveReport($query, array $dateRange): array
    {
        return [
            'title' => 'Báo cáo Tổng Hợp',
            'framework_stats' => $this->generateFrameworkReport($query, $dateRange),
            'type_stats' => $this->generateDocumentTypeReport($query, $dateRange),
            'department_stats' => $this->generateDepartmentReport($query, $dateRange),
            'date_range' => $dateRange
        ];
    }

    /**
     * Generate document statistics
     */
    protected function generateDocumentStatistics($query, array $dateRange): array
    {
        $total = $query->count();
        $byStatus = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return [
            'title' => 'Thống kê Tài liệu',
            'total_records' => $total,
            'by_status' => $byStatus,
            'date_range' => $dateRange
        ];
    }

    /**
     * Generate document catalog
     */
    protected function generateDocumentCatalog($query, array $dateRange): array
    {
        $records = $query->with(['documentType', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'title' => 'Danh mục Tài liệu',
            'records' => $records,
            'total' => $records->count(),
            'date_range' => $dateRange
        ];
    }

    /**
     * Generate document category report
     */
    protected function generateDocumentCategory($query, array $dateRange): array
    {
        $byCategory = $query->selectRaw('document_type_id, COUNT(*) as count')
            ->with('documentType')
            ->groupBy('document_type_id')
            ->get();

        return [
            'title' => 'Tài liệu Theo Thể loại',
            'data' => $byCategory,
            'date_range' => $dateRange,
            'total' => $byCategory->sum('count')
        ];
    }

    /**
     * Generate new documents report
     */
    protected function generateNewDocuments($query, array $dateRange): array
    {
        $records = $query->with(['documentType', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'title' => 'Tài liệu Mới',
            'records' => $records,
            'total' => $records->count(),
            'date_range' => $dateRange
        ];
    }

    /**
     * Generate published documents report
     */
    protected function generatePublishedDocuments($query, array $dateRange): array
    {
        $records = $query->where('status', 'approved')
            ->with(['documentType', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'title' => 'Tài liệu Đã Xuất bản',
            'records' => $records,
            'total' => $records->count(),
            'date_range' => $dateRange
        ];
    }

    /**
     * Generate document year report
     */
    protected function generateDocumentYearReport($query, array $dateRange): array
    {
        $byYear = $query->selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return [
            'title' => 'Theo Năm Xuất bản',
            'data' => $byYear,
            'date_range' => $dateRange,
            'total' => $byYear->sum('count')
        ];
    }

    /**
     * Get cataloging statistics
     */
    protected function getCatalogingStatistics(array $dateRange, ?int $frameworkId): array
    {
        $query = BibliographicRecord::whereBetween('created_at', [$dateRange['from'], $dateRange['to']]);
        
        if ($frameworkId) {
            $query->where('framework', MarcFramework::find($frameworkId)->code);
        }

        $totalRecords = $query->count();
        
        // Records by status
        $byStatus = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Records by type
        $byType = $query->selectRaw('record_type, COUNT(*) as count')
            ->groupBy('record_type')
            ->pluck('count', 'record_type')
            ->toArray();

        // Records by framework
        $byFramework = BibliographicRecord::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->selectRaw('framework, COUNT(*) as count')
            ->groupBy('framework')
            ->pluck('count', 'framework')
            ->toArray();

        // Daily cataloging trend
        $dailyTrend = BibliographicRecord::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total_records' => $totalRecords,
            'by_status' => $byStatus,
            'by_type' => $byType,
            'by_framework' => $byFramework,
            'daily_trend' => $dailyTrend,
            'approved_rate' => $totalRecords > 0 ? (($byStatus['approved'] ?? 0) / $totalRecords) * 100 : 0
        ];
    }

    /**
     * Get recent cataloging activity
     */
    protected function getRecentCatalogingActivity(array $dateRange, ?int $frameworkId): array
    {
        $query = ActivityLog::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->where('action', 'like', '%marc%')
            ->with('user');

        if ($frameworkId) {
            $framework = MarcFramework::find($frameworkId);
            $query->where('action', 'like', '%' . $framework->code . '%');
        }

        return $query->latest()->limit(10)->get()->map(function ($log) {
            return [
                'user' => $log->user->name,
                'action' => $log->action,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                'ip_address' => $log->ip_address
            ];
        })->toArray();
    }

    /**
     * Get productivity metrics
     */
    protected function getProductivityMetrics(array $dateRange, ?int $frameworkId): array
    {
        $query = BibliographicRecord::whereBetween('created_at', [$dateRange['from'], $dateRange['to']]);
        
        if ($frameworkId) {
            $query->where('framework', MarcFramework::find($frameworkId)->code);
        }

        // Records per user
        $recordsPerUser = DB::table('activity_logs')
            ->whereBetween('activity_logs.created_at', [$dateRange['from'], $dateRange['to']])
            ->where('activity_logs.action', 'like', '%created%')
            ->where('activity_logs.action', 'like', '%marc%')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->selectRaw('users.name, COUNT(*) as count')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Average records per day
        $days = $dateRange['from']->diffInDays($dateRange['to']) + 1;
        $totalRecords = $query->count();
        $avgPerDay = $days > 0 ? $totalRecords / $days : 0;

        // Peak cataloging day
        $peakDay = BibliographicRecord::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderByDesc('count')
            ->first();

        return [
            'records_per_user' => $recordsPerUser,
            'avg_per_day' => round($avgPerDay, 2),
            'peak_day' => $peakDay ? [
                'date' => $peakDay->date,
                'count' => $peakDay->count
            ] : null
        ];
    }

    /**
     * Get quality metrics
     */
    protected function getQualityMetrics(array $dateRange, ?int $frameworkId): array
    {
        $query = BibliographicRecord::whereBetween('created_at', [$dateRange['from'], $dateRange['to']]);
        
        if ($frameworkId) {
            $query->where('framework', MarcFramework::find($frameworkId)->code);
        }

        // Records with complete fields (has title, author, publisher)
        $completeRecords = $query->whereHas('fields', function ($q) {
            $q->where('tag', '245')->whereHas('subfields', function ($sq) {
                $sq->where('code', 'a')->whereNotNull('value')->where('value', '!=', '');
            });
        })->whereHas('fields', function ($q) {
            $q->where('tag', '100')->whereHas('subfields', function ($sq) {
                $sq->where('code', 'a')->whereNotNull('value')->where('value', '!=', '');
            });
        })->whereHas('fields', function ($q) {
            $q->where('tag', '260')->whereHas('subfields', function ($sq) {
                $sq->where('code', 'b')->whereNotNull('value')->where('value', '!=', '');
            });
        })->count();

        $totalRecords = $query->count();
        $completenessRate = $totalRecords > 0 ? ($completeRecords / $totalRecords) * 100 : 0;

        // Average fields per record
        $avgFields = DB::table('bibliographic_records as br')
            ->whereBetween('br.created_at', [$dateRange['from'], $dateRange['to']])
            ->join('marc_fields as mf', 'br.id', '=', 'mf.record_id')
            ->selectRaw('COUNT(mf.id) / COUNT(DISTINCT br.id) as avg_fields')
            ->value('avg_fields');

        // Records with ISBN
        $recordsWithISBN = $query->whereHas('fields', function ($q) {
            $q->where('tag', '020')->whereHas('subfields', function ($sq) {
                $sq->where('code', 'a')->whereNotNull('value')->where('value', '!=', '');
            });
        })->count();

        $isbnRate = $totalRecords > 0 ? ($recordsWithISBN / $totalRecords) * 100 : 0;

        return [
            'completeness_rate' => round($completenessRate, 2),
            'avg_fields_per_record' => round($avgFields, 2),
            'isbn_rate' => round($isbnRate, 2),
            'total_records' => $totalRecords,
            'complete_records' => $completeRecords,
            'records_with_isbn' => $recordsWithISBN
        ];
    }

    /**
     * Generate summary report
     */
    protected function generateSummaryReport(array $dateRange, ?int $frameworkId): array
    {
        return [
            'statistics' => $this->getCatalogingStatistics($dateRange, $frameworkId),
            'productivity' => $this->getProductivityMetrics($dateRange, $frameworkId),
            'quality' => $this->getQualityMetrics($dateRange, $frameworkId),
            'date_range' => $dateRange,
            'framework' => $frameworkId ? MarcFramework::find($frameworkId) : null
        ];
    }

    /**
     * Generate productivity report
     */
    protected function generateProductivityReport(array $dateRange, ?int $frameworkId): array
    {
        $productivity = $this->getProductivityMetrics($dateRange, $frameworkId);
        
        // Detailed user productivity
        $userProductivity = DB::table('activity_logs')
            ->whereBetween('activity_logs.created_at', [$dateRange['from'], $dateRange['to']])
            ->where('activity_logs.action', 'like', '%created%')
            ->where('activity_logs.action', 'like', '%marc%')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->selectRaw('
                users.name,
                users.email,
                COUNT(*) as total_records,
                COUNT(DISTINCT DATE(activity_logs.created_at)) as active_days
            ')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_records')
            ->get();

        return [
            'productivity' => $productivity,
            'user_productivity' => $userProductivity,
            'date_range' => $dateRange,
            'framework' => $frameworkId ? MarcFramework::find($frameworkId) : null
        ];
    }

    /**
     * Generate quality report
     */
    protected function generateQualityReport(array $dateRange, ?int $frameworkId): array
    {
        $quality = $this->getQualityMetrics($dateRange, $frameworkId);
        
        // Field completion analysis
        $fieldCompletion = DB::table('bibliographic_records as br')
            ->whereBetween('br.created_at', [$dateRange['from'], $dateRange['to']])
            ->when($frameworkId, function ($q) use ($frameworkId) {
                $framework = MarcFramework::find($frameworkId);
                $q->where('br.framework', $framework->code);
            })
            ->leftJoin('marc_fields as mf', 'br.id', '=', 'mf.bibliographic_record_id')
            ->leftJoin('marc_subfields as msf', 'mf.id', '=', 'msf.marc_field_id')
            ->selectRaw('
                mf.tag,
                COUNT(DISTINCT br.id) as total_records,
                COUNT(DISTINCT CASE WHEN msf.value IS NOT NULL AND msf.value != "" THEN br.id END) as records_with_field,
                ROUND(COUNT(DISTINCT CASE WHEN msf.value IS NOT NULL AND msf.value != "" THEN br.id END) * 100.0 / COUNT(DISTINCT br.id), 2) as completion_rate
            ')
            ->groupBy('mf.tag')
            ->orderByDesc('completion_rate')
            ->get();

        return [
            'quality' => $quality,
            'field_completion' => $fieldCompletion,
            'date_range' => $dateRange,
            'framework' => $frameworkId ? MarcFramework::find($frameworkId) : null
        ];
    }

    /**
     * Generate detailed report
     */
    protected function generateDetailedReport(array $dateRange, ?int $frameworkId): array
    {
        $query = BibliographicRecord::with(['fields.subfields'])
            ->whereBetween('created_at', [$dateRange['from'], $dateRange['to']]);
            
        if ($frameworkId) {
            $framework = MarcFramework::find($frameworkId);
            $query->where('framework', $framework->code);
        }

        $records = $query->get()->map(function ($record) {
            $title = '';
            $author = '';
            $isbn = '';
            $publisher = '';
            
            foreach ($record->fields as $field) {
                if ($field->tag === '245') {
                    foreach ($field->subfields as $subfield) {
                        if ($subfield->code === 'a') {
                            $title = $subfield->value;
                        }
                    }
                }
                if ($field->tag === '100') {
                    foreach ($field->subfields as $subfield) {
                        if ($subfield->code === 'a') {
                            $author = $subfield->value;
                        }
                    }
                }
                if ($field->tag === '020') {
                    foreach ($field->subfields as $subfield) {
                        if ($subfield->code === 'a') {
                            $isbn = $subfield->value;
                        }
                    }
                }
                if ($field->tag === '260') {
                    foreach ($field->subfields as $subfield) {
                        if ($subfield->code === 'b') {
                            $publisher = $subfield->value;
                        }
                    }
                }
            }
            
            return [
                'id' => $record->id,
                'title' => $title,
                'author' => $author,
                'isbn' => $isbn,
                'publisher' => $publisher,
                'framework' => $record->framework,
                'record_type' => $record->record_type,
                'status' => $record->status,
                'fields_count' => $record->fields->count(),
                'created_at' => $record->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $record->updated_at->format('Y-m-d H:i:s')
            ];
        });

        return [
            'records' => $records,
            'total_records' => $records->count(),
            'date_range' => $dateRange,
            'framework' => $frameworkId ? MarcFramework::find($frameworkId) : null
        ];
    }

    /**
     * Get date range from request
     */
    protected function getDateRange(Request $request): array
    {
        $defaultFrom = Carbon::now()->subDays(30);
        $defaultTo = Carbon::now();

        return [
            'from' => $request->get('date_from') ? Carbon::parse($request->date_from) : $defaultFrom,
            'to' => $request->get('date_to') ? Carbon::parse($request->date_to) : $defaultTo
        ];
    }
}
