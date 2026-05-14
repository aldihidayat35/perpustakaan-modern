<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BorrowingDetailStatus;
use App\Enums\BorrowingStatus;
use App\Enums\FineStatus;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Fine;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BorrowingSeeder extends Seeder
{
    /**
     * Seed borrowings across the last 12 months for dashboard chart visibility.
     * Each month gets a realistic distribution of active/late/returned borrowings.
     */
    public function run(): void
    {
        // Clear existing data
        BorrowingDetail::query()->delete();
        Fine::query()->delete();
        Borrowing::query()->delete();

        $members = Member::all();
        $books = Book::where('stock', '>', 0)->get();

        if ($members->isEmpty() || $books->isEmpty()) {
            $this->command->info('Members and books needed before seeding borrowings.');
            return;
        }

        // Distribusi peminjaman per bulan (12 bulan ke belakang)
        // Format: [total_borrowings_this_month, late_ratio (0.0–1.0), returned_ratio (0.0–1.0)]
        // late_ratio = proporsi yang terlambat, returned_ratio = proporsi yang sudah dikembalikan
        $monthlyDistribution = [
            11 => ['total' => 8,  'late' => 0.2, 'returned' => 0.7],
            10 => ['total' => 12, 'late' => 0.3, 'returned' => 0.8],
            9  => ['total' => 6,  'late' => 0.1, 'returned' => 0.9],
            8  => ['total' => 15, 'late' => 0.4, 'returned' => 0.85],
            7  => ['total' => 10, 'late' => 0.25,'returned' => 0.9],
            6  => ['total' => 14, 'late' => 0.35,'returned' => 0.88],
            5  => ['total' => 9,  'late' => 0.15,'returned' => 0.92],
            4  => ['total' => 18, 'late' => 0.45,'returned' => 0.9],
            3  => ['total' => 11, 'late' => 0.2, 'returned' => 0.87],
            2  => ['total' => 7,  'late' => 0.1, 'returned' => 0.95],
            1  => ['total' => 13, 'late' => 0.3, 'returned' => 0.8],
            0  => ['total' => 5,  'late' => 0.0, 'returned' => 0.0], // bulan ini belum selesai
        ];

        $borrowingCount = 0;

        foreach ($monthlyDistribution as $monthsAgo => $config) {
            $total = (int) $config['total'];
            $lateRatio = (float) $config['late'];
            $returnedRatio = (float) $config['returned'];

            for ($i = 0; $i < $total; $i++) {
                $member = $members->random();
                $bookCount = fake()->numberBetween(1, min(3, $books->count()));
                $selectedBooks = $books->random($bookCount);

                $loanDate = now()->subMonths($monthsAgo)->addDays(fake()->numberBetween(0, 27));
                $dueDate = (clone $loanDate)->addDays(7);

                // Tentukan status
                $rand = fake()->randomFloat(3, 0, 1);
                $isReturned = $rand < $returnedRatio;
                $isLate = $rand >= (1 - $lateRatio) && !$isReturned;

                if ($isReturned && !$isLate) {
                    $status = BorrowingStatus::Returned;
                    $returnDate = (clone $dueDate)->addDays(fake()->numberBetween(0, 3));
                } elseif ($isLate) {
                    $status = BorrowingStatus::Late;
                    $returnDate = null;
                } else {
                    $status = BorrowingStatus::Active;
                    $returnDate = null;
                }

                $transactionCode = 'TRX-' . strtoupper(Str::random(8));

                $borrowing = Borrowing::create([
                    'transaction_code' => $transactionCode,
                    'member_id' => $member->id,
                    'loan_date' => $loanDate,
                    'due_date' => $dueDate,
                    'return_date' => $returnDate,
                    'status' => $status,
                    'notes' => fake()->optional()->sentence(),
                ]);

                $borrowingCount++;

                // Borrowing details
                foreach ($selectedBooks as $book) {
                    BorrowingDetail::create([
                        'borrowing_id' => $borrowing->id,
                        'book_id' => $book->id,
                        'status' => $status === BorrowingStatus::Returned
                            ? BorrowingDetailStatus::Returned
                            : BorrowingDetailStatus::Borrowed,
                    ]);
                }

                // Fine untuk yang terlambat
                if ($status === BorrowingStatus::Late) {
                    $daysLate = (int) now()->diffInDays($dueDate);
                    $daysLate = max(1, $daysLate);
                    $fineIsPaid = fake()->boolean(40); // 40% sudah lunas

                    Fine::create([
                        'borrowing_id' => $borrowing->id,
                        'member_id' => $member->id,
                        'days_late' => $daysLate,
                        'amount_per_day' => 1000,
                        'total_amount' => $daysLate * 1000,
                        'status' => $fineIsPaid ? FineStatus::Paid : FineStatus::Unpaid,
                        'paid_at' => $fineIsPaid ? now() : null,
                    ]);
                }

                // Fine untuk yang sudah dikembalikan tapi terlambat
                if ($status === BorrowingStatus::Returned && $loanDate->lt($dueDate->copy()->subDay())) {
                    $daysLate = (int) $returnDate->diffInDays($dueDate);
                    if ($daysLate > 0) {
                        $fineIsPaid = fake()->boolean(70); // 70% sudah lunas
                        Fine::create([
                            'borrowing_id' => $borrowing->id,
                            'member_id' => $member->id,
                            'days_late' => $daysLate,
                            'amount_per_day' => 1000,
                            'total_amount' => $daysLate * 1000,
                            'status' => $fineIsPaid ? FineStatus::Paid : FineStatus::Unpaid,
                            'paid_at' => $fineIsPaid ? $returnDate : null,
                        ]);
                    }
                }
            }
        }

        $this->command->info("BorrowingSeeder: Created {$borrowingCount} borrowings across 12 months.");
    }
}