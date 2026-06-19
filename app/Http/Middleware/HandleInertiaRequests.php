<?php

namespace App\Http\Middleware;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $isAdminRoute = str_starts_with($request->route()?->getPrefix() ?? '', 'admin');

        return array_merge(parent::share($request), [
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],

            'auth' => fn() => [
                'user' => Auth::check() ? [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'avatar' => Auth::user()->avatar ?? '/images/default-avatar.jpg',
                    'roles' => $request->user()->getRoleNames(),
                    'permissions' => $request->user()->getAllPermissions()->pluck('name'),
                ] : null,
            ],

            'unreadMessagesCount' => fn() => Auth::check() && $isAdminRoute
                ? Cache::remember('unread_messages_count', 300, fn() => Message::where('status', 'unread')->count())
                : 0,

            'recaptchaSiteKey' => config('services.recaptcha.site_key'),
        ]);
    }
}
