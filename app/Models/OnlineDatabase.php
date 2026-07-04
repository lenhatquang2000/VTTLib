<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineDatabase extends Model
{
    protected $table = 'online_databases';

    protected $fillable = [
        'title',
        'image_url',
        'url',
        'hd_url',
        'content',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Resolve image URL dynamically (supports both local uploads and remote URLs)
     */
    public function getImageUrlAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset('storage/' . $value);
    }
}
