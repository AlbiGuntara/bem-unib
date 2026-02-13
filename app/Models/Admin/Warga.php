<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'wargas';

    protected $fillable = [
        'foto',
        'nama',
        'nis',
        'asrama',
        'pendidikan_pagi',
        'no_induk_pendidikan_pagi',
        'kelas_pendidikan_pagi',
        'pendidikan_sore',
        'no_induk_pendidikan_sore',
        'kelas_pendidikan_sore',
        'pendidikan_malam',
        'no_induk_pendidikan_malam',
        'kelas_pendidikan_malam',
        'wali',
        'no_telp_wali',
        'email',
        'ig',
        'wa',
        'kampung',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'latitude',
        'longitude',
        'status',
        'tahun_mondok',
        'tahun_lulus',
    ];

    public function prestasis()
    {
        return $this->hasMany(Prestasi::class, 'id_warga');
    }

    public function skorPendidikans()
    {
        return $this->hasMany(SkorPendidikan::class, 'id_warga');
    }

    public function getTotalSkorAttribute()
    {
        return $this->presensis()->sum('skor');
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'id_warga');
    }

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class, 'id_warga');
    }
}
