<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BookStatus;
use App\Enums\BorrowingDetailStatus;
use App\Enums\BorrowingStatus;
use App\Enums\FineStatus;
use App\Models\Book;
use App\Models\BookReturn;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Fine;
use App\Models\Member;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingService
{
    public const DEFAULT_LOAN_DAYS = 7;

    public const MAX_BORROWINGS_PER_MEMBER = 3;

    public function __construct(
        private readonly ?WhatsAppService $whatsApp = null,
    ) {}

    /**
     * Create new borrowing transaction
     *
     * @throws ValidationException
     */
    public function createBorrowing(Member $member, array $bookIds, ?Carbon $dueDate = null, ?string $notes = null): Borrowing
    {
        // Validate member first
        $this->validateMember($member);

        // Normalize and dedupe book IDs
        $bookIds = array_values(array_unique($bookIds));

        if (empty($bookIds)) {
            throw ValidationException::withMessages(['book_ids' => 'Pilih setidaknya satu buku.']);
        }

        // Check remaining slots
        $remainingSlots = $this->getRemainingSlots($member);
        if (count($bookIds) > $remainingSlots) {
            throw ValidationException::withMessages([
                'book_ids' => "Sisa slot peminjaman hanya {$remainingSlots} buku. Kurangi jumlah buku yang dipinjam.",
            ]);
        }

        return DB::transaction(function () use ($member, $bookIds, $dueDate, $notes) {
            // Lock books for update to prevent race conditions
            $books = Book::query()
                ->whereIn('id', $bookIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($books->count() !== count($bookIds)) {
                $notFoundIds = array_diff($bookIds, $books->pluck('id')->toArray());
                throw ValidationException::withMessages([
                    'book_ids' => 'Sebagian buku tidak ditemukan (ID: '.implode(', ', $notFoundIds).').',
                ]);
            }

            // Validate each book
            foreach ($books as $book) {
                $this->validateBook($book);
            }

            // Final check: total borrowed after this transaction
            $newTotal = $this->getActiveBorrowingsCount($member) + count($bookIds);
            if ($newTotal > self::MAX_BORROWINGS_PER_MEMBER) {
                throw ValidationException::withMessages([
                    'book_ids' => "Melebihi batas maksimal peminjaman. Sisa slot: {$remainingSlots} buku.",
                ]);
            }

            // Generate transaction code
            $transactionCode = $this->generateTransactionCode();

            // Create borrowing record
            $borrowing = Borrowing::create([
                'transaction_code' => $transactionCode,
                'member_id' => $member->id,
                'user_id' => auth()->id(),
                'loan_date' => now()->toDateString(),
                'due_date' => ($dueDate ?? now()->addDays($this->getLoanDuration()))->toDateString(),
                'status' => BorrowingStatus::Active,
                'notes' => $notes,
            ]);

            // Create borrowing details and update stock
            foreach ($books as $book) {
                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'book_id' => $book->id,
                    'status' => BorrowingDetailStatus::Borrowed,
                ]);

                // Decrement stock
                $book->decrement('stock');
                if ($book->stock <= 0) {
                    $book->update(['status' => BookStatus::Unavailable]);
                }
            }

            // Send notification
            $this->sendBorrowedNotification($member, $borrowing);

            return $borrowing->load(['member', 'details.book']);
        });
    }

    /**
     * Return books from a borrowing
     */
    public function returnBorrowing(Borrowing $borrowing, array $data): Borrowing
    {
        return DB::transaction(function () use ($borrowing, $data) {
            $borrowing->load(['details.book', 'member']);

            $returnDate = Carbon::parse($data['return_date'] ?? now());
            $detailIds = $data['detail_ids'] ?? $borrowing->activeDetails()->pluck('id')->toArray();
            $condition = $data['condition'] ?? null;
            $notes = $data['notes'] ?? null;

            // Check which detail IDs are still borrowable
            $validDetailIds = $borrowing->activeDetails()
                ->whereIn('id', $detailIds)
                ->pluck('id')
                ->toArray();

            if (empty($validDetailIds)) {
                throw new Exception('Semua buku sudah dikembalikan.');
            }

            // Update details as returned
            $borrowing->details()
                ->whereIn('id', $validDetailIds)
                ->update([
                    'status' => BorrowingDetailStatus::Returned,
                    'returned_at' => $returnDate->toDateString(),
                    'condition' => $condition,
                ]);

            // Restore stock for returned books
            $returnedDetails = $borrowing->details()->whereIn('id', $validDetailIds)->get();
            foreach ($returnedDetails as $detail) {
                $detail->book->increment('stock');
                if ($detail->book->stock > 0) {
                    $detail->book->update(['status' => BookStatus::Available]);
                }
            }

            // Check if all details are returned
            $allReturned = $borrowing->activeDetails()->count() === 0;

            if ($allReturned) {
                // Create book return record
                BookReturn::create([
                    'borrowing_id' => $borrowing->id,
                    'return_date' => $returnDate->toDateString(),
                    'condition' => $condition,
                    'notes' => $notes,
                ]);

                // Update borrowing status
                $borrowing->update([
                    'return_date' => $returnDate->toDateString(),
                    'status' => $returnDate->gt($borrowing->due_date) ? BorrowingStatus::Late : BorrowingStatus::Returned,
                ]);

                // Handle fine if late
                $this->handleFine($borrowing);
            }

            return $borrowing->refresh();
        });
    }

    /**
     * Send reminder to member
     */
    public function sendReminder(Borrowing $borrowing): bool
    {
        $borrowing->loadMissing('member');
        $member = $borrowing->member;

        if (! $member?->whatsapp) {
            return false;
        }

        $daysLeft = $borrowing->due_date->diffInDays(now());
        $overdueDays = $borrowing->daysOverdue;

        if ($overdueDays > 0) {
            $message = "🔔 Halo {$member->name}, buku Anda terlambat {$overdueDays} hari!\n";
            $message .= "Kode: {$borrowing->transaction_code}\n";
            $message .= 'Segera kembalikan ke perpustakaan.';
        } else {
            $message = "📚 Halo {$member->name}, buku akan jatuh tempo dalam {$daysLeft} hari.\n";
            $message .= "Kode: {$borrowing->transaction_code}\n";
            $message .= " Jatuh tempo: {$borrowing->due_date->format('d M Y')}";
        }

        return $this->whatsApp?->sendMessage($member, $member->whatsapp, $message) ?? false;
    }

    // ── Validasi ──────────────────────────────────────────────────────────────

    /**
     * Validate member can borrow
     *
     * @throws ValidationException
     */
    public function validateMember(Member $member): void
    {
        if ($member->status->value !== 'active') {
            throw ValidationException::withMessages(['member_id' => 'Anggota tidak aktif.']);
        }

        if (! $member->canBorrow()) {
            $remainingSlots = $member->remaining_slots;
            throw ValidationException::withMessages([
                'member_id' => $remainingSlots <= 0
                    ? 'Anggota sudah mencapai batas maksimal peminjaman (3 buku).'
                    : "Anggota hanya boleh meminjam {$remainingSlots} buku lagi.",
            ]);
        }
    }

    /**
     * Validate single book
     *
     * @throws ValidationException
     */
    public function validateBook(Book $book): void
    {
        if ($book->stock <= 0) {
            throw ValidationException::withMessages([
                'book_ids' => "Stok buku \"{$book->title}\" habis.",
            ]);
        }

        // Check if book is currently borrowed by someone
        $currentlyBorrowed = BorrowingDetail::where('book_id', $book->id)
            ->where('status', BorrowingDetailStatus::Borrowed)
            ->exists();

        if ($currentlyBorrowed) {
            throw ValidationException::withMessages([
                'book_ids' => "Buku \"{$book->title}\" sedang dipinjam anggota lain.",
            ]);
        }
    }

    /**
     * Validate a single book by code (for API lookup)
     *
     * @throws ValidationException
     */
    public function validateBookByCode(string $bookCode, int $requestedSlots = 1): array
    {
        $book = Book::with('category')->where('book_code', $bookCode)->first();

        if (! $book) {
            return ['success' => false, 'error' => 'Buku tidak ditemukan.'];
        }

        if ($book->stock <= 0) {
            return ['success' => false, 'error' => "Stok buku \"{$book->title}\" habis."];
        }

        $currentlyBorrowed = BorrowingDetail::where('book_id', $book->id)
            ->where('status', BorrowingDetailStatus::Borrowed)
            ->exists();

        if ($currentlyBorrowed) {
            return ['success' => false, 'error' => "Buku \"{$book->title}\" sedang dipinjam."];
        }

        return [
            'success' => true,
            'book' => [
                'id' => $book->id,
                'book_code' => $book->book_code,
                'title' => $book->title,
                'author' => $book->author,
                'category' => $book->category?->name,
                'stock' => $book->stock,
                'cover' => $book->cover_url,
            ],
        ];
    }

    /**
     * Validate a member by code (for API lookup)
     */
    public function validateMemberByCode(string $memberCode): array
    {
        $member = Member::where('member_code', $memberCode)->first();

        if (! $member) {
            return ['success' => false, 'error' => 'Anggota tidak ditemukan.'];
        }

        if ($member->status->value !== 'active') {
            return ['success' => false, 'error' => 'Anggota tidak aktif.'];
        }

        $activeCount = $this->getActiveBorrowingsCount($member);
        $remainingSlots = $this->getRemainingSlots($member);
        $activeBorrowings = $member->activeBorrowings()->with(['details' => fn ($q) => $q->with('book')->where('status', BorrowingDetailStatus::Borrowed)])->get();

        return [
            'success' => true,
            'member' => [
                'id' => $member->id,
                'member_code' => $member->member_code,
                'name' => $member->name,
                'photo' => $member->photo ? asset('storage/'.$member->photo) : null,
                'status' => $member->status->value,
                'active_borrowings_count' => $activeCount,
                'remaining_slots' => $remainingSlots,
                'active_borrowings' => $activeBorrowings->map(fn ($b) => [
                    'id' => $b->id,
                    'transaction_code' => $b->transaction_code,
                    'loan_date' => $b->loan_date->format('d M Y'),
                    'due_date' => $b->due_date->format('d M Y'),
                    'is_overdue' => $b->isOverdue(),
                    'books' => $b->details->map(fn ($d) => [
                        'id' => $d->id,
                        'title' => $d->book->title,
                        'book_code' => $d->book->book_code,
                        'cover' => $d->book->cover_url,
                        'status' => $d->status->value,
                    ]),
                ])->toArray(),
            ],
        ];
    }

    // ── Query Helpers ─────────────────────────────────────────────────────────

    public function getActiveBorrowingsCount(Member $member): int
    {
        return (int) BorrowingDetail::whereHas('borrowing', fn ($q) => $q
            ->where('member_id', $member->id)
            ->whereIn('status', [BorrowingStatus::Active->value, BorrowingStatus::Late->value]))
            ->where('status', BorrowingDetailStatus::Borrowed)
            ->count();
    }

    public function getRemainingSlots(Member $member): int
    {
        return max(0, self::MAX_BORROWINGS_PER_MEMBER - $this->getActiveBorrowingsCount($member));
    }

    public function getLoanDuration(): int
    {
        return (int) Setting::getValue('loan_duration_days', self::DEFAULT_LOAN_DAYS);
    }

    public function getDefaultDueDate(): Carbon
    {
        return now()->addDays($this->getLoanDuration());
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function generateTransactionCode(): string
    {
        $date = now()->format('Ymd');
        $count = Borrowing::whereDate('created_at', now()->toDateString())->count() + 1;

        return 'TRX-'.$date.'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }

    private function handleFine(Borrowing $borrowing): void
    {
        if ($borrowing->return_date && $borrowing->return_date->gt($borrowing->due_date)) {
            $daysLate = (int) $borrowing->due_date->diffInDays($borrowing->return_date);
            if ($daysLate <= 0) {
                return;
            }

            $amountPerDay = (float) Setting::getValue('fine_amount_per_day', 1000);
            $totalAmount = $amountPerDay * $daysLate;

            Fine::updateOrCreate(
                ['borrowing_id' => $borrowing->id],
                [
                    'member_id' => $borrowing->member_id,
                    'days_late' => $daysLate,
                    'amount_per_day' => $amountPerDay,
                    'total_amount' => $totalAmount,
                    'status' => FineStatus::Unpaid,
                ]
            );
        }
    }

    private function sendBorrowedNotification(Member $member, Borrowing $borrowing): void
    {
        if (! $member->whatsapp) {
            return;
        }

        $message = "📚 Hai {$member->name}!\n";
        $message .= "Peminjaman berhasil dicatat.\n\n";
        $message .= "📋 Kode: {$borrowing->transaction_code}\n";
        $message .= "📅 Pinjam: {$borrowing->loan_date->format('d M Y')}\n";
        $message .= "⏰ Kembali: {$borrowing->due_date->format('d M Y')}\n\n";
        $message .= "📕 Buku: {$borrowing->details->count()} item\n";
        $message .= 'Silakan cek ke perpustakaan untuk info lebih lanjut.';

        $this->whatsApp?->sendMessage($member, $member->whatsapp, $message);
    }
}
