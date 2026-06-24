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
        $contacts = Contact::orderBy('type')->latest()->get();

        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => $contacts,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:email,phone,address,instagram,tiktok,youtube,facebook,maps',
            'value' => 'required',
        ]);

        Contact::create($data);

        return back()->with('success', 'Kontak berhasil ditambahkan.');
    }

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'type' => 'required|in:email,phone,address,instagram,tiktok,youtube,facebook,maps',
            'value' => 'required',
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
