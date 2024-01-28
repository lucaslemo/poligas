<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Vendor;
use Carbon\Carbon;
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

        $vendors = Vendor::get();
        for($j = 0; $j < 5; $j++) {
            foreach($vendors as $vendor) {
                $productsQty = fake()->randomDigitNotNull() * 3;
                $product = Product::inRandomOrder()->first();
                $brand = Brand::inRandomOrder()->first();
                $value = fake()->randomFloat(2, 100, 300);
                $date = Carbon::now()->subMinutes(rand(1, 1440))->subDays(rand(1, 730));
                for($i = 0; $i < $productsQty; $i++) {
                    Stock::factory()
                        ->for($product)
                        ->for($brand)
                        ->for($vendor)
                        ->create([
                            'vendor_value' => $value,
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);
                }
            }
        }
    }
}
