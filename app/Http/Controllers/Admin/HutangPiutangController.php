<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\HutangPiutang;
use App\Models\Admin\KasUmum;
use App\Models\Admin\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HutangPiutangController extends Controller
{
    public function index(Request $request)
    {
        $hutangPiutang = HutangPiutang::query()
            ->when($request->search, function ($q, $v) {
                $q->where('nama', 'like', "%$v%")
                    ->orWhere('kontak', 'like', "%$v%")
                    ->orWhere('keterangan', 'like', "%$v%");
            })
            ->when($request->jenis, fn ($q, $v) => $q->where('jenis', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when(
                $request->sort_by && in_array($request->sort_direction, ['asc', 'desc']),
                fn ($q) => $q->orderBy($request->sort_by, $request->sort_direction),
                fn ($q) => $q->orderBy('tanggal', 'desc') // default sort
            )
            ->paginate(10)
            ->withQueryString();

        /* ================= SUMMARY ================= */
        $totalHutang = HutangPiutang::where('jenis', 'Hutang')
            ->where('status', 'Belum')
            ->sum('nominal');

        $totalPiutang = HutangPiutang::where('jenis', 'Piutang')
            ->where('status', 'Belum')
            ->sum('nominal');

        $jatuhTempo = HutangPiutang::whereDate('jatuh_tempo', '<=', now())
            ->where('status', 'Belum')
            ->count();

        $saldoKas = DB::table('kas_umum')
            ->selectRaw("
            SUM(CASE WHEN jenis='Debet' THEN nominal ELSE 0 END) -
            SUM(CASE WHEN jenis='Kredit' THEN nominal ELSE 0 END) as saldo
        ")
            ->value('saldo');

        /* ================= TREND ================= */
        $trend = HutangPiutang::selectRaw("
            DATE_FORMAT(tanggal, '%Y-%m') as bulan,
            SUM(CASE WHEN jenis='Hutang' THEN nominal ELSE 0 END) as hutang,
            SUM(CASE WHEN jenis='Piutang' THEN nominal ELSE 0 END) as piutang
        ")
            ->whereBetween('tanggal', [
                now()->subMonths(5)->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return Inertia::render('Admin/HutangPiutang/Index', [
            'data' => $hutangPiutang,
            'summary' => [
                'total_hutang' => (float) $totalHutang,
                'total_piutang' => (float) $totalPiutang,
                'jatuh_tempo' => (int) $jatuhTempo,
                'saldo_kas' => (float) $saldoKas,
            ],
            'trend' => $trend,
            'filters' => $request->only(
                'search',
                'jenis',
                'status',
                'per_page'
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/HutangPiutang/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis' => 'required|in:Hutang,Piutang',
            'tanggal' => 'required|date',
            'nama' => 'required|string',
            'kontak' => 'nullable|string',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
            'jatuh_tempo' => 'nullable|date',
            'foto' => 'nullable|image',
            'bukti_transaksi' => 'nullable|file',
            'status' => 'required|in:Belum,Lunas',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('hutang-piutang/foto', 'public');
        }

        if ($request->hasFile('bukti_transaksi')) {
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('hutang-piutang/bukti', 'public');
        }

        DB::transaction(function () use ($data) {

            // Simpan Hutang / Piutang
            $hp = HutangPiutang::create($data);

            // Catat ke Kas Umum
            KasUmum::create([
                'periode_id' => Periode::aktif()->id,
                'tanggal' => $hp->tanggal,
                'uraian' => "{$hp->jenis} - {$hp->nama}",
                'jenis' => $hp->jenis === 'Hutang' ? 'Debet' : 'Kredit',
                'kategori_id' => config('kas.kategori_hutang_piutang'),
                'nominal' => $hp->nominal,
                'bukti_transaksi' => $hp->bukti_transaksi,
                'source_type' => HutangPiutang::class,
                'source_id' => $hp->id,
            ]);
        });

        return redirect()->route('hutang-piutang.index')
            ->with('success', 'Data berhasil ditambahkan & tercatat di Kas Umum');
    }

    public function edit(HutangPiutang $hutangPiutang)
    {
        return Inertia::render('Admin/HutangPiutang/Edit', [
            'item' => $hutangPiutang,
        ]);
    }

    public function update(Request $request, HutangPiutang $hutangPiutang)
    {
        $data = $request->validate([
            'jenis' => 'required|in:Hutang,Piutang',
            'tanggal' => 'required|date',
            'nama' => 'required|string',
            'kontak' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'jatuh_tempo' => 'nullable|date',
            'foto' => 'nullable|image',
            'bukti_transaksi' => 'nullable|file',
            'status' => 'required|in:Belum,Lunas',
        ]);

        if ($request->hasFile('foto')) {
            if ($hutangPiutang->foto) {
                Storage::disk('public')->delete($hutangPiutang->foto);
            }
            $data['foto'] = $request->file('foto')->store('hutang-piutang/foto', 'public');
        }

        if ($request->hasFile('bukti_transaksi')) {
            if ($hutangPiutang->bukti_transaksi) {
                Storage::disk('public')->delete($hutangPiutang->bukti_transaksi);
            }
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('hutang-piutang/bukti', 'public');
        }

        DB::transaction(function () use ($hutangPiutang, $data) {

            KasUmum::where('source_type', HutangPiutang::class)
                ->where('source_id', $hutangPiutang->id)
                ->delete();

            $hutangPiutang->update($data);

            KasUmum::create([
                'periode_id' => Periode::aktif()->id,
                'tanggal' => $hutangPiutang->tanggal,
                'uraian' => "{$hutangPiutang->jenis} - {$hutangPiutang->nama}",
                'jenis' => $hutangPiutang->jenis === 'Hutang' ? 'Debet' : 'Kredit',
                'kategori_id' => config('kas.kategori_hutang_piutang'),
                'nominal' => $hutangPiutang->nominal,
                'bukti_transaksi' => $hutangPiutang->bukti_transaksi,
                'source_type' => HutangPiutang::class,
                'source_id' => $hutangPiutang->id,
            ]);
        });

        return redirect()->route('hutang-piutang.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(HutangPiutang $hutangPiutang)
    {
        if ($hutangPiutang->foto) {
            Storage::disk('public')->delete($hutangPiutang->foto);
        }

        if ($hutangPiutang->bukti_transaksi) {
            Storage::disk('public')->delete($hutangPiutang->bukti_transaksi);
        }

        $hutangPiutang->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    public function riwayat(HutangPiutang $hutangPiutang)
    {
        return response()->json(
            $hutangPiutang->pembayarans()
                ->orderBy('tanggal')
                ->get()
        );
    }
}
