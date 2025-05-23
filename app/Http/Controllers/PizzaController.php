<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use App\Models\Ingredient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class PizzaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pizzas = Pizza::with('ingredients')->get();
        $allIngredients = Ingredient::orderBy('name')->get();

        $viewData = [
            'pizzas' => $pizzas->map(function ($pizza) {
                $defaultIngredients = $pizza->ingredients->map(function ($ingredient) {
                    return [
                        'id' => $ingredient->id,
                        'name' => $ingredient->name,
                        'price' => (float)$ingredient->price, // Ensure price is float
                    ];
                });
                
                $priceWithDefaults = (float)$pizza->price; // Start with base price
                foreach ($defaultIngredients as $ingredient) {
                    $priceWithDefaults += $ingredient['price'];
                }

                return [
                    'id' => $pizza->id,
                    'name' => $pizza->name,
                    'description' => $pizza->description,
                    'base_price' => (float)$pizza->price, // True base price of the pizza
                    'price_with_defaults' => $priceWithDefaults, // For initial display
                    'image_url' => $pizza->image_url,
                    'default_ingredients' => $defaultIngredients,
                ];
            }),
            'allIngredients' => $allIngredients->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'price' => (float)$ingredient->price, // Ensure price is float
                ];
            })
        ];

        Log::info('Data for Pizzas/Index view (v2 price logic):', $viewData);

        return Inertia::render('Pizzas/Index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pizza $pizza)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pizza $pizza)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pizza $pizza)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pizza $pizza)
    {
        //
    }
}
