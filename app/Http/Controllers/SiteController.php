<?php

namespace App\Http\Controllers;

use App\Models\SiteNode;
use App\Models\BibliographicRecord;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display the home page.
     */
    public function home(Request $request)
    {
        // 1. Lấy dữ liệu cơ bản cho Menu/Footer
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');

        // 2. Xử lý AJAX nạp tab sidebar (Mới | Nổi bật)
        if ($request->ajax() && $request->has('resource_type')) {
            $sidebarType = $request->query('resource_type', 'new');
            $sidebarQuery = \App\Models\BibliographicRecord::with(['fields.subfields'])
                ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED);

            if ($sidebarType === 'featured') {
                $sidebarQuery->where('is_featured', 1);
            } else {
                $sidebarQuery->where('record_type', 'book');
            }

            $sidebarBooks = $sidebarQuery->latest()->take(10)->get();

            return view('site.pages.partials.sidebar-books', compact('sidebarBooks'));
        }

        // 3. Lấy dữ liệu cho các Section trang chủ
        $newResources = \App\Models\DigitalResource::with('folder')
            ->where('status', 'published')
            ->latest()
            ->take(8)
            ->get();

        $medicalResources = \App\Models\DigitalResource::whereHas('folder', function($q) {
                $q->where('folder_name', 'LIKE', '%Y khoa%');
            })
            ->where('status', 'published')
            ->take(4)
            ->get();

        // Xử lý lọc cho Section 1 tabs
        $type = $request->query('type', 'book');
        $query = \App\Models\BibliographicRecord::with(['fields.subfields', 'items'])
            ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED);
        
        if ($type === 'journal') {
            $query->where('record_type', 'resource');
        } elseif ($type === 'folder') {
            $query->where('record_type', 'collection');
        } else {
            $query->where('record_type', 'book');
        }

        $newBooks = $query->where('status', 'approved')->latest()->take(20)->get();

        // Kiểm tra AJAX cho Section 1 tabs (Sách mới | Tạp chí | Thư mục)
        if ($request->ajax() && $request->has('type')) {
            return view('site.pages.partials.home-books', compact('newBooks'));
        }

        // Kiểm tra AJAX cho Section 3 tabs (Tin mới | Video)
        if ($request->ajax() && $request->has('news_type')) {
            $newsType = $request->query('news_type', 'news');
            if ($newsType === 'video') {
                $tabNews = \App\Models\News::published()
                    ->whereHas('category', function($q) {
                        $q->where('slug', 'video');
                    })
                    ->latest()
                    ->take(6)
                    ->get();
            } else {
                $tabNews = \App\Models\News::published()->latest()->take(6)->get();
            }
            return view('site.pages.partials.home-news', compact('tabNews', 'newsType'));
        }

        // Kiểm tra AJAX cho Section 5 tabs (Sản khoa | Nhi khoa | Nội khoa)
        if ($request->ajax() && $request->has('medical_type')) {
            $medicalType = $request->query('medical_type', 'Sản khoa');
            $medicalResources = \App\Models\DigitalResource::whereHas('folder', function($q) use ($medicalType) {
                    $q->where('folder_name', 'LIKE', '%' . $medicalType . '%');
                })
                ->where('status', 'published')
                ->take(4)
                ->get();
            return view('site.pages.partials.home-medical', compact('medicalResources'));
        }

        // 4. Lấy dữ liệu Tin tức & Thông báo
        $homeNews = \App\Models\News::published()
            ->whereHas('category', function($q) {
                $q->where('slug', 'tin-tuc');
            })
            ->featured()
            ->latest()
            ->take(5)
            ->get();

        $homeAnnouncements = \App\Models\News::published()
            ->whereHas('category', function($q) {
                $q->where('slug', 'thong-bao');
            })
            ->latest()
            ->take(5)
            ->get();

        // Dữ liệu cho tab Tin Mới (Section 3) - Lấy tất cả tin mới không nhất thiết phải nổi bật
        $tabNews = \App\Models\News::published()
            ->latest()
            ->take(6)
            ->get();

        // Dữ liệu cho section Giới Thiệu Sách Hàng Tháng (category_id == 7)
        $bookIntroductionNews = \App\Models\News::published()
            ->where('category_id', 7)
            ->latest()
            ->take(2)
            ->get();

        // Lấy sidebarBooks mặc định (tab Mới)
        $sidebarBooks = \App\Models\BibliographicRecord::with(['fields.subfields'])
            ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED)
            ->where('record_type', 'book')
            ->latest()
            ->take(10)
            ->get();

        // Lấy dữ liệu Network Logos cho slide
        $networkLogos = \App\Models\LibraryNetworkLogo::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        // Lấy Videos cho sidebar bên phải (tab VIDEO)
        $sidebarVideos = \App\Models\News::published()
            ->whereHas('category', function($q) {
                $q->where('slug', 'video');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('site.pages.home', compact(
            'menuItems', 'footerItems', 'newResources', 'medicalResources',
            'newBooks', 'homeNews', 'sidebarBooks', 'homeAnnouncements', 'tabNews', 'bookIntroductionNews', 'networkLogos', 'sidebarVideos'
        ));
    }

    /**
     * Display the OPAC search page.
     */
    public function opac(Request $request)
    {
        $query = $request->query('q');
        $type = $request->query('type', 'all');
        $locationId = $request->query('location');
        $ddcCode = $request->query('ddc');

        $booksQuery = \App\Models\BibliographicRecord::with(['fields.subfields', 'items'])
            ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED);

        if ($query) {
            $booksQuery->whereHas('fields.subfields', function ($q) use ($query, $type) {
                if ($type !== 'all') {
                    $tags = [
                        'title' => ['245'],
                        'author' => ['100', '700'],
                        'subject' => ['650', '651'],
                    ];
                    if (isset($tags[$type])) {
                        $q->whereIn('tag', $tags[$type]);
                    }
                }
                $q->where('value', 'LIKE', "%{$query}%");
            });
        }

        if ($locationId) {
            $booksQuery->whereHas('items', function($q) use ($locationId) {
                $q->where('location_id', $locationId);
            });
        }

        if ($ddcCode) {
            $booksQuery->whereHas('fields.subfields', function($q) use ($ddcCode) {
                $q->where('tag', '082')->where('code', 'a')->where('value', 'LIKE', $ddcCode . '%');
            });
        }

        $books = $booksQuery->latest()->paginate(12)->withQueryString();
        $totalRecords = \App\Models\BibliographicRecord::where('status', \App\Models\BibliographicRecord::STATUS_APPROVED)->count();

        // Prepare Sidebar Data
        $sidebar = [
            'locations' => \App\Models\StorageLocation::withCount(['bookItems' => function($q) {
                $q->whereHas('bibliographicRecord', function($rq) {
                    $rq->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED);
                });
            }])->get(),
            'ddc' => $this->getDdcStats(),
            'mostBorrowed' => \App\Models\BibliographicRecord::with(['fields.subfields'])
                ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED)
                ->take(5)
                ->get(), // Temporary, should be based on actual loans
            'hotKeywords' => \App\Models\MarcSubfield::whereHas('field', function($q) {
                    $q->whereIn('tag', ['650', '651']);
                })
                ->whereNotNull('value')
                ->groupBy('value')
                ->orderByRaw('COUNT(*) DESC')
                ->take(10)
                ->pluck('value')
                ->toArray()
        ];

        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');

        return view('site.pages.opac', compact('books', 'totalRecords', 'menuItems', 'footerItems', 'sidebar'));
    }

    /**
     * Get DDC Statistics for Sidebar
     */
    private function getDdcStats()
    {
        $ddcs = [
            ['code' => '000', 'name' => 'Tin học, Kiến thức chung'],
            ['code' => '100', 'name' => 'Triết học & Tâm lý học'],
            ['code' => '200', 'name' => 'Tôn giáo'],
            ['code' => '300', 'name' => 'Khoa học xã hội'],
            ['code' => '400', 'name' => 'Ngôn ngữ'],
            ['code' => '500', 'name' => 'Khoa học tự nhiên'],
            ['code' => '600', 'name' => 'Công nghệ (Khoa học ứng dụng)'],
            ['code' => '700', 'name' => 'Nghệ thuật & Giải trí'],
            ['code' => '800', 'name' => 'Văn học'],
            ['code' => '900', 'name' => 'Địa lý & Lịch sử'],
        ];

        foreach ($ddcs as &$ddc) {
            $ddc['count'] = \App\Models\BibliographicRecord::where('status', \App\Models\BibliographicRecord::STATUS_APPROVED)
                ->whereHas('fields.subfields', function($q) use ($ddc) {
                    $q->where('tag', '082')->where('code', 'a')->where('value', 'LIKE', substr($ddc['code'], 0, 1) . '%');
                })->count();
        }

        return array_filter($ddcs, fn($d) => $d['count'] > 0);
    }

    /**
     * Display the book detail page.
     */
    public function bookDetail(\App\Models\BibliographicRecord $record)
    {
        $record->load(['fields.subfields', 'items']);
        
        // Tăng lượt xem
        $record->increment('view_count');
        
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');

        return view('site.pages.book-detail', compact('record', 'menuItems', 'footerItems'));
    }

    /**
     * Reserve a book.
     */
    public function reserveBook(Request $request, \App\Models\BibliographicRecord $record)
    {
        // Implementation for book reservation
        return back()->with('success', 'Yêu cầu mượn sách đã được gửi.');
    }

    /**
     * Display the user profile page.
     */
    public function profile()
    {
        $user = auth()->user();
        $patron = \App\Models\PatronDetail::where('user_id', $user->id)->with('patronGroup')->first();
        
        $activeLoans = collect();
        $returnedLoans = collect();
        $reservations = collect();
        $stats = [
            'total_borrowed' => 0,
            'active_loans' => 0,
            'overdue_loans' => 0,
            'total_fines' => 0,
        ];

        if ($patron) {
            $activeLoans = \App\Models\LoanTransaction::where('patron_detail_id', $patron->id)
                ->where('status', 'borrowed')
                ->with(['bookItem.bibliographicRecord.fields.subfields'])
                ->latest('loan_date')
                ->get();

            $returnedLoans = \App\Models\LoanTransaction::where('patron_detail_id', $patron->id)
                ->where('status', 'returned')
                ->with(['bookItem.bibliographicRecord.fields.subfields'])
                ->latest('return_date')
                ->take(10)
                ->get();

            $reservations = \App\Models\Reservation::where('patron_detail_id', $patron->id)
                ->with(['bibliographicRecord.fields.subfields'])
                ->latest('reservation_date')
                ->take(10)
                ->get();

            $stats = [
                'total_borrowed' => \App\Models\LoanTransaction::where('patron_detail_id', $patron->id)->count(),
                'active_loans' => $activeLoans->count(),
                'overdue_loans' => $activeLoans->filter(fn($loan) => $loan->isOverdue())->count(),
                'total_fines' => $patron->total_outstanding_fine ?? 0,
            ];
        }

        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');

        return view('site.pages.profile', compact(
            'user', 'patron', 'menuItems', 'footerItems', 
            'stats', 'activeLoans', 'returnedLoans', 'reservations'
        ));
    }

    /**
     * Display the specified page.
     */
    public function page($code = 'home')
    {
        // 1. Tìm node duy nhất theo mã (không lọc theo cột language)
        $siteNode = SiteNode::where('node_code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$siteNode) {
            if ($code === 'home') {
                return redirect('/');
            }
            abort(404);
        }

        // Check access permissions
        if (!$siteNode->canAccess(auth()->user())) {
            if (auth()->guest()) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem trang này');
            }
            abort(403, 'Bạn không có quyền truy cập trang này');
        }

        // Nạp menu với eager loading children
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

        // Khởi tạo extraData để tránh lỗi undefined
        $extraData = [];

        // Xử lý nạp dữ liệu cho trang chủ hoặc Ajax nạp tab sidebar
        $isHome = ($code === 'home' || $siteNode->masterpage === 'home' || request()->query('preview_template') === 'home');
        $isAjaxSidebar = request()->ajax() && request()->has('resource_type');

        if ($isHome || $isAjaxSidebar) {
            // Nạp các dữ liệu chung cho Home
            $extraData['homeNews'] = \App\Models\News::published()
                ->whereHas('category', function($q) {
                    $q->where('slug', 'tin-tuc');
                })
                ->featured()
                ->latest()
                ->take(5)
                ->get();
                
            $extraData['homeAnnouncements'] = \App\Models\News::published()
                ->whereHas('category', function($q) {
                    $q->where('slug', 'thong-bao');
                })
                ->latest()
                ->take(5)
                ->get();

            $extraData['tabNews'] = \App\Models\News::published()
                ->latest()
                ->take(6)
                ->get();
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

            $extraData['newBooks'] = \App\Models\BibliographicRecord::with(['fields.subfields', 'items'])
                ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED)
                ->latest()
                ->take(8)
                ->get();

            // Logic Sidebar (Mới | Nổi bật)
            $sidebarType = request('resource_type', 'new');
            $sidebarQuery = \App\Models\BibliographicRecord::with(['fields.subfields'])
                ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED);

            if ($sidebarType === 'featured') {
                $sidebarQuery->where('is_featured', 1);
            } else {
                $sidebarQuery->where('record_type', 'book');
            }

            $extraData['sidebarBooks'] = $sidebarQuery->latest()
                ->take(10)
                ->get();
            $extraData['activeResourceType'] = $sidebarType;

            // Nếu là AJAX gọi sidebar, trả về ngay partial view
            if ($isAjaxSidebar) {
                return view('site.pages.partials.sidebar-books', [
                    'sidebarBooks' => $extraData['sidebarBooks']
                ]);
            }
        }

        // Nạp dữ liệu sách nếu dùng template opac
        if ($siteNode->masterpage === 'opac' || request()->query('preview_template') === 'opac') {
            $extraData['newBooks'] = \App\Models\BibliographicRecord::with(['fields.subfields', 'items'])
                ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED)
                ->latest()
                ->take(8)
                ->get();
                
            $extraData['books'] = \App\Models\BibliographicRecord::with(['fields.subfields', 'items'])
                ->where('status', \App\Models\BibliographicRecord::STATUS_APPROVED)
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

        // Nạp dữ liệu tài liệu số nếu truy cập trang tài liệu số
        if ($code === 'tai-lieu-so' || $siteNode->masterpage === 'digital-resources') {
            $sort = request()->query('sort', 'latest');
            $query = \App\Models\DigitalResource::with('folder')->where('status', 'published');
            
            switch ($sort) {
                case 'most_viewed':
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'most_downloaded':
                    $query->orderBy('download_count', 'desc');
                    break;
                default:
                    $query->latest();
            }
            
            $extraData['resources'] = $query->paginate(15);
            $extraData['totalCount'] = \App\Models\DigitalResource::where('status', 'published')->count();
            $extraData['currentSort'] = $sort;
        }

        // Nạp dữ liệu OER nếu truy cập trang tài nguyên giáo dục mở (chỉ load list, không phải landing page)
        if ($code === 'tai-nguyen-giao-duc-mo' && $siteNode->masterpage === 'oer') {
            $sort = request()->query('sort', 'latest');
            $subject = request()->query('subject');
            $keyword = request()->query('q');
            
            $query = \App\Models\OpenEducationalResource::where('status', 'published');
            
            if ($subject) {
                $query->where('subjects', 'like', '%' . $subject . '%');
            }
            
            if ($keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('title', 'like', '%' . $keyword . '%')
                      ->orWhere('authors', 'like', '%' . $keyword . '%')
                      ->orWhere('description', 'like', '%' . $keyword . '%');
                });
            }

            switch ($sort) {
                case 'most_viewed':
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'most_downloaded':
                    $query->orderBy('download_count', 'desc');
                    break;
                default:
                    $query->latest();
            }
            
            $extraData['resources'] = $query->paginate(15)->withQueryString();
            $extraData['totalCount'] = $query->count();
            $extraData['currentSort'] = $sort;
            $extraData['currentSubject'] = $subject;
            $extraData['keyword'] = $keyword;
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

        // 2. Ưu tiên render theo masterpage nếu có
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
