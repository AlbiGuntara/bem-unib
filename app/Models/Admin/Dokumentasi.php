<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasis';

    protected $fillable = [
        'foto',
        'id_program_kerja',
        'link',
        'deskripsi',
    ];

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_program_kerja');
    }
}
