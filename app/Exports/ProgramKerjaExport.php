<?php

namespace App\Exports;

use App\Models\Admin\ProgramKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProgramKerjaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected int $row = 0;

    public function collection()
    {
        return ProgramKerja::with('jabatan')
            ->orderBy('tanggal_mulai')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kegiatan',
            'Penanggung Jawab',
            'Tanggal Pelaksanaan',
            'Peserta',
            'Anggaran',
            'Deskripsi',
        ];
    }

    public function map($item): array
    {
        $this->row++;

        $tanggal = '-';
        if ($item->tanggal_mulai && $item->tanggal_selesai) {
            $tanggal = $item->tanggal_mulai === $item->tanggal_selesai
                ? $item->tanggal_mulai
                : $item->tanggal_mulai.' - '.$item->tanggal_selesai;
        } elseif ($item->tanggal_mulai) {
            $tanggal = $item->tanggal_mulai;
        }

        return [
            $this->row,
            $item->nama_kegiatan,
            optional($item->jabatan)->departemen,
            $tanggal,
            $item->peserta,
            $item->anggaran,
            $item->deskripsi,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => 'center',
                ],
            ],
        ];
    }
}
