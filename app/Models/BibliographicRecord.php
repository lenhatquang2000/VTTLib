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
        // Many views expect ->title, so we check for tag 245
        $field = $this->fields->where('tag', '245')->first();
        if (!$field) return __('Untitled');

        // Concatenate subfields a, b, c from tag 245
        $titleParts = $field->subfields
            ->whereIn('code', ['a', 'b', 'c'])
            ->sortBy('sequence')
            ->pluck('value')
            ->toArray();

        return implode(' ', $titleParts) ?: __('Untitled');
    }
}
