<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|unique:users,username',
                'password' => 'required|min:6'
            ], [
                'email.unique' => 'Email sudah digunakan!',
                'username.unique' => 'Username sudah terdaftar!',
            ]);

            // Buat user baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ]);

            // Login otomatis
            Auth::login($user);

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '!');
        } catch (ValidationException $e) {
            // Jika validasi gagal, kirim error ke flash session
            $errors = $e->validator->errors()->all();
            return redirect()->back()
                ->with('error', implode(' ', $errors))
                ->withInput();
        } catch (\Exception $e) {
            // Error lain
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
