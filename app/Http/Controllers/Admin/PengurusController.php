<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PengurusController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort');
        $order = $request->get('order', 'asc');

        $query = Pengurus::with('department');

        if ($sort === 'department') {
            $query->leftJoin(
                'departments',
                'pengurus.department_id',
                '=',
                'departments.id'
            )
            ->select('pengurus.*')
            ->orderBy('departments.name', $order);
        } elseif (!empty($sort)) {
            $query->orderBy($sort, $order);
        }

        $pengurus = $query
            ->paginate($request->perPage ?? 10)
            ->withQueryString();

        return Inertia::render('Admin/Pengurus/Index', [
            'pengurus' => $pengurus,
            'departments' => Department::all(),
            'filters' => $request->only([
                'search',
                'sort',
                'order',
                'perPage',
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required'],
            'name' => ['required'],
            'npm' => ['required'],
            'email' => ['required', 'email'],
            'position' => ['required'],
            'photo' => ['nullable', 'image'],
            'instagram' => ['nullable'],
            'facebook' => ['nullable'],
            'whatsapp' => ['nullable'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request
                ->file('photo')
                ->store('pengurus', 'public');
        }

        Pengurus::create($validated);

        return back()->with(
            'success',
            'Data berhasil ditambahkan.'
        );
    }

    public function update(Request $request, Pengurus $penguru)
    {
        $validated = $request->validate([
            'department_id' => ['required'],
            'name' => ['required'],
            'npm' => ['required'],
            'email' => ['required', 'email'],
            'position' => ['required'],
            'photo' => ['nullable', 'image'],
            'instagram' => ['nullable'],
            'facebook' => ['nullable'],
            'whatsapp' => ['nullable'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request
                ->file('photo')
                ->store('pengurus', 'public');
        } else {
            unset($validated['photo']);
        }

        $penguru->update($validated);

        return back()->with(
            'success',
            'Data berhasil diperbarui.'
        );
    }

    public function destroy(Pengurus $penguru)
    {
        $penguru->delete();

        return back()->with(
            'success',
            'Data berhasil dihapus.'
        );
    }
}