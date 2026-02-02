<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarcodeConfig extends Model
{
    protected $fillable = [
        'name',
        'prefix',
        'length',
        'start_number',
        'current_number',
        'is_active',
        'target_type'
    ];

    /**
     * Generate the next barcode based on the configuration.
     */
    public function generateNext(): string
    {
        // Calculate the next number
        $nextNumber = max($this->current_number + 1, $this->start_number);
        
        // Format the number with leading zeros
        $formattedNumber = str_pad($nextNumber, $this->length, '0', STR_PAD_LEFT);
        
        // Combine with prefix
        $barcode = ($this->prefix ?? '') . $formattedNumber;
        
        // Update the current number (optional here, usually done during actual assignment)
        // We might want to do this in a transaction in the service layer
        
        return $barcode;
    }
}
