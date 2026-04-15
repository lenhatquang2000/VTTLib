<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MediaCategory;
use Illuminate\Support\Str;

class MediaCategoryController extends Controller
{
    public function index()
    {
        $categories = MediaCategory::byLanguage(session('locale', app()->getLocale()))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.media-categories.index', compact('categories'));
    }

    public function create()
    {
        $types = [
            'slider' => 'Slider',
            'banner' => 'Banner',
            'logo' => 'Logo',
            'gallery' => 'Gallery',
            'other' => 'Other'
        ];
        
        return view('admin.media-categories.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:media_categories,code',
            'position' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'type' => 'required|in:slider,banner,logo,gallery,other',
            'is_active' => 'boolean',
            'language' => 'required|string|max:5',
            'settings' => 'nullable|json'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        MediaCategory::create($validated);

        return redirect()->route('admin.media-categories.index')
            ->with('success', __('Media category created successfully'));
    }

    public function edit(MediaCategory $mediaCategory)
    {
        $types = [
            'slider' => 'Slider',
            'banner' => 'Banner',
            'logo' => 'Logo',
            'gallery' => 'Gallery',
            'other' => 'Other'
        ];

        return view('admin.media-categories.edit', compact('mediaCategory', 'types'));
    }

    public function update(Request $request, MediaCategory $mediaCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:media_categories,code,' . $mediaCategory->id,
            'position' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'type' => 'required|in:slider,banner,logo,gallery,other',
            'is_active' => 'boolean',
            'language' => 'required|string|max:5',
            'settings' => 'nullable|json'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $mediaCategory->update($validated);

        return redirect()->route('admin.media-categories.index')
            ->with('success', __('Media category updated successfully'));
    }

    public function destroy(MediaCategory $mediaCategory)
    {
        $mediaCategory->delete();

        return redirect()->route('admin.media-categories.index')
            ->with('success', __('Media category deleted successfully'));
    }
}
