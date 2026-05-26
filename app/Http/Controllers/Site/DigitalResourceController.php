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
        
        // Tăng lượt xem (sử dụng view_count để đồng bộ với Admin)
        $resource->increment('view_count');
        
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

    /**
     * Download digital resource
     */
    public function download($id)
    {
        $resource = DigitalResource::findOrFail($id);
        
        // Tăng lượt tải
        $resource->increment('download_count');
        
        $filePath = storage_path('app/public/' . $resource->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $resource->file_name);
    }

    /**
     * Stream PDF file for viewer
     */
    public function streamPdf($id)
    {
        $resource = DigitalResource::findOrFail($id);
        
        // Đường dẫn file PDF trong storage
        $filePath = storage_path('app/public/' . $resource->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $resource->file_name . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }
}
