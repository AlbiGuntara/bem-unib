<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $table = 'program_kerjas';

    protected $fillable = [
        'nama_kegiatan',
        'tujuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jabatan_id',
        'peserta',
        'anggaran',
        'deskripsi',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'id_program_kerja');
    }

    public function dokumentasis()
    {
        return $this->hasMany(Dokumentasi::class, 'id_program_kerja');
    }

    // generate presensi untuk semua santri
    protected static function booted()
    {
        static::created(function ($programKerja) {
            $santris = Warga::where('status', 'Santri')->get();

            foreach ($santris as $santri) {
                Presensi::create([
                    'id_warga' => $santri->id,
                    'id_program_kerja' => $programKerja->id,
                    'keterangan' => 'alpha',
                    'skor' => 0,
                    'pengurusan' => 'belum',
                ]);
            }
        });
    }
}
