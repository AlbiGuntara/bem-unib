<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IncomingLetter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Storage;

class IncomingLetterController extends Controller
{
    public function index(Request $request)
    {
        $query = IncomingLetter::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('letter_number', 'like', "%{$request->search}%")
                    ->orWhere('subject', 'like', "%{$request->search}%")
                    ->orWhere('sender', 'like', "%{$request->search}%");
            });
        }

        if ($request->sort) {
            $query->orderBy(
                $request->sort,
                $request->order ?? 'asc'
            );
        }

        $letters = $query
            ->with('user')
            ->paginate($request->perPage ?? 10)
            ->withQueryString();

        return Inertia::render('Admin/SuratMenyurat/Masuk/Index', [
            'letters' => $letters,
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
        $data = $request->validate([
            'letter_number' => 'required',
            'subject' => 'required',
            'sender' => 'required',
            'date' => 'required|date',
            'file' => 'nullable|file|max:5120',
        ]);

        $data['user_id'] = auth()->id();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request
                ->file('file')
                ->store('incoming-letters', 'public');
        }

        IncomingLetter::create($data);

        return back()->with(
            'success',
            'Data berhasil ditambahkan.'
        );
    }

    public function update(Request $request, IncomingLetter $incoming_letter)
    {
        $data = $request->validate([
            'letter_number' => 'required',
            'subject' => 'required',
            'sender' => 'required',
            'date' => 'required|date',
            'file' => 'nullable|file|max:5120',
        ]);

        if ($request->hasFile('file')) {

            if ($incoming_letter->file_path) {
                Storage::disk('public')
                    ->delete($incoming_letter->file_path);
            }

            $data['file_path'] = $request
                ->file('file')
                ->store('incoming-letters', 'public');
        }

        $incoming_letter->update($data);

        return back()->with(
            'success',
            'Data berhasil diperbarui.'
        );
    }

    public function destroy(IncomingLetter $incoming_letter)
    {
        if ($incoming_letter->file_path) {
            Storage::disk('public')
                ->delete($incoming_letter->file_path);
        }

        $incoming_letter->delete();

        return back()->with(
            'success',
            'Data berhasil dihapus.'
        );
    }
}
