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
                'image_url' => 'https://picsum.photos/400/300?random=1',
            ],
            [
                'name' => 'Pepperoni',
                'description' => 'America\'s favorite with spicy pepperoni slices and melted mozzarella cheese',
                'price' => 12.99,
                'image_url' => 'https://picsum.photos/400/300?random=2',
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Garden fresh vegetables including bell peppers, mushrooms, onions, and black olives',
                'price' => 11.99,
                'image_url' => 'https://picsum.photos/400/300?random=3',
            ],
            [
                'name' => 'Hawaiian',
                'description' => 'Tropical delight with ham, pineapple chunks, and mozzarella cheese',
                'price' => 13.49,
                'image_url' => 'https://picsum.photos/400/300?random=4',
            ],
            [
                'name' => 'Meat Lovers',
                'description' => 'Ultimate meat feast with pepperoni, sausage, ham, and bacon',
                'price' => 15.99,
                'image_url' => 'https://picsum.photos/400/300?random=5',
            ],
            [
                'name' => 'BBQ Chicken',
                'description' => 'Grilled chicken with BBQ sauce, red onions, and cilantro',
                'price' => 14.49,
                'image_url' => 'https://picsum.photos/400/300?random=6',
            ]
        ];

        foreach ($pizzas as $pizza) {
            Pizza::create($pizza);
        }
    }
}
