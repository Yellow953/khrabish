<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->insert([
            'name' => 'Client 1',
            'phone' => '123456789',
            'email' => 'client1@gmail.com',
            'country' => 'Lebanon',
            'city' => 'Beirut',
            'address' => 'test address',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
