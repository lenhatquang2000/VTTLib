<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalResource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'folder_id',
        'title',
        'resource_type',
        'file_path',
        'cover_path',
        'file_name',
        'file_size',
        'language',
        'authors',
        'subjects',
        'topics',
        'secondary_authors',
        'description',
        'publisher',
        'publish_year',
        'format',
        'identifier',
        'source',
        'link',
        'coverage',
        'copyright',
        'cataloging_link',
        'status',
        'view_count',
        'download_count',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'authors' => 'array',
        'subjects' => 'array',
        'topics' => 'array',
        'secondary_authors' => 'array',
        'view_count' => 'integer',
        'download_count' => 'integer',
        'file_size' => 'integer'
    ];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(DigitalFolder::class, 'folder_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Accessor for author name (from authors array)
     */
    public function getAuthorAttribute()
    {
        if (is_array($this->authors) && count($this->authors) > 0) {
            return implode(', ', $this->authors);
        }
        return $this->authors; // Fallback if string
    }

    /**
     * Get full file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->cover_path) {
            return asset('storage/' . $this->cover_path);
        }
        // For now using placeholder, should be actual cover image if exists
        return "https://placehold.co/300x400/7B0000/FFFFFF?text=DOC";
    }

    // Helper to check if resource is published
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
