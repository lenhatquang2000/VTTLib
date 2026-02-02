<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'patron_detail_id',
        'loan_transaction_id',
        'fine_type',
        'amount',
        'paid_amount',
        'waived_amount',
        'status',
        'paid_date',
        'collected_by',
        'payment_method',
        'description',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'waived_amount' => 'decimal:2',
        'paid_date' => 'datetime',
    ];

    public function patron()
    {
        return $this->belongsTo(PatronDetail::class, 'patron_detail_id');
    }

    public function loanTransaction()
    {
        return $this->belongsTo(LoanTransaction::class);
    }

    public function collectedByUser()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    /**
     * Get remaining balance
     */
    public function getBalanceAttribute(): float
    {
        return $this->amount - $this->paid_amount - $this->waived_amount;
    }

    /**
     * Check if fully paid
     */
    public function isPaid(): bool
    {
        return $this->balance <= 0;
    }

    /**
     * Scope for unpaid fines
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['pending', 'partial']);
    }
}
