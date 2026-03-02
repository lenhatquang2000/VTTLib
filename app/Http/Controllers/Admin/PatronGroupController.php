<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatronGroup;
use Illuminate\Http\Request;

class PatronGroupController extends Controller
{
    public function index()
    {
        $groups = PatronGroup::orderBy('order')->get();
        return view('admin.patrons.groups', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:patron_groups',
            'description' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        PatronGroup::create($validated);

        return back()->with('success', __('Patron category created successfully.'));
    }

    public function update(Request $request, PatronGroup $patronGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:patron_groups,code,' . $patronGroup->id,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $patronGroup->update($validated);

        return back()->with('success', __('Patron category updated successfully.'));
    }

    public function destroy(PatronGroup $patronGroup)
    {
        if ($patronGroup->patrons()->exists()) {
            return back()->with('error', __('Cannot delete category that has assigned patrons.'));
        }

        $patronGroup->delete();

        return back()->with('success', __('Patron category deleted successfully.'));
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:patron_groups,id'
        ]);

        foreach ($request->ids as $index => $id) {
            PatronGroup::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
