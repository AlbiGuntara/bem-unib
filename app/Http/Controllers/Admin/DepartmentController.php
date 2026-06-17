<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Storage;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('code', 'like', "%{$request->search}%");
        }

        if ($request->sort) {
            $query->orderBy(
                $request->sort,
                $request->order ?? 'asc'
            );
        }

        $departments = $query
            ->paginate($request->perPage ?? 10)
            ->withQueryString();

        return Inertia::render(
            'Admin/Departemen/Index',
            [
                'departments' => $departments,
                'filters' => $request->only([
                    'search',
                    'sort',
                    'order',
                    'perPage',
                ]),
            ]
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:departments',
            'description' => 'nullable',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request
                ->file('logo')
                ->store('departments', 'public');
        }

        Department::create($data);

        return back()->with(
            'success',
            'Data berhasil ditambahkan.'
        );
    }

    public function update(
        Request $request,
        Department $department
    ) {
        $data = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:departments,code,' . $department->id,
            'description' => 'nullable',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {

            if ($department->logo_path) {
                Storage::disk('public')
                    ->delete($department->logo_path);
            }

            $data['logo_path'] = $request
                ->file('logo')
                ->store('departments', 'public');
        }

        $department->update($data);

        return back()->with(
            'success',
            'Data berhasil diperbarui.'
        );
    }

    public function destroy(Department $department)
    {
        if ($department->logo_path) {
            Storage::disk('public')
                ->delete($department->logo_path);
        }

        $department->delete();

        return back()->with(
            'success',
            'Data berhasil dihapus.'
        );
    }
}