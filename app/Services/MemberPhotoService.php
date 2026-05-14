<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MemberPhotoService
{
    private const DISK = 'public';
    private const FOLDER = 'photos/members';

    /**
     * Upload and resize member photo.
     * Returns the stored path string.
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = $this->generateFileName($file);

        // Resize to max 600x600 keeping aspect ratio, then store
        $path = $file->getRealPath();
        $image = imagecreatefromstring(file_get_contents($path));
        if (!$image) {
            throw new \RuntimeException('Cannot read uploaded image.');
        }

        $origWidth  = imagesx($image);
        $origHeight = imagesy($image);
        $maxSize    = 600;

        // Scale down if needed
        if ($origWidth > $maxSize || $origHeight > $maxSize) {
            $ratio = min($maxSize / $origWidth, $maxSize / $origHeight);
            $newWidth  = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($image);
            $image = $resized;
        }

        // Encode as JPEG (quality 85)
        ob_start();
        imagejpeg($image, null, 85);
        $jpegContent = ob_get_clean();
        imagedestroy($image);

        $fullPath = self::FOLDER . '/' . $fileName;
        Storage::disk(self::DISK)->put($fullPath, $jpegContent, 'public');

        return $fullPath;
    }

    /**
     * Delete a stored member photo by its path.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /**
     * Generate a unique, safe filename.
     */
    private function generateFileName(UploadedFile $file): string
    {
        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $ext  = 'jpg'; // always store as jpg for consistency

        return $uuid . '.' . $ext;
    }
}