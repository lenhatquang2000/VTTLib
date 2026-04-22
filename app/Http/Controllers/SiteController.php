<?php

namespace App\Http\Controllers;

use App\Models\SiteNode;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display the homepage
     */
    public function home()
    {
        // Lấy tài liệu mới nhất
        $newResources = \App\Models\DigitalResource::with('folder')
            ->where('status', 'published')
            ->latest()
            ->take(8)
            ->get();

        // Lấy tài liệu Y khoa (Giả định folder_id hoặc lọc theo folder name)
        $medicalResources = \App\Models\DigitalResource::whereHas('folder', function($q) {
                $q->where('folder_name', 'LIKE', '%Y khoa%');
            })
            ->where('status', 'published')
            ->take(4)
            ->get();

        $homeNode = SiteNode::getByCode('home');
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');

        if (view()->exists('site.pages.home')) {
            return view('site.pages.home', [
                'homeNode' => $homeNode,
                'menuItems' => $menuItems,
                'footerItems' => $footerItems,
                'breadcrumb' => [],
                'newResources' => $newResources,
                'medicalResources' => $medicalResources,
            ]);
        }
        
        return view('site.home', compact('homeNode', 'menuItems', 'footerItems'));
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

        // Nếu là trang chủ HOẶC node đang dùng template home, nạp thêm dữ liệu động cho tài liệu số
        $extraData = [];
        if ($code === 'home' || $siteNode->masterpage === 'home' || request()->query('preview_template') === 'home') {
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
