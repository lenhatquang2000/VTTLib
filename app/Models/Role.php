<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'display_name'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function sidebars()
    {
        return $this->belongsToMany(Sidebar::class, 'role_sidebars');
    }
}
