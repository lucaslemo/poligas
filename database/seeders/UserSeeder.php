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
        User::truncate();

        User::create([
            'id' => 1,
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@email.com',
            'type' => 'Administrador',
            'password' => 'senha',
            'status' => true,
        ])->assignRole('Administrador');

        User::create([
            'id' => 2,
            'first_name' => 'lucas',
            'last_name' => 'lemos',
            'username' => 'lucaslemo',
            'email' => 'lucas@email.com',
            'type' => 'Administrador',
            'password' => 'senha',
            'status' => true,
        ])->assignRole('Administrador');
    }
}
