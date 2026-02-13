<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Admin\Prestasi;
use Illuminate\Http\Request; // ✅ INI WAJIB
use Inertia\Inertia;

class PrestasisController extends Controller
{
    public function prestasi(Request $request)
    {
        $query = Prestasi::with('warga');

        // SEARCH (opsional)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('prestasi', 'like', "%{$request->search}%")
                    ->orWhere('kategori', 'like', "%{$request->search}%")
                    ->orWhere('tingkat', 'like', "%{$request->search}%")
                    ->orWhere('tempat', 'like', "%{$request->search}%")
                    ->orWhere('penyelenggara', 'like', "%{$request->search}%")
                    ->orWhereHas('warga', function ($qw) use ($request) {
                        $qw->where('nama', 'like', "%{$request->search}%");
                    });
            });
        }

        // FILTER TANGGAL (opsional)
        if ($request->filled('start')) {
            $query->whereDate('tanggal', '>=', $request->start);
        }

        if ($request->filled('end')) {
            $query->whereDate('tanggal', '<=', $request->end);
        }

        $prestasis = $query
            ->latest('tanggal')
            ->paginate(9)
            ->withQueryString()
            ->through(fn ($item) => [
                'nama' => $item->warga->nama,
                'prestasi' => $item->prestasi,
                'kategori' => $item->kategori,
                'tingkat' => $item->tingkat,
                'tempat' => $item->tempat,
                'penyelenggara' => $item->penyelenggara,
                'tanggal_raw' => $item->tanggal,
                'tanggal' => $item->tanggal
                    ? date('d M Y', strtotime($item->tanggal))
                    : null,
                'deskripsi' => $item->deskripsi,
                'dokumentasi' => $item->dokumentasi
                    ? asset('storage/'.$item->dokumentasi)
                    : null,
            ]);

        return Inertia::render('Public/Prestasi', [
            'prestasis' => $prestasis,
            'filters' => $request->only(['search', 'start', 'end']),
        ]);
    }
}
