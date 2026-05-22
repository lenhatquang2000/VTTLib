<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index(Request $request)
    {
        $query = Announcement::with(['author']);

        $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->search}%")
                  ->orWhere('summary', 'LIKE', "%{$request->search}%");
            });
        }

        $announcements = $query->paginate(15);
        
        $stats = [
            'total' => Announcement::count(),
            'published' => Announcement::published()->count(),
            'draft' => Announcement::draft()->count(),
            'pending' => Announcement::pending()->count(),
            'featured' => Announcement::featured()->count(),
        ];

        return view('admin.announcements.index', compact('announcements', 'stats'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:announcements,slug',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:255',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,pending,published,archived',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date|after:published_at',
            'is_featured' => 'nullable',
            'language' => 'required|string|max:5',
        ]);

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('announcements', 'public');
            $validated['featured_image'] = asset('storage/' . $path);
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        }

        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->has('is_featured');

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Announcement::create($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Tạo thông báo thành công!');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:announcements,slug,' . $announcement->id,
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:255',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,pending,published,archived',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date|after:published_at',
            'is_featured' => 'nullable',
            'language' => 'required|string|max:5',
        ]);

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('announcements', 'public');
            $validated['featured_image'] = asset('storage/' . $path);
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        }

        $validated['is_featured'] = $request->has('is_featured');

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Cập nhật thông báo thành công!');
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Xóa thông báo thành công!');
    }

    /**
     * Bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Vui lòng chọn ít nhất một thông báo']);
        }

        switch ($action) {
            case 'publish':
                Announcement::whereIn('id', $ids)->update(['status' => 'published', 'published_at' => now()]);
                break;
            case 'archive':
                Announcement::whereIn('id', $ids)->update(['status' => 'archived']);
                break;
            case 'delete':
                Announcement::whereIn('id', $ids)->delete();
                break;
        }

        return response()->json(['success' => true, 'message' => 'Thực hiện hành động thành công!']);
    }

    /**
     * Auto generate announcement content.
     */
    public function autoGenerate(Request $request)
    {
        try {
            $maxOrder = Announcement::max('sort_order') ?? -1;

            $topics = [
                'Thông báo về việc mượn trả sách trong kỳ nghỉ lễ',
                'Thông báo hướng dẫn sử dụng hệ thống thư viện mới',
                'Thông báo lịch bảo trì hệ thống VTTLib',
                'Thông báo về việc cập nhật quy định mượn tài liệu số',
                'Thông báo tuyển cộng tác viên thư viện số',
            ];

            $topic = $topics[array_rand($topics)];
            $content = "Đây là nội dung tự động được tạo cho thông báo: {$topic}.\n\n" .
                      "Thư viện số VTTLib xin thông báo đến quý bạn đọc về các nội dung liên quan.\n\n" .
                      "Mọi chi tiết xin vui lòng liên hệ văn phòng thư viện.";

            $announcement = Announcement::create([
                'title' => $topic . ' - ' . now()->format('H:i:s'),
                'slug' => Str::slug($topic) . '-' . time(),
                'summary' => 'Tóm tắt tự động cho thông báo: ' . $topic,
                'content' => $content,
                'status' => 'published',
                'author_id' => auth()->id(),
                'published_at' => now(),
                'language' => 'vi',
                'is_featured' => rand(0, 1),
                'sort_order' => $maxOrder + 1,
                'featured_image' => 'https://img.freepik.com/free-vector/announcement-concept-illustration_114360-125.jpg'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tự động tạo thông báo thành công!',
                'announcement' => $announcement,
                'redirect' => route('admin.announcements.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tự động tạo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder announcements.
     */
    public function reorder(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) return response()->json(['success' => false]);

        foreach ($ids as $index => $id) {
            Announcement::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
