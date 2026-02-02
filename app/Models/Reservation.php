<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'patron_detail_id',
        'bibliographic_record_id',
        'book_item_id',
        'reservation_date',
        'expiry_date',
        'pickup_date',
        'status',
        'pickup_branch_id',
        'notified',
        'notified_at',
        'notes'
    ];

    protected $casts = [
        'reservation_date' => 'datetime',
        'expiry_date' => 'datetime',
        'pickup_date' => 'datetime',
        'notified_at' => 'datetime',
        'notified' => 'boolean',
    ];

    public function patron()
    {
        return $this->belongsTo(PatronDetail::class, 'patron_detail_id');
    }

    public function bibliographicRecord()
    {
        return $this->belongsTo(BibliographicRecord::class);
    }

    public function bookItem()
    {
        return $this->belongsTo(BookItem::class);
    }

    public function pickupBranch()
    {
        return $this->belongsTo(Branch::class, 'pickup_branch_id');
    }

    /**
     * Scope for pending reservations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for ready reservations
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }
}
