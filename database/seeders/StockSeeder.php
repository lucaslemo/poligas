<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stocks')->delete();

        for($i = 0; $i < 500; $i++) {
            $product = Product::inRandomOrder()->first();
            $brand = Brand::inRandomOrder()->first();
            Stock::factory()
                ->for($product)
                ->for($brand)
                ->create();
        }
    }
}
