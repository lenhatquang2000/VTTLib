<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'code', 'address', 'phone', 'is_active'];

    public function storageLocations()
    {
        return $this->hasMany(StorageLocation::class);
    }

    public function bookItems()
    {
        return $this->hasMany(BookItem::class);
    }
}
