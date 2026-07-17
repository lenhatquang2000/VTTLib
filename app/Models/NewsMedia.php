<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsMedia extends Model
{
    protected $table = 'news_media';

    protected $fillable = [
        'news_id',
        'media_name',
        'media_extension',
        'media_type',
        'media_note',
        'file_path',
        'is_display',
        'customer_id'
    ];

    protected $casts = [
        'is_display' => 'boolean'
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
