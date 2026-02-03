<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleSidebar extends Model
{
    protected $fillable = ['role_id', 'sidebar_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function sidebar()
    {
        return $this->belongsTo(Sidebar::class);
    }
}
