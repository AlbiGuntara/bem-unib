<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Prestasi;
use App\Models\Admin\Warga;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prestasi::query()
            ->with('warga')
            ->leftJoin('wargas', 'prestasis.id_warga', '=', 'wargas.id')
            ->select(
                'prestasis.*',
                'wargas.nama',
                'wargas.nis',
                'wargas.foto'
            );

        // SEARCH
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('wargas.nama', 'like', "%{$search}%")
                    ->orWhere('prestasis.prestasi', 'like', "%{$search}%")
                    ->orWhere('prestasis.kategori', 'like', "%{$search}%")
                    ->orWhere('prestasis.tingkat', 'like', "%{$search}%")
                    ->orWhere('prestasis.tempat', 'like', "%{$search}%");
            });
        }

        // SORTING
        $sortable = [
            "nama" => "wargas.nama",
            "prestasi" => "prestasis.prestasi",
            "kategori" => "prestasis.kategori",
            "tingkat" => "prestasis.tingkat",
            "tanggal" => "prestasis.tanggal",
            "tempat" => "prestasis.tempat",
        ];

        $sortBy = $request->get("sort_by");
        $sortDirection = $request->get("sort_direction", "asc");

        if ($sortBy && isset($sortable[$sortBy])) {
            $query->orderBy($sortable[$sortBy], $sortDirection);
        } else {
            $query->latest("prestasis.created_at");
        }

        // PAGINATION
        $perPage = $request->get("per_page", 10);
        $prestasi = $query->paginate($perPage)->withQueryString();

        $warga = Warga::select("id", "nama", "nis", "foto")->get();

        return Inertia::render("Admin/Prestasi/Index", [
            "prestasi" => $prestasi,
            "warga" => $warga,
            "filters" => $request->only([
                "search",
                "sort_by",
                "sort_direction",
                "per_page",
            ]),
        ]);
    }

    public function create()
    {
        $warga = Warga::select("id", "nama", "nis", "foto")->get();

        return Inertia::render("Admin/Prestasi/Create", [
            "warga" => $warga,

        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "id_warga" => "required|exists:wargas,id",
            "prestasi" => "required|string",
            "kategori" => "nullable|string",
            "tingkat" => "nullable|string",
            "tempat" => "nullable|string",
            "penyelenggara" => "nullable|string",
            "tanggal" => "nullable|date",
            "deskripsi" => "nullable|string",
            "dokumentasi" => "nullable|image|max:2048",
        ]);

        // Upload file
        if ($request->hasFile("dokumentasi")) {
            $data["dokumentasi"] = $request->file("dokumentasi")->store("prestasi", "public");
        }

        Prestasi::create($data);

        return redirect()->route('prestasi.index')->with("success", "Prestasi berhasil ditambahkan.");
    }

    public function edit(Prestasi $prestasi)
    {
        $prestasi = Prestasi::with("warga")->findOrFail($prestasi->id);

        $warga = Warga::select("id", "nama", "nis", "foto")->get();

        return Inertia::render("Admin/Prestasi/Edit", [
            "prestasi" => $prestasi,
            "warga" => $warga,
        ]);
    }


    public function update(Request $request, Prestasi $prestasi)
    {
        $data = $request->validate([
            "id_warga" => "required|exists:wargas,id",
            "prestasi" => "required|string",
            "kategori" => "nullable|string",
            "tingkat" => "nullable|string",
            "tempat" => "nullable|string",
            "penyelenggara" => "nullable|string",
            "tanggal" => "nullable|date",
            "deskripsi" => "nullable|string",
            "dokumentasi" => "nullable|image|max:2048",
        ]);

        if ($request->hasFile("dokumentasi")) {
            if ($prestasi->dokumentasi && file_exists(public_path("storage/" . $prestasi->dokumentasi))) {
                unlink(public_path("storage/" . $prestasi->dokumentasi));
            }

            $data["dokumentasi"] = $request->file("dokumentasi")->store("prestasi", "public");
        }

        $prestasi->update($data);

        return redirect()->route('prestasi.index')->with("success", "Prestasi berhasil diperbarui.");
    }


    public function destroy(Prestasi $prestasi)
    {
        if ($prestasi->dokumentasi && file_exists(public_path("storage/" . $prestasi->dokumentasi))) {
            unlink(public_path("storage/" . $prestasi->dokumentasi));
        }

        $prestasi->delete();

        return back()->with("success", "Prestasi berhasil dihapus.");
    }

    public function byWarga($id)
    {
        $warga = Warga::findOrFail($id);

        $prestasi = Prestasi::where('id_warga', $id)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Jika ada ?shared=1 → kirim flash success
        if (request()->shared == 1) {
            session()->flash('success', 'Prestasi berhasil dibagikan!');
        }

        // Jika ada ?copied=1 → flash copy success
        if (request()->copied == 1) {
            session()->flash('success', 'Link prestasi berhasil disalin!');
        }

        return Inertia::render('Admin/Prestasi/ByWarga', [
            'warga' => $warga,
            'prestasi' => $prestasi,
        ]);
    }
}
