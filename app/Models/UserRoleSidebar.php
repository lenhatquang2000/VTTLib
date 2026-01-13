<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoleSidebar extends Model
{
    protected $fillable = ['role_user_id', 'sidebar_id'];

    public function sidebar()
    {
        return $this->belongsTo(Sidebar::class);
    }

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, 'role_user_id');
    }
}
