<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarcFramework;
use App\Models\MarcTagDefinition;
use App\Models\MarcSubfieldDefinition;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarcDefinitionController extends Controller
{
    public function index(Request $request)
    {
        $frameworks = MarcFramework::all();
        $frameworkId = $request->query('framework_id');
        $search = $request->query('search');

        // If no framework selected and frameworks exist, pick the first one
        if (!$frameworkId && $frameworks->isNotEmpty()) {
            $frameworkId = $frameworks->first()->id;
        }

        $currentFramework = $frameworkId ? MarcFramework::with(['tags' => function($q) use ($search) {
            $q->with('subfields');
            if ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('tag', 'like', "%{$search}%")
                        ->orWhere('label', 'like', "%{$search}%");
                });
            }
        }])->find($frameworkId) : null;

        $tags = $currentFramework ? $currentFramework->tags : [];

        return view('admin.marc_definitions.index', compact('frameworks', 'currentFramework', 'tags', 'search', 'frameworkId'));
    }

    public function storeFramework(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:marc_frameworks,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        MarcFramework::create($validated);

        return back()->with('success', 'MARC Framework created successfully.');
    }

    public function updateFramework(Request $request, MarcFramework $framework)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $framework->update($validated);

        return back()->with('success', 'Framework updated successfully.');
    }

    public function destroyFramework(MarcFramework $framework)
    {
        $framework->delete();
        return redirect()->route('admin.marc.index')->with('success', 'Framework deleted.');
    }

    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'framework_id' => 'required|exists:marc_frameworks,id',
            'tag' => 'required|string|size:3',
            'label' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'description' => 'nullable|string'
        ]);

        // 1. Create the global tag definition (or we could search for existing one)
        // For now, as per user's "create new if different", we create it.
        $tag = MarcTagDefinition::create([
            'tag' => $validated['tag'],
            'label' => $validated['label'],
            'description' => $validated['description'] ?? null
        ]);

        // 2. Attach it to the framework
        $framework = MarcFramework::findOrFail($validated['framework_id']);
        $framework->tags()->attach($tag->id, [
            'is_visible' => $request->boolean('is_visible', true),
            'order' => $framework->tags()->count() + 1
        ]);

        return back()->with('success', 'MARC Tag added to framework successfully.');
    }

    public function storeSubfield(Request $request)
    {
        $validated = $request->validate([
            'tag_id' => 'required|exists:marc_tag_definitions,id',
            'code' => [
                'required',
                'string',
                'size:1',
                Rule::unique('marc_subfield_definitions')->where(function ($query) use ($request) {
                    return $query->where('tag_id', $request->tag_id);
                }),
            ],
            'label' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'is_mandatory' => 'boolean',
            'is_repeatable' => 'boolean',
            'help_text' => 'nullable|string'
        ], [
            'code.unique' => __('This subfield code already exists for this tag.')
        ]);

        MarcSubfieldDefinition::create($validated);

        return back()->with('success', 'Subfield definition added successfully.');
    }

    public function updateTag(Request $request, MarcTagDefinition $tag)
    {
        $validated = $request->validate([
            'framework_id' => 'required|exists:marc_frameworks,id',
            'label' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'description' => 'nullable|string'
        ]);

        // Update global definition
        $tag->update([
            'label' => $validated['label'],
            'description' => $validated['description'] ?? null
        ]);

        // Update framework-specific visibility
        $tag->frameworks()->updateExistingPivot($validated['framework_id'], [
            'is_visible' => $request->boolean('is_visible', true)
        ]);

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

    public function destroyTag(Request $request, MarcTagDefinition $tag)
    {
        $frameworkId = $request->query('framework_id');
        if ($frameworkId) {
            // Just detach from this framework
            $tag->frameworks()->detach($frameworkId);
            return back()->with('success', 'Tag removed from framework.');
        }

        // Optional: delete global definition if no longer used
        $tag->delete();
        return back()->with('success', 'Tag definition deleted globally.');
    }

    public function destroySubfield(MarcSubfieldDefinition $subfield)
    {
        $subfield->delete();
        return back()->with('success', 'Subfield definition removed.');
    }
}
