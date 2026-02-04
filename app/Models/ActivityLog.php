<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'method',
        'url',
        'status_code',
        'request_data',
        'model_type',
        'model_id',
        'details',
        'ip_address'
    ];

    protected $casts = [
        'details' => 'array',
        'request_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $model = null, $details = [])
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'status_code' => null, // Will be filled by middleware if any
            'request_data' => request()->except(['password', 'password_confirmation']),
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'details' => $details,
            'ip_address' => request()->ip()
        ]);
    }
}
