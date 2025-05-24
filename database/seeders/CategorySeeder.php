<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::query()->delete();

        $categories = [
            [
                'name' => 'Pizza',
                'slug' => 'pizza',
                'description' => 'Delicious handcrafted pizzas with fresh ingredients and crispy crusts',
                'image_url' => 'https://picsum.photos/400/300?random=10',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Burgers',
                'slug' => 'burgers',
                'description' => 'Juicy beef and chicken burgers with premium toppings and fresh buns',
                'image_url' => 'https://picsum.photos/400/300?random=11',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Pasta',
                'slug' => 'pasta',
                'description' => 'Fresh pasta dishes with authentic Italian sauces and ingredients',
                'image_url' => 'https://picsum.photos/400/300?random=12',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Salads',
                'slug' => 'salads',
                'description' => 'Fresh and healthy salads with premium ingredients and house-made dressings',
                'image_url' => 'https://picsum.photos/400/300?random=13',
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
