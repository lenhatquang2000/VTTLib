<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SiteNodeItem extends Model
{
    protected $fillable = [
        'site_node_id',
        'item_type',
        'item_data',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'item_data' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relationships
     */
    public function siteNode()
    {
        return $this->belongsTo(SiteNode::class);
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get item data attribute with default values
     */
    public function getDataAttribute($key, $default = null)
    {
        return data_get($this->item_data, $key, $default);
    }

    /**
     * Available item types
     */
    public static function getAvailableTypes()
    {
        return [
            'hero' => 'Hero Section',
            'features' => 'Features Grid',
            'text' => 'Text Block',
            'image' => 'Image',
            'button' => 'Button',
            'gallery' => 'Image Gallery',
            'testimonial' => 'Testimonial',
            'contact' => 'Contact Form',
            'divider' => 'Divider'
        ];
    }
}
