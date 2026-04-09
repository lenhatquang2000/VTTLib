<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WebsiteAccess extends Model
{
    protected $fillable = [
        'access_time',
        'ip_address',
        'user_agent',
        'page_url',
        'session_id',
        'user_id',
        'access_type',
        'device_type',
        'browser',
        'platform',
        'referrer'
    ];

    protected $casts = [
        'access_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for today's accesses
     */
    public function scopeForToday($query)
    {
        return $query->whereDate('access_time', Carbon::today());
    }

    /**
     * Scope for date range
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('access_time', [$startDate, $endDate]);
    }

    /**
     * Scope by access type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('access_type', $type);
    }

    /**
     * Scope by device type
     */
    public function scopeByDevice($query, $device)
    {
        return $query->where('device_type', $device);
    }

    /**
     * Scope unique sessions
     */
    public function scopeUniqueSessions($query)
    {
        return $query->distinct('session_id');
    }

    /**
     * Get browser name from user agent
     */
    public function getBrowserName()
    {
        if (!$this->user_agent) return 'Unknown';
        
        if (strpos($this->user_agent, 'Chrome') !== false) return 'Chrome';
        if (strpos($this->user_agent, 'Firefox') !== false) return 'Firefox';
        if (strpos($this->user_agent, 'Safari') !== false) return 'Safari';
        if (strpos($this->user_agent, 'Edge') !== false) return 'Edge';
        
        return 'Other';
    }

    /**
     * Get device type from user agent
     */
    public function getDeviceType()
    {
        if (!$this->user_agent) return 'Unknown';
        
        if (strpos($this->user_agent, 'Mobile') !== false) return 'Mobile';
        if (strpos($this->user_agent, 'Tablet') !== false) return 'Tablet';
        
        return 'Desktop';
    }
}
