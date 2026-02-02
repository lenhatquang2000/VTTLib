<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'marc_type',
        'description',
        'icon',
        'default_loan_days',
        'is_loanable',
        'is_active',
        'order',
    ];

    protected $casts = [
        'default_loan_days' => 'integer',
        'is_loanable' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get book items of this document type
     */
    public function bookItems(): HasMany
    {
        return $this->hasMany(BookItem::class);
    }

    /**
     * Scope for active document types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for loanable document types
     */
    public function scopeLoanable($query)
    {
        return $query->where('is_loanable', true);
    }

    /**
     * Scope ordered by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
