<?php

namespace App\Http\Controllers;

use App\Models\SiteNode;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display the homepage
     */
    public function home(Request $request)
    {
        // Lấy tài liệu số mới nhất
        $newResources = \App\Models\DigitalResource::with('folder')
            ->where('status', 'published')
            ->latest()
            ->take(8)
            ->get();

        // Lấy tài liệu Y khoa
        $medicalResources = \App\Models\DigitalResource::whereHas('folder', function($q) {
                $q->where('folder_name', 'LIKE', '%Y khoa%');
            })
            ->where('status', 'published')
            ->take(4)
            ->get();

        // Xử lý lọc theo loại cho Section 1
        $type = $request->query('type', 'book');
        $query = \App\Models\BibliographicRecord::with(['fields.subfields', 'items']);
        
        if ($type === 'journal') {
            $query->where('record_type', 'journal');
        } elseif ($type === 'folder') {
            $query->where('record_type', 'folder');
        } else {
            $query->where('record_type', 'book');
        }

        $newBooks = $query->latest()->take(8)->get();

        // Lấy tin tức thực tế cho trang chủ
        $homeNews = \App\Models\News::published()->latest()->take(5)->get();

        // Xử lý lọc cho Section 3 (Tin mới | Video)
        $newsType = $request->query('news_type', 'news');
        $tabNews = [];
        if ($newsType === 'news') {
            $tabNews = $homeNews;
        }

        // Xử lý lọc cho Sidebar (Tài liệu số Mới | Nổi bật)
        $resourceType = $request->query('resource_type', 'new');
        if ($resourceType === 'featured') {
            $newResources = \App\Models\DigitalResource::with('folder')
                ->where('status', 'published')
                // Tạm thời lấy các tài liệu mới nhất thay cho 'nổi bật' vì chưa có cột is_featured
                ->latest()
                ->take(5)
                ->get();
        }

        $homeNode = SiteNode::getByCode('home');
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');

        // Handle AJAX requests for tabs
        if ($request->ajax()) {
            if ($request->has('type')) {
                return view('site.pages.partials.home-books', [
                    'newBooks' => $newBooks,
                    'activeType' => $type
                ])->render();
            }
            if ($request->has('resource_type')) {
                $resourceType = $request->query('resource_type', 'new');
                if ($resourceType === 'new') {
                    $sidebarBooks = \App\Models\BibliographicRecord::with(['fields.subfields'])
                        ->latest()
                        ->take(10)
                        ->get();
                    
                    return view('site.pages.partials.sidebar-books', [
                        'sidebarBooks' => $sidebarBooks,
                        'activeResourceType' => 'new'
                    ])->render();
                }

                return view('site.pages.partials.home-resources', [
                    'newResources' => $newResources,
                    'activeResourceType' => $resourceType
                ])->render();
            }
            if ($request->has('news_type')) {
                $newsType = $request->query('news_type', 'news');
                $tabNews = [];
                if ($newsType === 'news') {
                    $tabNews = \App\Models\News::published()->latest()->take(5)->get();
                }
                return view('site.pages.partials.home-news', [
                    'newsType' => $newsType,
                    'tabNews' => $tabNews
                ])->render();
            }
            if ($request->has('medical_type')) {
                $medicalType = $request->query('medical_type');
                $medicalResources = \App\Models\DigitalResource::whereHas('folder', function($q) use ($medicalType) {
                        $q->where('folder_name', 'LIKE', "%{$medicalType}%");
                    })
                    ->where('status', 'published')
                    ->take(3)
                    ->get();
                return view('site.pages.partials.home-medical', [
                    'medicalResources' => $medicalResources
                ])->render();
            }
        }

        // Lấy 10 cuốn sách mới nhất cho Sidebar Home
        $sidebarBooks = \App\Models\BibliographicRecord::with(['fields.subfields'])
            ->latest()
            ->take(10)
            ->get();

        if (view()->exists('site.pages.home')) {
            return view('site.pages.home', [
                'homeNode' => $homeNode,
                'menuItems' => $menuItems,
                'footerItems' => $footerItems,
                'breadcrumb' => [],
                'newResources' => $newResources,
                'medicalResources' => $medicalResources,
                'newBooks' => $newBooks,
                'sidebarBooks' => $sidebarBooks,
                'homeNews' => $homeNews,
                'tabNews' => $tabNews,
                'activeType' => $type,
                'activeNewsType' => $newsType,
                'activeResourceType' => $resourceType
            ]);
        }
        
        return view('site.home', compact('homeNode', 'menuItems', 'footerItems', 'newBooks'));
    }

    /**
     * Display OPAC search page
     */
    public function opac(Request $request)
    {
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');
        
        $query = \App\Models\BibliographicRecord::with(['fields.subfields', 'items']);

        // Xử lý tìm kiếm nếu có từ khóa
        if ($request->has('q') && !empty($request->q)) {
            $keyword = $request->q;
            $query->whereHas('fields', function($q) use ($keyword) {
                $q->where(function($subQ) use ($keyword) {
                    $subQ->whereIn('tag', ['245', '100', '700'])
                         ->whereHas('subfields', function($ssQ) use ($keyword) {
                             $ssQ->where('value', 'LIKE', "%{$keyword}%");
                         });
                });
            });
        }
        
        $books = $query->latest()->paginate(12)->withQueryString();

        // --- LẤY DỮ LIỆU THỐNG KÊ CHO SIDEBAR ---
        
        // 1. Sách theo kho (Storage Locations)
        $sidebarData = [];
        $sidebarData['locations'] = \App\Models\StorageLocation::withCount(['bookItems' => function($q) {
            $q->where('status', 'available');
        }])->where('is_active', true)->get();

        // 2. Phân loại DDC (Lấy từ trường MARC 082$a)
        // Group by đầu số (ví dụ: 300, 600...)
        $ddcData = \DB::table('marc_subfields')
            ->join('marc_fields', 'marc_subfields.marc_field_id', '=', 'marc_fields.id')
            ->where('marc_fields.tag', '082')
            ->where('marc_subfields.code', 'a')
            ->selectRaw('LEFT(value, 1) as ddc_prefix, count(*) as count')
            ->groupBy('ddc_prefix')
            ->get();
        
        $ddcNames = [
            '0' => 'Tác phẩm tổng quát', '1' => 'Tâm lý triết lý', '2' => 'Tôn giáo',
            '3' => 'Khoa học xã hội', '4' => 'Ngôn ngữ', '5' => 'Khoa học tự nhiên',
            '6' => 'Công nghệ', '7' => 'Nghệ thuật - Thể thao', '8' => 'Văn học', '9' => 'Lịch sử - địa lý'
        ];

        $sidebarData['ddc'] = $ddcData->map(function($item) use ($ddcNames) {
            $prefix = $item->ddc_prefix . '00';
            return [
                'code' => $prefix,
                'name' => $ddcNames[$item->ddc_prefix] ?? 'Khác',
                'count' => $item->count
            ];
        })->sortBy('code');

        // 3. Mượn nhiều nhất (Tạm thời lấy theo số lượng bản thu nhập có sẵn)
        $sidebarData['mostBorrowed'] = \App\Models\BibliographicRecord::with(['fields.subfields'])
            ->withCount('items')
            ->orderBy('items_count', 'desc')
            ->take(3)
            ->get();

        // 4. Từ khóa hot (Lấy từ trường MARC 650$a)
        $sidebarData['hotKeywords'] = \DB::table('marc_subfields')
            ->join('marc_fields', 'marc_subfields.marc_field_id', '=', 'marc_fields.id')
            ->where('marc_fields.tag', '650')
            ->where('marc_subfields.code', 'a')
            ->select('value', \DB::raw('count(*) as count'))
            ->groupBy('value')
            ->orderBy('count', 'desc')
            ->take(10)
            ->pluck('value');

        // Tổng số bản ghi thực tế
        $totalRecords = \App\Models\BibliographicRecord::count();

        return view('site.pages.opac', array_merge(
            compact('menuItems', 'footerItems', 'books', 'totalRecords'),
            ['sidebar' => $sidebarData]
        ));
    }

    /**
     * Display OPAC book detail page
     */
    public function bookDetail(\App\Models\BibliographicRecord $record)
    {
        $record->load(['fields.subfields', 'items.storageLocation', 'items.branch']);
        
        $menuItems = \App\Models\SiteNode::getMenuItems('menu');
        $footerItems = \App\Models\SiteNode::getMenuItems('footer');
        
        // Lấy thông tin cơ bản từ MARC fields
        $marcData = [];
        foreach ($record->fields as $field) {
            $subfields = [];
            foreach ($field->subfields as $sub) {
                $subfields[$sub->code] = $sub->value;
            }
            $marcData[$field->tag] = $subfields;
        }

        // Tóm tắt (Summary - Trường 520)
        $summary = $marcData['520']['a'] ?? 'Không có tóm tắt cho tài liệu này.';
        
        // Nhan đề (245)
        $title = $marcData['245']['a'] ?? 'Không có nhan đề';
        $remTitle = $marcData['245']['b'] ?? '';
        $fullTitle = $title . ($remTitle ? ' : ' . $remTitle : '');
        
        // Tác giả (100 hoặc 700)
        $author = $marcData['100']['a'] ?? ($marcData['700']['a'] ?? 'Đang cập nhật tác giả');
        
        // Thông tin xuất bản (260)
        $publisher = $marcData['260']['b'] ?? 'Đang cập nhật';
        $pubPlace = $marcData['260']['a'] ?? '';
        $pubYear = $marcData['260']['c'] ?? '';

        return view('site.pages.book-detail', compact(
            'record', 'menuItems', 'footerItems', 'summary', 'fullTitle', 'author', 'publisher', 'pubPlace', 'pubYear', 'marcData'
        ));
    }

    /**
     * Reserve a book (Register for loan)
     */
    public function reserveBook(Request $request, \App\Models\BibliographicRecord $record)
    {
        $user = auth()->user();
        $patron = $user->patronDetail;

        if (!$patron) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => __('Bạn cần cập nhật thông tin độc giả trước khi mượn sách.')]);
            }
            return back()->with('error', __('Bạn cần cập nhật thông tin độc giả trước khi mượn sách.'));
        }

        // Kiểm tra xem đã đăng ký cuốn này chưa
        $existing = \App\Models\Reservation::where('patron_detail_id', $patron->id)
            ->where('bibliographic_record_id', $record->id)
            ->whereIn('status', ['pending', 'ready'])
            ->first();

        if ($existing) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => __('Bạn đã có một yêu cầu đang chờ cho tài liệu này.')]);
            }
            return back()->with('error', __('Bạn đã có một yêu cầu đang chờ cho tài liệu này.'));
        }

        // Tạo yêu cầu mới
        \App\Models\Reservation::create([
            'patron_detail_id' => $patron->id,
            'bibliographic_record_id' => $record->id,
            'reservation_date' => now(),
            'status' => 'pending',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('Đăng ký mượn sách thành công! Yêu cầu của bạn đang chờ quản trị viên phê duyệt.')]);
        }

        return redirect()->route('profile')->with('success', __('Đăng ký mượn sách thành công!'));
    }

    /**
     * Display user profile with loans and reservations
     */
    public function profile()
    {
        $user = auth()->user();
        $menuItems = \App\Models\SiteNode::getMenuItems('menu');
        $footerItems = \App\Models\SiteNode::getMenuItems('footer');
        
        $patron = $user->patronDetail;
        
        if (!$patron) {
            return view('site.pages.profile', compact('user', 'menuItems', 'footerItems'))->with('patron', null);
        }

        // Lấy danh sách đăng ký (đang chờ, sẵn sàng, hủy, từ chối)
        $reservations = $patron->reservations()
            ->with(['bibliographicRecord.fields.subfields', 'pickupBranch'])
            ->latest()
            ->get();

        // Lấy các giao dịch mượn (đang mượn, đã trả)
        $loanTransactions = $patron->loanTransactions()
            ->with(['bookItem.bibliographicRecord.fields.subfields', 'loanBranch', 'returnBranch'])
            ->latest()
            ->get();

        $activeLoans = $loanTransactions->where('status', 'borrowed');
        $returnedLoans = $loanTransactions->where('status', 'returned');
        
        // Thống kê
        $stats = [
            'total_borrowed' => $loanTransactions->count(),
            'active_loans' => $activeLoans->count(),
            'overdue_loans' => $loanTransactions->filter(fn($l) => $l->isOverdue())->count(),
            'total_fines' => $patron->total_outstanding_fine,
        ];

        return view('site.pages.profile', compact(
            'user', 
            'patron', 
            'reservations', 
            'activeLoans', 
            'returnedLoans',
            'loanTransactions',
            'stats',
            'menuItems', 
            'footerItems'
        ));
    }

    /**
     * Display a static page by node code
     */
    public function page($code)
    {
        $siteNode = SiteNode::getByCode($code);
        
        if (!$siteNode) {
            abort(404, 'Trang không tồn tại');
        }
        
        // Check access permissions
        if (!$siteNode->canAccess(auth()->user())) {
            if (auth()->guest()) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem trang này');
            }
            abort(403, 'Bạn không có quyền truy cập trang này');
        }
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');
        
        // Tạo breadcrumb
        $breadcrumb = [];
        $tempNode = $siteNode;
        while ($tempNode) {
            array_unshift($breadcrumb, [
                'name' => $tempNode->display_name,
                'url' => route('site.page', $tempNode->node_code)
            ]);
            $tempNode = $tempNode->parent;
        }

        // Nếu là trang chủ HOẶC dùng template opac/home, nạp thêm dữ liệu động
        $extraData = [];
        if ($code === 'home' || $siteNode->masterpage === 'home' || request()->query('preview_template') === 'home') {
            $extraData['homeNews'] = \App\Models\News::published()->latest()->take(5)->get();
            $extraData['tabNews'] = $extraData['homeNews'];
            $extraData['newResources'] = \App\Models\DigitalResource::with('folder')
                ->where('status', 'published')
                ->latest()
                ->take(8)
                ->get();

            $extraData['medicalResources'] = \App\Models\DigitalResource::whereHas('folder', function($q) {
                    $q->where('folder_name', 'LIKE', '%Y khoa%');
                })
                ->where('status', 'published')
                ->take(4)
                ->get();

            // Đảm bảo luôn có newBooks cho template home
            $extraData['newBooks'] = \App\Models\BibliographicRecord::with(['fields.subfields', 'items'])
                ->latest()
                ->take(8)
                ->get();

            // Lấy 10 cuốn sách mới nhất cho Sidebar Home
            $extraData['sidebarBooks'] = \App\Models\BibliographicRecord::with(['fields.subfields'])
                ->latest()
                ->take(10)
                ->get();
        }

        // Nạp dữ liệu sách nếu dùng template opac
        if ($siteNode->masterpage === 'opac' || request()->query('preview_template') === 'opac') {
            $extraData['newBooks'] = \App\Models\BibliographicRecord::with(['fields.subfields', 'items'])
                ->latest()
                ->take(8)
                ->get();
                
            $extraData['books'] = \App\Models\BibliographicRecord::with(['fields.subfields', 'items'])
                ->latest()
                ->paginate(12);
        }

        // Nạp dữ liệu tin tức nếu dùng template news
        if ($siteNode->masterpage === 'news' || request()->query('preview_template') === 'news' || $code === 'tin-tuc') {
            $extraData['news'] = \App\Models\News::where('status', 'published')
                ->with(['category', 'author', 'tags'])
                ->orderBy('sort_order', 'asc')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
            
            // Lấy tổng số bạn đọc (User) cho template news
            $extraData['totalUsers'] = \App\Models\User::count();
            
            // Lấy 3 avatar người dùng mới nhất (từ PatronDetail nếu có profile_image)
            $extraData['latestPatrons'] = \App\Models\PatronDetail::whereNotNull('profile_image')
                ->latest()
                ->take(3)
                ->get();
        }

        // 1. Kiểm tra template preview qua query param
        $previewTemplate = request()->query('preview_template');
        if ($previewTemplate && view()->exists("site.pages.{$previewTemplate}")) {
            return view("site.pages.{$previewTemplate}", array_merge([
                'node' => $siteNode,
                'siteNode' => $siteNode,
                'menuItems' => $menuItems,
                'footerItems' => $footerItems,
                'breadcrumb' => $breadcrumb
            ], $extraData));
        }

        // 2. Ưu tiên render theo masterpage nếu có (Đây là phần quan trọng nhất)
        if ($siteNode->masterpage && view()->exists("site.pages.{$siteNode->masterpage}")) {
            return view("site.pages.{$siteNode->masterpage}", array_merge([
                'node' => $siteNode,
                'siteNode' => $siteNode,
                'menuItems' => $menuItems,
                'footerItems' => $footerItems,
                'breadcrumb' => $breadcrumb
            ], $extraData));
        }

        // 3. Fallback theo node_code chỉ khi không có masterpage
        if (!$siteNode->masterpage && view()->exists("site.pages.{$code}")) {
            return view("site.pages.{$code}", array_merge([
                'node' => $siteNode,
                'siteNode' => $siteNode,
                'menuItems' => $menuItems,
                'footerItems' => $footerItems,
                'breadcrumb' => $breadcrumb
            ], $extraData));
        }

        // 4. Mặc định render template chung
        return view('site.page', array_merge([
            'node' => $siteNode,
            'siteNode' => $siteNode,
            'menuItems' => $menuItems,
            'footerItems' => $footerItems,
            'breadcrumb' => $breadcrumb
        ], $extraData));
    }

    /**
     * Display sitemap
     */
    public function sitemap()
    {
        $tree = SiteNode::getTree();
        
        return view('site.sitemap', compact('tree'));
    }

    /**
     * Generate XML sitemap
     */
    public function xmlSitemap()
    {
        $nodes = SiteNode::active()->get();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($nodes as $node) {
            if ($node->canAccess() && ($node->hasContent() || $node->route_name || $node->url)) {
                $xml .= '<url>';
                $xml .= '<loc>' . url($node->getUrl()) . '</loc>';
                $xml .= '<lastmod>' . $node->updated_at->format('Y-m-d') . '</lastmod>';
                $xml .= '<changefreq>weekly</changefreq>';
                $xml .= '<priority>0.8</priority>';
                $xml .= '</url>';
            }
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }
}
