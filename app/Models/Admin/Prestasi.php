<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasis';

    protected $fillable = [
        'id_warga',
        'prestasi',
        'kategori',
        'tingkat',
        'tempat',
        'penyelenggara',
        'tanggal',
        'deskripsi',
        'dokumentasi',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'id_warga');
    }
}
