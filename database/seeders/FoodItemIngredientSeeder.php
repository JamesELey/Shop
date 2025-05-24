<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Category;
use App\Models\FoodItem;

class FoodItemIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::query()->delete();

        $categories = Category::all()->keyBy('slug');

        // Pizza ingredients
        if ($categories->has('pizza')) {
            $this->createPizzaIngredients($categories->get('pizza'));
        }

        // Burger ingredients  
        if ($categories->has('burgers')) {
            $this->createBurgerIngredients($categories->get('burgers'));
        }

        // Pasta ingredients
        if ($categories->has('pasta')) {
            $this->createPastaIngredients($categories->get('pasta'));
        }

        // Salad ingredients
        if ($categories->has('salads')) {
            $this->createSaladIngredients($categories->get('salads'));
        }

        // Assign default ingredients
        $this->assignDefaultIngredients();
    }

    private function createPizzaIngredients($category)
    {
        $ingredients = [
            ['name' => 'Extra Cheese', 'price' => 1.50, 'type' => 'cheese'],
            ['name' => 'Fresh Mozzarella', 'price' => 2.00, 'type' => 'cheese'],
            ['name' => 'Pepperoni', 'price' => 1.00, 'type' => 'meat'],
            ['name' => 'Italian Sausage', 'price' => 1.25, 'type' => 'meat'],
            ['name' => 'Ham', 'price' => 1.20, 'type' => 'meat'],
            ['name' => 'Bacon', 'price' => 1.30, 'type' => 'meat'],
            ['name' => 'Mushrooms', 'price' => 0.75, 'type' => 'vegetable'],
            ['name' => 'Red Onions', 'price' => 0.50, 'type' => 'vegetable'],
            ['name' => 'Bell Peppers', 'price' => 0.65, 'type' => 'vegetable'],
            ['name' => 'Fresh Basil', 'price' => 0.75, 'type' => 'vegetable'],
            ['name' => 'BBQ Sauce', 'price' => 0.50, 'type' => 'sauce'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create([
                'name' => $ingredient['name'],
                'price' => $ingredient['price'],
                'category_id' => $category->id,
                'type' => $ingredient['type'],
                'is_available' => true
            ]);
        }
    }

    private function createBurgerIngredients($category)
    {
        $ingredients = [
            ['name' => 'Cheddar Cheese', 'price' => 1.00, 'type' => 'cheese'],
            ['name' => 'Swiss Cheese', 'price' => 1.25, 'type' => 'cheese'],
            ['name' => 'Bacon', 'price' => 1.50, 'type' => 'meat'],
            ['name' => 'Lettuce', 'price' => 0.25, 'type' => 'vegetable'],
            ['name' => 'Tomato', 'price' => 0.50, 'type' => 'vegetable'],
            ['name' => 'Red Onion', 'price' => 0.30, 'type' => 'vegetable'],
            ['name' => 'Pickles', 'price' => 0.25, 'type' => 'vegetable'],
            ['name' => 'Avocado', 'price' => 1.25, 'type' => 'vegetable'],
            ['name' => 'Special Sauce', 'price' => 0.25, 'type' => 'sauce'],
            ['name' => 'BBQ Sauce', 'price' => 0.25, 'type' => 'sauce'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create([
                'name' => $ingredient['name'],
                'price' => $ingredient['price'],
                'category_id' => $category->id,
                'type' => $ingredient['type'],
                'is_available' => true
            ]);
        }
    }

    private function createPastaIngredients($category)
    {
        $ingredients = [
            ['name' => 'Grilled Chicken', 'price' => 3.00, 'type' => 'protein'],
            ['name' => 'Shrimp', 'price' => 4.00, 'type' => 'protein'],
            ['name' => 'Mushrooms', 'price' => 1.00, 'type' => 'vegetable'],
            ['name' => 'Spinach', 'price' => 0.75, 'type' => 'vegetable'],
            ['name' => 'Extra Parmesan', 'price' => 1.50, 'type' => 'cheese'],
            ['name' => 'Pesto Sauce', 'price' => 1.50, 'type' => 'sauce'],
            ['name' => 'Alfredo Sauce', 'price' => 1.25, 'type' => 'sauce'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create([
                'name' => $ingredient['name'],
                'price' => $ingredient['price'],
                'category_id' => $category->id,
                'type' => $ingredient['type'],
                'is_available' => true
            ]);
        }
    }

    private function createSaladIngredients($category)
    {
        $ingredients = [
            ['name' => 'Grilled Chicken', 'price' => 3.00, 'type' => 'protein'],
            ['name' => 'Salmon', 'price' => 4.50, 'type' => 'protein'],
            ['name' => 'Feta Cheese', 'price' => 1.25, 'type' => 'cheese'],
            ['name' => 'Avocado', 'price' => 1.50, 'type' => 'vegetable'],
            ['name' => 'Cherry Tomatoes', 'price' => 0.75, 'type' => 'vegetable'],
            ['name' => 'Croutons', 'price' => 0.50, 'type' => 'topping'],
            ['name' => 'Ranch Dressing', 'price' => 0.50, 'type' => 'dressing'],
            ['name' => 'Caesar Dressing', 'price' => 0.50, 'type' => 'dressing'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create([
                'name' => $ingredient['name'],
                'price' => $ingredient['price'],
                'category_id' => $category->id,
                'type' => $ingredient['type'],
                'is_available' => true
            ]);
        }
    }

    private function assignDefaultIngredients()
    {
        // Simple default assignments - can be expanded later
        $categories = Category::all()->keyBy('slug');
        
        // Pizza defaults
        if ($categories->has('pizza')) {
            $pizzaCategory = $categories->get('pizza');
            $pizzaIngredients = Ingredient::where('category_id', $pizzaCategory->id)->get()->keyBy('name');
            
            if ($margherita = FoodItem::where('name', 'Margherita')->where('category_id', $pizzaCategory->id)->first()) {
                if (isset($pizzaIngredients['Fresh Mozzarella']) && isset($pizzaIngredients['Fresh Basil'])) {
                    $margherita->ingredients()->attach([
                        $pizzaIngredients['Fresh Mozzarella']->id => ['is_default' => true],
                        $pizzaIngredients['Fresh Basil']->id => ['is_default' => true],
                    ]);
                }
            }
        }
    }
}
