<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class KasKategori extends Model
{
    protected $table = 'kas_kategori';
    protected $fillable = ['kategori'];

    public function kasUmum()
    {
        return $this->hasMany(KasUmum::class, 'kategori_id');
    }
}
