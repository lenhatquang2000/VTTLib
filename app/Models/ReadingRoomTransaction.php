<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReadingRoomTransaction extends Model
{
    protected $fillable = [
        'patron_detail_id',
        'book_item_id', 
        'checkout_time',
        'checkin_time',
        'due_time',
        'status',
        'staff_id',
        'checkout_branch_id',
        'notes'
    ];

    protected $casts = [
        'checkout_time' => 'datetime',
        'checkin_time' => 'datetime', 
        'due_time' => 'datetime',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_RETURNED = 'returned';
    const STATUS_OVERDUE = 'overdue';

    /**
     * Relationships
     */
    public function patron()
    {
        return $this->belongsTo(PatronDetail::class, 'patron_detail_id');
    }

    public function bookItem()
    {
        return $this->belongsTo(BookItem::class, 'book_item_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function checkoutBranch()
    {
        return $this->belongsTo(Branch::class, 'checkout_branch_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_RETURNED);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }

    public function scopeForPatron($query, $patronId)
    {
        return $query->where('patron_detail_id', $patronId);
    }

    public function scopeForToday($query)
    {
        return $query->whereDate('checkout_time', Carbon::today());
    }

    /**
     * Check if transaction is overdue
     */
    public function isOverdue()
    {
        return $this->status === self::STATUS_ACTIVE && 
               $this->due_time->isPast() && 
               !$this->checkin_time;
    }

    /**
     * Get duration in minutes
     */
    public function getDurationMinutes()
    {
        $endTime = $this->checkin_time ?: Carbon::now();
        return $this->checkout_time->diffInMinutes($endTime);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration()
    {
        $minutes = $this->getDurationMinutes();
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$remainingMinutes}m";
        }
        return "{$minutes} phút";
    }

    /**
     * Auto-update overdue status
     */
    public function updateOverdueStatus()
    {
        if ($this->isOverdue() && $this->status !== self::STATUS_OVERDUE) {
            $this->status = self::STATUS_OVERDUE;
            $this->save();
        }
    }

    /**
     * Mark as returned
     */
    public function markAsReturned($staffId = null)
    {
        $this->checkin_time = Carbon::now();
        $this->status = self::STATUS_RETURNED;
        if ($staffId) {
            $this->staff_id = $staffId;
        }
        $this->save();
    }

    /**
     * Get due time for reading room (end of current day)
     */
    public static function getDueTimeForToday()
    {
        // Set due time to end of library service hours (e.g., 5:00 PM)
        return Carbon::today()->setHour(17)->setMinute(0)->setSecond(0);
    }
}
