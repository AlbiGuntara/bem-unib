<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kolaborasi extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'type',
        'url',
        'order',
        'is_active',
    ];
}
