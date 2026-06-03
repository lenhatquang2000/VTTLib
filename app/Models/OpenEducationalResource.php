<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpenEducationalResource extends Model
{
    use SoftDeletes;

    protected $table = 'oer_resources';

    protected $fillable = [
        'title',
        'resource_type',
        'file_path',
        'language',
        'authors',
        'subjects',
        'educational_levels',
        'license',
        'license_url',
        'description',
        'publisher',
        'publish_year',
        'format',
        'identifier',
        'source',
        'external_link',
        'keywords',
        'file_name',
        'file_size',
        'cover_path',
        'status',
        'view_count',
        'download_count',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'authors' => 'array',
        'subjects' => 'array',
        'educational_levels' => 'array',
        'view_count' => 'integer',
        'download_count' => 'integer',
        'file_size' => 'integer'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getAuthorAttribute()
    {
        if (is_array($this->authors) && count($this->authors) > 0) {
            return implode(', ', $this->authors);
        }
        return $this->authors;
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->cover_path) {
            return asset('storage/' . $this->cover_path);
        }
        return "https://placehold.co/300x400/7B0000/FFFFFF?text=OER";
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
