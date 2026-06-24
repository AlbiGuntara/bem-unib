<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kolaborasi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KolaborasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Kolaborasi::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->sort) {
            $query->orderBy($request->sort, $request->order ?? 'asc');
        } else {
            $query->orderBy('order');
        }

        $kolaborasis = $query->paginate($request->perPage ?? 10)->withQueryString();

        return Inertia::render('Admin/Kolaborasi/Index', [
            'kolaborasis' => $kolaborasis,
            'filters' => $request->only(['search', 'sort', 'order', 'perPage']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'logo' => 'required|image',
            'type' => 'required|in:external,internal',
            'url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
        ]);

        $data['logo'] = $request->file('logo')->store('kolaborasi', 'public');

        if (!isset($data['order'])) {
            $maxOrder = Kolaborasi::max('order');
            $data['order'] = ($maxOrder ?? 0) + 1;
        }

        Kolaborasi::create($data);

        return back()->with('success', 'Kolaborasi berhasil ditambahkan.');
    }

    public function update(Request $request, Kolaborasi $kolaborasi)
    {
        $data = $request->validate([
            'name' => 'required',
            'logo' => 'nullable|image',
            'type' => 'required|in:external,internal',
            'url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('kolaborasi', 'public');
        } else {
            unset($data['logo']);
        }

        $kolaborasi->update($data);

        return back()->with('success', 'Kolaborasi berhasil diperbarui.');
    }

    public function destroy(Kolaborasi $kolaborasi)
    {
        $kolaborasi->delete();

        return back()->with('success', 'Kolaborasi berhasil dihapus.');
    }
}
