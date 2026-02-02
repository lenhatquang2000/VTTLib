<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatronAddress extends Model
{
    protected $fillable = ['patron_detail_id', 'address_line', 'type', 'is_primary'];
    
    public function patronDetail()
    {
        return $this->belongsTo(PatronDetail::class);
    }
}
