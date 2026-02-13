<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KasUmumExport;
use App\Http\Controllers\Controller;
use App\Models\Admin\KasKategori;
use App\Models\Admin\KasUmum;
use App\Models\Admin\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class KasUmumController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'tanggal');
        $sortDirection = $request->get('sort_direction');
        $perPage = $request->get('per_page', 10);

        $kategori_paginated = KasKategori::withCount('kasumum')
            ->orderBy('id', 'desc')
            ->paginate(5);

        $jenis = $request->get('jenis');

        // QUERY UTAMA
        $kategori_paginated = KasKategori::withCount('kasumum')
            ->orderBy('id', 'desc')
            ->paginate(5, ['*'], 'kategori_page');

        $kas = KasUmum::with(['periode', 'kategori'])
            ->when($search, function ($q) use ($search) {
                $q->where('uraian', 'like', "%$search%")
                    ->orWhereHas('kategori', fn ($k) => $k->where('kategori', 'like', "%$search%")
                    );
            })
            ->when($jenis, fn ($q) => $q->where('jenis', $jenis))
            ->when(in_array($sortDirection, ['asc', 'desc']), fn ($q) => $q->orderBy($sortBy, $sortDirection)
            )
            ->paginate($perPage, ['*'], 'kas_page')
            ->withQueryString();

        // TOTAL GLOBAL (TIDAK TERIKAT FILTER)
        $total_kredit = KasUmum::where('jenis', 'Kredit')->sum('nominal');
        $total_debet = KasUmum::where('jenis', 'Debet')->sum('nominal');
        $total_saldo = KasUmum::sum(DB::raw("CASE WHEN jenis='Debet' THEN nominal ELSE -nominal END"));

        // DATA UNTUK CHART
        $monthlyDebet = KasUmum::where('jenis', 'Debet')
            ->where('tanggal', '>=', now()->subDays(30))
            ->orderBy('tanggal')
            ->select('tanggal', 'nominal')
            ->get();

        $monthlyKredit = KasUmum::where('jenis', 'Kredit')
            ->where('tanggal', '>=', now()->subDays(30))
            ->orderBy('tanggal')
            ->select('tanggal', 'nominal')
            ->get();

        return Inertia::render('Admin/Keuangan/Index', [
            'kas' => $kas,
            'periodeList' => Periode::orderBy('tahun_mulai', 'desc')
                ->orderBy('tahun_selesai', 'desc')
                ->get(),
            'monthlyTransactions' => [
                'debet' => $monthlyDebet,
                'kredit' => $monthlyKredit,
            ],
            'kategoriPaginated' => $kategori_paginated,
            'totalGlobal' => [
                'kredit' => (int) $total_kredit,
                'debet' => (int) $total_debet,
                'saldo' => (int) $total_saldo,
            ],
            'filters' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
                'per_page' => $perPage,
                'jenis' => $jenis,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Keuangan/Create', [
            'periodeList' => Periode::orderBy('tahun_mulai', 'desc')
                ->orderBy('tahun_selesai', 'desc')
                ->get(),
            'kategoriList' => KasKategori::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'periode_id' => 'required|exists:periode,id',
            'tanggal' => 'required|date',
            'uraian' => 'required|string',
            'jenis' => 'required|in:Debet,Kredit',
            'kategori_id' => 'required|exists:kas_kategori,id',
            'nominal' => 'required|numeric',
            'bukti_transaksi' => 'nullable',
        ]);

        if ($request->hasFile('bukti_transaksi')) {
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti_kas', 'public');
        }

        KasUmum::create($data);

        return redirect()->route('kas-umum.index')->with('success', 'Data kas berhasil ditambahkan');
    }

    public function edit(KasUmum $kas_umum)
    {
        if ($kas_umum->kas_kegiatan_id) {
            abort(403, 'Transaksi kegiatan tidak dapat diedit dari Kas Umum');
        }

        return inertia('Admin/Keuangan/Edit', [
            'kas' => $kas_umum,
            'periodeList' => Periode::orderBy('tahun_mulai', 'desc')->get(),
            'kategoriList' => KasKategori::orderBy('kategori')->get(),
        ]);
    }

    public function update(Request $request, KasUmum $kas)
    {
        $data = $request->validate([
            'periode_id' => 'required|exists:periode,id',
            'tanggal' => 'required|date',
            'uraian' => 'required|string',
            'jenis' => 'required|in:Debet,Kredit',
            'kategori_id' => 'required|exists:kas_kategori,id',
            'nominal' => 'required|numeric',
            'bukti_transaksi' => 'nullable',
        ]);

        if ($request->hasFile('bukti_transaksi')) {
            if ($kas->bukti_transaksi) {
                Storage::disk('public')->delete($kas->bukti_transaksi);
            }
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti_kas', 'public');
        }

        $kas->update($data);

        return redirect()->route('kas-umum.index')->with('success', 'Data kas berhasil diperbarui');
    }

    public function destroy(KasUmum $kas_umum)
    {
        if ($kas_umum->bukti_transaksi) {
            Storage::disk('public')->delete($kas_umum->bukti_transaksi);
        }

        $kas_umum->delete();

        return back()->with('success', 'Data kas berhasil dihapus');
    }

    private function getEnhancedChartData()
    {
        $today = now();
        $startOfMonth = $today->copy()->startOfMonth();

        // Data 30 hari terakhir dengan granularitas harian
        $monthlyDebet = KasUmum::where('jenis', 'Debet')
            ->where('tanggal', '>=', $today->copy()->subDays(30))
            ->selectRaw('DATE(tanggal) as date, SUM(nominal) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->total];
            });

        $monthlyKredit = KasUmum::where('jenis', 'Kredit')
            ->where('tanggal', '>=', $today->copy()->subDays(30))
            ->selectRaw('DATE(tanggal) as date, SUM(nominal) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->total];
            });

        // Fill missing dates
        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $dates[] = $today->copy()->subDays($i)->format('Y-m-d');
        }

        $debetData = [];
        $kreditData = [];

        foreach ($dates as $date) {
            $debetData[] = [
                'tanggal' => $date,
                'nominal' => $monthlyDebet[$date] ?? 0,
            ];

            $kreditData[] = [
                'tanggal' => $date,
                'nominal' => $monthlyKredit[$date] ?? 0,
            ];
        }

        return [
            'debet' => $debetData,
            'kredit' => $kreditData,
        ];
    }

    // Export to Excel
    public function export(Request $request)
    {
        return Excel::download(
            new KasUmumExport($request),
            'kas-umum-'.now()->format('Y-m-d').'.xlsx'
        );
    }
}
