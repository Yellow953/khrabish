<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Supplier 1',
                'phone' => '123456789',
                'address' => 'test address',
                'email' => 'supplier1@gmail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DHL',
                'phone' => '+4915204820649',
                'address' => 'testing',
                'email' => 'support@dhl.de',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->insert($supplier);
        }
    }
}
