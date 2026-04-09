<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LibraryEntry extends Model
{
    protected $fillable = [
        'patron_detail_id',
        'entry_time',
        'exit_time',
        'purpose',
        'entry_type',
        'branch_id',
        'notes'
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    public function patron()
    {
        return $this->belongsTo(PatronDetail::class, 'patron_detail_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get duration in minutes
     */
    public function getDurationMinutes()
    {
        if (!$this->exit_time) {
            return null;
        }
        return $this->entry_time->diffInMinutes($this->exit_time);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration()
    {
        if (!$this->exit_time) {
            return 'Chưa ra';
        }
        
        $minutes = $this->getDurationMinutes();
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$remainingMinutes}m";
        }
        return "{$minutes} phút";
    }

    /**
     * Scope for today's entries
     */
    public function scopeForToday($query)
    {
        return $query->whereDate('entry_time', Carbon::today());
    }

    /**
     * Scope for date range
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_time', [$startDate, $endDate]);
    }

    /**
     * Scope by purpose
     */
    public function scopeByPurpose($query, $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    /**
     * Scope by entry type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('entry_type', $type);
    }
}
