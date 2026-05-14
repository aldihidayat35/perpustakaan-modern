<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_code')->unique();
            $table->string('isbn')->nullable();
            $table->string('title');
            $table->foreignIdFor(Category::class)->nullable()->constrained()->nullOnDelete();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('rack_location')->nullable();
            $table->string('cover')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('status')->default('available')->comment('available|unavailable');
            $table->timestamps();

            $table->index(['title', 'author']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
