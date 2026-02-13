<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->orWhere('username', 'like', '%'.$request->search.'%');
        }

        if ($request->sort && $request->order) {
            $query->orderBy($request->sort, $request->order);
        }

        return Inertia::render('Admin/Pengguna/Users', [
            'users' => $query->paginate($request->perPage ?? 10)->withQueryString(),
            'roles' => Role::all(),
            'filters' => $request->only(['search', 'sort', 'order', 'perPage']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|unique:users,username',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('users', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'username' => 'nullable|string|unique:users,username,'.$user->id,
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('users', 'public');
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    // Assign role to user
    public function updateRole(Request $request, User $user)
    {
        if ($user->hasRole('super-admin')) {
            return back()->with('error', 'Role Super Admin tidak bisa diubah');
        }

        $data = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$data['role']]);

        return back()->with('success', 'Role user berhasil diperbarui');
    }
}
