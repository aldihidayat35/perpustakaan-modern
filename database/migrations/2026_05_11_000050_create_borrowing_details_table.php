<?php

declare(strict_types=1);

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Borrowing::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Book::class)->constrained()->cascadeOnDelete();
            $table->string('status')->default('borrowed')->comment('borrowed|returned');
            $table->timestamps();

            $table->unique(['borrowing_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowing_details');
    }
};
