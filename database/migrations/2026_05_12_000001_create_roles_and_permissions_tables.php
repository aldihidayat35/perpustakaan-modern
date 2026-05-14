<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // role_user pivot table
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Role::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        // permission_role pivot table
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Permission::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Role::class)->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        // permission_user pivot table
        Schema::create('permission_user', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Permission::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
