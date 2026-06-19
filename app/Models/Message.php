<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'name',
        'nim',
        'email',
        'category',
        'subject',
        'content',
        'status',
        'attachment',
        'reply_content',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];
}
