<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatronDetail;
use App\Models\PatronGroup;
use App\Models\Branch;
use App\Models\User;
use App\Exports\DynamicPatronReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class PatronReportController extends Controller
{
    /**
     * Display the patron reports list and filters.
     */
    public function index(Request $request)
    {
        $reportType = $request->query('report_type', 'patron_list');
        
        $patronGroups = PatronGroup::all();
        $branches = Branch::where('is_active', true)->get();
        
        $reportsList = [
            'patron_list' => [
                'title' => __('Danh sách độc giả trong thư viện'),
                'desc' => __('Xuất danh sách tất cả các độc giả đã đăng ký tài khoản sử dụng thư viện.')
            ],
            'print_cards' => [
                'title' => __('In thẻ độc giả'),
                'desc' => __('Xuất dữ liệu thông tin thẻ của độc giả để chuẩn bị in thẻ thành viên.')
            ],
            'renew_list' => [
                'title' => __('Danh sách độc giả gia hạn thẻ'),
                'desc' => __('Xuất danh sách độc giả đã làm thủ tục gia hạn thẻ thư viện.')
            ],
            'renew_by_period' => [
                'title' => __('Danh sách độc giả gia hạn thẻ theo một khoảng thời gian'),
                'desc' => __('Xuất danh sách độc giả làm thủ tục gia hạn thẻ trong khoảng thời gian xác định.')
            ],
            'viewer_patron_list' => [
                'title' => __('[ReportViewer] Danh sách độc giả trong thư viện'),
                'desc' => __('Xem trước và in ấn danh sách độc giả trực tuyến thông qua ReportViewer.')
            ],
            'viewer_print_cards' => [
                'title' => __('[ReportViewer] In thẻ độc giả'),
                'desc' => __('Xem trước và thiết kế mẫu thẻ độc giả trước khi in.')
            ]
        ];

        if (!array_key_exists($reportType, $reportsList)) {
            $reportType = 'patron_list';
        }

        $activeReport = $reportsList[$reportType];
        
        $patrons = null;
        if ($reportType === 'patron_list') {
            $query = $this->buildQuery($request);
            
            // Limit results if result_limit is set
            $limit = intval($request->input('result_limit', 0));
            if ($limit > 0) {
                $query->limit($limit);
            }
            
            // Paginate preview list
            $patrons = $query->latest('registration_date')->paginate(15)->withQueryString();
        }
        
        return view('admin.patrons.reports.index', compact(
            'patronGroups', 
            'branches', 
            'reportType',
            'activeReport',
            'reportsList',
            'patrons'
        ));
    }

    /**
     * Build the query for patron reports based on filters.
     */
    public function buildQuery(Request $request)
    {
        $query = PatronDetail::with(['user', 'patronGroup', 'branch', 'addresses']);

        // 1. Simple search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('patron_code', 'LIKE', "%{$search}%")
                  ->orWhere('display_name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($subQ) use ($search) {
                      $subQ->where('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 2. Advanced Search Tab 1: Info (Logic builder)
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
                        if ($field === 'patron_code') {
                            $q->where('patron_code', 'LIKE', "%{$val}%");
                        } elseif ($field === 'name') {
                            $q->where('display_name', 'LIKE', "%{$val}%");
                        } elseif ($field === 'email') {
                            $q->whereHas('user', function($userQ) use ($val) {
                                $userQ->where('email', 'LIKE', "%{$val}%");
                              });
                        } elseif ($field === 'phone') {
                            $q->where('phone', 'LIKE', "%{$val}%")
                              ->orWhere('phone_contact', 'LIKE', "%{$val}%");
                        } elseif ($field === 'mssv') {
                            $q->where('mssv', 'LIKE', "%{$val}%");
                        } elseif ($field === 'id_card') {
                            $q->where('id_card', 'LIKE', "%{$val}%");
                        } elseif ($field === 'department') {
                            $q->where('department', 'LIKE', "%{$val}%");
                        } elseif ($field === 'any') {
                            $q->where('patron_code', 'LIKE', "%{$val}%")
                              ->orWhere('display_name', 'LIKE', "%{$val}%")
                              ->orWhere('phone', 'LIKE', "%{$val}%")
                              ->orWhereHas('user', function($userQ) use ($val) {
                                  $userQ->where('email', 'LIKE', "%{$val}%");
                              });
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

        // 3. Tab 2: Status and Patron Group
        $statuses = $request->input('statuses', []);
        if (!empty($statuses)) {
            $query->whereIn('card_status', $statuses);
        } else {
            $query->where('card_status', '!=', 'pending');
        }

        $group_ids = $request->input('patron_group_ids', []);
        if (!empty($group_ids)) {
            $query->whereIn('patron_group_id', $group_ids);
        }

        // 4. Tab 3: Limits & Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('registration_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('registration_date', '<=', $request->input('date_to'));
        }
        if ($request->filled('expiry_from')) {
            $query->whereDate('expiry_date', '>=', $request->input('expiry_from'));
        }
        if ($request->filled('expiry_to')) {
            $query->whereDate('expiry_date', '<=', $request->input('expiry_to'));
        }

        // 5. Tab 4: Location / Branch
        if ($request->filled('branch_id')) {
            $query->where('branch', $request->input('branch_id'));
        }

        return $query;
    }

    /**
     * Generate and export the patron report.
     */
    public function generate(Request $request)
    {
        $reportType = $request->input('report_type', 'patron_list');
        $format = $request->input('format', 'excel');

        // Check query quickly
        $query = $this->buildQuery($request);
        if (!$query->exists()) {
            return response()->json([
                'success' => false,
                'message' => __('Không tìm thấy độc giả phù hợp với bộ lọc đã chọn.')
            ], 422);
        }

        $meta = [
            'patron_list' => [
                'title' => __('Danh sách độc giả trong thư viện'),
                'prefix' => 'danh_sach_doc_gia'
            ],
            'print_cards' => [
                'title' => __('Báo cáo in thẻ độc giả'),
                'prefix' => 'in_the_doc_gia'
            ],
            'renew_list' => [
                'title' => __('Báo cáo gia hạn thẻ độc giả'),
                'prefix' => 'gia_han_the'
            ],
            'renew_by_period' => [
                'title' => __('Báo cáo gia hạn thẻ độc giả'),
                'prefix' => 'gia_han_the'
            ],
        ];

        $reportMeta = $meta[$reportType] ?? [
            'title' => __('Danh sách độc giả trong thư viện'),
            'prefix' => 'danh_sach_doc_gia'
        ];

        $fileName = $reportMeta['prefix'] . '_' . now()->format('Ymd_His');
        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $fullFileName = $fileName . '.' . $extension;

        // Create export history
        $history = \App\Models\ExportHistory::create([
            'user_id'     => auth()->id(),
            'report_type' => $reportType,
            'title'       => $reportMeta['title'],
            'filename'    => $fullFileName,
            'format'      => $format,
            'status'      => 'pending',
        ]);

        // Dispatch job in the background to the default queue connection (database) so it runs asynchronously
        \App\Jobs\ExportPatronReportJob::dispatch(
            $history->id,
            $request->all(),
            $reportType,
            $format
        );

        return response()->json([
            'success' => true,
            'message' => __('Yêu cầu xuất file của bạn đã được nhận và đang được xử lý dưới nền. Bạn sẽ nhận được thông báo sau khi hoàn tất.'),
            'history' => $history
        ]);
    }
}
