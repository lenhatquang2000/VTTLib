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
        'record_type_vi',
        'record_type_en',
        'status',
        'status_vi',
        'status_en',
        'is_featured',
        'framework',
        'document_type_id',
        'subject_category',
        'bibliographic_level',
        'bibliographic_level_vi',
        'bibliographic_level_en',
        'serial_frequency',
        'serial_frequency_vi',
        'serial_frequency_en',
        'date_type',
        'date_type_vi',
        'date_type_en',
        'acquisition_method',
        'acquisition_method_vi',
        'acquisition_method_en',
        'document_format',
        'document_format_vi',
        'document_format_en',
        'cataloging_standard',
        'cataloging_standard_vi',
        'cataloging_standard_en',
        'view_count',
        'loan_count'
    ];

    public function fields()
    {
        return $this->hasMany(MarcField::class, 'record_id')->orderBy('sequence');
    }

    public function items()
    {
        return $this->hasMany(BookItem::class, 'bibliographic_record_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
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
     * Get Record Author from MARC 100 or 700
     */
    public function getAuthorAttribute()
    {
        return $this->getMarcValue('100', 'a') ?: ($this->getMarcValue('700', 'a') ?: __('N/A'));
    }

    /**
     * Get Record Call Number from MARC 082 or 090
     */
    public function getCallNumberAttribute()
    {
        $class = $this->getMarcValue('082', 'a') ?: $this->getMarcValue('090', 'a');
        $item = $this->getMarcValue('090', 'b') ?: ($this->getMarcValue('100', 'a') ? substr($this->getMarcValue('100', 'a'), 0, 3) : '');
        if (!$class && !$item) {
            return __('N/A');
        }
        return trim($class . ' ' . $item);
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
