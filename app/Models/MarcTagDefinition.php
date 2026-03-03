<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcTagDefinition extends Model
{
    protected $fillable = ['tag', 'label', 'description'];

    public function frameworks()
    {
        return $this->belongsToMany(MarcFramework::class, 'marc_framework_tags', 'tag_id', 'framework_id')
                    ->withPivot('id', 'is_visible', 'order')
                    ->withTimestamps();
    }

    public function subfields()
    {
        return $this->hasMany(MarcSubfieldDefinition::class, 'tag_id');
    }
}
