<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $fillable = [
        'no_surat',
        'asal',
        'perihal',
        'tanggal',
        'foto',
    ];
}
