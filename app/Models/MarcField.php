<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcField extends Model
{
    protected $fillable = ['record_id', 'tag', 'indicator1', 'indicator2', 'sequence'];

    public function record()
    {
        return $this->belongsTo(BibliographicRecord::class, 'record_id');
    }

    public function subfields()
    {
        return $this->hasMany(MarcSubfield::class, 'marc_field_id');
    }

    public function definition()
    {
        return $this->belongsTo(MarcTagDefinition::class, 'tag', 'tag');
    }
}
