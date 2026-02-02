<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatronGroup extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function patrons()
    {
        return $this->hasMany(PatronDetail::class);
    }

    public function circulationPolicies()
    {
        return $this->hasMany(CirculationPolicy::class);
    }

    public function activePolicy()
    {
        return $this->hasOne(CirculationPolicy::class)->where('is_active', true);
    }
}
