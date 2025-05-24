<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Ingredient;

class FoodController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->ordered()
            ->with(['availableFoodItems' => function($query) {
                $query->featured()->limit(3);
            }])
            ->get();

        return Inertia::render('Food/Index', [
            'categories' => $categories
        ]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['availableFoodItems'])
            ->firstOrFail();

        return Inertia::render('Food/Category', [
            'category' => $category,
            'foodItems' => $category->availableFoodItems
        ]);
    }

    public function show($categorySlug, $foodItemId)
    {
        $category = Category::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $foodItem = FoodItem::where('id', $foodItemId)
            ->where('category_id', $category->id)
            ->where('is_available', true)
            ->with(['defaultIngredients', 'availableIngredients'])
            ->firstOrFail();

        // Get available ingredients grouped by type
        $availableIngredients = Ingredient::where('category_id', $category->id)
            ->where('is_available', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return Inertia::render('Food/Show', [
            'category' => $category,
            'foodItem' => $foodItem,
            'availableIngredients' => $availableIngredients
        ]);
    }
}
