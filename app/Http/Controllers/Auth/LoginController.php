<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->username,
            'password' => $request->password,
        ];

        $remember = $request->has('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return redirect()->back()
                ->with('error', 'Username/Email atau Password salah!')
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Selamat datang kembali, '.Auth::user()->name.'!');
    }
}
