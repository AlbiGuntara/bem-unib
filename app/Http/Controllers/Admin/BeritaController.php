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

        unset($validated['thumbnail']);

        if ($request->hasFile('thumbnail')) {

            if ($berita->thumbnail) {
                Storage::disk('public')->delete($berita->thumbnail);
            }

            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('berita', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        $berita->update($validated);

        return redirect()
            ->route('berita.index')
            ->with('success', 'Berita berhasil diperbarui');
    }

    public function updateStatus(Request $request, Berita $berita)
    {
        $request->validate([
            'is_published' => ['required', 'boolean']
        ]);

        $berita->update([
            'is_published' => $request->boolean('is_published')
        ]);

        return redirect()
            ->route('berita.index')
            ->with('success', 'Status berhasil diperbarui');
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

    public function indexPublic(Request $request)
    {
        $beritas = Berita::with('user')
            ->where('is_published', true)
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Berita::where('is_published', true)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        $featured = Berita::with('user')
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        $latest = Berita::with('user')
            ->where('is_published', true)
            ->latest()
            ->take(5)
            ->get();

        $popular = Berita::with('user')
            ->where('is_published', true)
            ->where('views', '>', 0)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $totalNews = Berita::where('is_published', true)
            ->count();

        $totalCategories = Berita::where('is_published', true)
            ->distinct('category')
            ->count('category');

        $totalViews = Berita::where('is_published', true)
            ->sum('views');

        return Inertia::render('Public/Berita/Index', [
            'beritas' => $beritas,
            'categories' => $categories,
            'featured' => $featured,
            'latest' => $latest,
            'popular' => $popular,
            'filters' => $request->only(['search', 'category']),
            'stats' => [
                'totalNews' => $totalNews,
                'totalCategories' => $totalCategories,
                'totalViews' => $totalViews,
            ],
        ]);
    }

    public function showPublic($slug)
    {
        $berita = Berita::with('user')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $berita->increment('views');

        $related = Berita::with('user')
            ->where('is_published', true)
            ->where('category', $berita->category)
            ->where('id', '!=', $berita->id)
            ->latest()
            ->take(3)
            ->get();

        $latest = Berita::with('user')
            ->where('is_published', true)
            ->where('id', '!=', $berita->id)
            ->latest()
            ->take(5)
            ->get();

        $popular = Berita::with('user')
            ->where('is_published', true)
            ->where('views', '>', 0)
            ->where('id', '!=', $berita->id)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $categories = Berita::where('is_published', true)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return Inertia::render('Public/Berita/Show', [
            'berita' => $berita,
            'related' => $related,
            'latest' => $latest,
            'popular' => $popular,
            'categories' => $categories,
        ]);
    }

    public function preview(Berita $berita)
    {
        $related = Berita::with('user')
            ->where('is_published', true)
            ->where('category', $berita->category)
            ->where('id', '!=', $berita->id)
            ->latest()
            ->take(3)
            ->get();

        $latest = Berita::with('user')
            ->where('is_published', true)
            ->where('id', '!=', $berita->id)
            ->latest()
            ->take(5)
            ->get();

        $popular = Berita::with('user')
            ->where('is_published', true)
            ->where('views', '>', 0)
            ->where('id', '!=', $berita->id)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $categories = Berita::where('is_published', true)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        $berita->load('user');

        return Inertia::render('Public/Berita/Show', [
            'berita' => $berita,
            'related' => $related,
            'latest' => $latest,
            'popular' => $popular,
            'categories' => $categories,
            'isPreview' => true,
        ]);
    }
}
