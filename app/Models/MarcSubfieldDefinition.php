<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcSubfieldDefinition extends Model
{
    protected $fillable = ['tag', 'code', 'label', 'help_text'];

    public function tagDefinition()
    {
        return $this->belongsTo(MarcTagDefinition::class, 'tag', 'tag');
    }
}
