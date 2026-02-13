<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Prestasi;
use App\Models\Admin\ProgramKerja;
use App\Models\Admin\Warga;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $totalWarga = Warga::count();
        $totalSantri = Warga::where('status', 'Santri')->count();
        $totalAlumni = Warga::where('status', 'Alumni')->count();
        $totalPrestasi = Prestasi::count();

        // Program Kerja Terdekat
        $today = Carbon::today();

        $programTerdekat = ProgramKerja::whereDate('tanggal_mulai', '>=', $today)
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        if (! $programTerdekat) {
            $programTerdekat = ProgramKerja::orderBy('tanggal_mulai', 'desc')->first();
        }

        // 3 Prestasi Terbaru
        $recentPrestasi = Prestasi::with('warga')
            ->orderBy('tanggal', 'desc')
            ->take(3)
            ->get()
            ->map(function ($p) {
                return [
                    'title' => $p->prestasi,
                    'person' => $p->warga ? $p->warga->nama : '-',
                    'level' => $p->tingkat,
                    'date' => $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->diffForHumans() : '-',
                ];
            });

        // Trend Pertumbuhan 10 Tahun Terakhir
        $currentYear = Carbon::now()->year;
        $years = collect(range($currentYear - 9, $currentYear));

        $santriTrend = [];
        $alumniTrend = [];

        foreach ($years as $year) {
            $santriCount = Warga::where('status', 'Santri')
                ->where('tahun_mondok', $year)
                ->count();
            $santriTrend[] = $santriCount;

            $alumniCount = Warga::where('status', 'Alumni')
                ->where('tahun_lulus', $year)
                ->count();
            $alumniTrend[] = $alumniCount;
        }

        // Program kerja untuk frontend
        $formattedProgram = $programTerdekat ? [
            'id' => $programTerdekat->id,
            'nama_kegiatan' => $programTerdekat->nama_kegiatan,
            'tujuan' => $programTerdekat->tujuan,
            'tanggal_mulai' => $programTerdekat->tanggal_mulai,
            'tanggal_selesai' => $programTerdekat->tanggal_selesai,
            'lokasi' => $programTerdekat->lokasi,
            'penanggung_jawab' => $programTerdekat->penanggung_jawab,
        ] : null;

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'warga' => $totalWarga,
                'santri' => $totalSantri,
                'alumni' => $totalAlumni,
                'prestasi' => $totalPrestasi,
            ],
            'programTerdekat' => $formattedProgram,
            'recentPrestasi' => $recentPrestasi,
            'trendData' => [
                'years' => $years->values()->toArray(),
                'santri' => $santriTrend,
                'alumni' => $alumniTrend,
            ],
        ]);
    }
}
