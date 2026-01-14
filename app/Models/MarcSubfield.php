<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcSubfield extends Model
{
    protected $fillable = ['marc_field_id', 'code', 'value'];

    public function field()
    {
        return $this->belongsTo(MarcField::class, 'marc_field_id');
    }

    public function definition()
    {
        return $this->hasOne(MarcSubfieldDefinition::class, 'code', 'code')
            ->whereColumn('tag', 'marc_fields.tag')
            ->join('marc_fields', 'marc_subfields.marc_field_id', '=', 'marc_fields.id');
    }
}
