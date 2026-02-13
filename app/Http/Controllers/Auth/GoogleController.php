<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Admin\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Arahkan user ke halaman login Google
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        // Ambil data user dari Google
        $googleUser = Socialite::driver('google')->user();

        // Simpan atau update user berdasarkan email
        $user = User::updateOrCreate(
            [
                'email' => $googleUser->getEmail(),
            ],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(str()->random(16)),
            ]
        );

        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . Auth::user()->name . '!');;
    }
}
