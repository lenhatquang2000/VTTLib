<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsTagController extends Controller
{
    /**
     * Display a listing of news tags.
     */
    public function index(Request $request)
    {
        $query = NewsTag::withCount('news')->orderBy('name');

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $tags = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => NewsTag::count(),
            'active' => NewsTag::active()->count(),
            'inactive' => NewsTag::where('is_active', false)->count(),
            'this_month' => NewsTag::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.news-tags.index', compact('tags', 'stats'));
    }

    /**
     * Show the form for creating a new news tag.
     */
    public function create()
    {
        return view('admin.news-tags.create');
    }

    /**
     * Store a newly created news tag.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'nullable|string|max:50|unique:news_tags,slug',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'language' => 'required|string|max:5'
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Set default values
        $validated['is_active'] = $validated['is_active'] ?? true;

        DB::beginTransaction();
        try {
            $tag = NewsTag::create($validated);

            // Log activity
            activity_log('news_tag_created', $tag, [
                'name' => $tag->name
            ]);

            DB::commit();

            return redirect()
                ->route('admin.news-tags.index')
                ->with('success', 'Tạo thẻ tag thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Lỗi khi tạo thẻ tag: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified news tag.
     */
    public function show(NewsTag $newsTag)
    {
        $newsTag->load(['news' => function ($q) {
            $q->with('author', 'category')
              ->orderBy('created_at', 'desc')
              ->limit(10);
        }]);

        return view('admin.news-tags.show', compact('newsTag'));
    }

    /**
     * Show the form for editing the specified news tag.
     */
    public function edit(NewsTag $newsTag)
    {
        return view('admin.news-tags.edit', compact('newsTag'));
    }

    /**
     * Update the specified news tag.
     */
    public function update(Request $request, NewsTag $newsTag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'nullable|string|max:50|unique:news_tags,slug,' . $newsTag->id,
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'language' => 'required|string|max:5'
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Set default values
        $validated['is_active'] = $validated['is_active'] ?? true;

        DB::beginTransaction();
        try {
            $newsTag->update($validated);

            // Log activity
            activity_log('news_tag_updated', $newsTag, [
                'name' => $newsTag->name
            ]);

            DB::commit();

            return redirect()
                ->route('admin.news-tags.index')
                ->with('success', 'Cập nhật thẻ tag thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Lỗi khi cập nhật thẻ tag: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified news tag.
     */
    public function destroy(NewsTag $newsTag)
    {
        DB::beginTransaction();
        try {
            $name = $newsTag->name;
            
            $newsTag->delete();

            // Log activity
            activity_log('news_tag_deleted', null, [
                'name' => $name
            ]);

            DB::commit();

            return redirect()
                ->route('admin.news-tags.index')
                ->with('success', 'Xóa thẻ tag thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi xóa thẻ tag: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status (AJAX)
     */
    public function toggleStatus(NewsTag $newsTag)
    {
        try {
            $newsTag->is_active = !$newsTag->is_active;
            $newsTag->save();
            
            $status = $newsTag->is_active ? 'kích hoạt' : 'vô hiệu';
            
            return response()->json([
                'success' => true,
                'message' => "Thẻ tag đã được {$status}!",
                'is_active' => $newsTag->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái!'
            ], 500);
        }
    }

    /**
     * Get tags as JSON (for AJAX autocomplete)
     */
    public function json(Request $request)
    {
        $query = $request->get('q', '');
        $language = $request->get('language', 'vi');

        $tags = NewsTag::active()
            ->byLanguage($language)
            ->where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'slug', 'color']);

        return response()->json([
            'success' => true,
            'tags' => $tags
        ]);
    }

    /**
     * Get popular tags (AJAX)
     */
    public function popular(Request $request)
    {
        $language = $request->get('language', 'vi');
        $limit = $request->get('limit', 20);

        $tags = NewsTag::withCount('publishedNews')
            ->active()
            ->byLanguage($language)
            ->orderBy('published_news_count', 'desc')
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'color', 'published_news_count']);

        return response()->json([
            'success' => true,
            'tags' => $tags
        ]);
    }

    /**
     * Merge tags (AJAX)
     */
    public function merge(Request $request)
    {
        $validated = $request->validate([
            'source_tag_ids' => 'required|array',
            'source_tag_ids.*' => 'exists:news_tags,id',
            'target_tag_id' => 'required|exists:news_tags,id|different:source_tag_ids.*'
        ]);

        DB::beginTransaction();
        try {
            $sourceTagIds = $validated['source_tag_ids'];
            $targetTagId = $validated['target_tag_id'];

            // Move all news from source tags to target tag
            foreach ($sourceTagIds as $sourceTagId) {
                $sourceTag = NewsTag::find($sourceTagId);
                $news = $sourceTag->news()->get();
                
                foreach ($news as $newsItem) {
                    $newsItem->tags()->attach($targetTagId);
                }
                
                // Delete source tag
                $sourceTag->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gộp thẻ tag thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gộp thẻ tag: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean unused tags (AJAX)
     */
    public function cleanup()
    {
        DB::beginTransaction();
        try {
            // Delete tags with no news
            $unusedTags = NewsTag::whereDoesntHave('news')->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Đã dọn dẹp {$unusedTags} thẻ tag không sử dụng!",
                'count' => $unusedTags
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi dọn dẹp thẻ tag!'
            ], 500);
        }
    }

    /**
     * Bulk actions (AJAX)
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete,merge',
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'exists:news_tags,id',
            'target_tag_id' => 'nullable|exists:news_tags,id'
        ]);

        DB::beginTransaction();
        try {
            $tagIds = $validated['tag_ids'];
            $count = 0;

            switch ($validated['action']) {
                case 'activate':
                    NewsTag::whereIn('id', $tagIds)->update(['is_active' => true]);
                    $count = count($tagIds);
                    $message = "Đã kích hoạt {$count} thẻ tag";
                    break;

                case 'deactivate':
                    NewsTag::whereIn('id', $tagIds)->update(['is_active' => false]);
                    $count = count($tagIds);
                    $message = "Đã vô hiệu hóa {$count} thẻ tag";
                    break;

                case 'delete':
                    NewsTag::whereIn('id', $tagIds)->delete();
                    $count = count($tagIds);
                    $message = "Đã xóa {$count} thẻ tag";
                    break;

                case 'merge':
                    if (!$validated['target_tag_id']) {
                        throw new \Exception('Vui lòng chọn thẻ tag đích');
                    }
                    
                    // Move news to target tag
                    foreach ($tagIds as $sourceTagId) {
                        if ($sourceTagId != $validated['target_tag_id']) {
                            $sourceTag = NewsTag::find($sourceTagId);
                            $news = $sourceTag->news()->get();
                            
                            foreach ($news as $newsItem) {
                                $newsItem->tags()->attach($validated['target_tag_id']);
                            }
                            
                            $sourceTag->delete();
                            $count++;
                        }
                    }
                    $message = "Đã gộp {$count} thẻ tag";
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
