<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Contact;
use App\Models\Department;
use App\Models\Dokumentasi;
use App\Models\Faq;
use App\Models\IncomingLetter;
use App\Models\Message;
use App\Models\OutgoingLetter;
use App\Models\Pengurus;
use App\Models\ProgramKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDepartemen = Department::count();
        $totalPengurus = Pengurus::count();
        $totalProgramKerja = ProgramKerja::count();
        $totalBerita = Berita::count();
        $totalDokumentasi = Dokumentasi::count();
        $totalPesan = Message::count();
        $totalFaq = Faq::count();
        $totalUsers = User::count();
        $totalSuratMasuk = IncomingLetter::count();
        $totalSuratKeluar = OutgoingLetter::count();

        $prokerByStatus = ProgramKerja::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $prokerByDepartemen = Department::withCount('programKerja')
            ->get()
            ->map(fn($d) => ['name' => $d->name, 'total' => $d->program_kerja_count]);

        $messageByCategory = Message::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $messageByStatus = Message::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $beritaByMonth = Berita::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($b) => ['month' => $b->month, 'total' => $b->total]);

        $recentBerita = Berita::latest()->take(5)->get(['id', 'title', 'category', 'created_at']);

        $unreadMessages = Message::where('status', 'unread')->latest()->take(5)->get(['id', 'name', 'subject', 'created_at']);

        $prokerTerdekat = ProgramKerja::where('status', 'rencana')
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get(['id', 'title', 'start_date']);

        $programKerjaByStatusLabels = ['Rencana', 'Berjalan', 'Selesai', 'Batal'];
        $programKerjaByStatusData = [
            $prokerByStatus['rencana'] ?? 0,
            $prokerByStatus['berjalan'] ?? 0,
            $prokerByStatus['selesai'] ?? 0,
            $prokerByStatus['batal'] ?? 0,
        ];

        $messageCategoryLabels = ['Saran', 'Kritik', 'Aspirasi', 'Aduan'];
        $messageCategoryData = [
            $messageByCategory['saran'] ?? 0,
            $messageByCategory['kritik'] ?? 0,
            $messageByCategory['aspirasi'] ?? 0,
            $messageByCategory['aduan'] ?? 0,
        ];

        $messageStatusLabels = ['Belum Dibaca', 'Dibaca', 'Dibalas'];
        $messageStatusData = [
            $messageByStatus['unread'] ?? 0,
            $messageByStatus['read'] ?? 0,
            $messageByStatus['replied'] ?? 0,
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalDepartemen' => $totalDepartemen,
                'totalPengurus' => $totalPengurus,
                'totalProgramKerja' => $totalProgramKerja,
                'totalBerita' => $totalBerita,
                'totalDokumentasi' => $totalDokumentasi,
                'totalPesan' => $totalPesan,
                'totalFaq' => $totalFaq,
                'totalUsers' => $totalUsers,
                'totalSuratMasuk' => $totalSuratMasuk,
                'totalSuratKeluar' => $totalSuratKeluar,
                'unreadMessages' => $messageByStatus['unread'] ?? 0,
            ],
            'prokerByDepartemen' => $prokerByDepartemen,
            'programKerjaByStatusLabels' => $programKerjaByStatusLabels,
            'programKerjaByStatusData' => $programKerjaByStatusData,
            'messageCategoryLabels' => $messageCategoryLabels,
            'messageCategoryData' => $messageCategoryData,
            'messageStatusLabels' => $messageStatusLabels,
            'messageStatusData' => $messageStatusData,
            'beritaByMonth' => $beritaByMonth,
            'recentBerita' => $recentBerita,
            'unreadMessages' => $unreadMessages,
            'prokerTerdekat' => $prokerTerdekat,
        ]);
    }
}
