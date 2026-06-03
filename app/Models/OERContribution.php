<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OERContribution extends Model
{
    protected $table = 'oer_contributions';

    protected $fillable = [
        'full_name',
        'contact_info',
        'license',
        'additional_info',
        'file_path',
        'file_name',
        'status'
    ];
}
