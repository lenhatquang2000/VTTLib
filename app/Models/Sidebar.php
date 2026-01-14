<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    protected $fillable = ['parent_id', 'name', 'route_name', 'icon', 'order', 'is_active'];

    public function parent()
    {
        return $this->belongsTo(Sidebar::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Sidebar::class, 'parent_id')->orderBy('order');
    }

    public function userRoleSidebars()
    {
        return $this->hasMany(UserRoleSidebar::class, 'sidebar_id');
    }
}
