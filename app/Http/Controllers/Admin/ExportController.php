<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function books(): StreamedResponse|Response
    {
        $books = Book::with('category')->get();

        $csvData = $this->generateCsv([
            ['Kode', 'ISBN', 'Judul', 'Kategori', 'Penulis', 'Penerbit', 'Tahun', 'Stok', 'Lokasi Rak', 'Status'],
            ...$books->map(fn ($book) => [
                $book->book_code,
                $book->isbn ?? '',
                $book->title,
                $book->category?->name ?? '',
                $book->author ?? '',
                $book->publisher ?? '',
                $book->year ?? '',
                $book->stock,
                $book->rack_location ?? '',
                $book->status->value,
            ])->toArray()
        ]);

        $filename = 'buku_' . now()->format('Ymd_His') . '.csv';

        return new StreamedResponse(function () use ($csvData) {
            echo $csvData;
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    public function members(): StreamedResponse|Response
    {
        $members = Member::with('user')->get();

        $csvData = $this->generateCsv([
            ['Kode', 'Nama', 'Email', 'No. HP', 'Alamat', 'Tanggal Daftar', 'Status'],
            ...$members->map(fn ($member) => [
                $member->member_code,
                $member->name,
                $member->email ?? '',
                $member->whatsapp ?? '',
                $member->address ?? '',
                $member->created_at->format('d/m/Y'),
                $member->status->value,
            ])->toArray()
        ]);

        $filename = 'anggota_' . now()->format('Ymd_His') . '.csv';

        return new StreamedResponse(function () use ($csvData) {
            echo $csvData;
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    public function borrowings(Request $request): StreamedResponse|Response
    {
        $year = $request->integer('year', now()->year);

        $borrowings = Borrowing::with(['member', 'details.book'])
            ->whereYear('loan_date', $year)
            ->get();

        $csvData = $this->generateCsv([
            ['Kode Transaksi', 'Anggota', 'Kode Anggota', 'Tanggal Pinjam', 'Jatuh Tempo', 'Tanggal Kembali', 'Status', 'Buku'],
            ...$borrowings->map(fn ($b) => [
                $b->transaction_code,
                $b->member->name,
                $b->member->member_code,
                $b->loan_date->format('d/m/Y'),
                $b->due_date->format('d/m/Y'),
                $b->return_date?->format('d/m/Y') ?? '-',
                $b->status->value,
                $b->details->map(fn ($d) => $d->book->title)->implode(', '),
            ])->toArray()
        ]);

        $filename = 'peminjaman_' . $year . '_' . now()->format('Ymd_His') . '.csv';

        return new StreamedResponse(function () use ($csvData) {
            echo $csvData;
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    private function generateCsv(array $rows): string
    {
        $handle = fopen('php://temp', 'r+');

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return "\xEF\xBB\xBF" . $csv;
    }
}