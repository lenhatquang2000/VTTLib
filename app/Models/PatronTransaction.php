<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatronTransaction extends Model
{
    protected $table = 'patron_transactions';
    
    protected $fillable = [
        'patron_detail_id',
        'created_by',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'notes',
        'payment_method',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Transaction types
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';
    const TYPE_FEE = 'fee';
    const TYPE_FINE = 'fine';
    const TYPE_PENALTY = 'penalty';

    // Payment methods
    const PAYMENT_CASH = 'cash';
    const PAYMENT_TRANSFER = 'transfer';
    const PAYMENT_CARD = 'card';

    public function patron(): BelongsTo
    {
        return $this->belongsTo(PatronDetail::class, 'patron_detail_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_DEPOSIT => 'Thêm tiền',
            self::TYPE_WITHDRAW => 'Rút tiền',
            self::TYPE_FEE => 'Phí dịch vụ',
            self::TYPE_FINE => 'Phạt',
            self::TYPE_PENALTY => 'Phạt khác',
            default => $this->type,
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            self::PAYMENT_CASH => 'Tiền mặt',
            self::PAYMENT_TRANSFER => 'Chuyển khoản',
            self::PAYMENT_CARD => 'Thẻ',
            default => $this->payment_method ?? '-',
        };
    }

    public function isCredit(): bool
    {
        return in_array($this->type, [self::TYPE_DEPOSIT]);
    }

    public function isDebit(): bool
    {
        return in_array($this->type, [self::TYPE_WITHDRAW, self::TYPE_FEE, self::TYPE_FINE, self::TYPE_PENALTY]);
    }
}
