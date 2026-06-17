<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use App\Models\Dokumentasi;
use Inertia\Inertia;

class ProkerController extends Controller
{
    public function index()
    {
        $prokers = ProgramKerja::with([
            'department',
            'ketuaPanitia',
        ])
        ->select([
            'id',
            'title',
            'description',
            'start_date',
            'end_date',
            'location',
            'departemen_id',
            'pengurus_id',
        ])
        ->where('is_public', true)
        ->orderBy('start_date')
        ->get();

        $documentations = Dokumentasi::with('programKerja')
            ->whereHas('programKerja', function ($query) {
                $query->where('is_public', true);
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,

                    'title' => $item->programKerja?->title,

                    'year' => $item->programKerja?->start_date
                        ? \Carbon\Carbon::parse(
                            $item->programKerja->start_date
                        )->format('Y')
                        : '-',

                    'drive' => $item->link_drive,

                    'image' => $item->thumbnail_portrait
                        ? asset('storage/' . $item->thumbnail_portrait)
                        : (
                            $item->thumbnail_landscape
                                ? asset('storage/' . $item->thumbnail_landscape)
                                : '/images/default-event.jpg'
                        ),
                ];
            });

        return Inertia::render('Public/ProgramKerja', [
            'prokers' => $prokers,
            'documentations' => $documentations,
        ]);
    }
}
