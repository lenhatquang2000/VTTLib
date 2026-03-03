<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcSubfieldDefinition extends Model
{
    protected $fillable = ['tag_id', 'code', 'label', 'is_visible', 'is_mandatory', 'is_repeatable', 'help_text'];

    public function tagDefinition()
    {
        return $this->belongsTo(MarcTagDefinition::class, 'tag_id');
    }
}
