<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use App\Models\Berita;
use App\Models\Kolaborasi;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $pastEvents = ProgramKerja::with('dokumentasi')
            ->where('is_public', true)
            ->where('status', 'selesai') // sesuaikan dengan enum/status milikmu
            ->whereDate('end_date', '<', now())
            ->latest('end_date')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $thumbnail =
                    $item->dokumentasi->first()?->thumbnail_landscape
                    ?? $item->dokumentasi->first()?->thumbnail_portrait;

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'date' => $item->start_date
                        ? Carbon::parse($item->start_date)->translatedFormat('d M Y')
                        : '-',
                    'description' => $item->description,
                    'location' => $item->location,
                    'participants' => $item->realization_participants
                        ?? $item->participants,
                    'image' => $thumbnail
                        ? asset('storage/' . $thumbnail)
                        : '/images/default-event.jpg',
                ];
            });

        $ongoingEvent = ProgramKerja::query()
            ->where('is_public', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('start_date')
            ->first();

        $nextEvent = ProgramKerja::query()
            ->where('is_public', true)
            ->whereDate('start_date', '>', now())
            ->orderBy('start_date')
            ->first();

        $latestNews = Berita::with('user')
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,

                    'slug' => $item->slug,

                    'date' => $item->created_at
                        ->translatedFormat('d F Y'),

                    'category' => $item->category,

                    'excerpt' => \Illuminate\Support\Str::limit(
                        strip_tags($item->content),
                        120
                    ),

                    'image' => $item->thumbnail
                        ? asset('storage/' . $item->thumbnail)
                        : '/images/default-news.jpg',

                    'author' => $item->user?->name,

                    'views' => $item->views,
                ];
            });

        $externalPartners = Kolaborasi::where('type', 'external')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'logo' => asset('storage/' . $item->logo),
                'url' => $item->url,
            ]);

        $internalPartners = Kolaborasi::where('type', 'internal')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'logo' => asset('storage/' . $item->logo),
                'url' => $item->url,
            ]);

        return Inertia::render('Public/Home', [
            'pastEvents' => $pastEvents,

            'ongoingEvent' => $ongoingEvent
                ? [
                    'id' => $ongoingEvent->id,
                    'title' => $ongoingEvent->title,
                    'location' => $ongoingEvent->location,
                    'description' => $ongoingEvent->description,
                    'start_date' => $ongoingEvent->start_date,
                    'end_date' => $ongoingEvent->end_date,
                ]
                : null,

            'nextEvent' => $nextEvent
                ? [
                    'id' => $nextEvent->id,
                    'title' => $nextEvent->title,
                    'location' => $nextEvent->location,
                    'description' => $nextEvent->description,

                    'date' => Carbon::parse(
                        $nextEvent->start_date
                    )->translatedFormat('d F Y'),

                    'targetDate' => Carbon::parse(
                        $nextEvent->start_date
                    )->format('Y-m-d\T00:00:00'),

                    'endDate' => Carbon::parse(
                        $nextEvent->end_date
                    )->format('Y-m-d\T23:59:59'),
                ]
                : null,

            'latestNews' => $latestNews,
            'externalPartners' => $externalPartners,
            'internalPartners' => $internalPartners,
        ]);
    }
}