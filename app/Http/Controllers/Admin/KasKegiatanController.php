<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KasKegiatanExport;
use App\Http\Controllers\Controller;
use App\Models\Admin\KasKategori;
use App\Models\Admin\KasKegiatan;
use App\Models\Admin\KasUmum;
use App\Models\Admin\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class KasKegiatanController extends Controller
{
    /** INDEX */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $start = $request->get('start');
        $end = $request->get('end');

        $kegiatan = KasKegiatan::selectRaw('
        MIN(id) as id,
        nama_kegiatan,
        MIN(tanggal_pelaksanaan) as tanggal_pelaksanaan,
        SUM(CASE WHEN jenis = "Kredit" THEN nominal ELSE 0 END) as total_pengeluaran
    ')
            ->when($search, fn ($q) => $q->where('nama_kegiatan', 'like', "%$search%"))
            ->when($start, fn ($q) => $q->whereDate('tanggal_pelaksanaan', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('tanggal_pelaksanaan', '<=', $end))
            ->groupBy('nama_kegiatan')
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();

        return inertia('Admin/Keuangan/Kegiatan/Index', [
            'kegiatan' => $kegiatan,
            'filters' => [
                'search' => $search,
                'start' => $start,
                'end' => $end,
            ],
        ]);
    }

    /** STORE */
    public function storeKegiatan(Request $request)
    {
        $data = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'nullable|date',
            'nominal' => 'required|numeric|min:1',
            'bukti_transaksi' => 'nullable|file|max:2048',
        ]);

        // Upload bukti transaksi
        $path = null;
        if ($request->hasFile('bukti_transaksi')) {
            $path = $request->file('bukti_transaksi')->store('bukti', 'public');
        }

        $kegiatan = KasKegiatan::create([
            'nama_kegiatan' => $data['nama_kegiatan'],
            'tanggal_pelaksanaan' => $data['tanggal_pelaksanaan'],
            'tanggal' => now(),
            'uraian' => 'Saldo Awal Kegiatan',
            'jenis' => 'Debet',
            'kategori_id' => 1,
            'nominal' => $data['nominal'],
            'bukti_transaksi' => $path,
        ]);

        $periode = Periode::orderBy('tahun_mulai', 'desc')->first();

        // Catat saldo keluar dari kas umum
        KasUmum::create([
            'periode_id' => $periode->id,
            'tanggal' => now(),
            'uraian' => 'Kegiatan '.$kegiatan->nama_kegiatan,
            'jenis' => 'Kredit',
            'kategori_id' => 1,
            'nominal' => $data['nominal'],
            'bukti_transaksi' => $path,
            'kas_kegiatan_id' => $kegiatan->id,
        ]);

        return back()->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function updateKegiatan(Request $request, $id)
    {
        $data = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'nullable|date',
        ]);

        KasKegiatan::where('id', $id)->update($data);

        return back()->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /** DELETE */
    public function destroy($id)
    {
        $kasKegiatan = KasKegiatan::findOrFail($id);

        if ($kasKegiatan->bukti_transaksi) {
            Storage::disk('public')->delete($kasKegiatan->bukti_transaksi);
        }

        $kasKegiatan->delete();

        return back()->with('success', 'Kas kegiatan berhasil dihapus.');
    }

    /** SHOW DETAIL */
    public function show(Request $request, $id)
    {
        $kegiatan = KasKegiatan::findOrFail($id);

        // BASE QUERY
        $query = KasKegiatan::with('kategori')
            ->leftJoin('kas_kategori', 'kas_kegiatan.kategori_id', '=', 'kas_kategori.id')
            ->where('kas_kegiatan.nama_kegiatan', $kegiatan->nama_kegiatan)
            ->select(
                'kas_kegiatan.*',
                'kas_kategori.kategori as nama_kategori'
            );

        // SEARCH
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kas_kegiatan.uraian', 'like', "%{$search}%")
                    ->orWhere('kas_kegiatan.jenis', 'like', "%{$search}%")
                    ->orWhere('kas_kegiatan.nominal', 'like', "%{$search}%")
                    ->orWhere('kas_kegiatan.tanggal', 'like', "%{$search}%");
            });
        }

        // SORTING (WHITELIST)
        $sortable = [
            'tanggal' => 'kas_kegiatan.tanggal',
            'uraian' => 'kas_kegiatan.uraian',
            'jenis' => 'kas_kegiatan.jenis',
            'nominal' => 'kas_kegiatan.nominal',
            'kategori' => 'kas_kategori.kategori',
        ];

        if ($request->sort_by && isset($sortable[$request->sort_by])) {
            $query->orderBy(
                $sortable[$request->sort_by],
                $request->sort_direction ?? 'asc'
            );
        } else {
            $query->orderBy('kas_kegiatan.tanggal', 'asc');
        }

        // PAGINATION
        $transaksi = $query
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        // SUMMARY
        $allTransaksi = KasKegiatan::where(
            'nama_kegiatan',
            $kegiatan->nama_kegiatan
        )->get();

        $totalDebet = $allTransaksi->where('jenis', 'Debet')->sum('nominal');
        $totalKredit = $allTransaksi->where('jenis', 'Kredit')->sum('nominal');
        $saldo = $totalDebet - $totalKredit;

        return inertia('Admin/Keuangan/Kegiatan/Detail', [
            'kegiatan' => $kegiatan,
            'transaksi' => $transaksi,
            'kategoriList' => KasKategori::all(),
            'filters' => $request->only([
                'search',
                'sort_by',
                'sort_direction',
                'per_page',
            ]),
            'summary' => [
                'debet' => $totalDebet,
                'kredit' => $totalKredit,
                'saldo' => $saldo,
            ],
        ]);
    }

    /** STORE TRANSAKSI */
    public function storeTransaksi(Request $request, $id)
    {
        $kegiatan = KasKegiatan::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'uraian' => 'required|string',
            'jenis' => 'required|in:Debet,Kredit',
            'kategori_id' => 'required|exists:kas_kategori,id',
            'nominal' => 'required|numeric',
            'bukti_transaksi' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('bukti_transaksi')) {
            $validated['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti_kegiatan');
        }

        $validated['nama_kegiatan'] = $kegiatan->nama_kegiatan;
        $validated['tanggal_pelaksanaan'] = $kegiatan->tanggal_pelaksanaan;

        KasKegiatan::create($validated);

        return back()->with('success', 'Transaksi berhasil ditambahkan!');
    }

    /** UPDATE TRANSAKSI */
    public function updateTransaksi(Request $request, KasKegiatan $transaksi)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'uraian' => 'required|string',
            'jenis' => 'required|in:Debet,Kredit',
            'kategori_id' => 'required|exists:kas_kategori,id',
            'nominal' => 'required|numeric',
            'bukti_transaksi' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('bukti_transaksi')) {
            $validated['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti_kegiatan');
        }

        $transaksi->update($validated);

        return back()->with('success', 'Transaksi diperbarui!');
    }

    /** DELETE TRANSAKSI */
    public function destroyTransaksi(KasKegiatan $transaksi)
    {
        $transaksi->delete();

        return back()->with('success', 'Transaksi dihapus!');
    }

    // EXPORT EXCEL
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new KasKegiatanExport($request),
            'kas-'.now()->format('Y-m-d').'.xlsx'
        );
    }
}
