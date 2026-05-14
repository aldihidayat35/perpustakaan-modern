<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('illustration_image')->nullable()->after('image_url');
            $table->string('illustration_type')->default('emoji')->after('illustration_image');
            // illustration_type: 'emoji' | 'image'
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['illustration_image', 'illustration_type']);
        });
    }
};
