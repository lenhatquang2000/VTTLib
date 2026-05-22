<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogDictionary extends Model
{
    protected $fillable = [
        'type',
        'code',
        'name_vi',
        'name_en',
        'sort_order',
        'is_active'
    ];
}
