<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BibliographicLevel extends Model
{
    protected $fillable = [
        'code',
        'name_en',
        'name_vi',
        'description',
        'is_active',
        'order',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getDisplayNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'vi' ? $this->name_vi : $this->name_en;
    }
}
