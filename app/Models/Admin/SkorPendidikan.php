<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkorPendidikan extends Model
{
    use HasFactory;

    protected $table = 'skor_pendidikans';

    protected $fillable = [
        'id_warga',
        'jumlah',
        'bulan',
    ];

    // Relasi ke Warga
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'id_warga');
    }
}
