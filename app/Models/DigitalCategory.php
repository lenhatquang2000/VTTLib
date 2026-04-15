<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DigitalCategory extends Model
{
    protected $table = 'digital_categories';

    protected $fillable = [
        'code',
        'name',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(DigitalDocument::class, 'folder_id')->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
