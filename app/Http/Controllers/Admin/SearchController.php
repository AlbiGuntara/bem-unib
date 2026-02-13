<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Admin\Warga;
use App\Models\Admin\SkorPendidikan;
use App\Models\Admin\Prestasi;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (!$q) {
            return response()->json(['results' => []]);
        }

        $results = collect();

        // USERS
        $users = User::where('name', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->take(5)
            ->get()
            ->map(fn($u) => [
                'type' => 'User',
                'title' => $u->name,
                'category' => $u->email ?? 'User',
                'url' => route('users.index') . '?q=' . urlencode($u->name),
            ]);

        // WARGA
        $wargas = Warga::where('nama', 'like', "%$q%")
            ->orWhere('nis', 'like', "%$q%")
            ->orWhere('asrama', 'like', "%$q%")
            ->take(5)
            ->get()
            ->map(fn($w) => [
                'type' => 'Warga',
                'title' => $w->nama,
                'category' => $w->asrama ?? 'Warga',
                'url' => route('warga.index') . '?search=' . urlencode($w->nama),
            ]);

        // SKOR PENDIDIKAN
        $skor = SkorPendidikan::with('warga')
            ->whereHas('warga', function ($w) use ($q) {
                $w->where('nama', 'like', "%$q%")
                    ->orWhere('nis', 'like', "%$q%");
            })
            ->orWhere('bulan', 'like', "%$q%")
            ->take(5)
            ->get()
            ->map(function ($s) {
                return [
                    'type' => 'Skor Pendidikan',
                    'title' => $s->warga->nama ?? 'Tidak diketahui',
                    'category' => "Bulan: {$s->bulan}",
                    'url' => route('skor-pendidikan.index') . '?search=' . urlencode($s->warga->nama ?? ''),
                ];
            });

        // PRESTASI
        $prestasi = Prestasi::with('warga')
            ->where('prestasi', 'like', "%$q%")
            ->orWhere('kategori', 'like', "%$q%")
            ->orWhere('tingkat', 'like', "%$q%")
            ->orWhere('tempat', 'like', "%$q%")
            ->orWhere('penyelenggara', 'like', "%$q%")
            ->orWhereHas('warga', function ($w) use ($q) {
                $w->where('nama', 'like', "%$q%")
                    ->orWhere('nis', 'like', "%$q%");
            })
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'type' => 'Prestasi',
                    'title' => $p->prestasi,
                    'category' => $p->warga->nama ?? 'Tidak diketahui',
                    'url' => route('prestasi.index') . '?search=' . urlencode($p->prestasi),
                ];
            });


        $results = $results
            ->merge($users)
            ->merge($wargas)
            ->merge($skor)
            ->merge($prestasi);

        return response()->json(['results' => $results->values()]);
    }
}
