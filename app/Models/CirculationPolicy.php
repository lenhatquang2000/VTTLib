<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CirculationPolicy extends Model
{
    protected $fillable = [
        'name',
        'patron_group_id',
        'max_loan_days',
        'max_items',
        'max_renewals',
        'renewal_days',
        'fine_per_day',
        'max_fine',
        'grace_period_days',
        'can_reserve',
        'max_reservations',
        'reservation_hold_days',
        'max_outstanding_fine',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'fine_per_day' => 'decimal:2',
        'max_fine' => 'decimal:2',
        'max_outstanding_fine' => 'decimal:2',
        'can_reserve' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function patronGroup()
    {
        return $this->belongsTo(PatronGroup::class);
    }

    public function loanTransactions()
    {
        return $this->hasMany(LoanTransaction::class);
    }

    /**
     * Calculate fine for overdue days
     */
    public function calculateFine(int $overdueDays): float
    {
        if ($overdueDays <= $this->grace_period_days) {
            return 0;
        }

        $chargeableDays = $overdueDays - $this->grace_period_days;
        $fine = $chargeableDays * $this->fine_per_day;

        return min($fine, $this->max_fine);
    }
}
