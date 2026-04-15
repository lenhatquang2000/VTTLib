<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalCategory;
use App\Models\DigitalDocument;
use Illuminate\Http\Request;

class DigitalDocumentController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->integer('category_id');

        $category = null;
        if ($categoryId) {
            $category = DigitalCategory::findOrFail($categoryId);
        } else {
            $category = DigitalCategory::orderBy('created_at', 'desc')->first();
        }

        $categories = DigitalCategory::orderBy('name')->get(['id', 'name', 'code']);

        $documents = DigitalDocument::query()
            ->when($category, fn ($q) => $q->where('folder_id', $category->id))
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.digital-documents.index', compact('categories', 'category', 'documents'));
    }

    public function create(Request $request)
    {
        $categories = DigitalCategory::orderBy('name')->get(['id', 'name', 'code']);
        $categoryId = $request->integer('category_id');

        return view('admin.digital-documents.create', compact('categories', 'categoryId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'folder_id' => 'required|exists:digital_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        DigitalDocument::create($validated);

        return redirect()->route('admin.digital-documents.index', ['category_id' => $validated['folder_id']])
            ->with('success', 'Tạo tài liệu số thành công!');
    }

    public function edit(DigitalDocument $digitalDocument)
    {
        $categories = DigitalCategory::orderBy('name')->get(['id', 'name', 'code']);

        return view('admin.digital-documents.edit', compact('digitalDocument', 'categories'));
    }

    public function update(Request $request, DigitalDocument $digitalDocument)
    {
        $validated = $request->validate([
            'folder_id' => 'required|exists:digital_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        $digitalDocument->update($validated);

        return redirect()->route('admin.digital-documents.index', ['category_id' => $validated['folder_id']])
            ->with('success', 'Cập nhật tài liệu số thành công!');
    }

    public function destroy(DigitalDocument $digitalDocument)
    {
        $folderId = $digitalDocument->folder_id;
        $digitalDocument->delete();

        return redirect()->route('admin.digital-documents.index', ['category_id' => $folderId])
            ->with('success', 'Xóa tài liệu số thành công!');
    }
}
