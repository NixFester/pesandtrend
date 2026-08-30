<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SavedSchoolController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sekolah
Route::get('/sekolah', [SchoolController::class, 'index'])->name('schools.index');
Route::get('/sekolah/{slug}', [SchoolController::class, 'show'])->name('schools.show');

// Perbandingan
Route::get('/bandingkan', [CompareController::class, 'index'])->name('compare.index');
Route::post('/bandingkan/tambah', [CompareController::class, 'add'])->name('compare.add');
Route::post('/bandingkan/hapus', [CompareController::class, 'remove'])->name('compare.remove');

// Kalkulator biaya
Route::get('/kalkulator', [\App\Http\Controllers\CalculatorController::class, 'index'])->name('calculator.index');

// Artikel
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Newsletter
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');

// Simpan sekolah (butuh login)
Route::middleware('auth')->post('/sekolah/{school}/simpan', [SavedSchoolController::class, 'toggle'])->name('schools.save');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ── Parent Onboarding ──
Route::middleware(['auth'])->prefix('orang-tua')->group(function () {
    Route::get('/pendaftaran', [\App\Http\Controllers\OnboardingController::class, 'index'])->name('onboarding.index');
    Route::get('/pendaftaran/{application}', [\App\Http\Controllers\OnboardingController::class, 'show'])->name('onboarding.show');
    Route::get('/pendaftaran/{application}/bayar', [\App\Http\Controllers\OnboardingController::class, 'pay'])->name('onboarding.pay');
    Route::get('/daftar', \App\Livewire\Onboarding\ApplyWizard::class)->name('onboarding.apply');
});

// ── Signed download routes ──
Route::get('/bukti-pembayaran/{payment}/cetak', [\App\Http\Controllers\ProofController::class, 'print'])
    ->name('proof.print')
    ->middleware('signed');

Route::get('/dokumen/{document}/unduh', [\App\Http\Controllers\DocumentDownloadController::class, 'download'])
    ->name('documents.download')
    ->middleware('signed');

