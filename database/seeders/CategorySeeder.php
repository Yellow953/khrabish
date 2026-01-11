<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Main Categories
            [
                'name' => 'Home Improvement',
                'image' => '/frontend/images/categories/home.png',
            ],
            [
                'name' => 'Cleaning',
                'image' => '/frontend/images/categories/cleaning.png',
            ],
            [
                'name' => 'Kids',
                'image' => '/frontend/images/categories/kids.png',
            ],
            [
                'name' => 'Personal Care',
                'image' => '/frontend/images/categories/personal-care.png',
            ],
            [
                'name' => 'Party & Deco',
                'image' => '/frontend/images/categories/party_and_decoration.png',
            ],
            [
                'name' => 'Accessories & Gadgets',
                'image' => '/frontend/images/categories/accessories.png',
            ],

            // Sub Categories
            [
                'name' => 'Kitchen',
                'image' => '/frontend/images/categories/kitchen.png',
                'parent_id' => 1,
            ],
            [
                'name' => 'Bathroom',
                'image' => '/frontend/images/categories/bathroom.png',
                'parent_id' => 1,
            ],
            [
                'name' => 'Home Items',
                'image' => 'assets/images/no_img.png',
                'parent_id' => 1,
            ],
            [
                'name' => 'Party & Decoration',
                'image' => 'assets/images/no_img.png',
                'parent_id' => 5,
            ],
            [
                'name' => 'Birthday Themes',
                'image' => 'assets/images/no_img.png',
                'parent_id' => 5,
            ],
            [
                'name' => 'Accessories',
                'image' => 'assets/images/no_img.png',
                'parent_id' => 6,
            ],
            [
                'name' => 'Gift Gadgets',
                'image' => 'assets/images/no_img.png',
                'parent_id' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
