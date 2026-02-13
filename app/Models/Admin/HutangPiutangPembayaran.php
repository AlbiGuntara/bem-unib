<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class HutangPiutangPembayaran extends Model
{
    protected $table = 'hutang_piutang_pembayaran';

    protected $fillable = ['hutang_piutang_id', 'tanggal', 'nominal'];

    public function hutangPiutang()
    {
        return $this->belongsTo(HutangPiutang::class);
    }
}
