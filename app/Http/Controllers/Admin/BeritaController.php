<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::with('user');

        if ($request->search) {
            $query->where(
                'title',
                'like',
                "%{$request->search}%"
            );
        }

        if ($request->sort) {
            $query->orderBy(
                $request->sort,
                $request->order ?? 'asc'
            );
        } else {
            $query->latest();
        }

        $beritas = $query
            ->paginate($request->perPage ?? 10)
            ->withQueryString();

        return Inertia::render(
            'Admin/Berita/Index',
            [
                'beritas' => $beritas,
                'filters' => $request->only([
                    'search',
                    'sort',
                    'order',
                    'perPage',
                ]),
            ]
        );
    }

    public function create()
    {
        return Inertia::render(
            'Admin/Berita/Create',
            [
                'categories' => Berita::query()
                    ->select('category')
                    ->distinct()
                    ->pluck('category')
                    ->filter()
                    ->values()
            ]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => 'required',
                'content' => 'required',
                'thumbnail' => 'nullable|image',
                'category' => 'required',
                'label' => 'nullable|array',
                'is_published' => 'boolean',
            ],
            [
                'title.required' =>
                    'Judul berita wajib diisi.',

                'content.required' =>
                    'Isi berita belum ditulis.',

                'category.required' =>
                    'Kategori berita wajib dipilih.',

                'thumbnail.image' =>
                    'Thumbnail harus berupa gambar.',
            ]
        );

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('berita', 'public');
        }

        $validated['user_id'] = auth()->id();

        $validated['slug'] = Str::slug($validated['title']);

        Berita::create($validated);

        return redirect()
        ->route('berita.index')
        ->with(
            'success',
            'Berita berhasil diperbarui'
        );
    }

    public function edit(Berita $berita)
    {
        return Inertia::render(
            'Admin/Berita/Edit',
            [
                'berita' => $berita,
                'categories' => Berita::query()
                    ->select('category')
                    ->distinct()
                    ->pluck('category')
                    ->filter()
                    ->values(),
            ]
        );
    }

    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'thumbnail' => 'nullable|image',
            'category' => 'required',
            'label' => 'nullable|array',
            'is_published' => 'boolean'
        ]);

        if ($request->hasFile('thumbnail')) {

            if ($berita->thumbnail) {
                Storage::disk('public')
                    ->delete($berita->thumbnail);
            }

            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('berita', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        $berita->update($validated);

        return redirect()
        ->route('berita.index')
        ->with(
            'success',
            'Berita berhasil dibuat'
        );
    }

    public function destroy(Berita $berita)
    {
        if ($berita->thumbnail) {
            Storage::disk('public')
                ->delete($berita->thumbnail);
        }

        $berita->delete();

        return redirect()
            ->route('berita.index')
            ->with(
                'success',
                'Berita berhasil dihapus'
            );
    }
}
