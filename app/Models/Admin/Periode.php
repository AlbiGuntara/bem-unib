<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = ['periode', 'tahun_mulai', 'tahun_selesai'];

    public function kasUmum()
    {
        return $this->hasMany(KasUmum::class, 'periode_id');
    }

    public static function aktif()
    {
        return static::orderByDesc('tahun_selesai')
            ->orderByDesc('tahun_mulai')
            ->firstOrFail();
    }

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class, 'id_periode');
    }
}
