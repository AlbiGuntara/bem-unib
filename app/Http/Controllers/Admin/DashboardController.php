<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Department;
use App\Models\Dokumentasi;
use App\Models\Faq;
use App\Models\IncomingLetter;
use App\Models\Message;
use App\Models\OutgoingLetter;
use App\Models\Pengurus;
use App\Models\ProgramKerja;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => Cache::remember('dashboard:stats', 300, function () {
                return [
                    'totalDepartemen' => Department::count(),
                    'totalPengurus' => Pengurus::count(),
                    'totalProgramKerja' => ProgramKerja::count(),
                    'totalBerita' => Berita::count(),
                    'totalDokumentasi' => Dokumentasi::count(),
                    'totalPesan' => Message::count(),
                    'totalFaq' => Faq::count(),
                    'totalUsers' => User::count(),
                    'totalSuratMasuk' => IncomingLetter::count(),
                    'totalSuratKeluar' => OutgoingLetter::count(),
                    'unreadMessages' => Message::where('status', 'unread')->count(),
                ];
            }),

            'prokerByDepartemen' => fn() => Cache::remember('dashboard:proker_by_departemen', 300, fn() =>
                Department::withCount('programKerja')
                    ->get()
                    ->map(fn($d) => ['name' => $d->name, 'total' => $d->program_kerja_count])
            ),

            'programKerjaByStatusLabels' => ['Rencana', 'Berjalan', 'Selesai', 'Batal'],

            'programKerjaByStatusData' => fn() => Cache::remember('dashboard:proker_status_data', 300, function () {
                $data = ProgramKerja::selectRaw('status, count(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status');

                return [
                    $data['rencana'] ?? 0,
                    $data['berjalan'] ?? 0,
                    $data['selesai'] ?? 0,
                    $data['batal'] ?? 0,
                ];
            }),

            'messageCategoryLabels' => ['Saran', 'Kritik', 'Aspirasi', 'Aduan'],

            'messageCategoryData' => fn() => Cache::remember('dashboard:msg_category_data', 300, function () {
                $data = Message::selectRaw('category, count(*) as total')
                    ->groupBy('category')
                    ->pluck('total', 'category');

                return [
                    $data['saran'] ?? 0,
                    $data['kritik'] ?? 0,
                    $data['aspirasi'] ?? 0,
                    $data['aduan'] ?? 0,
                ];
            }),

            'messageStatusLabels' => ['Belum Dibaca', 'Dibaca', 'Dibalas'],

            'messageStatusData' => fn() => Cache::remember('dashboard:msg_status_data', 300, function () {
                $data = Message::selectRaw('status, count(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status');

                return [
                    $data['unread'] ?? 0,
                    $data['read'] ?? 0,
                    $data['replied'] ?? 0,
                ];
            }),

            'beritaByMonth' => fn() => Cache::remember('dashboard:berita_by_month', 300, fn() =>
                Berita::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as total")
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->map(fn($b) => ['month' => $b->month, 'total' => $b->total])
            ),

            'recentBerita' => fn() => Cache::remember('dashboard:recent_berita', 300, fn() =>
                Berita::latest()->take(5)->get(['id', 'title', 'category', 'created_at'])
            ),

            'unreadMessages' => fn() => Cache::remember('dashboard:unread_messages', 300, fn() =>
                Message::where('status', 'unread')->latest()->take(5)->get(['id', 'name', 'subject', 'created_at'])
            ),

            'prokerTerdekat' => fn() => Cache::remember('dashboard:proker_terdekat', 300, fn() =>
                ProgramKerja::where('status', 'rencana')
                    ->whereDate('start_date', '>=', now())
                    ->orderBy('start_date')
                    ->take(5)
                    ->get(['id', 'title', 'start_date'])
            ),
        ]);
    }
}
