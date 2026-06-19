<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->search) {
            $query->where('question', 'like', "%{$request->search}%")
                ->orWhere('answer', 'like', "%{$request->search}%");
        }

        if ($request->sort) {
            $query->orderBy($request->sort, $request->order ?? 'asc');
        }

        $faqs = $query->paginate($request->perPage ?? 10)->withQueryString();

        return Inertia::render('Admin/Faqs/Index', [
            'faqs' => $faqs,
            'filters' => $request->only(['search', 'sort', 'order', 'perPage']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        Faq::create($data);

        return back()->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faq->update($data);

        return back()->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ berhasil dihapus.');
    }
}
