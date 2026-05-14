<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\BorrowingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Borrowings\StoreBorrowingRequest;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $statusParam = $request->query('status');

        $query = Borrowing::with(['member', 'details.book']);

        // Status filter
        if ($statusParam && BorrowingStatus::tryFrom($statusParam)) {
            $query->where('status', $statusParam);
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        // Count per status for filter badges
        $totalAll     = Borrowing::count();
        $countActive  = Borrowing::where('status', BorrowingStatus::Active)->count();
        $countLate    = Borrowing::where('status', BorrowingStatus::Late)->count();
        $countReturned = Borrowing::where('status', BorrowingStatus::Returned)->count();

        $members = Member::orderBy('name')->get();
        $books = Book::orderBy('title')->get();

        return view('admin.borrowings.index', compact(
            'borrowings', 'members', 'books',
            'statusParam', 'totalAll', 'countActive', 'countLate', 'countReturned'
        ));
    }

    public function store(StoreBorrowingRequest $request, BorrowingService $service): RedirectResponse
    {
        $member = Member::findOrFail($request->integer('member_id'));
        $bookIds = $request->input('book_ids', []);

        $service->createBorrowing(
            $member,
            $bookIds,
            Carbon::parse($request->input('due_date')),
            $request->input('notes')
        );

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil disimpan.');
    }

    public function remind(Borrowing $borrowing, BorrowingService $service): RedirectResponse
    {
        if ($borrowing->status !== BorrowingStatus::Active) {
            return redirect()->route('admin.borrowings.index')->with('error', 'Transaksi sudah selesai.');
        }

        $sent = $service->sendReminder($borrowing);

        return redirect()->route('admin.borrowings.index')
            ->with($sent ? 'success' : 'error', $sent ? 'Reminder WhatsApp terkirim.' : 'Gagal mengirim reminder.');
    }
}
