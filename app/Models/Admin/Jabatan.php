<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans';

    protected $fillable = [
        'IKSASS',
        'nama_jabatan',
        'departemen',
    ];

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class, 'id_jabatan');
    }

    public function programKerjas()
    {
        return $this->hasMany(ProgramKerja::class);
    }
}
