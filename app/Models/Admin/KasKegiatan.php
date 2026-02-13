<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasKegiatan extends Model
{
    use HasFactory;

    protected $table = 'kas_kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'tanggal_pelaksanaan',
        'tanggal',
        'uraian',
        'jenis',
        'kategori_id',
        'nominal',
        'bukti_transaksi',
    ];

    public function kategori()
    {
        return $this->belongsTo(KasKategori::class);
    }

    public function kasUmum()
    {
        return $this->hasOne(KasUmum::class, 'kas_kegiatan_id');
    }
}
