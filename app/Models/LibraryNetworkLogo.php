<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryNetworkLogo extends Model
{
    protected $table = 'library_network_logos';

    protected $fillable = [
        'name',
        'logo_path',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to get only active logos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1)->orderBy('sort_order');
    }
}

