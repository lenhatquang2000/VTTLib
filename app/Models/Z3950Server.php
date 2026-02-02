<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Z3950Server extends Model
{
    use HasFactory;

    protected $table = 'z3950_servers';

    protected $fillable = [
        'name',
        'host',
        'port',
        'database_name',
        'username',
        'password',
        'charset',
        'record_syntax',
        'description',
        'is_active',
        'use_ssl',
        'timeout',
        'max_records',
        'order',
        'last_connected_at',
        'last_status',
        'last_error',
    ];

    protected $casts = [
        'port' => 'integer',
        'is_active' => 'boolean',
        'use_ssl' => 'boolean',
        'timeout' => 'integer',
        'max_records' => 'integer',
        'order' => 'integer',
        'last_connected_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getConnectionStringAttribute(): string
    {
        return "{$this->host}:{$this->port}/{$this->database_name}";
    }

    public function updateStatus(string $status, ?string $error = null): void
    {
        $this->update([
            'last_connected_at' => now(),
            'last_status' => $status,
            'last_error' => $error,
        ]);
    }
}
