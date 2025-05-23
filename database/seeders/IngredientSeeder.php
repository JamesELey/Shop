<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Pizza;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::query()->delete(); // Clear existing ingredients

        // Create base ingredients
        $cheese = Ingredient::create(['name' => 'Extra Cheese', 'price' => 1.50]);
        $mozzarella = Ingredient::create(['name' => 'Fresh Mozzarella', 'price' => 2.00]);
        $basil = Ingredient::create(['name' => 'Fresh Basil', 'price' => 0.75]);
        
        // Meat ingredients
        $pepperoni_ingredient = Ingredient::create(['name' => 'Pepperoni Slices', 'price' => 1.00]);
        $ham = Ingredient::create(['name' => 'Ham Pieces', 'price' => 1.20]);
        $sausage = Ingredient::create(['name' => 'Italian Sausage', 'price' => 1.25]);
        $bacon = Ingredient::create(['name' => 'Bacon Bits', 'price' => 1.30]);
        $chicken = Ingredient::create(['name' => 'Grilled Chicken', 'price' => 1.75]);
        
        // Vegetable ingredients
        $mushrooms = Ingredient::create(['name' => 'Mushrooms', 'price' => 0.75]);
        $onions = Ingredient::create(['name' => 'Red Onions', 'price' => 0.50]);
        $bell_peppers = Ingredient::create(['name' => 'Bell Peppers', 'price' => 0.65]);
        $olives = Ingredient::create(['name' => 'Black Olives', 'price' => 0.75]);
        $pineapple_chunk = Ingredient::create(['name' => 'Pineapple Chunks', 'price' => 0.90]);
        $cilantro = Ingredient::create(['name' => 'Fresh Cilantro', 'price' => 0.60]);
        
        // Sauce ingredients
        $bbq_sauce = Ingredient::create(['name' => 'BBQ Sauce', 'price' => 0.50]);
        $extra_tomato_sauce = Ingredient::create(['name' => 'Extra Tomato Sauce', 'price' => 0.40]);

        // Assign default ingredients to pizzas
        $margherita = Pizza::where('name', 'Margherita')->first();
        if ($margherita) {
            $margherita->ingredients()->syncWithoutDetaching([$mozzarella->id, $basil->id]);
        }

        $pepperoni_pizza = Pizza::where('name', 'Pepperoni')->first();
        if ($pepperoni_pizza) {
            $pepperoni_pizza->ingredients()->syncWithoutDetaching([$cheese->id, $pepperoni_ingredient->id]);
        }

        $vegetarian = Pizza::where('name', 'Vegetarian')->first();
        if ($vegetarian) {
            $vegetarian->ingredients()->syncWithoutDetaching([$cheese->id, $mushrooms->id, $bell_peppers->id, $onions->id, $olives->id]);
        }
        
        $hawaiian = Pizza::where('name', 'Hawaiian')->first();
        if ($hawaiian) {
            $hawaiian->ingredients()->syncWithoutDetaching([$cheese->id, $pineapple_chunk->id, $ham->id]);
        }
        
        $meat_lovers = Pizza::where('name', 'Meat Lovers')->first();
        if ($meat_lovers) {
            $meat_lovers->ingredients()->syncWithoutDetaching([$cheese->id, $pepperoni_ingredient->id, $sausage->id, $ham->id, $bacon->id]);
        }
        
        $bbq_chicken = Pizza::where('name', 'BBQ Chicken')->first();
        if ($bbq_chicken) {
            $bbq_chicken->ingredients()->syncWithoutDetaching([$cheese->id, $chicken->id, $onions->id, $cilantro->id, $bbq_sauce->id]);
        }
    }
}
