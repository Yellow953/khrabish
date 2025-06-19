<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cleaning',
                'image' => '/frontend/images/categories/cleaning.png',
            ],
            [
                'name' => 'Home',
                'image' => '/frontend/images/categories/home.png',
            ],
            [
                'name' => 'Bathroom',
                'image' => '/frontend/images/categories/bathroom.png',
            ],
            [
                'name' => 'Kitchen',
                'image' => '/frontend/images/categories/kitchen.png',
            ],
            [
                'name' => 'Kids',
                'image' => '/frontend/images/categories/kids.png',
            ],
            [
                'name' => 'Party & Deco',
                'image' => '/frontend/images/categories/party_and_decoration.png',
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
