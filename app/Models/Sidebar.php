<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    protected $fillable = ['parent_id', 'name', 'name_vi', 'name_en', 'route_name', 'icon', 'order', 'is_active'];

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

    /**
     * Get display name with localization
     */
    public function getDisplayNameAttribute()
    {
        $locale = app()->getLocale();

        // Try locale-specific column first
        if ($locale === 'vi' && !empty($this->name_vi)) {
            return $this->name_vi;
        }

        if ($locale === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }

        // Fallback to original name
        return $this->name;
    }
}
