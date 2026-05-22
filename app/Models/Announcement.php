<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'author_id',
        'status',
        'published_at',
        'expired_at',
        'view_count',
        'sort_order',
        'language',
        'is_featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Relationships
     */
    public function author()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published')
                    ->where(function ($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expired_at')
                          ->orWhere('expired_at', '>', now());
                    });
    }

    public function scopeDraft(Builder $query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending(Builder $query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeArchived(Builder $query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Accessors
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Bản nháp',
            'pending' => 'Chờ duyệt',
            'published' => 'Đã đăng',
            'archived' => 'Lưu trữ',
            default => 'Không xác định'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'slate',
            'pending' => 'amber',
            'published' => 'emerald',
            'archived' => 'rose',
            default => 'gray'
        };
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('d/m/Y H:i') : null;
    }
}
