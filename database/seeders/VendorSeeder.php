<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendors')->delete();
        DB::table('addresses')->whereNotNull('get_vendor_id')->delete();

        Vendor::factory()->has(Address::factory()->count(1))->count(50)->create();
    }
}
