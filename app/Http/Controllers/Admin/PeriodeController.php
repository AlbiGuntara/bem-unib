<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Periode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeriodeController extends Controller
{
    public function index(Request $request)
    {
        $periode = Periode::query()
            ->when($request->search, fn ($q, $v) => $q->where('periode', 'like', "%$v%")
                ->orWhere('tahun_mulai', 'like', "%$v%")
                ->orWhere('tahun_selesai', 'like', "%$v%")
            )
            ->when(
                $request->sort_by,
                fn ($q) => $q->orderBy($request->sort_by, $request->sort_direction ?? 'asc'),
                fn ($q) => $q->orderByDesc('tahun_selesai')
            )
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('Admin/Periode/Index', [
            'data' => $periode,
            'filters' => $request->only('search', 'per_page', 'sort_by', 'sort_direction'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'periode' => 'required|string|max:255',
            'tahun_mulai' => 'required|digits:4',
            'tahun_selesai' => 'required|digits:4|gte:tahun_mulai',
        ]);

        Periode::create($data);

        return back()->with('success', 'Periode berhasil ditambahkan');
    }

    public function update(Request $request, Periode $periode)
    {
        $data = $request->validate([
            'periode' => 'required|string|max:255',
            'tahun_mulai' => 'required|digits:4',
            'tahun_selesai' => 'required|digits:4|gte:tahun_mulai',
        ]);

        $periode->update($data);

        return back()->with('success', 'Periode berhasil diperbarui');
    }

    public function destroy(Periode $periode)
    {
        $periode->delete();

        return back()->with('success', 'Periode berhasil dihapus');
    }
}
