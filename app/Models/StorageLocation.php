<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    protected $fillable = ['branch_id', 'name', 'code', 'description', 'is_active'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function bookItems()
    {
        return $this->hasMany(BookItem::class);
    }
}
