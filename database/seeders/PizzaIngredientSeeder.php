<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pizza;
use App\Models\Ingredient;
use App\Models\Category;

class PizzaIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get pizzas and pizza category ingredients
        $pizzas = Pizza::all();
        $pizzaCategory = Category::where('slug', 'pizza')->first();
        
        if (!$pizzaCategory) {
            $this->command->info('Pizza category not found. Please run CategorySeeder first.');
            return;
        }
        
        $ingredients = Ingredient::where('category_id', $pizzaCategory->id)->get();

        if ($pizzas->isEmpty() || $ingredients->isEmpty()) {
            $this->command->info('No pizzas or pizza ingredients found. Please run PizzaSeeder and FoodItemIngredientSeeder first.');
            return;
        }

        // Clear existing relationships
        foreach ($pizzas as $pizza) {
            $pizza->ingredients()->detach();
        }

        // Define default ingredients for each pizza type (mapped to new ingredient names)
        $pizzaIngredients = [
            'Margherita' => ['Fresh Mozzarella', 'Fresh Basil'],
            'Pepperoni' => ['Fresh Mozzarella', 'Pepperoni'],
            'Vegetarian' => ['Fresh Mozzarella', 'Bell Peppers', 'Mushrooms', 'Red Onions'],
            'Hawaiian' => ['Fresh Mozzarella', 'Ham'],
            'Meat Lovers' => ['Fresh Mozzarella', 'Pepperoni', 'Italian Sausage', 'Ham', 'Bacon'],
            'BBQ Chicken' => ['Fresh Mozzarella', 'BBQ Sauce', 'Red Onions']
        ];

        foreach ($pizzas as $pizza) {
            if (isset($pizzaIngredients[$pizza->name])) {
                $pizzaIngredientNames = $pizzaIngredients[$pizza->name];
                
                foreach ($pizzaIngredientNames as $ingredientName) {
                    $ingredient = $ingredients->firstWhere('name', $ingredientName);
                    if ($ingredient) {
                        $pizza->ingredients()->attach($ingredient->id);
                    } else {
                        $this->command->info("Ingredient '{$ingredientName}' not found for pizza '{$pizza->name}'");
                    }
                }
            } else {
                // Default ingredients for any pizza not specifically defined
                $defaultIngredients = $ingredients->whereIn('name', ['Fresh Mozzarella'])->pluck('id');
                $pizza->ingredients()->attach($defaultIngredients);
            }
        }

        $this->command->info('Pizza ingredients relationships created successfully using category-specific ingredients.');
    }
} 