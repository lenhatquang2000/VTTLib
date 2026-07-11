<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportHistory extends Model
{
    protected $fillable = [
        'user_id',
        'report_type',
        'title',
        'filename',
        'format',
        'file_path',
        'status',
        'error_message',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
