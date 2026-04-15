<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SiteNode extends Model
{
    protected $fillable = [
        'node_code',
        'node_name',
        'display_name',
        'description',
        'parent_id',
        'icon',
        'masterpage',
        'display_type',
        'target',
        'is_active',
        'access_type',
        'allowed_roles',
        'allow_guest',
        'content',
        'route_name',
        'url',
        'sort_order',
        'language',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'allowed_roles' => 'array',
        'content' => 'string',
        'is_active' => 'boolean',
        'allow_guest' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relationships
     */
    public function parent()
    {
        return $this->belongsTo(SiteNode::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SiteNode::class, 'parent_id')
            ->orderBy('sort_order')
            ->orderBy('display_name');
    }

    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot(Builder $query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeByLanguage(Builder $query, $language = 'vi')
    {
        return $query->where('language', $language);
    }

    public function scopeByDisplayType(Builder $query, $type)
    {
        return $query->where('display_type', $type);
    }

    public function scopeAccessible(Builder $query, $user = null)
    {
        $query->where(function ($q) use ($user) {
            $q->where('access_type', 'public')
              ->orWhere('allow_guest', true);
              
            if ($user) {
                $q->orWhere('access_type', 'auth')
                  ->orWhere(function ($subQ) use ($user) {
                      $subQ->where('access_type', 'roles')
                           ->whereJsonContains('allowed_roles', $user->role ?? []);
                  });
            }
        });
        
        return $query;
    }

    /**
     * Get full URL for this node
     */
    public function getUrl()
    {
        if ($this->route_name) {
            return route($this->route_name);
        }
        
        if ($this->url) {
            return $this->url;
        }
        
        return '/page/' . $this->node_code;
    }

    /**
     * Check if current user can access this node
     */
    public function canAccess($user = null)
    {
        // Public access
        if ($this->access_type === 'public' || $this->allow_guest) {
            return true;
        }

        // Require authentication
        if ($this->access_type === 'auth' && !$user) {
            return false;
        }

        // Authenticated user can access
        if ($this->access_type === 'auth' && $user) {
            return true;
        }

        // Role-based access
        if ($this->access_type === 'roles' && $user) {
            $userRoles = is_array($user->role) ? $user->role : [$user->role];
            $allowedRoles = $this->allowed_roles ?? [];
            
            return !empty(array_intersect($userRoles, $allowedRoles));
        }

        return false;
    }

    /**
     * Get breadcrumb trail
     */
    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $current = $this;
        
        while ($current) {
            array_unshift($breadcrumb, [
                'name' => $current->display_name,
                'url' => $current->getUrl()
            ]);
            $current = $current->parent;
        }
        
        return $breadcrumb;
    }

    /**
     * Get tree structure as array
     */
    public static function getTree($language = 'vi', $user = null)
    {
        return self::with(['activeChildren' => function ($query) use ($user) {
                $query->accessible($user);
            }])
            ->active()
            ->root()
            ->byLanguage($language)
            ->accessible($user)
            ->orderBy('sort_order')
            ->orderBy('display_name')
            ->get()
            ->toArray();
    }

    /**
     * Get menu items for specific display type
     */
    public static function getMenuItems($displayType = 'menu', $language = 'vi', $user = null)
    {
        return self::with(['activeChildren' => function ($query) use ($user, $displayType) {
                $query->accessible($user)->byDisplayType($displayType);
            }])
            ->active()
            ->byDisplayType($displayType)
            ->byLanguage($language)
            ->accessible($user)
            ->orderBy('sort_order')
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Get page by node code
     */
    public static function getByCode($code, $language = 'vi')
    {
        return self::where('node_code', $code)
            ->byLanguage($language)
            ->active()
            ->first();
    }

    /**
     * Get siblings (nodes with same parent)
     */
    public function getSiblings()
    {
        return self::where('parent_id', $this->parent_id)
            ->where('id', '!=', $this->id)
            ->active()
            ->orderBy('sort_order')
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Get level depth
     */
    public function getDepth()
    {
        $depth = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        
        return $depth;
    }

    /**
     * Check if node has content
     */
    public function hasContent()
    {
        return !empty($this->content) && !$this->route_name && !$this->url;
    }

    /**
     * Check if node is external link
     */
    public function isExternal()
    {
        return $this->url && str_starts_with($this->url, 'http');
    }

    /**
     * Get display name with icon
     */
    public function getDisplayNameWithIcon()
    {
        $icon = $this->icon ? "<i class='{$this->icon}'></i> " : '';
        return $icon . $this->display_name;
    }
}
