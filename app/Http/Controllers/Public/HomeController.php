<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Admin\Dokumentasi;
use App\Models\Admin\Pengurus;
use App\Models\Admin\Periode;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $latestPeriode = Periode::latest('tahun_mulai')->first();

        // Jika belum ada periode
        if (! $latestPeriode) {
            return Inertia::render('Public/Home', [
                'strukturPengurus' => [],
                'periode' => null,
                'documentation' => [],
            ]);
        }

        $pengurus = Pengurus::with(['warga', 'jabatan'])
            ->where('id_periode', $latestPeriode->id)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->warga->nama ?? '',
                    'iksass' => $item->jabatan->IKSASS ?? '',
                    'role' => $item->jabatan->nama_jabatan ?? '',
                    'photo' => $item->warga->foto
                        ? asset('storage/'.$item->warga->foto)
                        : null,
                ];
            });

        $dokumentasi = Dokumentasi::with('programKerja')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($doc) {
                return [
                    'title' => $doc->programKerja->nama_kegiatan ?? 'Kegiatan',
                    'date' => $doc->programKerja?->tanggal_mulai
                        ? date('d M Y', strtotime($doc->programKerja->tanggal_mulai))
                        : '',
                    'description' => $doc->deskripsi
                        ?? $doc->programKerja->deskripsi
                        ?? '',
                    'photo' => $doc->foto
                        ? asset('storage/'.$doc->foto)
                        : null,
                    'link' => $doc->link,
                ];
            });

        return Inertia::render('Public/Home', [
            'strukturPengurus' => $pengurus,
            'periode' => $latestPeriode->periode,
            'documentation' => $dokumentasi,
        ]);
    }
}
