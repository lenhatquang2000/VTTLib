<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MediaItem;
use App\Models\MediaCategory;

class MediaItemController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->get('category_id');
        $category = MediaCategory::findOrFail($categoryId);
        $items = $category->items()->paginate(10);

        return view('admin.media-items.index', compact('category', 'items'));
    }

    public function create(Request $request)
    {
        $categoryId = $request->get('category_id');
        $category = MediaCategory::findOrFail($categoryId);
        
        return view('admin.media-items.create', compact('category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:media_categories,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'required|string|max:500',
            'link_url' => 'nullable|string|max:500',
            'link_target' => 'required|in:_self,_blank',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'metadata' => 'nullable|json'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        MediaItem::create($validated);

        return redirect()->route('admin.media-items.index', ['category_id' => $request->category_id])
            ->with('success', __('Media item created successfully'));
    }

    public function edit(MediaItem $mediaItem)
    {
        $category = $mediaItem->category;
        return view('admin.media-items.edit', compact('mediaItem', 'category'));
    }

    public function update(Request $request, MediaItem $mediaItem)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'required|string|max:500',
            'link_url' => 'nullable|string|max:500',
            'link_target' => 'required|in:_self,_blank',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'metadata' => 'nullable|json'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $mediaItem->update($validated);

        return redirect()->route('admin.media-items.index', ['category_id' => $mediaItem->category_id])
            ->with('success', __('Media item updated successfully'));
    }

    public function destroy(MediaItem $mediaItem)
    {
        $categoryId = $mediaItem->category_id;
        $mediaItem->delete();

        return redirect()->route('admin.media-items.index', ['category_id' => $categoryId])
            ->with('success', __('Media item deleted successfully'));
    }
}
