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
                'image_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop&auto=format',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Burgers',
                'slug' => 'burgers',
                'description' => 'Juicy beef and chicken burgers with premium toppings and fresh buns',
                'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop&auto=format',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Pasta',
                'slug' => 'pasta',
                'description' => 'Fresh pasta dishes with authentic Italian sauces and ingredients',
                'image_url' => 'https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?w=400&h=300&fit=crop&auto=format',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Salads',
                'slug' => 'salads',
                'description' => 'Fresh and healthy salads with premium ingredients and house-made dressings',
                'image_url' => 'https://images.unsplash.com/photo-1546793665-c74683f339c1?w=400&h=300&fit=crop&auto=format',
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
