<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::all();

        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => $contacts,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'maps_embed' => 'nullable',
        ]);

        Contact::create($data);

        return back()->with('success', 'Kontak berhasil ditambahkan.');
    }

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'email' => 'sometimes|email',
            'phone' => 'sometimes',
            'address' => 'sometimes',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'maps_embed' => 'nullable',
        ]);

        $contact->update($data);

        return back()->with('success', 'Kontak berhasil diperbarui.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return back()->with('success', 'Kontak berhasil dihapus.');
    }
}
