<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\KasKategori;
use Illuminate\Http\Request;

class KasKategoriController extends Controller
{
    public function index()
    {
        return KasKategori::orderBy('id', 'desc')->paginate(5);
    }

    public function store(Request $request)
    {
        $request->validate(['kategori' => 'required|string|max:255']);
        KasKategori::create($request->only('kategori'));

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['kategori' => 'required|string|max:255']);
        $kategori = KasKategori::findOrFail($id);
        $kategori->update($request->only('kategori'));

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        KasKategori::findOrFail($id)->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
