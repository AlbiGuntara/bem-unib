<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SkorPendidikan;
use App\Models\Admin\Warga;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;


class SkorPendidikanController extends Controller
{
    public function index(Request $request)
    {
        $query = SkorPendidikan::query()
            ->with('warga')
            ->leftJoin('wargas', 'skor_pendidikans.id_warga', '=', 'wargas.id')
            ->select(
                'skor_pendidikans.*',
                'wargas.nama',
                'wargas.nis',
                'wargas.pendidikan_pagi',
                'wargas.pendidikan_sore',
                'wargas.pendidikan_malam',
                'wargas.kelas_pendidikan_pagi',
                'wargas.kelas_pendidikan_sore',
                'wargas.kelas_pendidikan_malam'
            );


        $bulanList = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $monthlyAverage = [];

        foreach ($bulanList as $bulan) {
            $avg = SkorPendidikan::where('bulan', $bulan)->avg('jumlah');
            $monthlyAverage[$bulan] = round($avg ?: 0, 1);
        }


        for ($i = 1; $i <= 12; $i++) {
            $namaBulan = Carbon::create()->month($i)->translatedFormat('F');

            $avg = SkorPendidikan::where('bulan', $namaBulan)->avg('jumlah');
            $monthlyAverage[$namaBulan] = round($avg ?: 0, 1);
        }

        // SEARCH
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('wargas.nama', 'like', "%{$search}%")
                    ->orWhere('wargas.nis', 'like', "%{$search}%")
                    ->orWhere('skor_pendidikans.bulan', 'like', "%{$search}%");
            });
        }

        // FILTER LEMBAGA
        if ($request->lembaga) {
            $query->where(function ($q) use ($request) {
                $q->where('wargas.pendidikan_pagi', $request->lembaga)
                    ->orWhere('wargas.pendidikan_sore', $request->lembaga)
                    ->orWhere('wargas.pendidikan_malam', $request->lembaga);
            });
        }

        // FILTER BULAN
        if ($request->bulan) {
            $query->where('skor_pendidikans.bulan', $request->bulan);
        }

        // FILTER MIN SCORE (jumlah >= X)
        if ($request->min_score) {
            $query->where('skor_pendidikans.jumlah', '>=', $request->min_score);
        }

        // SORTING
        $sortableColumns = [
            'nama' => 'wargas.nama',
            'nis' => 'wargas.nis',
            'lembaga' => 'wargas.pendidikan_pagi',
            'kelas' => 'wargas.kelas_pendidikan_pagi',
            'jumlah' => 'skor_pendidikans.jumlah',
            'bulan' => 'skor_pendidikans.bulan',
        ];

        $sortBy = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction', 'asc');

        if ($sortBy && isset($sortableColumns[$sortBy])) {
            $query->orderBy($sortableColumns[$sortBy], $sortDirection);
        } else {
            $query->latest('skor_pendidikans.created_at');
        }

        // PAGINATION
        $perPage = $request->get('per_page', 10);
        $skor = $query->paginate($perPage)->withQueryString();

        // DATA WARGA
        $warga = Warga::select(
            'id',
            'nama',
            'nis',
            'foto',
            'pendidikan_pagi',
            'kelas_pendidikan_pagi',
            'pendidikan_sore',
            'kelas_pendidikan_sore',
            'pendidikan_malam',
            'kelas_pendidikan_malam'
        )->get();

        $warningTotal = SkorPendidikan::where('jumlah', '>=', 10)->count();

        $bulanDipilih = $request->bulan ?: now()->translatedFormat('F');

        $averageByMonth = SkorPendidikan::where('bulan', $bulanDipilih)->avg('jumlah');
        $averageByMonth = round($averageByMonth ?: 0, 1);


        return Inertia::render('Admin/SkorPendidikan/Index', [
            'skor' => $skor,
            'warga' => $warga,
            'monthly_average' => $monthlyAverage,
            'warning_total' => $warningTotal,
            'average_month' => $averageByMonth,
            'bulan_dipilih' => $bulanDipilih,
            'filters' => $request->only([
                'search',
                'sort_by',
                'sort_direction',
                'per_page',
                'lembaga',
                'bulan',
                'min_score',
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_warga' => 'required|exists:wargas,id',
            'jumlah' => 'required|integer|min:0',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'jenis' => 'required|in:pagi,sore,malam',
        ]);

        SkorPendidikan::create($validated);
        return back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, SkorPendidikan $skor_pendidikan)
    {
        $validated = $request->validate([
            'id_warga' => 'required|exists:wargas,id',
            'jumlah' => 'required|integer|min:0',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'jenis' => 'required|in:pagi,sore,malam',
        ]);

        $skor_pendidikan->update($validated);
        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(SkorPendidikan $skor_pendidikan)
    {
        $skor_pendidikan->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
