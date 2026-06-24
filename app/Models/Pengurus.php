<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    protected $table = 'pengurus';

    protected $fillable = [
        'department_id',
        'name',
        'npm',
        'email',
        'position',
        'position_type',
        'photo',
        'instagram',
        'facebook',
        'whatsapp',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}