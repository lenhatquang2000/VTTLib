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
        // Reading Room Policies
        'can_use_reading_room',
        'max_reading_room_items',
        'reading_room_hours',
        'reading_room_due_time',
        'reading_room_fine_per_hour',
        'reading_room_max_fine',
        // Hold/Reserve Policies
        'can_place_hold',
        'max_holds',
        'hold_expiry_days',
        'hold_notification_days',
        'hold_cancellation_fee',
        'allow_hold_renewal',
        'max_hold_renewals',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'fine_per_day' => 'decimal:2',
        'max_fine' => 'decimal:2',
        'max_outstanding_fine' => 'decimal:2',
        'can_reserve' => 'boolean',
        'is_active' => 'boolean',
        // Reading Room Policies
        'can_use_reading_room' => 'boolean',
        'reading_room_due_time' => 'datetime:H:i:s',
        'reading_room_fine_per_hour' => 'decimal:2',
        'reading_room_max_fine' => 'decimal:2',
        // Hold/Reserve Policies
        'can_place_hold' => 'boolean',
        'hold_cancellation_fee' => 'decimal:2',
        'allow_hold_renewal' => 'boolean',
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

    /**
     * Calculate reading room fine for overdue hours
     */
    public function calculateReadingRoomFine(int $overdueHours): float
    {
        $fine = $overdueHours * $this->reading_room_fine_per_hour;
        return min($fine, $this->reading_room_max_fine);
    }

    /**
     * Check if patron can use reading room
     */
    public function canUseReadingRoom(): bool
    {
        return $this->can_use_reading_room && $this->is_active;
    }

    /**
     * Check if patron can place holds
     */
    public function canPlaceHolds(): bool
    {
        return $this->can_place_hold && $this->is_active;
    }

    /**
     * Get reading room due time for today
     */
    public function getReadingRoomDueTime(): \Carbon\Carbon
    {
        $now = \Carbon\Carbon::now();
        $dueTime = $now->setTimeFromTimeString($this->reading_room_due_time);
        
        // If due time has passed, set to tomorrow
        if ($dueTime->lt($now)) {
            $dueTime->addDay();
        }
        
        return $dueTime;
    }

    /**
     * Get hold expiry date
     */
    public function getHoldExpiryDate(): \Carbon\Carbon
    {
        return \Carbon\Carbon::now()->addDays($this->hold_expiry_days);
    }

    /**
     * Get hold notification date
     */
    public function getHoldNotificationDate(): \Carbon\Carbon
    {
        return \Carbon\Carbon::now()->addDays($this->hold_notification_days);
    }

    /**
     * Check if patron can renew holds
     */
    public function canRenewHolds(): bool
    {
        return $this->allow_hold_renewal && $this->max_hold_renewals > 0;
    }

    /**
     * Get policy summary for display
     */
    public function getPolicySummary(): array
    {
        return [
            'loan' => [
                'max_days' => $this->max_loan_days,
                'max_items' => $this->max_items,
                'max_renewals' => $this->max_renewals,
                'renewal_days' => $this->renewal_days,
            ],
            'reading_room' => [
                'allowed' => $this->can_use_reading_room,
                'max_items' => $this->max_reading_room_items,
                'max_hours' => $this->reading_room_hours,
                'due_time' => $this->reading_room_due_time,
                'fine_per_hour' => $this->reading_room_fine_per_hour,
            ],
            'holds' => [
                'allowed' => $this->can_place_hold,
                'max_holds' => $this->max_holds,
                'expiry_days' => $this->hold_expiry_days,
                'notification_days' => $this->hold_notification_days,
                'cancellation_fee' => $this->hold_cancellation_fee,
                'can_renew' => $this->allow_hold_renewal,
                'max_renewals' => $this->max_hold_renewals,
            ],
            'fines' => [
                'per_day' => $this->fine_per_day,
                'max_fine' => $this->max_fine,
                'grace_period' => $this->grace_period_days,
                'max_outstanding' => $this->max_outstanding_fine,
            ]
        ];
    }
}
