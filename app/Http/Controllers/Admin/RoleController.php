<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions')
            ->withCount('permissions')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->when($request->sort, function ($q) use ($request) {
                $q->orderBy(
                    $request->sort,
                    $request->order ?? 'asc'
                );
            })
            ->paginate($request->perPage ?? 10)
            ->withQueryString();

        return Inertia::render('Admin/Pengguna/Roles', [
            'roles' => $roles,
            'permissions' => Permission::orderBy('name')->get(),
            'filters' => $request->only([
                'search',
                'perPage',
                'sort',
                'order',
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Role berhasil dibuat');
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->with(
                'error',
                'Role Super Admin tidak dapat diubah'
            );
        }

        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Role berhasil diupdate');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->with('error', 'Super Admin tidak bisa dihapus');
        }

        $role->delete();

        return back()->with('success', 'Role berhasil dihapus');
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]); 

        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permission role berhasil disimpan');
    }
}
