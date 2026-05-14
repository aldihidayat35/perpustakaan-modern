<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookService
{
    public function create(array $data): Book
    {
        return DB::transaction(function () use ($data) {
            $coverPath = $this->handleCoverUpload($data);
            if ($coverPath) {
                $data['cover'] = $coverPath;
            }

            $book = Book::create($data);
            $this->generateQrCode($book);

            return $book->refresh();
        });
    }

    public function update(Book $book, array $data): Book
    {
        return DB::transaction(function () use ($book, $data) {
            $originalCode = $book->book_code;

            // Handle cover upload
            $coverPath = $this->handleCoverUpload($data);
            if ($coverPath) {
                $data['cover'] = $coverPath;
            }

            // Delete old cover if new one uploaded
            if (isset($data['_remove_cover']) && $data['_remove_cover']) {
                $this->deleteCover($book->cover);
                $data['cover'] = null;
            } elseif (!$coverPath && isset($data['cover']) && $data['cover'] === null) {
                // No new cover and cover was not explicitly set - keep old
                unset($data['cover']);
            }

            $book->update($data);

            if ($originalCode !== $book->book_code || !$book->qr_code) {
                $this->deleteQrCode($book->qr_code);
                $this->generateQrCode($book);
            }

            return $book->refresh();
        });
    }

    public function delete(Book $book): void
    {
        $this->deleteQrCode($book->qr_code);
        $this->deleteCover($book->cover);
        $book->delete();
    }

    public function regenerateQrCode(Book $book): Book
    {
        $this->deleteQrCode($book->qr_code);
        $this->generateQrCode($book);

        return $book->refresh();
    }

    public function generateQrCodeOnly(Book $book): void
    {
        if ($book->qr_code) {
            $this->deleteQrCode($book->qr_code);
        }
        $this->generateQrCode($book);
    }

    private function handleCoverUpload(array &$data): ?string
    {
        if (!isset($data['cover']) || !$data['cover']) {
            return null;
        }

        if ($data['cover'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['cover']->store('covers', 'public');
            return $path;
        }

        return null;
    }

    private function deleteCover(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function generateQrCode(Book $book): void
    {
        // SVG format — works tanpa imagick extension
        $fileName = 'qr/books/' . $book->book_code . '.svg';
        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($book->book_code);

        Storage::disk('public')->put($fileName, $qrSvg);
        $book->update(['qr_code' => $fileName]);
    }

    private function deleteQrCode(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
