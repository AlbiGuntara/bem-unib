<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class HutangPiutang extends Model
{
    protected $table = 'hutang_piutang';

    protected $fillable = [
        'jenis',
        'tanggal',
        'nama',
        'foto',
        'kontak',
        'nominal',
        'keterangan',
        'jatuh_tempo',
        'bukti_transaksi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function pembayarans()
    {
        return $this->hasMany(HutangPiutangPembayaran::class);
    }
}
