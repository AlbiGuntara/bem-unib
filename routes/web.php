<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\Admin\HutangPiutangController;
use App\Http\Controllers\Admin\HutangPiutangPembayaranController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KasKegiatanController;
use App\Http\Controllers\Admin\KasUmumController;
use App\Http\Controllers\Admin\PengurusController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PrestasiController;
use App\Http\Controllers\Admin\ProgramKerjaController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\SkorPendidikanController;
use App\Http\Controllers\Admin\SuratKeluarController;
use App\Http\Controllers\Admin\SuratMasukController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PrestasisController;
use App\Http\Controllers\Public\ProkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', function () {
    return Inertia::render('Public/TentangKami', [
        'title' => 'Tentang Kami',
    ]);
})->name('tentang-kami');
Route::get('/program-kerja', [ProkerController::class, 'index'])->name('program-kerja');
Route::get('/prestasi', [PrestasisController::class, 'prestasi'])->name('prestasi');

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

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// Copyright Integrity Check
Route::get('/integrity-violation', function () {
    abort(499);
});

// Admin
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // ================= DASHBOARD =================
    Route::get('/', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/search', [SearchController::class, 'search'])
        ->middleware('permission:view dashboard')
        ->name('search');

    // ================= MASTER DATA =================
    Route::resource('pengurus', PengurusController::class)->middleware([
        'index' => 'permission:view pengurus',
        'store' => 'permission:create pengurus',
        'update' => 'permission:edit pengurus',
        'destroy' => 'permission:delete pengurus',
    ]);

    Route::resource('jabatan', JabatanController::class)->middleware([
        'index' => 'permission:view jabatan',
        'store' => 'permission:create jabatan',
        'update' => 'permission:edit jabatan',
        'destroy' => 'permission:delete jabatan',
    ]);

    Route::resource('periode', PeriodeController::class)->middleware([
        'index' => 'permission:view periode',
        'store' => 'permission:create periode',
        'update' => 'permission:edit periode',
        'destroy' => 'permission:delete periode',
    ]);

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

    // ================= WARGA =================
    Route::get('warga', [WargaController::class, 'index'])
        ->middleware('permission:view warga')
        ->name('warga.index');

    Route::get('warga/create', [WargaController::class, 'create'])
        ->middleware('permission:create warga')
        ->name('warga.create');

    Route::post('warga', [WargaController::class, 'store'])
        ->middleware(['permission:create warga'])
        ->name('warga.store');

    Route::get('warga/{warga}/edit', [WargaController::class, 'edit'])
        ->middleware('permission:edit warga')
        ->name('warga.edit');

    Route::put('warga/{warga}', [WargaController::class, 'update'])
        ->middleware(['permission:edit warga'])
        ->name('warga.update');

    Route::delete('warga/{warga}', [WargaController::class, 'destroy'])
        ->middleware(['permission:delete warga'])
        ->name('warga.destroy');

    Route::get('warga/{warga}', [WargaController::class, 'show'])
        ->middleware('permission:view warga')
        ->name('warga.show');

    Route::get('warga/export/excel', [WargaController::class, 'exportExcel'])
        ->middleware('permission:export warga')
        ->name('warga.export.excel');

    Route::get('warga/export/pdf', [WargaController::class, 'exportPDF'])
        ->middleware('permission:export warga')
        ->name('warga.export.pdf');

    // ================= SKOR PENDIDIKAN =================
    Route::resource('skor-pendidikan', SkorPendidikanController::class)->middleware([
        'index' => 'permission:view skor-pendidikan',
        'store' => 'permission:create skor-pendidikan',
        'update' => 'permission:edit skor-pendidikan',
        'destroy' => 'permission:delete skor-pendidikan',
    ]);

    // ================= PRESTASI =================
    Route::resource('prestasi', PrestasiController::class)->middleware([
        'index' => 'permission:view prestasi',
        'store' => 'permission:create prestasi',
        'update' => 'permission:edit prestasi',
        'destroy' => 'permission:delete prestasi',
    ]);

    Route::get('prestasi/warga/{id}', [PrestasiController::class, 'byWarga'])
        ->middleware('permission:view prestasi')
        ->name('prestasi.byWarga');

    // ================= PROGRAM KERJA =================
    Route::resource('program-kerja', ProgramKerjaController::class)->middleware([
        'index' => 'permission:view program-kerja',
        'store' => 'permission:create program-kerja',
        'update' => 'permission:edit program-kerja',
        'destroy' => 'permission:delete program-kerja',
    ]);

    Route::get('program-kerja/export', [ProgramKerjaController::class, 'export'])
        ->middleware('permission:export program-kerja')
        ->name('program-kerja.export');

    // ================= DOKUMENTASI =================
    Route::post('dokumentasi', [DokumentasiController::class, 'store'])
        ->middleware('permission:create dokumentasi');

    Route::post('dokumentasi/{dokumentasi}', [DokumentasiController::class, 'update'])
        ->middleware('permission:edit dokumentasi');

    Route::delete('dokumentasi/{dokumentasi}', [DokumentasiController::class, 'destroy'])
        ->middleware('permission:delete dokumentasi');

    // ================= SURAT =================
    Route::resource('surat-masuk', SuratMasukController::class)->middleware([
        'index' => 'permission:view surat',
        'store' => 'permission:create surat',
        'update' => 'permission:edit surat',
        'destroy' => 'permission:delete surat',
    ]);

    Route::resource('surat-keluar', SuratKeluarController::class)->middleware([
        'index' => 'permission:view surat',
        'store' => 'permission:create surat',
        'update' => 'permission:edit surat',
        'destroy' => 'permission:delete surat',
    ]);

    // ================= KAS UMUM =================
    Route::resource('kas-umum', KasUmumController::class)->middleware([
        'index' => 'permission:view kas-umum',
        'store' => 'permission:create kas-umum',
        'update' => 'permission:edit kas-umum',
        'destroy' => 'permission:delete kas-umum',
    ]);

    Route::get('kas-umum/export/excel', [KasUmumController::class, 'export'])
        ->middleware('permission:export kas-umum')
        ->name('kas-umum.export');

    // ================= KAS KEGIATAN =================
    Route::get('kas-kegiatan', [KasKegiatanController::class, 'index'])
        ->middleware('permission:view kas-kegiatan');

    Route::post('kas-kegiatan/store-kegiatan', [KasKegiatanController::class, 'storeKegiatan'])
        ->middleware('permission:create kas-kegiatan');

    Route::put('kas-kegiatan/update-kegiatan/{id}', [KasKegiatanController::class, 'updateKegiatan'])
        ->middleware('permission:edit kas-kegiatan');

    Route::delete('kas-kegiatan/{id}', [KasKegiatanController::class, 'destroy'])
        ->middleware('permission:delete kas-kegiatan');

    Route::get('kas/export/excel', [KasKegiatanController::class, 'exportExcel'])
        ->middleware('permission:export kas-kegiatan')
        ->name('kas.export.excel');

    // ================= HUTANG PIUTANG =================
    Route::resource('hutang-piutang', HutangPiutangController::class)->middleware([
        'index' => 'permission:view hutang-piutang',
        'store' => 'permission:create hutang-piutang',
        'update' => 'permission:edit hutang-piutang',
        'destroy' => 'permission:delete hutang-piutang',
    ]);

    Route::post('hutang-piutang/{hutangPiutang}/pembayaran',
        [HutangPiutangPembayaranController::class, 'store']
    )->middleware('permission:edit hutang-piutang');

    Route::get('hutang-piutang/{hutangPiutang}/riwayat',
        [HutangPiutangController::class, 'riwayat']
    )->middleware('permission:view hutang-piutang');
});
