<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SuratMasuk;
use App\Models\Admin\SuratKeluar;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('no_surat', 'like', "%{$request->search}%")
                    ->orWhere('tujuan', 'like', "%{$request->search}%")
                    ->orWhere('perihal', 'like', "%{$request->search}%")
                    ->orWhere('tanggal', 'like', "%{$request->search}%");
            });
        }

        // Sorting using unified format: sort_by + sort_direction
        if ($request->sort_by && $request->sort_direction) {
            $query->orderBy($request->sort_by, $request->sort_direction);
        }

        // Pagination
        $perPage = $request->per_page ?? 10;

        $surat = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/SuratKeluar', [
            'surat' => $surat,
            'filters' => $request->only(['search', 'sort_by', 'sort_direction', 'per_page']),
            'totalMasuk' => SuratMasuk::count(),
            'totalKeluar' => SuratKeluar::count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required',
            'tujuan' => 'required',
            'perihal' => 'required',
            "tanggal" => "nullable|date",
            'foto' => 'nullable|image',
        ]);

        $data = $request->only(['no_surat', 'tujuan', 'perihal', 'tanggal']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('surat-keluar', 'public');
        }

        SuratKeluar::create($data);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat Keluar berhasil ditambahkan');
    }

    public function update(Request $request, SuratKeluar $surat_keluar)
    {
        $request->validate([
            'no_surat' => 'required',
            'tujuan' => 'required',
            'perihal' => 'required',
            "tanggal" => "nullable|date",
            'foto' => 'nullable|image',
        ]);

        $data = $request->only(['no_surat', 'tujuan', 'perihal', 'tanggal']);

        if ($request->hasFile('foto')) {
            if ($surat_keluar->foto) {
                Storage::disk('public')->delete($surat_keluar->foto);
            }
            $data['foto'] = $request->file('foto')->store('surat-keluar', 'public');
        }

        $surat_keluar->update($data);

        return back()->with('success', 'Surat Keluar berhasil diperbarui');
    }

    public function destroy(SuratKeluar $surat_keluar)
    {
        if ($surat_keluar->foto) {
            Storage::disk('public')->delete($surat_keluar->foto);
        }
        $surat_keluar->delete();

        return back()->with('success', 'Surat Keluar berhasil dihapus');
    }
}
