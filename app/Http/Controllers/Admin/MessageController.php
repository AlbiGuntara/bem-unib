<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MessageReplyMail;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Storage;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('subject', 'like', "%{$request->search}%");
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->sort) {
            $query->orderBy($request->sort, $request->order ?? 'asc');
        } else {
            $query->latest();
        }

        $messages = $query->paginate($request->perPage ?? 10)->withQueryString();

        $stats = [
            'total' => Message::count(),
            'unread' => Message::where('status', 'unread')->count(),
            'read' => Message::where('status', 'read')->count(),
            'replied' => Message::where('status', 'replied')->count(),
        ];

        $categoryStats = Message::query()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return Inertia::render('Admin/Messages/Index', [
            'messages' => $messages,
            'filters' => $request->only(['search', 'sort', 'order', 'perPage', 'category', 'status', 'date_from', 'date_to']),
            'stats' => $stats,
            'categoryStats' => $categoryStats,
        ]);
    }

    public function show(Message $message)
    {
        if ($message->status === 'unread') {
            $message->update(['status' => 'read']);
        }

        return Inertia::render('Admin/Messages/Show', [
            'message' => $message,
        ]);
    }

    public function reply(Request $request, Message $message)
    {
        $data = $request->validate([
            'reply_content' => 'required',
        ]);

        $message->update([
            'reply_content' => $data['reply_content'],
            'status' => 'replied',
            'replied_at' => now(),
        ]);

        $replierName = Auth::user()->name ?? 'Admin';

        try {
            Mail::to($message->email)->send(new MessageReplyMail($message, $replierName));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email balasan ke ' . $message->email . ': ' . $e->getMessage());
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Message $message)
    {
        $data = $request->validate([
            'status' => 'required|in:unread,read,replied',
        ]);

        $message->update($data);

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:messages,id',
            'action' => 'required|in:read,deleted',
        ]);

        $query = Message::whereIn('id', $request->ids);

        if ($request->action === 'deleted') {
            foreach ($query->get() as $message) {
                if ($message->attachment) {
                    Storage::disk('public')->delete($message->attachment);
                }
            }
            $query->delete();
        } else {
            $query->update(['status' => $request->action]);
        }

        return back()->with('success', 'Tindakan berhasil dilakukan.');
    }

    public function destroy(Message $message)
    {
        if ($message->attachment) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return back()->with('success', 'Pesan berhasil dihapus.');
    }

    public function unreadCount()
    {
        return response()->json([
            'unread' => Message::where('status', 'unread')->count(),
        ]);
    }
}
