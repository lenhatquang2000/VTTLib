<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsAuthor extends Model
{
    protected $table = 'news_authors';

    protected $fillable = [
        'name',
        'description',
        'customer_id'
    ];

    public function news()
    {
        return $this->hasMany(News::class, 'news_author_id');
    }
}
