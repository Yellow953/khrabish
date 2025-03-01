<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $taxes = [
            [
                'name' => 'Tax Free',
                'rate' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vat 11%',
                'rate' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($taxes as $tax) {
            DB::table('taxes')->insert($tax);
        }
    }
}
