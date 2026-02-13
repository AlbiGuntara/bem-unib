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

        return Inertia::render('Admin/Pengguna/Roles', [
            'roles' => $roles,
            'permissions' => Permission::orderBy('name')->get(),
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
            'name' => 'required|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Role berhasil dibuat');
    }

    public function update(Request $request, Role $role)
    {
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
            'permissions' => 'array',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permission role berhasil disimpan');
    }
}
