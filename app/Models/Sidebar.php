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

    /**
     * Get localized name
     */
    public function getLocalizedNameAttribute()
    {
        // If name contains a dot, treat as translation key
        if (strpos($this->name, '.') !== false) {
            return __($this->name);
        }
        
        // Otherwise, check if there's a translation for this name
        $translationKey = 'sidebar.' . str_replace([' ', '-'], '_', strtolower($this->name));
        $translated = __($translationKey);
        
        // If translation exists and is different from key, return it
        if ($translated !== $translationKey) {
            return $translated;
        }
        
        // Return original name as fallback
        return $this->name;
    }

    /**
     * Get display name with localization
     */
    public function getDisplayNameAttribute()
    {
        return $this->getLocalizedNameAttribute();
    }
}
