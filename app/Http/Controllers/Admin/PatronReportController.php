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
        
        return view('admin.patrons.reports.index', compact(
            'patronGroups', 
            'branches', 
            'reportType',
            'activeReport',
            'reportsList'
        ));
    }

    /**
     * Generate and export the patron report.
     */
    public function generate(Request $request)
    {
        $reportType = $request->input('report_type', 'patron_list');
        $format = $request->input('format', 'excel');

        // Build query on PatronDetail
        $query = PatronDetail::with(['user', 'patronGroup', 'branch']);

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

        // Limit results
        $limit = intval($request->input('result_limit', 0));
        if ($limit > 0) {
            $query->limit($limit);
        }

        $patrons = $query->latest('registration_date')->get();

        if ($patrons->isEmpty()) {
            return back()->with('error', __('Không tìm thấy độc giả phù hợp với bộ lọc đã chọn.'));
        }

        // Prepare export rows based on report_type
        $title = '';
        $prefix = 'patrons';
        $headers = [];
        $rows = [];

        switch ($reportType) {
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
                $headers = [__('STT'), __('Mã bạn đọc'), __('Họ tên'), __('Email'), __('Số điện thoại'), __('Nhóm độc giả'), __('Ngày đăng ký'), __('Ngày hết hạn'), __('Trạng thái')];
                foreach ($patrons as $index => $patron) {
                    $rows[] = [
                        $index + 1,
                        $patron->patron_code,
                        $patron->display_name,
                        optional($patron->user)->email,
                        $patron->phone ?: ($patron->phone_contact ?: ''),
                        optional($patron->patronGroup)->name,
                        $patron->registration_date ? \Carbon\Carbon::parse($patron->registration_date)->format('d/m/Y') : '',
                        $patron->expiry_date ? \Carbon\Carbon::parse($patron->expiry_date)->format('d/m/Y') : '',
                        $patron->card_status === 'normal' ? __('Hoạt động') : __('Bị khóa')
                    ];
                }
                break;
        }

        $fileName = $prefix . '_' . now()->format('Ymd_His');

        if ($format === 'excel') {
            return Excel::download(
                new DynamicPatronReportExport($headers, $rows, $title), 
                $fileName . '.xlsx'
            );
        }

        // CSV format
        return Excel::download(
            new DynamicPatronReportExport($headers, $rows, $title), 
            $fileName . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
