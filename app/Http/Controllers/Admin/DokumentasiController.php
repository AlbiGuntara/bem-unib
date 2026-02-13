<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto' => 'nullable|image|max:2048',
            'id_program_kerja' => 'required|exists:program_kerjas,id',
            'link' => 'nullable|url',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('dokumentasi', 'public');
        }

        Dokumentasi::create($validated);

        return back()->with('success', 'Dokumentasi berhasil ditambahkan');
    }

    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        $validated = $request->validate([
            'foto' => 'nullable|image|max:2048',
            'id_program_kerja' => 'required|exists:program_kerjas,id',
            'link' => 'nullable|url',
            'deskripsi' => 'nullable|string',
        ]);

        $data = [
            'id_program_kerja' => $validated['id_program_kerja'],
            'link' => $validated['link'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        if ($request->hasFile('foto')) {
            if ($dokumentasi->foto) {
                Storage::disk('public')->delete($dokumentasi->foto);
            }

            $data['foto'] = $request->file('foto')
                ->store('dokumentasi', 'public');
        }

        $dokumentasi->update($data);

        return back()->with('success', 'Dokumentasi berhasil diperbarui');
    }

    public function destroy(Dokumentasi $dokumentasi)
    {
        if ($dokumentasi->foto) {
            Storage::disk('public')->delete($dokumentasi->foto);
        }

        $dokumentasi->delete();

        return back()->with('success', 'Dokumentasi berhasil dihapus');
    }
}
