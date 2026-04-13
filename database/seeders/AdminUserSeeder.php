<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['name' => 'admin'],
            [
                'email' => 'admin@elevator.local',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
                'role' => 1,
            ]
        );
    }
}