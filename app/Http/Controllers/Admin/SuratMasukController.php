<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SuratMasuk;
use App\Models\Admin\SuratKeluar;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratMasuk::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('no_surat', 'like', "%{$request->search}%")
                    ->orWhere('asal', 'like', "%{$request->search}%")
                    ->orWhere('perihal', 'like', "%{$request->search}%")
                    ->orWhere('tanggal', 'like', "%{$request->search}%");
            });
        }

        if ($request->sort_by && $request->sort_direction) {
            $query->orderBy($request->sort_by, $request->sort_direction);
        }

        $perPage = $request->per_page ?? 10;
        $surat = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/SuratMasuk', [
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
            'asal' => 'required',
            'perihal' => 'required',
            "tanggal" => "nullable|date",
            'foto' => 'nullable|image',
        ]);

        $data = $request->only(['no_surat', 'asal', 'perihal', 'tanggal']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('surat_masuk', 'public');
        }

        SuratMasuk::create($data);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil ditambahkan');
    }

    public function update(Request $request, SuratMasuk $surat_masuk)
    {
        $request->validate([
            'no_surat' => 'required',
            'asal' => 'required',
            'perihal' => 'required',
            "tanggal" => "nullable|date",
            'foto' => 'nullable|image',
        ]);

        $data = $request->only(['no_surat', 'asal', 'perihal', 'tanggal']);

        if ($request->hasFile('foto')) {
            if ($surat_masuk->foto) {
                Storage::disk('public')->delete($surat_masuk->foto);
            }
            $data['foto'] = $request->file('foto')->store('surat_masuk', 'public');
        }

        $surat_masuk->update($data);

        return back()->with('success', 'Surat Masuk berhasil diperbarui');
    }

    public function destroy(SuratMasuk $surat_masuk)
    {
        if ($surat_masuk->foto) {
            Storage::disk('public')->delete($surat_masuk->foto);
        }
        $surat_masuk->delete();

        return back()->with('success', 'Surat Masuk berhasil dihapus');
    }
}
