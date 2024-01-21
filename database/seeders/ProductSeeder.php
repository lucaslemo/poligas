<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->delete();
        $types = ['P2', 'P5', 'P13', 'P20', 'P45', 'P90'];

        foreach($types as $type) {
            Product::factory()->create([
                'name' => $type
            ]);
        }
    }
}
