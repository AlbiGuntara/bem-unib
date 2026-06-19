<?php

namespace App\Http\Controllers\Public;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::first();
        $faqs = Faq::latest()->get();

        return Inertia::render('Public/Contact', [
            'contacts' => $contacts,
            'faqs' => $faqs,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'category' => 'required|in:saran,kritik,aspirasi,aduan',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $message = new Message();
        $message->name = $validated['name'];
        $message->nim = $validated['nim'];
        $message->email = $validated['email'];
        $message->category = $validated['category'];
        $message->subject = $validated['subject'];
        $message->content = $validated['content'];
        $message->status = 'unread';

        if ($request->hasFile('attachment')) {
            $message->attachment = $request->file('attachment')->store('messages', 'public');
        }

        $message->save();

        event(new NewMessageEvent($message));

        return back()->with('success', 'Pesan Anda berhasil dikirim. Terima kasih!');
    }
}
