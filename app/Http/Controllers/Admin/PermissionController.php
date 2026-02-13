<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::query()
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->when($request->sort_by, function ($q) use ($request) {
                $q->orderBy(
                    $request->sort_by,
                    $request->sort_direction ?? 'asc'
                );
            })
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('Admin/Pengguna/Permissions', [
            'permissions' => $permissions,
            'filters' => $request->only([
                'search',
                'per_page',
                'sort_by',
                'sort_direction',
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Permission berhasil ditambahkan');
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Permission berhasil diupdate');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('success', 'Permission berhasil dihapus');
    }
}
