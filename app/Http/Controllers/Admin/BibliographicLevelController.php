<?php

namespace App\Http\Controllers\Admin;

use App\Models\BibliographicLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BibliographicLevelController extends Controller
{
    public function index()
    {
        $levels = BibliographicLevel::orderBy('order')->paginate(15);
        return view('admin.bibliographic_levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.bibliographic_levels.create');
    }

    public function store(Request $request)
    {
        \Log::info('BibliographicLevel store() - Request data:', $request->all());
        
        $validated = $request->validate([
            'code' => 'required|string|max:1|unique:bibliographic_levels',
            'name_en' => 'required|string|max:255',
            'name_vi' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        \Log::info('BibliographicLevel store() - Validated data:', $validated);

        BibliographicLevel::create($validated);

        return redirect()->route('admin.bibliographic-levels.index')->with('success', __('Bibliographic level created successfully'));
    }

    public function edit(BibliographicLevel $bibliographicLevel)
    {
        return view('admin.bibliographic_levels.edit', compact('bibliographicLevel'));
    }

    public function update(Request $request, BibliographicLevel $bibliographicLevel)
    {
        \Log::info('BibliographicLevel update() - Request data:', $request->all());
        \Log::info('BibliographicLevel update() - Updating ID: ' . $bibliographicLevel->id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:1|unique:bibliographic_levels,code,' . $bibliographicLevel->id,
            'name_en' => 'required|string|max:255',
            'name_vi' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        \Log::info('BibliographicLevel update() - Validated data:', $validated);

        $bibliographicLevel->update($validated);

        return redirect()->route('admin.bibliographic-levels.index')->with('success', __('Bibliographic level updated successfully'));
    }

    public function destroy(BibliographicLevel $bibliographicLevel)
    {
        $bibliographicLevel->delete();

        return redirect()->route('admin.bibliographic-levels.index')->with('success', __('Bibliographic level deleted successfully'));
    }

    public function updateOrder(Request $request)
    {
        $items = $request->input('items', []);

        foreach ($items as $index => $id) {
            BibliographicLevel::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
