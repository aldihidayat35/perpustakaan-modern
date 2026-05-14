<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->text('synopsis')->nullable()->after('cover');
            $table->string('published_year')->nullable()->after('publisher');
            $table->unsignedSmallInteger('pages')->nullable()->after('published_year');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['synopsis', 'published_year', 'pages']);
        });
    }
};