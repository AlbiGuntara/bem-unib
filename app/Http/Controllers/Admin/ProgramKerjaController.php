<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProgramKerjaExport;
use App\Http\Controllers\Controller;
use App\Models\Admin\Dokumentasi;
use App\Models\Admin\Jabatan;
use App\Models\Admin\ProgramKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ProgramKerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramKerja::with([
            'jabatan',
            'dokumentasis',
        ]);

        /* SEARCH */
        if ($request->search) {
            $query->where('nama_kegiatan', 'like', '%'.$request->search.'%');
        }

        /* FILTER JABATAN */
        if ($request->jabatan_id) {
            $query->where('jabatan_id', $request->jabatan_id);
        }

        /* SORT */
        if ($request->sort_by && $request->sort_direction) {
            $query->orderBy($request->sort_by, $request->sort_direction);
        } else {
            $query->latest();
        }

        $perPage = $request->per_page ?? 10;

        /* EVENTS (CALENDAR) */
        $events = ProgramKerja::with('jabatan')
            ->whereNotNull('tanggal_mulai')
            ->when($request->jabatan_id, function ($q) use ($request) {
                $q->where('jabatan_id', $request->jabatan_id);
            })
            ->get()
            ->map(function ($item) {

                $end = null;
                if ($item->tanggal_selesai && $item->tanggal_selesai != $item->tanggal_mulai) {
                    $end = Carbon::parse($item->tanggal_selesai)
                        ->addDay()
                        ->format('Y-m-d');
                }

                return [
                    'id' => $item->id,
                    'title' => $item->nama_kegiatan,
                    'start' => $item->tanggal_mulai,
                    'end' => $end,
                    'allDay' => true,

                    'nama_kegiatan' => $item->nama_kegiatan,
                    'tujuan' => $item->tujuan,
                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_selesai' => $item->tanggal_selesai,
                    'penanggung_jawab' => optional($item->jabatan)->departemen,
                    'peserta' => $item->peserta,
                    'anggaran' => $item->anggaran,
                    'deskripsi' => $item->deskripsi,
                ];
            });

        $dokumentasis = Dokumentasi::with('programKerja')
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'foto' => $item->foto
                        ? asset('storage/'.$item->foto)
                        : null,
                    'link' => $item->link,
                    'deskripsi' => $item->deskripsi,
                    'programKerja' => $item->programKerja,
                ];
            });

        return Inertia::render('Admin/ProgramKerja/Index', [
            'data' => $query->paginate($perPage)->withQueryString(),
            'events' => $events,
            'jabatans' => Jabatan::orderBy('departemen')->get(),

            'dokumentasis' => $dokumentasis,
            'programKerjas' => ProgramKerja::select('id', 'nama_kegiatan')->get(),

            'filters' => $request->only([
                'search',
                'per_page',
                'sort_by',
                'sort_direction',
                'jabatan_id',
            ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/ProgramKerja/Create', [
            'jabatans' => Jabatan::orderBy('departemen')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'peserta' => 'nullable|string',
            'anggaran' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        ProgramKerja::create($validated);

        return redirect()->route('program-kerja.index')
            ->with('success', 'Program kerja berhasil ditambahkan');
    }

    public function edit(ProgramKerja $programKerja)
    {
        return Inertia::render('Admin/ProgramKerja/Edit', [
            'programKerja' => $programKerja->load('jabatan'),
            'jabatans' => Jabatan::orderBy('departemen')->get(),
        ]);

    }

    public function update(Request $request, ProgramKerja $programKerja)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'peserta' => 'nullable|string',
            'anggaran' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        $programKerja->update($validated);

        return redirect()->route('program-kerja.index')
            ->with('success', 'Program kerja berhasil diperbarui');
    }

    public function destroy(ProgramKerja $programKerja)
    {
        $programKerja->delete();

        return back()->with('success', 'Program kerja berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(
            new ProgramKerjaExport,
            'program-kerja.xlsx'
        );
    }
}
