<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image_url')->comment('Path ke gambar di storage/public');
            $table->string('link_url')->nullable()->comment('URL yang dituju saat slide diklik');
            $table->string('link_text')->default('Lihat Selengkapnya');
            $table->integer('order')->default(0)->comment('Urutan tampil (ascending)');
            $table->boolean('is_active')->default(true)->comment('Aktif/nonaktif slide');
            $table->timestamps();

            $table->index(['order', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};