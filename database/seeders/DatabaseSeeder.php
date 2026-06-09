<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate([
            'phone' => '9999999999',
        ], [
            'role' => 'admin',
            'password' => Hash::make('admin12345'),
        ]);

        User::updateOrCreate([
            'phone' => '8888888888',
        ], [
            'role' => 'user',
            'password' => Hash::make('user12345'),
        ]);
    }
}
