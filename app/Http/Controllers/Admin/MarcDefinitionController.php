<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarcTagDefinition;
use App\Models\MarcSubfieldDefinition;
use Illuminate\Http\Request;

class MarcDefinitionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $tags = MarcTagDefinition::with('subfields')
            ->when($search, function ($query) use ($search) {
                $query->where('tag', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%");
            })
            ->orderBy('tag')
            ->get();
        return view('admin.marc_definitions.index', compact('tags', 'search'));
    }

    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'tag' => 'required|string|size:3|unique:marc_tag_definitions,tag',
            'label' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'description' => 'nullable|string'
        ]);

        MarcTagDefinition::create($validated);

        return back()->with('success', 'MARC Tag definition created successfully.');
    }

    public function storeSubfield(Request $request)
    {
        $validated = $request->validate([
            'tag' => 'required|string|exists:marc_tag_definitions,tag',
            'code' => 'required|string|size:1',
            'label' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'is_mandatory' => 'boolean',
            'is_repeatable' => 'boolean',
            'help_text' => 'nullable|string'
        ]);

        // Ensure unique tag+code
        if (MarcSubfieldDefinition::where('tag', $validated['tag'])->where('code', $validated['code'])->exists()) {
            return back()->with('error', 'This subfield code already exists for this tag.')->withInput();
        }

        MarcSubfieldDefinition::create($validated);

        return back()->with('success', 'Subfield definition added successfully.');
    }

    public function updateTag(Request $request, MarcTagDefinition $tag)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'description' => 'nullable|string'
        ]);

        $tag->update($validated);

        return back()->with('success', 'Tag definition updated successfully.');
    }

    public function updateSubfield(Request $request, MarcSubfieldDefinition $subfield)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'is_mandatory' => 'boolean',
            'is_repeatable' => 'boolean',
            'help_text' => 'nullable|string'
        ]);

        $subfield->update($validated);

        return back()->with('success', 'Subfield definition updated successfully.');
    }

    public function destroyTag(MarcTagDefinition $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag definition deleted.');
    }

    public function destroySubfield(MarcSubfieldDefinition $subfield)
    {
        $subfield->delete();
        return back()->with('success', 'Subfield definition removed.');
    }
}
