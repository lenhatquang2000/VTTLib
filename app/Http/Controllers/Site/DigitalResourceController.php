<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\DigitalResource;
use App\Models\SiteNode;
use Illuminate\Http\Request;

class DigitalResourceController extends Controller
{
    public function show($id)
    {
        $resource = DigitalResource::with('folder')->findOrFail($id);
        
        // Tăng lượt xem
        $resource->increment('views');
        
        // Get common data for site layout
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');
        
        // Get the parent node (Digital Library) for breadcrumb and sidebar
        $node = SiteNode::where('node_code', 'tai-lieu-so')->first();

        return view('site.pages.digital-resource-detail', compact('resource', 'menuItems', 'footerItems', 'node'));
    }

    public function viewPdf($id)
    {
        $resource = DigitalResource::findOrFail($id);
        
        // Get common data for site layout
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');
        
        // Get the parent node (Digital Library) for breadcrumb and sidebar
        $node = SiteNode::where('node_code', 'tai-lieu-so')->first();

        return view('site.pages.digital-resource-view', compact('resource', 'menuItems', 'footerItems', 'node'));
    }
}
