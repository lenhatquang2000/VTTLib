<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintQueue extends Model
{
    protected $table = 'print_queue';
    
    protected $fillable = [
        'patron_detail_id',
        'added_by',
        'status',
        'priority',
        'notes',
        'printed_at',
        'printed_by',
    ];

    protected $casts = [
        'priority' => 'integer',
        'printed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status values
    const STATUS_PENDING = 'pending';
    const STATUS_PRINTED = 'printed';
    const STATUS_CANCELLED = 'cancelled';

    public function patron(): BelongsTo
    {
        return $this->belongsTo(PatronDetail::class, 'patron_detail_id');
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function printedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Chờ in',
            self::STATUS_PRINTED => 'Đã in',
            self::STATUS_CANCELLED => 'Đã hủy',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PRINTED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePrinted($query)
    {
        return $query->where('status', self::STATUS_PRINTED);
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('created_at', 'asc');
    }

    public function markAsPrinted(int $printedBy): bool
    {
        return $this->update([
            'status' => self::STATUS_PRINTED,
            'printed_at' => now(),
            'printed_by' => $printedBy,
        ]);
    }

    public function cancel(): bool
    {
        return $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }
}
