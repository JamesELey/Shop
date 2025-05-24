<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pizza;

class PizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing pizzas to avoid duplicates if seeder is run multiple times
        Pizza::query()->delete();

        $pizzas = [
            [
                'name' => 'Margherita',
                'description' => 'Classic Italian pizza with fresh mozzarella, tomato sauce, and fresh basil leaves',
                'price' => 10.99,
                'image_url' => 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=400&h=300&fit=crop&auto=format',
            ],
            [
                'name' => 'Pepperoni',
                'description' => 'America\'s favorite with spicy pepperoni slices and melted mozzarella cheese',
                'price' => 12.99,
                'image_url' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400&h=300&fit=crop&auto=format',
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Garden fresh vegetables including bell peppers, mushrooms, onions, and black olives',
                'price' => 11.99,
                'image_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop&auto=format',
            ],
            [
                'name' => 'Hawaiian',
                'description' => 'Tropical delight with ham, pineapple chunks, and mozzarella cheese',
                'price' => 13.49,
                'image_url' => 'https://images.unsplash.com/photo-1576458088443-04a19d13da0c?w=400&h=300&fit=crop&auto=format',
            ],
            [
                'name' => 'Meat Lovers',
                'description' => 'Ultimate meat feast with pepperoni, sausage, ham, and bacon',
                'price' => 15.99,
                'image_url' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&h=300&fit=crop&auto=format',
            ],
            [
                'name' => 'BBQ Chicken',
                'description' => 'Grilled chicken with BBQ sauce, red onions, and cilantro',
                'price' => 14.49,
                'image_url' => 'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=400&h=300&fit=crop&auto=format',
            ]
        ];

        foreach ($pizzas as $pizza) {
            Pizza::create($pizza);
        }
    }
}
