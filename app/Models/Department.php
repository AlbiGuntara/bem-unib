<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'logo_path',
    ];

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class, 'departemen_id');
    }

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class);
    }
}
