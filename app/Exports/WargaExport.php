<?php

namespace App\Exports;

use App\Models\Admin\Warga;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WargaExport implements FromCollection, WithHeadings
{
    protected $search;

    protected $sortBy;

    protected $sortDirection;

    public function __construct($search = null, $sortBy = null, $sortDirection = 'asc')
    {
        $this->search = $search;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
    }

    public function collection()
    {
        $query = Warga::query();

        if ($this->search) {
            $query->where('nama', 'like', "%{$this->search}%")
                ->orWhere('nis', 'like', "%{$this->search}%")
                ->orWhere('asrama', 'like', "%{$this->search}%")
                ->orWhere('status', 'like', "%{$this->search}%")
                ->orWhere('wali', 'like', "%{$this->search}%");
        }

        if ($this->sortBy) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->get([
            'nama',
            'nis',
            'asrama',
            'pendidikan_pagi',
            'no_induk_pendidikan_pagi',
            'kelas_pendidikan_pagi',
            'pendidikan_sore',
            'no_induk_pendidikan_sore',
            'kelas_pendidikan_sore',
            'pendidikan_malam',
            'no_induk_pendidikan_malam',
            'kelas_pendidikan_malam',
            'wali',
            'no_telp_wali',
            'kampung',
            'rt',
            'rw',
            'desa',
            'kecamatan',
            'kabupaten',
            'provinsi',
            'kode_pos',
            'status',
            'jabatan',
            'tahun_mondok',
            'tahun_lulus',
        ]);
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIS',
            'Asrama',
            'Pendidikan Pagi',
            'No Induk Pagi',
            'Kelas Pagi',
            'Pendidikan Sore',
            'No Induk Sore',
            'Kelas Sore',
            'Pendidikan Malam',
            'No Induk Malam',
            'Kelas Malam',
            'Wali',
            'No Telp Wali',
            'Kampung',
            'RT',
            'RW',
            'Desa',
            'Kecamatan',
            'Kabupaten',
            'Provinsi',
            'Kode Pos',
            'Status',
            'Jabatan',
            'Tahun Mondok',
            'Tahun Lulus', ];
    }
}
