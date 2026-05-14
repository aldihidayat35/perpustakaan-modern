<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\BorrowingDetailStatus;
use App\Enums\BorrowingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Borrowings\StoreReturnRequest;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(): View
    {
        $status = request('status');

        $query = Borrowing::with(['member', 'details.book']);

        if ($status === 'returned') {
            // Semua detail sudah dikembalikan → borrowing.status = Returned
            $query->where('status', BorrowingStatus::Returned);
        } elseif ($status === 'late') {
            // Status late & masih ada buku yang belum dikembalikan
            $query->where('status', BorrowingStatus::Late)
                ->whereHas('details', fn ($q) => $q->where('status', BorrowingDetailStatus::Borrowed->value));
        } elseif ($status === 'active') {
            // Status aktif & masih ada buku yang belum dikembalikan
            $query->where('status', BorrowingStatus::Active)
                ->whereHas('details', fn ($q) => $q->where('status', BorrowingDetailStatus::Borrowed->value));
        } else {
            // Default: tampilkan semua borrowing yang masih punya buku belum dikembalikan
            $query->whereHas('details', fn ($q) => $q->where('status', BorrowingDetailStatus::Borrowed->value));
        }

        $borrowings = $query->latest()->paginate(10);

        return view('admin.returns.index', compact('borrowings', 'status'));
    }

    public function store(StoreReturnRequest $request, Borrowing $borrowing, BorrowingService $service): RedirectResponse
    {
        if (!$borrowing->details()->where('status', BorrowingDetailStatus::Borrowed->value)->exists()) {
            return redirect()->route('admin.returns.index')->with('error', 'Semua buku sudah dikembalikan.');
        }

        $service->returnBorrowing($borrowing, $request->validated());

        $remaining = $borrowing->details()->where('status', BorrowingDetailStatus::Borrowed->value)->count();
        $message = $remaining > 0
            ? 'Pengembalian berhasil dicatat. Masih ada ' . $remaining . ' buku yang belum dikembalikan.'
            : 'Semua buku berhasil dikembalikan.';

        return redirect()->route('admin.returns.index')->with('success', $message);
    }
}