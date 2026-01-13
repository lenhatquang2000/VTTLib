<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'publisher', 'year_publish', 'isbn'];

    public function metadataValues()
    {
        return $this->belongsToMany(MetadataValue::class, 'book_metadata_value');
    }
}
