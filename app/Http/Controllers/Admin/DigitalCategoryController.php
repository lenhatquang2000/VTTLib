<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalCategory;
use Illuminate\Http\Request;

class DigitalCategoryController extends Controller
{
    public function index()
    {
        $categories = DigitalCategory::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.digital-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.digital-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:digital_categories,code',
        ]);

        DigitalCategory::create($validated);

        return redirect()->route('admin.digital-categories.index')
            ->with('success', 'Tạo thư mục tài liệu số thành công!');
    }

    public function edit(DigitalCategory $digitalCategory)
    {
        return view('admin.digital-categories.edit', compact('digitalCategory'));
    }

    public function update(Request $request, DigitalCategory $digitalCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:digital_categories,code,' . $digitalCategory->id,
        ]);

        $digitalCategory->update($validated);

        return redirect()->route('admin.digital-categories.index')
            ->with('success', 'Cập nhật thư mục tài liệu số thành công!');
    }

    public function destroy(DigitalCategory $digitalCategory)
    {
        if ($digitalCategory->documents()->count() > 0) {
            return back()->with('error', 'Không thể xóa thư mục đang có tài liệu. Vui lòng chuyển/xóa tài liệu trước!');
        }

        $digitalCategory->delete();

        return redirect()->route('admin.digital-categories.index')
            ->with('success', 'Xóa thư mục tài liệu số thành công!');
    }
}
