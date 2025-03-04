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
                'image' => '/shop/images/categories/cleaning.png',
            ],
            [
                'name' => 'Home Items',
                'image' => '/shop/images/categories/home-items.png',
            ],
            [
                'name' => 'Bathroom',
                'image' => '/shop/images/categories/bathroom.png',
            ],
            [
                'name' => 'Kitchen',
                'image' => '/shop/images/categories/kitchen.png',
            ],
            [
                'name' => 'Kids',
                'image' => '/shop/images/categories/kids.png',
            ],
            [
                'name' => 'Party & Deco',
                'image' => '/shop/images/categories/party.png',
            ],
            [
                'name' => 'Personal Care',
                'image' => '/shop/images/categories/personal-care.png',
            ],
            [
                'name' => 'Phone & Accessories',
                'image' => '/shop/images/categories/phone-accessories.png',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
