<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookProposal extends Model
{
    protected $fillable = [
        'user_id',
        'fullname',
        'email_phone',
        'book_title',
        'author',
        'publisher_year',
        'quantity',
        'reason',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
