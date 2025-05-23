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

        Pizza::create([
            'name' => 'Margherita',
            'description' => 'Classic delight with 100% real mozzarella cheese',
            'price' => 10.99,
            'image_url' => 'https://via.placeholder.com/400x300.png/007722?text=Margherita_Pizza',
        ]);

        Pizza::create([
            'name' => 'Pepperoni',
            'description' => 'A meat lover\'s favorite with spicy pepperoni',
            'price' => 12.99,
            'image_url' => 'https://via.placeholder.com/400x300.png/dd2222?text=Pepperoni_Pizza',
        ]);

        Pizza::create([
            'name' => 'Vegetarian',
            'description' => 'Packed with fresh garden vegetables',
            'price' => 11.99,
            'image_url' => 'https://via.placeholder.com/400x300.png/22aa55?text=Vegetarian_Pizza',
        ]);

        Pizza::create([
            'name' => 'Hawaiian',
            'description' => 'Sweet and savory with pineapple and ham',
            'price' => 13.49,
            'image_url' => 'https://via.placeholder.com/400x300.png/ffcc00?text=Hawaiian_Pizza',
        ]);
    }
}
