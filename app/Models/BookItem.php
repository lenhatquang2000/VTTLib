<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookItem extends Model
{
    protected $fillable = [
        'bibliographic_record_id',
        'barcode',
        'accession_number',
        'storage_type',
        'quantity',
        'location',
        'temporary_location',
        'status',
        'order_code',
        'waits_for_print',
        'notes',
        'volume_issue',
        'day',
        'month_season',
        'year',
        'shelf',
        'shelf_position',
    ];

    public function bibliographicRecord()
    {
        return $this->belongsTo(BibliographicRecord::class);
    }
}
