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

    // Helper to check if resource is published
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
