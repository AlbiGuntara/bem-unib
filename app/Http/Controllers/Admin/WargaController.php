<?php

namespace App\Http\Controllers\Admin;

use App\Exports\WargaExport;
use App\Http\Controllers\Controller;
use App\Models\Admin\Warga;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class WargaController extends Controller
{
    public function index()
    {
        $query = Warga::query()
            ->when(request('search'), function ($q) {
                $q->where('nama', 'like', '%'.request('search').'%')
                    ->orWhere('kecamatan', 'like', '%'.request('search').'%')
                    ->orWhere('kabupaten', 'like', '%'.request('search').'%');
            });

        // Filter berdasarkan status
        if (request()->has('status') && request('status')) {
            $query->where('status', request('status'));
        }

        // Sorting
        if (request('sort_by') && in_array(request('sort_by'), ['nama', 'nis', 'asrama', 'status', 'tahun_mondok', 'tahun_lulus'])) {
            $query->orderBy(request('sort_by'), request('sort_direction', 'asc'));
        } else {
            $query->latest();
        }

        // Pagination size
        $perPage = request('per_page', 10);
        $wargas = $query->paginate($perPage)->withQueryString();

        // Statistik Pendidikan
        $chartData = [
            'pagi' => [
                'MI' => Warga::where('pendidikan_pagi', 'MI')->count(),
                'MTs' => Warga::where('pendidikan_pagi', 'MTs')->count(),
                'MA' => Warga::where('pendidikan_pagi', 'MA')->count(),
                'MTI' => Warga::where('pendidikan_pagi', 'MTI')->count(),
                'MDI' => Warga::where('pendidikan_pagi', 'MDI')->count(),
                'Maly' => Warga::where('pendidikan_pagi', 'Maly')->count(),
                'Lulus' => Warga::where('pendidikan_pagi', 'Lulus')->count(),
            ],
            'sore' => [
                'SD' => Warga::where('pendidikan_sore', 'SD')->count(),
                'SMP' => Warga::where('pendidikan_sore', 'SMP')->count(),
                'SMA' => Warga::where('pendidikan_sore', 'SMA')->count(),
                'SMK' => Warga::where('pendidikan_sore', 'SMK')->count(),
                'PT' => Warga::where('pendidikan_sore', 'PT')->count(),
                'Lulus' => Warga::where('pendidikan_sore', 'Lulus')->count(),
            ],
            'malam' => [
                'Qiraati' => Warga::where('pendidikan_malam', 'Qiraati')->count(),
                'Amtsilati' => Warga::where('pendidikan_malam', 'Amtsilati')->count(),
                'Pengajian' => Warga::where('pendidikan_malam', 'Pengajian')->count(),
            ],
        ];

        return Inertia::render('Admin/Warga/Index', [
            'wargas' => $wargas,
            'chartData' => $chartData,
            'filters' => request()->only('search', 'status', 'sort_by', 'sort_direction', 'per_page'),
        ]);
    }

    public function show(Warga $warga)
    {
        return Inertia::render('Admin/Warga/Show', [
            'warga' => $warga,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Warga/Create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'foto' => 'nullable|image|max:2048',
                'nama' => 'required|string|max:255',
                'nis' => 'required|string|unique:wargas,nis',
                'asrama' => 'nullable|string|max:255',

                'pendidikan_pagi' => 'nullable|string',
                'no_induk_pendidikan_pagi' => 'nullable|string|max:255',
                'kelas_pendidikan_pagi' => 'nullable|string|max:255',

                'pendidikan_sore' => 'nullable|string',
                'no_induk_pendidikan_sore' => 'nullable|string|max:255',
                'kelas_pendidikan_sore' => 'nullable|string|max:255',

                'pendidikan_malam' => 'nullable|string',
                'no_induk_pendidikan_malam' => 'nullable|string|max:255',
                'kelas_pendidikan_malam' => 'nullable|string|max:255',

                'wali' => 'nullable|string|max:255',
                'no_telp_wali' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255',
                'ig' => 'nullable|string|max:255',
                'wa' => 'nullable|string|max:255',

                'kampung' => 'nullable|string|max:255',
                'rt' => 'nullable|string|max:10',
                'rw' => 'nullable|string|max:10',
                'desa' => 'nullable|string|max:255',
                'kecamatan' => 'nullable|string|max:255',
                'kabupaten' => 'nullable|string|max:255',
                'provinsi' => 'nullable|string|max:255',
                'kode_pos' => 'nullable|string|max:10',

                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',

                'status' => 'required|string|in:Santri,Alumni,Tidak Jelas',
                'jabatan' => 'nullable|string|max:255',
                'tahun_mondok' => 'nullable|string|max:255',
                'tahun_lulus' => 'nullable|string|max:50',
            ]);

            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('warga', 'public');
            }

            Warga::create($validated);

            return redirect()->route('warga.index')->with('success', 'Data warga berhasil ditambahkan.');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan pada database: '.$e->getMessage());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan data: '.$e->getMessage());
        }
    }

    public function edit(Warga $warga)
    {
        return Inertia::render('Admin/Warga/Edit', [
            'warga' => $warga,
        ]);
    }

    public function update(Request $request, Warga $warga)
    {
        try {
            $validated = $request->validate([
                'foto' => 'nullable|image',
                'nama' => 'required|string|max:255',
                'nis' => 'required|string|unique:wargas,nis,'.$warga->id,
                'asrama' => 'nullable|string|max:255',

                'pendidikan_pagi' => 'nullable|string',
                'no_induk_pendidikan_pagi' => 'nullable|string|max:255',
                'kelas_pendidikan_pagi' => 'nullable|string|max:255',

                'pendidikan_sore' => 'nullable|string',
                'no_induk_pendidikan_sore' => 'nullable|string|max:255',
                'kelas_pendidikan_sore' => 'nullable|string|max:255',

                'pendidikan_malam' => 'nullable|string',
                'no_induk_pendidikan_malam' => 'nullable|string|max:255',
                'kelas_pendidikan_malam' => 'nullable|string|max:255',

                'wali' => 'nullable|string|max:255',
                'no_telp_wali' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255',
                'ig' => 'nullable|string|max:255',
                'wa' => 'nullable|string|max:255',

                'kampung' => 'nullable|string|max:255',
                'rt' => 'nullable|string|max:10',
                'rw' => 'nullable|string|max:10',
                'desa' => 'nullable|string|max:255',
                'kecamatan' => 'nullable|string|max:255',
                'kabupaten' => 'nullable|string|max:255',
                'provinsi' => 'nullable|string|max:255',
                'kode_pos' => 'nullable|string|max:10',

                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',

                'status' => 'required|string|in:Santri,Alumni,Tidak Jelas',
                'jabatan' => 'nullable|string|max:255',
                'tahun_mondok' => 'nullable|string|max:255',
                'tahun_lulus' => 'nullable|string|max:50',
            ]);

            if ($request->hasFile('foto')) {
                if ($warga->foto) {
                    Storage::disk('public')->delete($warga->foto);
                }
                $validated['foto'] = $request->file('foto')->store('warga', 'public');
            } else {
                unset($validated['foto']);
            }

            $warga->update($validated);

            return redirect()->route('warga.index')->with('success', 'Data warga berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data: '.$e->getMessage());
        }
    }

    public function destroy(Warga $warga)
    {
        try {
            if ($warga->foto) {
                Storage::disk('public')->delete($warga->foto);
            }

            $warga->delete();

            return redirect()->back()->with('success', 'Data warga berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    /**
     * EXPORT DINAMIS
     */
    public function exportPDF(Request $request)
    {
        $query = Warga::query();

        // Terapkan filter pencarian
        if ($search = $request->get('search')) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nis', 'like', "%$search%");
        }

        // Sorting
        if ($sortBy = $request->get('sort_by')) {
            $direction = $request->get('sort_direction', 'asc');
            $query->orderBy($sortBy, $direction);
        }

        $wargas = $query->get();

        $pdf = Pdf::loadView('exports.warga-pdf', compact('wargas'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-warga.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction', 'asc');

        return Excel::download(new WargaExport($search, $sortBy, $sortDirection), 'data-warga.xlsx');
    }
}
