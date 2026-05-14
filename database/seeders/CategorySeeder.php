<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi', 'icon' => '📕', 'description' => 'Novel, cerpen, dan karya fiksi sastra'],
            ['name' => 'Non-Fiksi', 'icon' => '📗', 'description' => 'Buku referensi, panduan, dan pengetahuan umum'],
            ['name' => 'Sains & Teknologi', 'icon' => '🔬', 'description' => 'Buku tentang sains, matematika, dan teknologi'],
            ['name' => 'Sejarah', 'icon' => '🏛️', 'description' => 'Kisah dan fakta sejarah Indonesia dan dunia'],
            ['name' => 'Bahasa', 'icon' => '📖', 'description' => 'Buku pelajaran bahasa Indonesia, Inggris, dan lainnya'],
            ['name' => 'Komputer & IT', 'icon' => '💻', 'description' => 'Buku panduan programming, jaringan, dan aplikasi komputer'],
            ['name' => 'Seni & Budaya', 'icon' => '🎨', 'description' => 'Seni, musik, tari, dan budaya Indonesia'],
            ['name' => 'Agama', 'icon' => '🕌', 'description' => 'Buku keagamaan dan spiritual'],
            ['name' => 'Olahraga', 'icon' => '⚽', 'description' => 'Buku olahraga dan kesehatan'],
            ['name' => 'Komik', 'icon' => '🦸', 'description' => 'Komik edukasi dan cerita bergambar'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'description' => $cat['description'],
                ]
            );
        }
    }
}