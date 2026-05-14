<?php

declare(strict_types=1);

use App\Models\Borrowing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Borrowing::class)->unique()->constrained()->cascadeOnDelete();
            $table->date('return_date');
            $table->string('condition')->nullable()->comment('Kondisi buku saat kembali');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_returns');
    }
};
