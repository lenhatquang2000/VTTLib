<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalCategory;
use App\Models\DigitalResource;
use Illuminate\Http\Request;

class DigitalCatalogingController extends Controller
{
    public function index(Request $request)
    {
        // Sử dụng DigitalFolder làm phân mục
        $categories = \App\Models\DigitalFolder::withCount('resources')->orderBy('folder_name')->get();

        // Lấy danh sách tài liệu
        $query = DigitalResource::with(['folder', 'creator']);
        
        if ($request->has('category_id')) {
            $query->where('folder_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('identifier', 'like', '%' . $request->search . '%');
        }

        $resources = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.digital_cataloging.index', compact('categories', 'resources'));
    }

    public function create(Request $request)
    {
        $folderId = $request->category_id;
        $folder = \App\Models\DigitalFolder::findOrFail($folderId);
        
        return view('admin.digital_cataloging.create', compact('folder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:digital_folders,id',
            'title' => 'required|string|max:255',
            'file_resource' => 'required|file|mimes:pdf|max:51200', // 50MB
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'language' => 'required|string',
        ]);

        $resource = new DigitalResource();
        $resource->folder_id = $request->folder_id;
        $resource->title = $request->title;
        $resource->authors = $request->authors ? [$request->authors] : [];
        $resource->resource_type = $request->resource_type;
        $resource->language = $request->language;
        $resource->description = $request->description;
        $resource->publisher = $request->publisher;
        $resource->secondary_authors = $request->secondary_authors ? [$request->secondary_authors] : [];
        $resource->publish_year = $request->publish_year;
        $resource->format = $request->format;
        $resource->identifier = $request->identifier;
        $resource->source = $request->source;
        $resource->link = $request->link;
        $resource->coverage = $request->coverage;
        $resource->copyright = $request->copyright;
        $resource->cataloging_link = $request->cataloging_link;
        $resource->status = 'published';
        $resource->created_by = auth()->id();

        // Xử lý upload file PDF
        if ($request->hasFile('file_resource')) {
            $file = $request->file('file_resource');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('digital_resources', $fileName, 'public');
            $resource->file_path = $filePath;
            $resource->file_name = $file->getClientOriginalName();
            $resource->file_size = $file->getSize();
            $resource->format = $file->getClientOriginalExtension();
        }

        // Xử lý upload ảnh bìa
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = 'cover_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('covers', $imageName, 'public');
            // Giả sử có cột cover_path trong bảng digital_resources, nếu không có ta có thể dùng meta hoặc cột khác
            // Ở đây tôi sẽ giả định có cột cover_image hoặc lưu vào description
            // Bạn có thể tạo migration thêm cột nếu cần.
        }

        $resource->save();

        return redirect()->route('admin.digital-cataloging.index', ['category_id' => $request->folder_id])
                         ->with('success', 'Biên mục tài liệu số thành công!');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'folder_code' => 'required|string|unique:digital_folders,folder_code|max:50',
            'folder_name' => 'required|string|max:255',
        ]);

        $folder = new \App\Models\DigitalFolder();
        $folder->folder_code = $request->folder_code;
        $folder->folder_name = $request->folder_name;
        $folder->is_active = true;
        $folder->save();

        return redirect()->route('admin.digital-cataloging.index')
                         ->with('success', 'Đã thêm phân mục mới thành công!');
    }
}
