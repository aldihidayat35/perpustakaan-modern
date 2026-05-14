<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BorrowingDetailStatus;
use App\Enums\BorrowingStatus;
use App\Enums\FineStatus;
use App\Enums\BookStatus;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\BookReturn;
use App\Models\Fine;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingService
{
    public function __construct(private readonly WhatsAppService $whatsApp)
    {
    }

    public function createBorrowing(Member $member, array $bookIds, Carbon $dueDate, ?string $notes = null): Borrowing
    {
        return DB::transaction(function () use ($member, $bookIds, $dueDate, $notes) {
            $bookIds = array_values(array_unique($bookIds));
            $books = Book::query()
                ->whereIn('id', $bookIds)
                ->lockForUpdate()
                ->get();

            if ($books->count() !== count($bookIds)) {
                throw ValidationException::withMessages(['book_ids' => 'Sebagian buku tidak ditemukan.']);
            }

            $outOfStock = $books->first(fn (Book $book) => $book->stock <= 0);
            if ($outOfStock) {
                throw ValidationException::withMessages([
                    'book_ids' => "Stok buku {$outOfStock->title} habis.",
                ]);
            }

            $transactionCode = $this->generateTransactionCode();
            $borrowing = Borrowing::create([
                'transaction_code' => $transactionCode,
                'member_id' => $member->id,
                'loan_date' => now()->toDateString(),
                'due_date' => $dueDate->toDateString(),
                'status' => BorrowingStatus::Active,
                'notes' => $notes,
            ]);

            foreach ($books as $book) {
                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'book_id' => $book->id,
                    'status' => BorrowingDetailStatus::Borrowed,
                ]);

                $book->decrement('stock');
                if ($book->stock <= 0) {
                    $book->update(['status' => BookStatus::Unavailable]);
                }
            }

            $this->sendBorrowedNotification($member, $borrowing);

            return $borrowing->refresh();
        });
    }

    public function returnBorrowing(Borrowing $borrowing, array $data): Borrowing
    {
        return DB::transaction(function () use ($borrowing, $data) {
            $borrowing->load(['details.book', 'member']);

            $returnDate = Carbon::parse($data['return_date']);
            $detailIds = $data['detail_ids'] ?? $borrowing->details->pluck('id')->toArray();
            $condition = $data['condition'] ?? null;
            $notes = $data['notes'] ?? null;

            // Update selected details as returned
            $borrowing->details()
                ->whereIn('id', $detailIds)
                ->update([
                    'status' => BorrowingDetailStatus::Returned,
                    'returned_at' => $returnDate->toDateString(),
                    'condition' => $condition,
                ]);

            // Restore stock for returned books
            $returnedDetails = $borrowing->details()->whereIn('id', $detailIds)->get();
            foreach ($returnedDetails as $detail) {
                $detail->book->increment('stock');
                if ($detail->book->stock > 0) {
                    $detail->book->update(['status' => BookStatus::Available]);
                }
            }

            // Check if all details are returned
            $allReturned = $borrowing->details()->where('status', BorrowingDetailStatus::Returned->value)->count()
                === $borrowing->details()->count();

            if ($allReturned) {
                // Create book return record
                BookReturn::create([
                    'borrowing_id' => $borrowing->id,
                    'return_date' => $returnDate->toDateString(),
                    'condition' => $condition,
                    'notes' => $notes,
                ]);

                $borrowing->update([
                    'return_date' => $returnDate->toDateString(),
                    'status' => $returnDate->gt($borrowing->due_date) ? BorrowingStatus::Late : BorrowingStatus::Returned,
                ]);

                $this->handleFine($borrowing);
            }

            return $borrowing->refresh();
        });
    }

    public function sendReminder(Borrowing $borrowing): bool
    {
        $borrowing->loadMissing('member');
        $member = $borrowing->member;
        if (!$member?->whatsapp) {
            return false;
        }

        $message = "Halo {$member->name}, ini pengingat pengembalian buku. Kode transaksi {$borrowing->transaction_code} jatuh tempo pada {$borrowing->due_date->format('d M Y')}.";
        return $this->whatsApp->sendMessage($member, $member->whatsapp, $message);
    }

    private function handleFine(Borrowing $borrowing): void
    {
        $daysLate = 0;
        if ($borrowing->return_date && $borrowing->return_date->gt($borrowing->due_date)) {
            $daysLate = $borrowing->due_date->diffInDays($borrowing->return_date);
        }

        if ($daysLate <= 0) {
            return;
        }

        $amountPerDay = (float) Setting::getValue('fine_amount_per_day', 0);
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

    private function generateTransactionCode(): string
    {
        $date = now()->format('Ymd');
        $count = Borrowing::whereDate('created_at', now()->toDateString())->count() + 1;
        return 'TRX-' . $date . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }

    private function sendBorrowedNotification(Member $member, Borrowing $borrowing): void
    {
        if (!$member->whatsapp) {
            return;
        }

        $message = "Halo {$member->name}, peminjaman buku berhasil. Kode transaksi {$borrowing->transaction_code} dengan batas pengembalian {$borrowing->due_date->format('d M Y')}.";
        $this->whatsApp->sendMessage($member, $member->whatsapp, $message);
    }
}
