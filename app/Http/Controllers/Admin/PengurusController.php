<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Jabatan;
use App\Models\Admin\Pengurus;
use App\Models\Admin\Periode;
use App\Models\Admin\Warga;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PengurusController extends Controller
{
    public function index(Request $request)
    {
        $pengurus = Pengurus::query()
            ->with(['warga', 'jabatan', 'periode'])

            // SEARCH
            ->when($request->search, function ($q, $search) {
                $q->whereHas('warga', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })
                    ->orWhereHas('jabatan', function ($q) use ($search) {
                        $q->where('nama_jabatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('periode', function ($q) use ($search) {
                        $q->where('periode', 'like', "%{$search}%");
                    });
            })

            // SORTING
            ->when($request->sort_by, function ($q) use ($request) {
                match ($request->sort_by) {
                    'nama' => $q->whereHas('warga', function ($q) use ($request) {
                        $q->orderBy('nama', $request->sort_direction ?? 'asc');
                    }),
                    'jabatan' => $q->whereHas('jabatan', function ($q) use ($request) {
                        $q->orderBy('nama_jabatan', $request->sort_direction ?? 'asc');
                    }),
                    'periode' => $q->whereHas('periode', function ($q) use ($request) {
                        $q->orderBy('periode', $request->sort_direction ?? 'asc');
                    }),
                    default => $q
                };
            }, fn ($q) => $q->latest())

            // PAGINATION
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('Admin/Pengurus/Index', [
            'pengurus' => $pengurus,
            'filters' => $request->only([
                'search',
                'per_page',
                'sort_by',
                'sort_direction',
            ]),

            // data pendukung modal
            'periodes' => Periode::orderByDesc('tahun_selesai')->get(),
            'wargas' => Warga::select('id', 'nama', 'status')->orderBy('nama')->get(),
            'jabatans' => Jabatan::select('id', 'nama_jabatan', 'IKSASS')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_warga' => 'required|exists:wargas,id',
            'id_jabatan' => 'required|exists:jabatans,id',
            'id_periode' => 'required|exists:periode,id',
        ]);

        $warga = Warga::findOrFail($request->id_warga);

        if ($warga->status === 'Tidak Jelas') {
            abort(422, 'Status warga tidak jelas');
        }

        $jabatan = Jabatan::findOrFail($request->id_jabatan);

        if (
            ($warga->status === 'Santri' && $jabatan->IKSASS !== 'IKSASS Santri') ||
            ($warga->status === 'Alumni' && $jabatan->IKSASS !== 'IKSASS Alumni')
        ) {
            abort(422, 'Jabatan tidak sesuai status warga');
        }

        Pengurus::create([
            'id_warga' => $request->id_warga,
            'id_jabatan' => $request->id_jabatan,
            'id_periode' => $request->id_periode,
        ]);

        return back()->with('success', 'Pengurus berhasil ditambahkan');
    }

    public function update(Request $request, Pengurus $penguru)
    {
        $request->validate([
            'id_warga' => 'required|exists:wargas,id',
            'id_jabatan' => 'required|exists:jabatans,id',
            'id_periode' => 'required|exists:periode,id',
        ]);

        $warga = Warga::findOrFail($request->id_warga);

        if ($warga->status === 'Tidak Jelas') {
            abort(422, 'Status warga tidak jelas');
        }

        $jabatan = Jabatan::findOrFail($request->id_jabatan);

        if (
            ($warga->status === 'Santri' && $jabatan->IKSASS !== 'IKSASS Santri') ||
            ($warga->status === 'Alumni' && $jabatan->IKSASS !== 'IKSASS Alumni')
        ) {
            abort(422, 'Jabatan tidak sesuai status warga');
        }

        $penguru->update([
            'id_warga' => $request->id_warga,
            'id_jabatan' => $request->id_jabatan,
            'id_periode' => $request->id_periode,
        ]);

        return back()->with('success', 'Pengurus berhasil diperbarui');
    }

    public function destroy(Pengurus $penguru)
    {
        $penguru->delete();

        return back()->with('success', 'Pengurus berhasil dihapus');
    }
}
