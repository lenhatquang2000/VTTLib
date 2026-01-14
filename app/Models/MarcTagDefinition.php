<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcTagDefinition extends Model
{
    protected $fillable = ['tag', 'label', 'description'];

    public function subfields()
    {
        return $this->hasMany(MarcSubfieldDefinition::class, 'tag', 'tag');
    }
}
