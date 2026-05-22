<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpus.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Member
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123',
        ]);

        $this->call([
            CategorySeeder::class,
            BookSeeder::class,
        ]);
    }
}
