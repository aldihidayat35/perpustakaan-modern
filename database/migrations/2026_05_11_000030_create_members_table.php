<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code')->unique();
            $table->string('name');
            $table->string('nis_nim')->nullable();
            $table->string('class')->nullable();
            $table->string('major')->nullable();
            $table->text('address')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('status')->default('active')->comment('active|inactive');
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['name', 'member_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
