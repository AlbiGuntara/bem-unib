<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'address',
        'instagram_url',
        'tiktok_url',
        'youtube_url',
        'facebook_url',
        'maps_embed',
    ];
}
