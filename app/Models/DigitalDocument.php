<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalDocument extends Model
{
    protected $table = 'digital_documents';

    protected $fillable = [
        'folder_id',
        'title',
        'description',
        'file_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DigitalCategory::class, 'folder_id');
    }
}
