<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_types')->delete();
        $types = ['dinheiro', 'pix', 'cartão de crédito', 'cartão de débito', 'cheque'];

        foreach($types as $type) {
            PaymentType::factory()->create([
                'name' => $type
            ]);
        }
    }
}
