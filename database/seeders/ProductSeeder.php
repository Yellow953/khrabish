<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryCount = Category::count();

        if ($categoryCount != 0) {
            for ($i = 0; $i < 50; $i++) {
                DB::table('products')->insert([
                    'name' => Str::random(10),
                    'category_id' => rand(1, $categoryCount),
                    'quantity' => rand(1, 100),
                    'cost' => rand(1, 50),
                    'price' => rand(1, 100),
                    'compare_price' => rand(1, 150),
                    'description' => Str::random(25),
                    'image' => 'assets/images/no_img.png',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
