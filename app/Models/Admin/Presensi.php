<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $fillable = [
        'id_warga',
        'id_program_kerja',
        'keterangan',
        'skor',
        'pengurusan',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'id_warga');
    }

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_program_kerja');
    }
}
