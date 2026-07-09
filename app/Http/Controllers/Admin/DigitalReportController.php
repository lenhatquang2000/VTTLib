<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DigitalResource;
use App\Models\DigitalFolder;
use App\Models\Branch;
use App\Exports\DynamicDigitalReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DigitalReportController extends Controller
{
    /**
     * Display the digital reports view.
     */
    public function index(Request $request)
    {
        $reportType = $request->query('report_type', 'digital_list');
        
        $folders = DigitalFolder::all();
        $branches = Branch::where('is_active', true)->get();
        
        $reportsList = [
            'digital_qty_stats' => [
                'title' => __('Thống kê số lượng tài liệu số'),
                'desc' => __('Báo cáo tổng hợp số lượng tài liệu số và dung lượng lưu trữ theo từng thư mục.')
            ],
            'most_viewed_by_period' => [
                'title' => __('Danh sách tài liệu số xem nhiều theo thời gian'),
                'desc' => __('Danh sách các tài liệu số có lượt xem cao nhất lọc theo một khoảng thời gian cụ thể.')
            ],
            'most_downloaded_by_period' => [
                'title' => __('Danh sách tài liệu số tải nhiều theo thời gian'),
                'desc' => __('Danh sách các tài liệu số có lượt tải về cao nhất lọc theo một khoảng thời gian cụ thể.')
            ],
            'digital_list' => [
                'title' => __('Danh sách tài liệu số trong thư viện'),
                'desc' => __('Báo cáo chi tiết danh sách tất cả các tài liệu số đang được quản lý trong thư viện.')
            ],
            'most_viewed' => [
                'title' => __('Danh sách tài liệu số xem nhiều'),
                'desc' => __('Danh sách các tài liệu số có tổng lượt xem trực tuyến cao nhất từ trước đến nay.')
            ],
            'most_downloaded' => [
                'title' => __('Danh sách tài liệu số tải nhiều'),
                'desc' => __('Danh sách các tài liệu số có tổng lượt tải về máy cao nhất từ trước đến nay.')
            ]
        ];

        if (!array_key_exists($reportType, $reportsList)) {
            $reportType = 'digital_list';
        }

        $activeReport = $reportsList[$reportType];
        
        return view('admin.digital-resources.reports.index', compact(
            'folders', 
            'branches', 
            'reportType',
            'activeReport',
            'reportsList'
        ));
    }

    /**
     * Generate and export the selected digital report.
     */
    public function generate(Request $request)
    {
        $reportType = $request->input('report_type', 'digital_list');
        $format = $request->input('format', 'excel');

        // Build query on DigitalResource
        $query = DigitalResource::with(['folder']);

        // 1. Simple search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('publisher', 'LIKE', "%{$search}%");
            });
        }

        // 2. Advanced Search Tab 1: Info (Logic Builder)
        $ops = $request->input('info_ops', []);
        $fields = $request->input('info_fields', []);
        $vals = $request->input('info_vals', []);

        if (!empty($fields)) {
            $query->where(function($subQ) use ($ops, $fields, $vals) {
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $val = $vals[$i] ?? '';
                    $op = $ops[$i] ?? 'AND';

                    if (empty($val)) continue;

                    $clause = function($q) use ($field, $val) {
                        if ($field === 'title') {
                            $q->where('title', 'LIKE', "%{$val}%");
                        } elseif ($field === 'author') {
                            $q->where('authors', 'LIKE', "%{$val}%");
                        } elseif ($field === 'publisher') {
                            $q->where('publisher', 'LIKE', "%{$val}%");
                        } elseif ($field === 'publish_year') {
                            $q->where('publish_year', '=', $val);
                        } elseif ($field === 'language') {
                            $q->where('language', 'LIKE', "%{$val}%");
                        } elseif ($field === 'any') {
                            $q->where('title', 'LIKE', "%{$val}%")
                              ->orWhere('authors', 'LIKE', "%{$val}%")
                              ->orWhere('publisher', 'LIKE', "%{$val}%")
                              ->orWhere('description', 'LIKE', "%{$val}%");
                        }
                    };

                    if ($i === 0 || $op === 'AND') {
                        $subQ->where($clause);
                    } elseif ($op === 'OR') {
                        $subQ->orWhere($clause);
                    } elseif ($op === 'NOT') {
                        $subQ->whereNot($clause);
                    }
                }
            });
        }

        // 3. Tab 2: Folders and Statuses
        $folder_ids = $request->input('folder_ids', []);
        if (!empty($folder_ids)) {
            $query->whereIn('folder_id', $folder_ids);
        }

        $statuses = $request->input('statuses', []);
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // 4. Tab 3: Limits & Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // File size range (in bytes)
        if ($request->filled('size_min_mb')) {
            $query->where('file_size', '>=', intval($request->input('size_min_mb')) * 1024 * 1024);
        }
        if ($request->filled('size_max_mb')) {
            $query->where('file_size', '<=', intval($request->input('size_max_mb')) * 1024 * 1024);
        }

        // Apply ordering based on report_type
        if (in_array($reportType, ['most_viewed', 'most_viewed_by_period'])) {
            $query->orderBy('view_count', 'desc');
        } elseif (in_array($reportType, ['most_downloaded', 'most_downloaded_by_period'])) {
            $query->orderBy('download_count', 'desc');
        } else {
            $query->latest();
        }

        // Limit results
        $limit = intval($request->input('result_limit', 0));
        if ($limit > 0) {
            $query->limit($limit);
        }

        $resources = $query->get();

        if ($reportType !== 'digital_qty_stats' && $resources->isEmpty()) {
            return back()->with('error', __('Không tìm thấy tài liệu số phù hợp với bộ lọc đã chọn.'));
        }

        $title = '';
        $prefix = 'digital_reports';
        $headers = [];
        $rows = [];

        switch ($reportType) {
            case 'digital_qty_stats':
                $title = __('Báo cáo thống kê số lượng tài liệu số');
                $prefix = 'thong_ke_so_luong';
                $headers = [__('STT'), __('Tên thư mục'), __('Loại tập tin chủ đạo'), __('Tổng số tập tin'), __('Tổng dung lượng (MB)')];
                
                $folders = DigitalFolder::withCount('resources')->get();
                foreach ($folders as $index => $folder) {
                    $totalSize = DigitalResource::where('folder_id', $folder->id)->sum('file_size');
                    $sizeMb = round($totalSize / (1024 * 1024), 2);
                    
                    // Determine dominant format
                    $dominantFormat = DigitalResource::where('folder_id', $folder->id)
                        ->select('format', DB::raw('count(*) as count'))
                        ->groupBy('format')
                        ->orderBy('count', 'desc')
                        ->value('format') ?: __('Không rõ');

                    $rows[] = [
                        $index + 1,
                        $folder->name,
                        strtoupper($dominantFormat),
                        $folder->resources_count,
                        $sizeMb
                    ];
                }
                break;

            case 'most_viewed':
            case 'most_viewed_by_period':
                $title = __('Báo cáo tài liệu số xem nhiều');
                $prefix = 'xem_nhieu';
                $headers = [__('STT'), __('Tiêu đề tài liệu'), __('Tác giả'), __('Nhà xuất bản'), __('Năm xuất bản'), __('Thư mục'), __('Tổng lượt xem')];
                foreach ($resources as $index => $r) {
                    $rows[] = [
                        $index + 1,
                        $r->title,
                        is_array($r->authors) ? implode(', ', $r->authors) : $r->authors,
                        $r->publisher,
                        $r->publish_year,
                        optional($r->folder)->name,
                        $r->view_count
                    ];
                }
                break;

            case 'most_downloaded':
            case 'most_downloaded_by_period':
                $title = __('Báo cáo tài liệu số tải nhiều');
                $prefix = 'tai_nhieu';
                $headers = [__('STT'), __('Tiêu đề tài liệu'), __('Tác giả'), __('Nhà xuất bản'), __('Năm xuất bản'), __('Thư mục'), __('Tổng lượt tải')];
                foreach ($resources as $index => $r) {
                    $rows[] = [
                        $index + 1,
                        $r->title,
                        is_array($r->authors) ? implode(', ', $r->authors) : $r->authors,
                        $r->publisher,
                        $r->publish_year,
                        optional($r->folder)->name,
                        $r->download_count
                    ];
                }
                break;

            case 'digital_list':
            default:
                $title = __('Danh sách tài liệu số trong thư viện');
                $prefix = 'danh_sach_tai_lieu_so';
                $headers = [__('STT'), __('Tiêu đề tài liệu'), __('Tác giả'), __('Nhà xuất bản'), __('Năm xuất bản'), __('Thư mục'), __('Dung lượng (MB)'), __('Ngôn ngữ'), __('Trạng thái')];
                foreach ($resources as $index => $r) {
                    $sizeMb = round($r->file_size / (1024 * 1024), 2);
                    $rows[] = [
                        $index + 1,
                        $r->title,
                        is_array($r->authors) ? implode(', ', $r->authors) : $r->authors,
                        $r->publisher,
                        $r->publish_year,
                        optional($r->folder)->name,
                        $sizeMb,
                        $r->language,
                        $r->status === 'active' ? __('Đã duyệt / Khả dụng') : __('Bản nháp / Chờ duyệt')
                    ];
                }
                break;
        }

        $fileName = $prefix . '_' . now()->format('Ymd_His');

        if ($format === 'excel') {
            return Excel::download(
                new DynamicDigitalReportExport($headers, $rows, $title), 
                $fileName . '.xlsx'
            );
        }

        // CSV format
        return Excel::download(
            new DynamicDigitalReportExport($headers, $rows, $title), 
            $fileName . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
