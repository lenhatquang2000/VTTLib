<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteNode;
use Illuminate\Http\Request;

class PageBuilderController extends Controller
{
    /**
     * Show the page builder for a specific site node.
     */
    public function edit(SiteNode $siteNode)
    {
        // Decode items_data if it exists
        $items = [];
        if ($siteNode->items_data) {
            $items = json_decode($siteNode->items_data, true) ?? [];
        }
        
        return view('admin.site-nodes.page-builder', compact('siteNode', 'items'));
    }
    
    /**
     * Update the page builder data for a specific site node.
     */
    public function update(Request $request, SiteNode $siteNode)
    {
        $validated = $request->validate([
            'items_data' => 'nullable|string',
        ]);
        
        // Update only the items_data field
        $siteNode->update([
            'items_data' => $validated['items_data'] ?? null,
        ]);
        
        activity_log("Updated page builder for site node: {$siteNode->node_code}");
        
        return redirect()
            ->route('admin.site-nodes.page-builder', $siteNode)
            ->with('success', 'Page builder updated successfully!');
    }
}
