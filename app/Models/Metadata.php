<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;

    protected $fillable = ['metadata_code', 'metadata_name', 'description', 'allow_multiple'];

    public function values()
    {
        return $this->hasMany(MetadataValue::class);
    }
}
