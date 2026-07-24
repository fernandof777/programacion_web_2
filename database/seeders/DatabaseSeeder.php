<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'luis@taller.com'],
            [
                'name' => 'Luis Fernando',
                'password' => Hash::make('password123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'maria@taller.com'],
            [
                'name' => 'Maria Garcia',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
