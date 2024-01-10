<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'User',
            'last_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@email.com',
            'password' => 'senha',
        ]);
    }
}
