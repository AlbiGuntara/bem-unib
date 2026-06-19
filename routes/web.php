<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PengurusController;
use App\Http\Controllers\Admin\ProgramKerjaController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\IncomingLetterController;
use App\Http\Controllers\Admin\OutgoingLetterController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\ContactController as PublicContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profil', function () {
    return Inertia::render('Public/Profile', [
        'title' => 'Profil',
    ]);
})->name('profil');

Route::get('/program-kerja', [ProkerController::class, 'index'])
    ->name('program-kerja');

Route::get('/berita', [BeritaController::class, 'indexPublic'])
    ->name('berita.public.index');

Route::get('/berita/{slug:slug}', [BeritaController::class, 'showPublic'])
    ->name('berita.public.show');

// Contact & Suggestion Box
Route::get('/kontak', [PublicContactController::class, 'index'])
    ->name('kontak');

Route::post('/kontak', [PublicContactController::class, 'store'])
    ->middleware(['throttle:5,10'])
    ->name('kontak.store');

// Login page
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

// Auth routes
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Anda telah keluar dari akun.');
})->name('logout');

// Copyright Integrity Check
Route::get('/integrity-violation', function () {
    abort(499);
});

// Admin
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // ================= DASHBOARD =================
    Route::get('/', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // ================= USER =================
    Route::get('users', [UserController::class, 'index'])
        ->middleware('permission:view users')
        ->name('users.index');

    Route::post('users', [UserController::class, 'store'])
        ->middleware('permission:create users')
        ->name('users.store');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->middleware('permission:edit users')
        ->name('users.update');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:delete users')
        ->name('users.destroy');

    Route::put('users/{user}/role', [UserController::class, 'updateRole'])
        ->middleware('permission:edit users')
        ->name('users.update-role');

    // ================= DEPARTMENTS =================
    Route::resource('departments',DepartmentController::class)->middleware([
        'index' => 'permission:view departemen',
        'store' => 'permission:create departemen',
        'update' => 'permission:edit departemen',
        'destroy' => 'permission:delete departemen',
    ])->except([
        'create',
        'show',
        'edit'
    ]);;

    // ================= PENGURUS =================
    Route::resource('pengurus',PengurusController::class)->middleware([
        'index' => 'permission:view pengurus',
        'store' => 'permission:create pengurus',
        'update' => 'permission:edit pengurus',
        'destroy' => 'permission:delete pengurus',
    ])->except([
        'create',
        'show',
        'edit'
    ]);;

    // ================= PROGRAM KERJA =================
    Route::resource('program-kerja',ProgramKerjaController::class)->middleware([
        'index' => 'permission:view program-kerja',
        'store' => 'permission:create program-kerja',
        'update' => 'permission:edit program-kerja',
        'destroy' => 'permission:delete program-kerja',
    ])->except([
        'create',
        'show',
        'edit'
    ]);;

    Route::patch('/program-kerja/{program_kerja}/status',[ProgramKerjaController::class, 'updateStatus'])->name('proker.status');

    Route::patch('/program-kerja/{program_kerja}/public',[ProgramKerjaController::class, 'updatePublic'])->name('proker.public');

    Route::patch('/program-kerja/{program_kerja}/complete',[ProgramKerjaController::class, 'complete'])->name('proker.complete');

    // ================= DOKUMENTASI =================
    Route::resource('dokumentasi',DokumentasiController::class)->middleware([
        'index' => 'permission:view dokumentasi',
        'store' => 'permission:create dokumentasi',
        'update' => 'permission:edit dokumentasi',
        'destroy' => 'permission:delete dokumentasi',
    ])->except([
        'create',
        'show',
        'edit'
    ]);;

    Route::delete('/dokumentasi/{dokumentasi}/thumbnail/{type}',[DokumentasiController::class, 'destroyThumbnail'])->name('dokumentasi.thumbnail.destroy');

    // ================= BERITA =================
    Route::resource('berita',BeritaController::class)->parameters([
        'berita' => 'berita'
    ])->middleware([
        'index' => 'permission:view berita',
        'create'=> 'permission:create berita',
        'store' => 'permission:create berita',
        'edit' => 'permission:edit berita',
        'update' => 'permission:edit berita',
        'destroy' => 'permission:delete berita',
    ]);

    Route::get('berita/{berita}/preview', [BeritaController::class, 'preview'])
        ->middleware('permission:view berita')
        ->name('berita.preview');

    Route::patch('/admin/berita/{berita}/status',[BeritaController::class, 'updateStatus'])->name('berita.update-status');

    // ================= SURAT MASUK =================
    Route::resource('surat-masuk', IncomingLetterController::class)->middleware([
        'index' => 'permission:view surat-masuk',
        'store' => 'permission:create surat-masuk',
        'update' => 'permission:edit surat-masuk',
        'destroy' => 'permission:delete surat-masuk',
    ])->except([
        'create',
        'show',
        'edit'
    ]);

    // ================= SURAT KELUAR =================
    Route::resource('surat-keluar', OutgoingLetterController::class)->middleware([
        'index' => 'permission:view surat-keluar',
        'store' => 'permission:create surat-keluar',
        'update' => 'permission:edit surat-keluar',
        'destroy' => 'permission:delete surat-keluar',
    ])->except([
        'create',
        'show',
        'edit'
    ]);

    // ================= ROLE & PERMISSION =================
    Route::resource('permissions', PermissionController::class)->middleware([
        'index' => 'permission:view permission',
        'store' => 'permission:create permission',
        'update' => 'permission:edit permission',
        'destroy' => 'permission:delete permission',
    ]);

    Route::resource('roles', RoleController::class)->middleware([
        'index' => 'permission:view role',
        'store' => 'permission:create role',
        'update' => 'permission:edit role',
        'destroy' => 'permission:delete role',
    ]);

    Route::post('roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])
        ->middleware('permission:edit role')
        ->name('roles.sync-permissions');

    // ================= CONTACTS =================
    Route::resource('contacts', ContactController::class)->middleware([
        'index' => 'permission:view contacts',
        'store' => 'permission:create contacts',
        'update' => 'permission:edit contacts',
        'destroy' => 'permission:delete contacts',
    ])->except([
        'create',
        'show',
        'edit'
    ]);

    // ================= MESSAGES =================
    Route::get('messages', [MessageController::class, 'index'])
        ->middleware('permission:view messages')
        ->name('messages.index');

    Route::get('messages/{message}', [MessageController::class, 'show'])
        ->middleware('permission:view messages')
        ->name('messages.show');

    Route::post('messages/{message}/reply', [MessageController::class, 'reply'])
        ->middleware('permission:reply messages')
        ->name('messages.reply');

    Route::patch('messages/{message}/status', [MessageController::class, 'updateStatus'])
        ->middleware('permission:reply messages')
        ->name('messages.update-status');

    Route::post('messages/bulk-action', [MessageController::class, 'bulkAction'])
        ->middleware('permission:delete messages')
        ->name('messages.bulk-action');

    Route::delete('messages/{message}', [MessageController::class, 'destroy'])
        ->middleware('permission:delete messages')
        ->name('messages.destroy');

    Route::get('messages/unread/count', [MessageController::class, 'unreadCount'])
        ->name('messages.unread-count');

    // ================= FAQS =================
    Route::resource('faqs', FaqController::class)->middleware([
        'index' => 'permission:view faqs',
        'store' => 'permission:create faqs',
        'update' => 'permission:edit faqs',
        'destroy' => 'permission:delete faqs',
    ])->except([
        'create',
        'show',
        'edit'
    ]);
});
