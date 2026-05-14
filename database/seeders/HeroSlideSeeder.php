<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class HeroSlideSeeder extends Seeder
{
    private const SLIDE_BG = [
        ['bg' => '#1A1A2E', 'fg' => '#FF6B35', 'tag' => 'SELAMAT DATANG'],
        ['bg' => '#FF6B35', 'fg' => '#FFF8F0', 'tag' => 'KOLEKSI BARU'],
        ['bg' => '#16213E', 'fg' => '#4ECDC4', 'tag' => 'QR CODE'],
    ];

    private const SLIDE_MESSAGES = [
        'Selamat Datang di\nAplikasi Perpustakaan!',
        'Koleksi Buku\nTerbaru 2024',
        'Pinjam Buku Mudah\ndengan QR Code',
    ];

    public function run(): void
    {
        $slides = [
            [
                'title' => 'Selamat Datang di Aplikasi Perpustakaan!',
                'subtitle' => 'Jelajahi ribuan koleksi buku digital dari berbagai kategori. Pinjam buku dengan mudah dan kapan saja.',
                'link_url' => '/books',
                'link_text' => 'Jelajahi Koleksi',
                'order' => 1,
                'is_active' => true,
                'image_url' => 'hero-slides/slide-1.svg',
            ],
            [
                'title' => 'Koleksi Buku Terbaru 2024',
                'subtitle' => 'Ribuan buku baru telah tiba! Eksplorasi koleksi terbaru dari berbagai kategori menarik.',
                'link_url' => '/books',
                'link_text' => 'Lihat Buku Terbaru',
                'order' => 2,
                'is_active' => true,
                'image_url' => 'hero-slides/slide-2.svg',
            ],
            [
                'title' => 'Pinjam Buku Mudah dengan QR Code',
                'subtitle' => 'Tanpa antri. Cukup scan QR code buku dan kartu anggota untuk langsung meminjam.',
                'link_url' => '/admin/scan',
                'link_text' => 'Coba Scan QR',
                'order' => 3,
                'is_active' => true,
                'image_url' => 'hero-slides/slide-3.svg',
            ],
        ];

        foreach ($slides as $index => $s) {
            $slide = HeroSlide::updateOrCreate(
                ['title' => $s['title']],
                $s
            );

            // Generate SVG placeholder if image not exists or is generic placeholder
            if (!$slide->image_url || str_contains($slide->image_url, 'placehold.co')) {
                $this->generateSlideImage($slide, $index);
            }
        }
    }

    private function generateSlideImage(HeroSlide $slide, int $index): void
    {
        try {
            $config = self::SLIDE_BG[$index];
            $message = self::SLIDE_MESSAGES[$index];

            $svg = $this->buildSvg($config['bg'], $config['fg'], $config['tag'], $message);
            $path = 'hero-slides/slide-' . $slide->id . '.svg';
            Storage::disk('public')->put($path, $svg);
            $slide->updateQuietly(['image_url' => $path]);
        } catch (\Throwable) {
            // Silently skip image generation on error
        }
    }

    private function buildSvg(string $bg, string $fg, string $tag, string $message): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="500" viewBox="0 0 1200 500">
  <defs>
    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
      <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/>
    </pattern>
  </defs>

  <rect width="1200" height="500" fill="{$bg}"/>
  <rect width="1200" height="500" fill="url(#grid)"/>

  <circle cx="100" cy="100" r="200" fill="rgba(255,107,53,0.08)"/>
  <circle cx="1100" cy="400" r="150" fill="rgba(78,205,196,0.08)"/>

  <rect x="50" y="50" width="220" height="40" fill="{$fg}" stroke="#000" stroke-width="3"/>
  <text x="160" y="77" font-family="Arial Black, sans-serif" font-size="14" font-weight="900"
        fill="#000" text-anchor="middle" letter-spacing="2">{$tag}</text>

  <text x="50" y="200" font-family="Arial Black, sans-serif" font-size="52" font-weight="900"
        fill="#fff" letter-spacing="1">Perpustakaan Digital</text>

  <rect x="50" y="220" width="120" height="6" fill="{$fg}"/>

  <text x="900" y="150" font-size="60">📚</text>
  <text x="980" y="220" font-size="50">📕</text>
  <text x="870" y="300" font-size="55">📗</text>
  <text x="950" y="380" font-size="45">📘</text>

  <text x="50" y="400" font-family="Arial, sans-serif" font-size="18" font-weight="700"
        fill="rgba(255,255,255,0.7)">
    perpustakaan digital sekolah • perpustakaan-smkn1.sch.id
  </text>
</svg>
SVG;
    }
}