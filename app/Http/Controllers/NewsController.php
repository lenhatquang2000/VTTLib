<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsTag;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display news listing page.
     */
    public function index(Request $request)
    {
        $query = News::published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc');

        // Filters
        if ($request->filled('category')) {
            $category = NewsCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('tag')) {
            $tag = NewsTag::where('slug', $request->tag)->first();
            if ($tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('tags.id', $tag->id);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate(12);
        
        // Get featured news
        $featuredNews = News::featured()
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // Get categories
        $categories = NewsCategory::active()
            ->withCount('publishedNews')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get popular tags
        $popularTags = NewsTag::getPopularTags(15);

        return view('news.index', compact('news', 'featuredNews', 'categories', 'popularTags'));
    }

    /**
     * Display single news article.
     */
    public function show($slug)
    {
        $news = News::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count
        $news->incrementView();

        // Get related news
        $relatedNews = $news->getRelatedNews(5);

        // Get previous and next news
        $previousNews = News::published()
            ->where('published_at', '<', $news->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextNews = News::published()
            ->where('published_at', '>', $news->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        return view('news.show', compact(
            'news', 
            'relatedNews', 
            'previousNews', 
            'nextNews'
        ));
    }

    /**
     * Display news by category.
     */
    public function category($slug)
    {
        $category = NewsCategory::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $news = News::published()
            ->where('category_id', $category->id)
            ->with(['author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get breadcrumb
        $breadcrumb = $category->getBreadcrumb();

        return view('news.category', compact(
            'category', 
            'news', 
            'breadcrumb'
        ));
    }

    /**
     * Display news by tag.
     */
    public function tag($slug)
    {
        $tag = NewsTag::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $news = News::published()
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('news.tag', compact('tag', 'news'));
    }

    /**
     * Display featured news.
     */
    public function featured()
    {
        $news = News::featured()
            ->published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('news.featured', compact('news'));
    }

    /**
     * Search news.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('news.index');
        }

        $news = News::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('summary', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get search suggestions
        $suggestions = News::published()
            ->where('title', 'like', "%{$query}%")
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->pluck('title');

        return view('news.search', compact(
            'news', 
            'query', 
            'suggestions'
        ));
    }

    /**
     * RSS feed.
     */
    public function rss()
    {
        $news = News::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(20)
            ->get();

        return response()
            ->view('news.rss', compact('news'))
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }

    /**
     * Sitemap for news.
     */
    public function sitemap()
    {
        $categories = NewsCategory::active()->get();
        $tags = NewsTag::active()->get();
        $news = News::published()
            ->orderBy('published_at', 'desc')
            ->get(['slug', 'updated_at']);

        return response()
            ->view('news.sitemap', compact('categories', 'tags', 'news'))
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    /**
     * API endpoint for news (JSON).
     */
    public function api(Request $request)
    {
        $query = News::published()
            ->with(['category:id,name,slug', 'author:id,name', 'tags:id,name,slug']);

        // Filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        if ($request->filled('limit')) {
            $query->limit($request->limit);
        }

        $news = $query->orderBy('published_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $news,
            'total' => $news->count()
        ]);
    }

    /**
     * Like/unlike news (AJAX).
     */
    public function like(News $news)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thích bài viết!'
            ], 401);
        }

        // Simple like system - in real app, you'd have a separate likes table
        $news->incrementLike();

        return response()->json([
            'success' => true,
            'message' => 'Đã thích bài viết!',
            'like_count' => $news->like_count
        ]);
    }
}
