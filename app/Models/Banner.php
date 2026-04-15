<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'link_target',
        'position',
        'sort_order',
        'status',
        'start_date',
        'end_date',
        'created_by',
        'language',
        'click_count',
        'view_count',
        'settings'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'settings' => 'array',
        'click_count' => 'integer',
        'view_count' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Relationships
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive(Builder $query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeExpired(Builder $query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeByPosition(Builder $query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeByLanguage(Builder $query, $language = 'vi')
    {
        return $query->where('language', $language);
    }

    public function scopeCurrentlyActive(Builder $query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Accessors & Mutators
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Hoạt động',
            'inactive' => 'Không hoạt động',
            'expired' => 'Hết hạn',
            default => 'Không xác định'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            'expired' => 'red',
            default => 'gray'
        };
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               $this->isWithinDateRange();
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date ? $this->start_date->format('d/m/Y H:i') : '';
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('d/m/Y H:i') : '';
    }

    /**
     * Methods
     */
    public function isWithinDateRange()
    {
        $now = now();
        
        if ($this->start_date && $this->start_date->isAfter($now)) {
            return false;
        }
        
        if ($this->end_date && $this->end_date->isBefore($now)) {
            return false;
        }
        
        return true;
    }

    public function isExpired()
    {
        return $this->end_date && $this->end_date->isBefore(now());
    }

    public function isScheduled()
    {
        return $this->start_date && $this->start_date->isAfter(now());
    }

    public function incrementView()
    {
        $this->increment('view_count');
    }

    public function incrementClick()
    {
        $this->increment('click_count');
    }

    public function getClickRateAttribute()
    {
        if ($this->view_count > 0) {
            return round(($this->click_count / $this->view_count) * 100, 2);
        }
        return 0;
    }

    public function activate()
    {
        $this->status = 'active';
        $this->save();
    }

    public function deactivate()
    {
        $this->status = 'inactive';
        $this->save();
    }

    public function expire()
    {
        $this->status = 'expired';
        $this->save();
    }

    /**
     * Static methods
     */
    public static function getActiveBanners($position = null, $language = 'vi', $limit = null)
    {
        $query = self::with('creator')
            ->currentlyActive()
            ->byLanguage($language)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc');

        if ($position) {
            $query->byPosition($position);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function getAvailablePositions()
    {
        return [
            'header' => 'Header',
            'sidebar_top' => 'Sidebar Top',
            'sidebar_middle' => 'Sidebar Middle',
            'sidebar_bottom' => 'Sidebar Bottom',
            'content_top' => 'Content Top',
            'content_middle' => 'Content Middle',
            'content_bottom' => 'Content Bottom',
            'footer' => 'Footer',
            'popup' => 'Popup',
            'home_hero' => 'Home Hero',
            'home_featured' => 'Home Featured',
        ];
    }

    public static function getStatistics()
    {
        return [
            'total' => self::count(),
            'active' => self::active()->count(),
            'inactive' => self::inactive()->count(),
            'expired' => self::expired()->count(),
            'total_clicks' => self::sum('click_count'),
            'total_views' => self::sum('view_count'),
            'avg_click_rate' => self::selectRaw('AVG(click_count / NULLIF(view_count, 0)) * 100 as avg_rate')->value('avg_rate') ?? 0,
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($banner) {
            if (empty($banner->created_by) && auth()->check()) {
                $banner->created_by = auth()->id();
            }
        });

        // Auto-expire banners
        static::addGlobalScope('not_expired', function (Builder $builder) {
            $builder->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>', now());
            });
        });
    }
}
