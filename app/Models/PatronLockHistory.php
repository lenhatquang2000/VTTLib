<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatronLockHistory extends Model
{
    protected $table = 'patron_lock_history';
    
    protected $fillable = [
        'patron_detail_id',
        'locked_by',
        'unlocked_by',
        'action',
        'reason',
        'unlock_fee',
        'locked_at',
        'unlocked_at',
    ];

    protected $casts = [
        'unlock_fee' => 'decimal:2',
        'locked_at' => 'datetime',
        'unlocked_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Actions
    const ACTION_LOCK = 'lock';
    const ACTION_UNLOCK = 'unlock';

    public function patron(): BelongsTo
    {
        return $this->belongsTo(PatronDetail::class, 'patron_detail_id');
    }

    public function lockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function unlockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unlocked_by');
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            self::ACTION_LOCK => 'Khóa thẻ',
            self::ACTION_UNLOCK => 'Mở khóa thẻ',
            default => $this->action,
        };
    }

    public function getDurationAttribute(): ?string
    {
        if ($this->locked_at && $this->unlocked_at) {
            $duration = $this->locked_at->diff($this->unlocked_at);
            return $duration->format('%d ngày %h giờ %i phút');
        }
        
        return null;
    }

    public function scopeLocked($query)
    {
        return $query->where('action', self::ACTION_LOCK);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('action', self::ACTION_UNLOCK);
    }

    public function scopeByPatron($query, $patronId)
    {
        return $query->where('patron_detail_id', $patronId);
    }
}
