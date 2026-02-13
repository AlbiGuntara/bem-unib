<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Admin\Dokumentasi;
use App\Models\Admin\ProgramKerja;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProkerController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramKerja::with(['jabatan', 'dokumentasis', 'presensis']);

        // SEARCH
        if ($request->search) {
            $query->where('nama_kegiatan', 'like', "%{$request->search}%");
        }

        // FILTER STATUS
        if ($request->status) {
            $today = now()->toDateString();

            if ($request->status === 'akan-datang') {
                $query->whereDate('tanggal_mulai', '>', $today);
            }

            if ($request->status === 'berlangsung') {
                $query->whereDate('tanggal_mulai', '<=', $today)
                    ->whereDate('tanggal_selesai', '>=', $today);
            }

            if ($request->status === 'selesai') {
                $query->whereDate('tanggal_selesai', '<', $today);
            }
        }

        $programs = $query
            ->orderBy('tanggal_mulai', 'asc')
            ->paginate(6)
            ->through(function ($item) {
                $today = now();

                if ($today->lt($item->tanggal_mulai)) {
                    $status = 'Akan Datang';
                    $color = 'blue';
                } elseif ($today->gt($item->tanggal_selesai)) {
                    $status = 'Selesai';
                    $color = 'gray';
                } else {
                    $status = 'Berlangsung';
                    $color = 'green';
                }

                return [
                    'id' => $item->id,
                    'nama' => $item->nama_kegiatan,
                    'tujuan' => $item->tujuan,
                    'deskripsi' => $item->deskripsi,
                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_selesai' => $item->tanggal_selesai,
                    'jabatan' => $item->jabatan?->nama_jabatan,
                    'peserta' => $item->peserta,
                    'anggaran' => number_format($item->anggaran, 0, ',', '.'),
                    'status' => $status,
                    'status_color' => $color,
                    'dokumentasi_count' => $item->dokumentasis->count(),
                    'presensi_count' => $item->presensis->count(),
                ];
            });

        return Inertia::render('Public/ProgramKerja', [
            'programs' => $programs,
            'filters' => $request->only(['search', 'status']),
            'stats' => [
                'total' => ProgramKerja::count(),
                'aktif' => ProgramKerja::whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_selesai', '>=', now())->count(),
                'selesai' => ProgramKerja::whereDate('tanggal_selesai', '<', now())->count(),
                'dokumentasi' => Dokumentasi::count(),
            ],
        ]);
    }
}
