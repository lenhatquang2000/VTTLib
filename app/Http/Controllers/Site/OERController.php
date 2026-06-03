<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\OpenEducationalResource;
use App\Models\OERContribution;
use App\Models\SiteNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OERController extends Controller
{
    public function landing()
    {
        return redirect()->route('site.page', ['code' => 'tai-nguyen-giao-duc-mo', 'view' => 'landing']);
    }

    public function intro()
    {
        return redirect()->route('site.page', ['code' => 'tai-nguyen-giao-duc-mo', 'view' => 'intro']);
    }

    public function contribute()
    {
        return redirect()->route('site.page', ['code' => 'tai-nguyen-giao-duc-mo', 'view' => 'contribute']);
    }

    /**
     * Store OER contribution
     */
    public function storeContribution(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'license' => 'required|string',
            'additional_info' => 'nullable|string',
            'oer_file' => 'required|file|max:51200', // max 50MB
        ]);

        if ($request->hasFile('oer_file')) {
            $file = $request->file('oer_file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('oer_contributions', 'public');

            OERContribution::create([
                'full_name' => $request->full_name,
                'contact_info' => $request->contact_info,
                'license' => $request->license,
                'additional_info' => $request->additional_info,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'status' => 'pending'
            ]);

            return back()->with('success', __('Cảm ơn bạn đã đóng góp tài liệu. Chúng tôi sẽ xem xét và phản hồi sớm nhất!'));
        }

        return back()->with('error', __('Có lỗi xảy ra, vui lòng thử lại sau.'));
    }

    public function show($id)
    {
        $resource = OpenEducationalResource::findOrFail($id);
        
        // Tăng lượt xem
        $resource->increment('view_count');
        
        // Get common data for site layout
        $menuItems = SiteNode::getMenuItems('menu');
        $footerItems = SiteNode::getMenuItems('footer');
        
        // Get the parent node (OER) for breadcrumb and sidebar
        $node = SiteNode::where('node_code', 'tai-nguyen-giao-duc-mo')->first();

        return view('site.pages.oer-detail', compact('resource', 'menuItems', 'footerItems', 'node'));
    }

    /**
     * Download OER resource
     */
    public function download($id)
    {
        $resource = OpenEducationalResource::findOrFail($id);
        
        // Tăng lượt tải
        $resource->increment('download_count');
        
        $filePath = storage_path('app/public/' . $resource->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $resource->file_name);
    }
}
