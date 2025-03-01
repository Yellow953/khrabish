<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TaxSeeder::class,
            CurrencySeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            ClientSeeder::class,
            SearchRouteSeeder::class,
            BankNoteSeeder::class,
        ]);
    }
}
