<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'image_url',
        'link_url',
        'link_target',
        'sort_order',
        'is_active',
        'start_date',
        'end_date',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'json',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(MediaCategory::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function($q) {
                         $q->whereNull('start_date')
                           ->orWhere('start_date', '<=', now());
                     })
                     ->where(function($q) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', now());
                     });
    }
}
