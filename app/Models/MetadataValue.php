<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetadataValue extends Model
{
    use HasFactory;

    protected $fillable = ['metadata_id', 'value_code', 'value_name', 'description', 'is_active'];

    public function metadata()
    {
        return $this->belongsTo(Metadata::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_metadata_value');
    }
}
