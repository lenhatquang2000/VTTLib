<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArticleType extends Model
{
    protected $table = 'news_article_types';

    protected $fillable = [
        'name',
        'friendly_name',
        'description',
        'customer_id'
    ];

    public function news()
    {
        return $this->hasMany(News::class, 'article_type_id');
    }
}
