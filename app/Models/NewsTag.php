<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class NewsTag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
        'is_active',
        'language'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relationships
     */
    public function news()
    {
        return $this->belongsToMany(News::class, 'news_tag');
    }

    public function publishedNews()
    {
        return $this->news()->published();
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLanguage(Builder $query, $language = 'vi')
    {
        return $query->where('language', $language);
    }

    /**
     * Accessors & Mutators
     */
    public function getUrlAttribute()
    {
        return '/tin-tuc/tag/' . $this->slug;
    }

    public function getNewsCountAttribute()
    {
        return $this->publishedNews()->count();
    }

    /**
     * Methods
     */
    public function getLatestNews($limit = 10)
    {
        return $this->publishedNews()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Static methods
     */
    public static function getPopularTags($limit = 20, $language = 'vi')
    {
        return self::withCount('publishedNews')
            ->active()
            ->byLanguage($language)
            ->orderBy('published_news_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getActiveTags($language = 'vi')
    {
        return self::active()
            ->byLanguage($language)
            ->orderBy('name')
            ->get();
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }
}
