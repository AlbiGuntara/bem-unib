<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ProgramKerja;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProgramKerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramKerja::with('department', 'ketuaPanitia');

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->sort) {
            $query->orderBy(
                $request->sort,
                $request->order ?? 'asc'
            );
        } else {
            $query->latest();
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->departemen_id) {
            $query->where('departemen_id', $request->departemen_id);
        }

        if ($request->pengurus_id) {
            $query->where('pengurus_id', $request->pengurus_id);
        }

        if ($request->is_public !== null && $request->is_public !== '') {
            $query->where('is_public', $request->is_public);
        }

        if ($request->date_from || $request->date_to) {

            $from = $request->date_from;
            $to = $request->date_to;

            $query->where(function ($q) use ($from, $to) {

                $q->where(function ($sub) use ($from, $to) {

                    $sub->whereNotNull('end_date');

                    if ($from && $to) {
                        $sub->whereDate('start_date', '<=', $to)
                            ->whereDate('end_date', '>=', $from);
                    } elseif ($from) {
                        $sub->whereDate('end_date', '>=', $from);
                    } elseif ($to) {
                        $sub->whereDate('start_date', '<=', $to);
                    }
                })

                ->orWhere(function ($sub) use ($from, $to) {

                    $sub->whereNull('end_date');

                    if ($from && $to) {
                        $sub->whereBetween('start_date', [$from, $to]);
                    } elseif ($from) {
                        $sub->whereDate('start_date', '>=', $from);
                    } elseif ($to) {
                        $sub->whereDate('start_date', '<=', $to);
                    }
                });
            });
        }

        $prokers = $query->paginate(
            $request->perPage ?? 10
        )->withQueryString();

        return Inertia::render(
            'Admin/Proker/Index',
            [
                'prokers' => $prokers,
                'calendarEvents' => ProgramKerja::with(
                    'department',
                    'ketuaPanitia'
                )->get(),
                'filters' => $request->only([
                    'search',
                    'sort',
                    'order',
                    'perPage',
                    'status',
                    'departemen_id',
                    'pengurus_id',
                    'is_public',
                    'date_from',
                    'date_to',
                ]),
                'departments' => Department::all(),
                'pengurus' => Pengurus::select(
                    'id',
                    'name',
                    'position'
                )->orderBy('name')->get(),
            ]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'description' => ['nullable'],
            'start_date' => ['nullable'],
            'end_date' => ['nullable'],
            'location' => ['nullable'],
            'departemen_id' => ['nullable'],
            'pengurus_id' => ['nullable','exists:pengurus,id'],
            'participants' => ['nullable', 'integer', 'min:0'],
            'budget' => ['nullable'],
            'realization_participants' => ['nullable','integer','min:0'],
            'realization_budget' => ['nullable','numeric','min:0'],
            'time' => ['nullable', 'date_format:H:i'],
            'is_public' => ['boolean'],
            'status' => ['required'],
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        ProgramKerja::create($validated);

        return back()->with('success', 'Program berhasil ditambah.');
    }

    public function update(Request $request, ProgramKerja $program_kerja)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'description' => ['nullable'],
            'start_date' => ['nullable'],
            'end_date' => ['nullable'],
            'location' => ['nullable'],
            'departemen_id' => ['nullable'],
            'pengurus_id' => ['nullable','exists:pengurus,id'],
            'participants' => ['nullable', 'integer', 'min:0'],
            'budget' => ['nullable'],
            'realization_participants' => ['nullable','integer','min:0'],
            'realization_budget' => ['nullable','numeric','min:0'],
            'time' => ['nullable', 'date_format:H:i'],
            'is_public' => ['boolean'],
            'status' => ['required'],
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $program_kerja->update($validated);

        return back()->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(ProgramKerja $program_kerja)
    {
        $program_kerja->delete();

        return back()->with('success', 'Program berhasil dihapus.');
    }

    public function updateStatus(
        Request $request,
        ProgramKerja $program_kerja
    ) {
        $request->validate([
            'status' => [
                'required',
                'in:rencana,berjalan,selesai,batal'
            ]
        ]);

        $program_kerja->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function updatePublic(
        Request $request,
        ProgramKerja $program_kerja
    ) {
        $request->validate([
            'is_public' => ['required','boolean']
        ]);

        $program_kerja->update([
            'is_public' => $request->is_public
        ]);

        return back()->with('success', 'Publikasi berhasil diperbarui.');
    }

    public function complete(
        Request $request,
        ProgramKerja $program_kerja
    )
    {
        $validated = $request->validate([
            'realization_participants' => [
                'required',
                'integer',
                'min:0'
            ],

            'realization_budget' => [
                'required',
                'numeric',
                'min:0'
            ],
        ]);

        $program_kerja->update([
            'status' => 'selesai',

            'realization_participants'
                => $validated['realization_participants'],

            'realization_budget'
                => $validated['realization_budget'],
        ]);

        return back()->with(
            'success',
            'Program kerja berhasil diselesaikan.'
        );
    }
}