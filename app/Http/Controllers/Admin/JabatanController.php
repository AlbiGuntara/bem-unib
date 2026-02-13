<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Jabatan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $jabatans = Jabatan::query()
            ->when($request->search, function ($q, $v) {
                $q->where('nama_jabatan', 'like', "%{$v}%")
                    ->orWhere('departemen', 'like', "%{$v}%")
                    ->orWhere('IKSASS', 'like', "%{$v}%");
            })
            ->when(
                $request->sort_by && in_array($request->sort_direction, ['asc', 'desc']),
                fn ($q) => $q->orderBy($request->sort_by, $request->sort_direction),
                fn ($q) => $q->orderBy('IKSASS')->orderBy('departemen')->orderBy('nama_jabatan')
            )
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('Admin/Jabatan/Index', [
            'data' => $jabatans,
            'filters' => $request->only(
                'search',
                'per_page',
                'sort_by',
                'sort_direction'
            ),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'IKSASS' => 'required|in:IKSASS Santri,IKSASS Alumni',
            'nama_jabatan' => 'required|string|max:255',
            'departemen' => 'nullable|string|max:255',
        ]);

        Jabatan::create($data);

        return back()->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $data = $request->validate([
            'IKSASS' => 'required|in:IKSASS Santri,IKSASS Alumni',
            'nama_jabatan' => 'required|string|max:255',
            'departemen' => 'nullable|string|max:255',
        ]);

        $jabatan->update($data);

        return back()->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return back()->with('success', 'Jabatan berhasil dihapus');
    }
}
