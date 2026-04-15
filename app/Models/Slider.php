<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_url',
        'mobile_image_url',
        'link_url',
        'link_target',
        'button_text',
        'button_class',
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
              ->orWhere('subtitle', 'like', "%{$search}%")
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

    public function getAnimationClass()
    {
        return $this->settings['animation'] ?? 'fade';
    }

    public function getAnimationDelay()
    {
        return $this->settings['delay'] ?? 0;
    }

    public function getAnimationDuration()
    {
        return $this->settings['duration'] ?? 1000;
    }

    /**
     * Static methods
     */
    public static function getActiveSliders($position = null, $language = 'vi', $limit = null)
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
            'home' => 'Trang chủ',
            'about' => 'Giới thiệu',
            'services' => 'Dịch vụ',
            'news' => 'Tin tức',
            'contact' => 'Liên hệ',
            'library' => 'Thư viện',
            'opac' => 'Tra cứu OPAC',
            'patrons' => 'Bạn đọc',
            'events' => 'Sự kiện',
        ];
    }

    public static function getAvailableAnimations()
    {
        return [
            'fade' => 'Fade In',
            'slide-left' => 'Slide from Left',
            'slide-right' => 'Slide from Right',
            'slide-up' => 'Slide from Up',
            'slide-down' => 'Slide from Down',
            'zoom-in' => 'Zoom In',
            'zoom-out' => 'Zoom Out',
            'rotate' => 'Rotate',
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

        static::creating(function ($slider) {
            if (empty($slider->created_by) && auth()->check()) {
                $slider->created_by = auth()->id();
            }
        });

        // Auto-expire sliders
        static::addGlobalScope('not_expired', function (Builder $builder) {
            $builder->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>', now());
            });
        });
    }
}
