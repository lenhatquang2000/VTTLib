<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    protected $fillable = ['name', 'route_name', 'icon', 'order', 'is_active'];

    public function userRoleSidebars()
    {
        return $this->hasMany(UserRoleSidebar::class, 'sidebar_id');
    }
}
