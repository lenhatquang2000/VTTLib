<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BibliographicRecord extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';

    protected $fillable = [
        'leader', 
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
}
