<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\HutangPiutang;
use App\Models\Admin\HutangPiutangPembayaran;
use App\Models\Admin\KasUmum;
use App\Models\Admin\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HutangPiutangPembayaranController extends Controller
{
    public function store(Request $request, HutangPiutang $hutangPiutang)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'nominal' => ['required', 'numeric', 'min:1'],
        ]);

        DB::transaction(function () use ($request, $hutangPiutang) {

            // 1. Simpan riwayat pembayaran
            $pembayaran = HutangPiutangPembayaran::create([
                'hutang_piutang_id' => $hutangPiutang->id,
                'tanggal' => $request->tanggal,
                'nominal' => $request->nominal,
            ]);

            // 2. Hitung sisa hutang / piutang
            $sisa = $hutangPiutang->nominal - $request->nominal;

            $hutangPiutang->update([
                'nominal' => max($sisa, 0),
                'status' => $sisa <= 0 ? 'Lunas' : 'Belum',
            ]);

            // 3. Catat ke Kas Umum (REAL TIME)
            KasUmum::create([
                'periode_id' => Periode::aktif()->id,
                'tanggal' => $request->tanggal,
                'uraian' => ($hutangPiutang->jenis === 'Hutang'
                    ? 'Bayar Hutang'
                    : 'Terima Piutang')." - {$hutangPiutang->nama}",

                'jenis' => $hutangPiutang->jenis === 'Hutang'
                    ? 'Kredit'
                    : 'Debet',

                'kategori_id' => config('kas.kategori_hutang_piutang'),
                'nominal' => $request->nominal,

                'source_type' => HutangPiutangPembayaran::class,
                'source_id' => $pembayaran->id,
            ]);
        });

        return back()->with('success', 'Pembayaran berhasil disimpan dan kas otomatis diperbarui');
    }
}
