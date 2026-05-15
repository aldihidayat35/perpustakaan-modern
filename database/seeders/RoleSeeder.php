<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Akses penuh ke semua fitur sistem',
            ],
            [
                'name' => 'operator',
                'display_name' => 'Operator',
                'description' => 'Akses untuk mengelola peminjaman dan anggota',
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Akses dasar查看 dan profile',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
