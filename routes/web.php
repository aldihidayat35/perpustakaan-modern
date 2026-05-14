<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\QrScanController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\WhatsAppSettingsController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Public Landing Page ──
Route::get('/', [LandingPageController::class, 'index'])->name('landing.home');
Route::get('/books', [LandingPageController::class, 'books'])->name('landing.books');
Route::get('/categories', [LandingPageController::class, 'categories'])->name('landing.categories');
Route::get('/books/{book}', [LandingPageController::class, 'showBook'])->name('landing.books.show');

Route::get('/login-redirect', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('books', BookController::class)->except(['edit']);
    Route::get('books/create', [BookController::class, 'create'])->name('books.create');
    Route::get('books/{book}/qr-modal', [BookController::class, 'qrModal'])->name('books.qr-modal');
    Route::post('books/{book}/qr-code', [BookController::class, 'regenerateQrCode'])->name('books.regenerate-qr');
    Route::post('books/bulk-qr', [BookController::class, 'bulkGenerateQr'])->name('books.bulk-qr');
    Route::get('books/bulk-qr/print', [BookController::class, 'bulkPrintQr'])->name('books.bulk-qr.print');
    Route::get('books/lookup', [BookController::class, 'lookupByCode'])->name('books.lookup');
    Route::resource('members', MemberController::class)->except(['create', 'edit', 'show']);
    Route::get('members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::get('members/{member}/print', [MemberController::class, 'printCard'])->name('members.print-card');
    Route::post('members/{member}/qr-code', [MemberController::class, 'regenerateQr'])->name('members.regenerate-qr');
    Route::get('members/lookup', [MemberController::class, 'lookupByCode'])->name('members.lookup');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('users', UserController::class);

    Route::get('fines', [FineController::class, 'index'])->name('fines.index');
    Route::post('fines/{fine}/mark-as-paid', [FineController::class, 'markAsPaid'])->name('fines.mark-as-paid');
    Route::post('fines/{fine}/mark-as-unpaid', [FineController::class, 'markAsUnpaid'])->name('fines.mark-as-unpaid');

    Route::get('borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::post('borrowings/{borrowing}/remind', [BorrowingController::class, 'remind'])->name('borrowings.remind');

    Route::get('scan', [QrScanController::class, 'index'])->name('scan.index');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('export/books', [ExportController::class, 'books'])->name('export.books');
    Route::get('export/members', [ExportController::class, 'members'])->name('export.members');
    Route::get('export/borrowings', [ExportController::class, 'borrowings'])->name('export.borrowings');

    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::post('returns/{borrowing}', [ReturnController::class, 'store'])->name('returns.store');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('settings/whatsapp', [WhatsAppSettingsController::class, 'index'])->name('settings.whatsapp');
    Route::put('settings/whatsapp', [WhatsAppSettingsController::class, 'update'])->name('settings.whatsapp.update');
    Route::post('settings/whatsapp/test', [WhatsAppSettingsController::class, 'test'])->name('settings.whatsapp.test');

    Route::resource('hero-slides', HeroSlideController::class)->except(['create', 'edit', 'show']);
    Route::post('hero-slides/{heroSlide}/toggle', [HeroSlideController::class, 'toggle'])->name('hero-slides.toggle');
});
