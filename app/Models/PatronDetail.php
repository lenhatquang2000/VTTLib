<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatronDetail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'patron_group_id',
        'patron_code',
        'id_card',
        'mssv',
        'phone_contact',
        'display_name',
        'card_status',
        'is_read_only',
        'is_waiting_for_print',
        'dob',
        'gender',
        'profile_image',
        'school_name',
        'batch',
        'department',
        'position_class',
        'phone',
        'fax',
        'branch',
        'classification',
        'card_fee',
        'deposit',
        'balance',
        'registration_date',
        'expiry_date',
        'creator_id',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addresses()
    {
        return $this->hasMany(PatronAddress::class);
    }

    public function patronGroup()
    {
        return $this->belongsTo(PatronGroup::class);
    }

    public function loanTransactions()
    {
        return $this->hasMany(LoanTransaction::class);
    }

    public function activeLoans()
    {
        return $this->hasMany(LoanTransaction::class)->where('status', 'borrowed');
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function unpaidFines()
    {
        return $this->hasMany(Fine::class)->whereIn('status', ['pending', 'partial']);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get total outstanding fine amount
     */
    public function getTotalOutstandingFineAttribute(): float
    {
        return $this->unpaidFines->sum(function ($fine) {
            return $fine->amount - $fine->paid_amount - $fine->waived_amount;
        });
    }

    /**
     * Check if patron can borrow based on policy
     */
    public function canBorrow(): bool
    {
        $policy = $this->patronGroup?->activePolicy;
        
        if (!$policy) {
            return false;
        }

        // Check max items
        if ($this->activeLoans()->count() >= $policy->max_items) {
            return false;
        }

        // Check outstanding fines
        if ($this->total_outstanding_fine > $policy->max_outstanding_fine) {
            return false;
        }

        return true;
    }
}
