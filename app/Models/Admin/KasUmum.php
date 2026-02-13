<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class KasUmum extends Model
{
    protected $table = 'kas_umum';

    protected $fillable = [
        'periode_id',
        'tanggal',
        'uraian',
        'jenis',
        'kategori_id',
        'nominal',
        'bukti_transaksi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KasKategori::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(KasKegiatan::class, 'kas_kegiatan_id');
    }

    public function getNominalSignedAttribute()
    {
        return $this->jenis === 'Debet'
            ? $this->nominal
            : -$this->nominal;
    }

    public function source()
    {
        return $this->morphTo();
    }
}
