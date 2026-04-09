<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * Check if reservation is expired
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast() && 
               in_array($this->status, ['pending', 'ready']);
    }

    /**
     * Get position in queue for pending reservations
     */
    public function getPositionInQueue()
    {
        if ($this->status !== 'pending') {
            return null;
        }

        return Reservation::where('bibliographic_record_id', $this->bibliographic_record_id)
            ->where('status', 'pending')
            ->where('reservation_date', '<=', $this->reservation_date)
            ->count();
    }

    /**
     * Get next patron in queue for this bibliographic record
     */
    public static function getNextInQueue($bibliographicRecordId)
    {
        return Reservation::with('patron')
            ->where('bibliographic_record_id', $bibliographicRecordId)
            ->where('status', 'pending')
            ->orderBy('reservation_date', 'asc')
            ->first();
    }

    /**
     * Auto-assign available book to next reservation in queue
     */
    public static function processQueue($bibliographicRecordId, $bookItemId)
    {
        $nextReservation = self::getNextInQueue($bibliographicRecordId);
        
        if ($nextReservation) {
            $nextReservation->update([
                'status' => 'ready',
                'book_item_id' => $bookItemId,
                'notified' => false,
                'notified_at' => null
            ]);
            
            // Update book item status
            BookItem::find($bookItemId)->update(['status' => 'reserved']);
            
            return $nextReservation;
        }
        
        return null;
    }

    /**
     * Mark as notified
     */
    public function markAsNotified()
    {
        $this->update([
            'notified' => true,
            'notified_at' => Carbon::now()
        ]);
    }

    /**
     * Cancel reservation and process queue
     */
    public function cancelAndProcessQueue()
    {
        $bibliographicRecordId = $this->bibliographic_record_id;
        $bookItemId = $this->book_item_id;
        
        // Mark as cancelled
        $this->update([
            'status' => 'cancelled',
            'book_item_id' => null
        ]);
        
        // If there was a book assigned, process queue
        if ($bookItemId) {
            self::processQueue($bibliographicRecordId, $bookItemId);
        }
    }
}
