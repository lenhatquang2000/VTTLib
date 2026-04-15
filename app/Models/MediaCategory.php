<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaCategory extends Model
{
    protected $fillable = [
        'name',
        'code',
        'position',
        'description',
        'type',
        'is_active',
        'language',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'json'
    ];

    public function items()
    {
        return $this->hasMany(MediaItem::class, 'category_id')->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLanguage($query, $lang = null)
    {
        return $query->where('language', $lang ?: app()->getLocale());
    }
}
