<?php

use App\Http\Controllers\Admin\SchoolActionController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BimbelController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentDownloadController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProofController;
use App\Http\Controllers\SavedSchoolController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SeoPageController;
use App\Livewire\Onboarding\ApplyWizard;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sekolah
Route::get('/sekolah', [SchoolController::class, 'index'])->name('schools.index');
Route::get('/sekolah/{slug}', [SchoolController::class, 'show'])->name('schools.show');

// ── SEO Pages: Sekolah & Pesantren Terbaik ──
Route::prefix('sekolah-terbaik')->group(function () {
    Route::get('/', [SeoPageController::class, 'schoolsBest'])->name('seo.schools.best');
    Route::get('/{city}', [SeoPageController::class, 'schoolsBestByCity'])->name('seo.schools.city');
});

Route::prefix('pesantren-terbaik')->group(function () {
    Route::get('/', [SeoPageController::class, 'pesantrenBest'])->name('seo.pesantren.best');
    Route::get('/{city}', [SeoPageController::class, 'pesantrenBestByCity'])->name('seo.pesantren.city');
});

// Perbandingan
Route::get('/bandingkan', [CompareController::class, 'index'])->name('compare.index');
Route::post('/bandingkan/tambah', [CompareController::class, 'add'])->name('compare.add');
Route::post('/bandingkan/hapus', [CompareController::class, 'remove'])->name('compare.remove');

// Kalkulator biaya
Route::get('/kalkulator', [CalculatorController::class, 'index'])->name('calculator.index');

// Artikel
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// ── Bantu Pesantren ──
Route::prefix('bantu-pesantren')->group(function () {
    Route::get('/', [DonationController::class, 'index'])->name('donations.index');
    Route::get('/{slug}', [DonationController::class, 'show'])->name('donations.show');
    Route::post('/{slug}/donasi', [DonationController::class, 'donate'])->name('donations.donate');
    Route::get('/pembayaran/{donation}', [DonationController::class, 'payment'])->name('donations.payment');
    Route::get('/pembayaran/{donation}/proses', [DonationController::class, 'processPayment'])->name('donations.process-payment');
    Route::get('/pembayaran/{donation}/simulasi', [DonationController::class, 'simulatePayment'])->name('donations.simulate-payment');
    Route::get('/{donation}/sukses', [DonationController::class, 'success'])->name('donations.success');
    Route::get('/{donation}/gagal', [DonationController::class, 'failed'])->name('donations.failed');
    Route::post('/xendit-callback', [DonationController::class, 'xenditCallback'])->name('donations.xendit-callback');
});

// ── Bimbel Online ──
Route::prefix('bimbel')->group(function () {
    Route::get('/', [BimbelController::class, 'index'])->name('bimbel.index');
    Route::get('/{slug}', [BimbelController::class, 'show'])->name('bimbel.show');
    Route::post('/{slug}/pesan', [BimbelController::class, 'book'])->name('bimbel.book');
    Route::get('/pembayaran/{booking}', [BimbelController::class, 'payment'])->name('bimbel.payment');
    Route::get('/pembayaran/{booking}/proses', [BimbelController::class, 'processPayment'])->name('bimbel.process-payment');
    Route::get('/pembayaran/{booking}/simulasi', [BimbelController::class, 'simulatePayment'])->name('bimbel.simulate-payment');
    Route::get('/{slug}/sukses/{booking}', [BimbelController::class, 'success'])->name('bimbel.success');
    Route::get('/{slug}/gagal/{booking}', [BimbelController::class, 'failed'])->name('bimbel.failed');
});

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

// Profil
Route::middleware('auth')->prefix('profil')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ── Parent Onboarding ──
Route::middleware(['auth'])->prefix('orang-tua')->group(function () {
    Route::get('/pendaftaran', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::get('/pendaftaran/{application}', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::get('/pendaftaran/{application}/bayar', [OnboardingController::class, 'pay'])->name('onboarding.pay');
    Route::get('/daftar', ApplyWizard::class)->name('onboarding.apply');
});

// ── Signed download routes ──
Route::get('/bukti-pembayaran/{payment}/cetak', [ProofController::class, 'print'])
    ->name('proof.print')
    ->middleware('signed');

Route::get('/bimbel/bukti/{booking}/cetak', [ProofController::class, 'printMentorBooking'])
    ->name('bimbel.proof')
    ->middleware('signed');

Route::get('/dokumen/{document}/unduh', [DocumentDownloadController::class, 'download'])
    ->name('documents.download')
    ->middleware('signed');

// ── Admin School Actions ──
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::post('/sekolah/{school}/toggle-publish', [SchoolActionController::class, 'togglePublish'])
        ->name('filament.admin.resources.schools.toggle-publish');
    Route::delete('/sekolah/{school}', [SchoolActionController::class, 'destroy'])
        ->name('filament.admin.resources.schools.destroy');
    Route::get('/sekolah/export', [SchoolActionController::class, 'export'])
        ->name('filament.admin.resources.schools.export');
});
