<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DigitalFolderController extends Controller
{
    public function index()
    {
        $folders = DigitalFolder::withCount('resources')
            ->orderBy('sort_order')
            ->get();
        return view('admin.digital-resources.folders.index', compact('folders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'folder_code' => 'required|string|max:50|unique:digital_folders,folder_code',
            'folder_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:digital_folders,id',
            'language' => 'required|string|max:5',
        ]);

        if (empty($validated['folder_code'])) {
            $validated['folder_code'] = Str::slug($validated['folder_name']);
        }

        DigitalFolder::create($validated);

        return redirect()->back()->with('success', __('Folder created successfully!'));
    }

    public function update(Request $request, DigitalFolder $folder)
    {
        $validated = $request->validate([
            'folder_code' => 'required|string|max:50|unique:digital_folders,folder_code,' . $folder->id,
            'folder_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:digital_folders,id',
        ]);

        $folder->update($validated);

        return redirect()->back()->with('success', __('Folder updated successfully!'));
    }

    public function destroy(DigitalFolder $folder)
    {
        // Kiểm tra ràng buộc: Nếu có tài liệu bên trong thì không cho xóa (Mục 2b)
        if ($folder->resources()->count() > 0) {
            return redirect()->back()->with('error', __('Cannot delete folder that contains documents!'));
        }

        $folder->delete();
        return redirect()->back()->with('success', __('Folder deleted successfully!'));
    }

    /**
     * Xuất danh sách toàn bộ danh mục tài liệu số (Mục 2a)
     */
    public function export()
    {
        // Logic xuất Excel sẽ được tích hợp ở bước tiếp theo
        return response()->json(['message' => 'Chức năng xuất dữ liệu đang được chuẩn bị']);
    }
}
