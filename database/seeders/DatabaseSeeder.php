<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // New food system (includes category-specific ingredients)
            CategorySeeder::class,
            FoodItemSeeder::class,
            FoodItemIngredientSeeder::class,
            
            // Legacy pizza system (for backward compatibility)
            // Note: PizzaSeeder and PizzaIngredientSeeder use the ingredients created by FoodItemIngredientSeeder
            PizzaSeeder::class,
            PizzaIngredientSeeder::class,
            
            // Skip legacy IngredientSeeder to avoid conflicts
            // IngredientSeeder::class,
        ]);
    }
}
