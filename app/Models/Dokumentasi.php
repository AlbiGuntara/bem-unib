<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $table = 'documentasis';

    protected $fillable = [
        'program_kerjas_id',
        'link_drive',
        'thumbnail_portrait',
        'thumbnail_landscape',
    ];

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'program_kerjas_id');
    }
}