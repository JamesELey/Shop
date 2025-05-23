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

        // Create some ingredients
        $cheese = Ingredient::create(['name' => 'Extra Cheese', 'price' => 1.50]);
        $pepperoni_ingredient = Ingredient::create(['name' => 'Pepperoni Slices', 'price' => 1.00]);
        $mushrooms = Ingredient::create(['name' => 'Mushrooms', 'price' => 0.75]);
        $onions = Ingredient::create(['name' => 'Onions', 'price' => 0.50]);
        $olives = Ingredient::create(['name' => 'Black Olives', 'price' => 0.75]);
        $pineapple_chunk = Ingredient::create(['name' => 'Pineapple Chunks', 'price' => 0.90]);
        $ham = Ingredient::create(['name' => 'Ham Pieces', 'price' => 1.20]);

        // Assign ingredients to pizzas (example)
        $margherita = Pizza::where('name', 'Margherita')->first();
        if ($margherita) {
            $margherita->ingredients()->syncWithoutDetaching([$cheese->id]); // Margherita typically has cheese
        }

        $pepperoni_pizza = Pizza::where('name', 'Pepperoni')->first();
        if ($pepperoni_pizza) {
            $pepperoni_pizza->ingredients()->syncWithoutDetaching([$cheese->id, $pepperoni_ingredient->id]);
        }

        $vegetarian = Pizza::where('name', 'Vegetarian')->first();
        if ($vegetarian) {
            $vegetarian->ingredients()->syncWithoutDetaching([$cheese->id, $mushrooms->id, $onions->id, $olives->id]);
        }
        
        $hawaiian = Pizza::where('name', 'Hawaiian')->first();
        if ($hawaiian) {
            $hawaiian->ingredients()->syncWithoutDetaching([$cheese->id, $pineapple_chunk->id, $ham->id]);
        }
    }
}
