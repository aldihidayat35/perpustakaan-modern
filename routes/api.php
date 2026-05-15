<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BorrowingApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Borrowing System
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // ── Member Lookups ──────────────────────────────────────────────────────
    Route::get('/members/lookup', [BorrowingApiController::class, 'lookupMember'])
        ->name('api.members.lookup');

    // ── Book Lookups ────────────────────────────────────────────────────────
    Route::get('/books/lookup', [BorrowingApiController::class, 'lookupBook'])
        ->name('api.books.lookup');

    // ── Borrowing CRUD ───────────────────────────────────────────────────────
    Route::get('/borrowings/{borrowing}', [BorrowingApiController::class, 'show'])
        ->name('api.borrowings.show');
    Route::post('/borrowings', [BorrowingApiController::class, 'store'])
        ->name('api.borrowings.store');
    Route::post('/borrowings/{borrowing}/remind', [BorrowingApiController::class, 'remind'])
        ->name('api.borrowings.remind');

    // ── Receipt ────────────────────────────────────────────────────────────
    Route::get('/borrowings/{borrowing}/receipt', [BorrowingApiController::class, 'receipt'])
        ->name('api.borrowings.receipt');
    Route::get('/borrowings/{borrowing}/receipt/pdf', [BorrowingApiController::class, 'receiptPdf'])
        ->name('api.borrowings.receipt.pdf');

    // ── Settings ────────────────────────────────────────────────────────────
    Route::get('/settings/borrowing', [BorrowingApiController::class, 'settings'])
        ->name('api.settings.borrowing');
});