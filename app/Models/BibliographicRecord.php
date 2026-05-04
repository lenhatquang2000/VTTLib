<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BibliographicRecord extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';

    protected $fillable = [
        'leader', 
        'cover_image',
        'record_type', 
        'status',
        'framework',
        'subject_category',
        'serial_frequency',
        'date_type',
        'acquisition_method',
        'document_format',
        'cataloging_standard'
    ];

    public function fields()
    {
        return $this->hasMany(MarcField::class, 'record_id')->orderBy('sequence');
    }

    public function items()
    {
        return $this->hasMany(BookItem::class, 'bibliographic_record_id');
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Get Record Title from MARC 245
     */
    public function getTitleAttribute()
    {
        return $this->getMarcValue('245', 'a') ?: __('Untitled');
    }

    /**
     * Helper to get a MARC field value by tag and subfield code
     */
    public function getMarcValue($tag, $subfieldCode = 'a')
    {
        $field = $this->fields->where('tag', $tag)->first();
        if (!$field) {
            return null;
        }

        $subfield = $field->subfields->where('code', $subfieldCode)->first();
        return $subfield ? $subfield->value : null;
    }
}
