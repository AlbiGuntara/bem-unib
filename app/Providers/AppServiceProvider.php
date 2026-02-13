<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    Inertia::share([
        'auth' => function () {
            return [
                'user' => Auth::check() ? [
                    'id' => Auth::id(),
                    'nama' => Auth::user()->nama,
                    'foto' => Auth::user()->foto,
                    'jabatan' => Auth::user()->jabatan,
                    'username' => Auth::user()->username,
                ] : null,
            ];
        },
    ]);
}
}
