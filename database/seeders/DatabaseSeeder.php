<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders obrigatórias
        $this->call([
            PermissionsSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
        ]);

        // Seeders apenas para desenvolvimento
        if(!App::isProduction()) {
            $this->call([
                CustomerSeeder::class,
                VendorSeeder::class,
                BrandSeeder::class,
                StockSeeder::class,
            ]);
        }
    }
}
