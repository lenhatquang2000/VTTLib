<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OpenEducationalResource;
use App\Models\OERContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OERController extends Controller
{
    public function index()
    {
        $resources = OpenEducationalResource::latest()->paginate(20);
        return view('admin.oer.index', compact('resources'));
    }

    public function contributions()
    {
        $contributions = OERContribution::latest()->paginate(20);
        return view('admin.oer.contributions', compact('contributions'));
    }

    public function create()
    {
        return view('admin.oer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'resource_type' => 'required|string',
            'language' => 'nullable|string',
            'authors' => 'nullable|array',
            'subjects' => 'nullable|array',
            'educational_levels' => 'nullable|array',
            'license' => 'nullable|string',
            'license_url' => 'nullable|url',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string',
            'publish_year' => 'nullable|integer',
            'format' => 'nullable|string',
            'identifier' => 'nullable|string',
            'source' => 'nullable|string',
            'external_link' => 'nullable|url',
            'keywords' => 'nullable|string',
            'file' => 'nullable|file|max:102400',
            'cover' => 'nullable|image|max:10240',
            'status' => 'required|in:draft,published',
        ]);

        $data = $validated;
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('oer/files', 'public');
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        // Handle cover upload
        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverPath = $cover->store('oer/covers', 'public');
            $data['cover_path'] = $coverPath;
        }

        OpenEducationalResource::create($data);

        return redirect()->route('admin.oer.index')->with('success', __('OER resource created successfully.'));
    }

    public function edit($id)
    {
        $resource = OpenEducationalResource::findOrFail($id);
        return view('admin.oer.edit', compact('resource'));
    }

    public function update(Request $request, $id)
    {
        $resource = OpenEducationalResource::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'resource_type' => 'required|string',
            'language' => 'nullable|string',
            'authors' => 'nullable|array',
            'subjects' => 'nullable|array',
            'educational_levels' => 'nullable|array',
            'license' => 'nullable|string',
            'license_url' => 'nullable|url',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string',
            'publish_year' => 'nullable|integer',
            'format' => 'nullable|string',
            'identifier' => 'nullable|string',
            'source' => 'nullable|string',
            'external_link' => 'nullable|url',
            'keywords' => 'nullable|string',
            'file' => 'nullable|file|max:102400',
            'cover' => 'nullable|image|max:10240',
            'status' => 'required|in:draft,published',
        ]);

        $data = $validated;
        $data['updated_by'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }
            $file = $request->file('file');
            $filePath = $file->store('oer/files', 'public');
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        // Handle cover upload
        if ($request->hasFile('cover')) {
            // Delete old cover
            if ($resource->cover_path) {
                Storage::disk('public')->delete($resource->cover_path);
            }
            $cover = $request->file('cover');
            $coverPath = $cover->store('oer/covers', 'public');
            $data['cover_path'] = $coverPath;
        }

        $resource->update($data);

        return redirect()->route('admin.oer.index')->with('success', __('OER resource updated successfully.'));
    }

    public function destroy($id)
    {
        $resource = OpenEducationalResource::findOrFail($id);

        // Delete files
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }
        if ($resource->cover_path) {
            Storage::disk('public')->delete($resource->cover_path);
        }

        $resource->delete();

        return redirect()->route('admin.oer.index')->with('success', __('OER resource deleted successfully.'));
    }

    public function approveContribution($id)
    {
        $contribution = OERContribution::findOrFail($id);
        $contribution->update(['status' => 'approved']);

        // Logic to convert contribution to resource can be added here

        return back()->with('success', __('Đã phê duyệt đóng góp.'));
    }

    public function rejectContribution($id)
    {
        $contribution = OERContribution::findOrFail($id);
        $contribution->update(['status' => 'rejected']);

        return back()->with('success', __('Đã từ chối đóng góp.'));
    }
}
