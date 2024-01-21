<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

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

        if(!App::isProduction()) {
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

            User::factory()
            ->has(
                User::factory()
                    ->unverified()
                    ->count(10)
                    ->state(function (array $attributes, User $user) {
                        return ['type' => 'Entregador'];
                    }),
                'deliveryMen',
            )
            ->unverified()
            ->count(10)
            ->create(['type' => 'Gerente']);
        }
    }
}
