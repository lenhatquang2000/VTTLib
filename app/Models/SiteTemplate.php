<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTemplate extends Model
{
    protected $fillable = [
        'template_code',
        'template_name',
        'preview_image',
        'sort_order',
        'is_active'
    ];
}
