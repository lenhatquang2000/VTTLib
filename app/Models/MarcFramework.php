<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcFramework extends Model
{
    protected $fillable = ['code', 'name', 'description', 'is_active'];

    public function tags()
    {
        return $this->belongsToMany(MarcTagDefinition::class, 'marc_framework_tags', 'framework_id', 'tag_id')
                    ->withPivot('id', 'is_visible', 'order')
                    ->withTimestamps()
                    ->orderBy('order')
                    ->orderBy('tag');
    }
}
