<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DokumentasiController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dokumentasi/Index', [
            'dokumentasis' => Dokumentasi::with([
                'programKerja.department'
            ])->latest()->get(),

            'programKerjas' => ProgramKerja::with('department')
                ->whereIn('status', [
                    'berjalan',
                    'selesai'
                ])
                ->select(
                    'id',
                    'title',
                    'departemen_id',
                    'start_date',
                    'status'
                )
                ->orderBy('title')
                ->get(),

            'departments' => \App\Models\Department::select(
                'id',
                'name'
            )->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_kerjas_id' => ['required','exists:program_kerjas,id'],
            'link_drive' => ['nullable','url'],
            'thumbnail_portrait' => ['nullable','image'],
            'thumbnail_landscape' => ['nullable','image'],
        ]);

        $program = ProgramKerja::findOrFail(
            $validated['program_kerjas_id']
        );

        if (!in_array($program->status, [
            'berjalan',
            'selesai'
        ])) {
            return back()->withErrors([
                'program_kerjas_id' =>
                'Program kerja harus berstatus berjalan atau selesai.'
            ]);
        }

        if ($request->hasFile('thumbnail_portrait')) {
            $validated['thumbnail_portrait'] = $request->file('thumbnail_portrait')
                ->store('dokumentasi/portrait', 'public');
        }

        if ($request->hasFile('thumbnail_landscape')) {
            $validated['thumbnail_landscape'] = $request->file('thumbnail_landscape')
                ->store('dokumentasi/landscape', 'public');
        }

        Dokumentasi::create($validated);

        return back()->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        $validated = $request->validate([
            'program_kerjas_id' => ['required','exists:program_kerjas,id'],
            'link_drive' => ['nullable','url'],
            'thumbnail_portrait' => ['nullable','image'],
            'thumbnail_landscape' => ['nullable','image'],
        ]);

        $program = ProgramKerja::findOrFail(
            $validated['program_kerjas_id']
        );

        if (!in_array($program->status, [
            'berjalan',
            'selesai'
        ])) {
            return back()->withErrors([
                'program_kerjas_id' =>
                'Program kerja harus berstatus berjalan atau selesai.'
            ]);
        }

        if ($request->hasFile('thumbnail_portrait')) {
            if ($dokumentasi->thumbnail_portrait) {
                Storage::disk('public')->delete($dokumentasi->thumbnail_portrait);
            }

            $validated['thumbnail_portrait'] = $request->file('thumbnail_portrait')
                ->store('dokumentasi/portrait', 'public');
        }

        if ($request->hasFile('thumbnail_landscape')) {
            if ($dokumentasi->thumbnail_landscape) {
                Storage::disk('public')->delete($dokumentasi->thumbnail_landscape);
            }

            $validated['thumbnail_landscape'] = $request->file('thumbnail_landscape')
                ->store('dokumentasi/landscape', 'public');
        }

        $dokumentasi->update($validated);

        return back()->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Dokumentasi $dokumentasi)
    {
        if ($dokumentasi->thumbnail_portrait) {
            Storage::disk('public')->delete($dokumentasi->thumbnail_portrait);
        }

        if ($dokumentasi->thumbnail_landscape) {
            Storage::disk('public')->delete($dokumentasi->thumbnail_landscape);
        }

        $dokumentasi->delete();

        return back()->with('success', 'Dokumentasi berhasil dihapus.');
    }

    public function destroyThumbnail(
        Dokumentasi $dokumentasi,
        string $type
    ) {
        if (!in_array($type, ['portrait', 'landscape'])) {
            abort(404);
        }

        $column =
            $type === 'portrait'
            ? 'thumbnail_portrait'
            : 'thumbnail_landscape';

        // hapus file thumbnail yg dipilih
        if ($dokumentasi->$column) {

            Storage::disk('public')
                ->delete($dokumentasi->$column);

            $dokumentasi->update([
                $column => null
            ]);
        }

        // cek apakah thumbnail lain masih ada
        $hasPortrait =
            !empty($dokumentasi->fresh()->thumbnail_portrait);

        $hasLandscape =
            !empty($dokumentasi->fresh()->thumbnail_landscape);

        // jika sudah tidak ada thumbnail sama sekali
        if (!$hasPortrait && !$hasLandscape) {

            $dokumentasi->delete();

            return back()->with(
                'success',
                'Dokumentasi berhasil dihapus.'
            );
        }

        return back()->with(
            'success',
            'Thumbnail berhasil dihapus.'
        );
    }
}