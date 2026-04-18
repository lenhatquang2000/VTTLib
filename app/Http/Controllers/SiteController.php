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
        $homeNode = SiteNode::getByCode('home');
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');
        
        $nodeView = 'site.pages.home';
        if (view()->exists($nodeView)) {
            return view($nodeView, [
                'node' => $homeNode,
                'homeNode' => $homeNode,
                'menuItems' => $menuItems,
                'footerItems' => $footerItems,
                'breadcrumb' => []
            ]);
        }
        
        return view('site.home', compact('homeNode', 'menuItems', 'footerItems'));
    }

    /**
     * Display a static page by node code
     */
    public function page($code)
    {
        $node = SiteNode::getByCode($code);
        
        if (!$node) {
            abort(404, 'Trang không tồn tại');
        }
        
        // Check access permissions
        if (!$node->canAccess(auth()->user())) {
            if (auth()->guest()) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem trang này');
            }
            abort(403, 'Bạn không có quyền truy cập trang này');
        }
        
        // If node has route_name, redirect to that route
        if ($node->route_name) {
            return redirect()->route($node->route_name);
        }
        
        // If node has external URL, redirect
        if ($node->url && $node->isExternal()) {
            return redirect($node->url);
        }
        
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');
        $breadcrumb = $node->getBreadcrumb();
        $previewTemplate = request()->query('preview_template');

        // Priority 1: Check preview template (from query string)
        if ($previewTemplate) {
            $previewView = 'site.pages.' . $previewTemplate;
            if (view()->exists($previewView)) {
                return view($previewView, compact('node', 'menuItems', 'footerItems', 'breadcrumb'));
            }
        }

        // Priority 2: Check masterpage (template selection)
        if ($node->masterpage) {
            $templateView = 'site.pages.' . $node->masterpage;
            if (view()->exists($templateView)) {
                return view($templateView, compact('node', 'menuItems', 'footerItems', 'breadcrumb'));
            }
        }

        // Priority 2: Check node_code (auto template)
        $nodeView = 'site.pages.' . $node->node_code;
        if (view()->exists($nodeView)) {
            return view($nodeView, compact('node', 'menuItems', 'footerItems', 'breadcrumb'));
        }
        
        // Priority 3: Fallback default
        return view('site.page', compact('node', 'menuItems', 'footerItems', 'breadcrumb'));
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
