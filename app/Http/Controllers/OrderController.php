<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pizza;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource for the authenticated user.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Get all current pizzas and food items for display data
        $currentPizzas = Pizza::all()->keyBy('id');
        $currentFoodItems = \App\Models\FoodItem::with('category')->get()->keyBy('id');

        return Inertia::render('Orders/History', [
            'orders' => $orders->map(function ($order) use ($currentPizzas, $currentFoodItems) {
                $decodedItems = json_decode($order->pizzas, true);
                $processedItems = [];
                
                if (is_array($decodedItems)) {
                    foreach ($decodedItems as $item) {
                        if (isset($item['pizza'])) {
                            // Process pizza item
                            $processedItems[] = $this->processPizzaItem($item, $currentPizzas);
                        } elseif (isset($item['food_item'])) {
                            // Process food item
                            $processedItems[] = $this->processFoodItem($item, $currentFoodItems);
                        }
                    }
                }

                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'total_amount' => (float)$order->total_amount,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'items' => $processedItems // Changed from 'pizzas' to 'items'
                ];
            }),
        ]);
    }

    private function processPizzaItem($pizzaItem, $currentPizzas)
    {
        // Get current pizza data for display (images, current name, etc.)
        $currentPizza = null;
        if (isset($pizzaItem['pizza']['id']) && $currentPizzas->has($pizzaItem['pizza']['id'])) {
            $currentPizza = $currentPizzas->get($pizzaItem['pizza']['id']);
        }

        // Ensure 'pizza' key exists and has defaults
        if (!isset($pizzaItem['pizza']) || !is_array($pizzaItem['pizza'])) {
            $pizzaItem['pizza'] = ['name' => 'Unknown Pizza', 'price' => 0.0, 'image_url' => null];
        } else {
            // Use historical data for name and price (what was actually ordered)
            if (!isset($pizzaItem['pizza']['name'])) {
                $pizzaItem['pizza']['name'] = $currentPizza ? $currentPizza->name : 'Unknown Pizza';
            }
            if (!isset($pizzaItem['pizza']['price'])) {
                $pizzaItem['pizza']['price'] = 0.0;
            } else {
                $pizzaItem['pizza']['price'] = (float)$pizzaItem['pizza']['price'];
            }
            
            // Always use current image for display consistency
            $pizzaItem['pizza']['image_url'] = $currentPizza ? $currentPizza->image_url : null;
        }

        // Process selected ingredients
        if (isset($pizzaItem['selected_ingredients']) && is_array($pizzaItem['selected_ingredients'])) {
            foreach ($pizzaItem['selected_ingredients'] as &$selectedIngredient) {
                if (isset($selectedIngredient['price'])) {
                    $selectedIngredient['price'] = (float)$selectedIngredient['price'];
                } else {
                    $selectedIngredient['price'] = 0.0;
                }
                if (!isset($selectedIngredient['name'])) {
                    $selectedIngredient['name'] = 'Customization';
                }
                if (!isset($selectedIngredient['id'])) {
                    $selectedIngredient['id'] = 'ing_' . mt_rand();
                }
            }
            unset($selectedIngredient);
        } else {
            $pizzaItem['selected_ingredients'] = [];
        }

        $pizzaItem['type'] = 'pizza';
        return $pizzaItem;
    }

    private function processFoodItem($foodItem, $currentFoodItems)
    {
        // Get current food item data for display
        $currentFoodItem = null;
        if (isset($foodItem['food_item']['id']) && $currentFoodItems->has($foodItem['food_item']['id'])) {
            $currentFoodItem = $currentFoodItems->get($foodItem['food_item']['id']);
        }

        // Ensure 'food_item' key exists and has defaults
        if (!isset($foodItem['food_item']) || !is_array($foodItem['food_item'])) {
            $foodItem['food_item'] = ['name' => 'Unknown Item', 'price' => 0.0, 'image_url' => null, 'category' => 'Food'];
        } else {
            // Use historical data for name and price
            if (!isset($foodItem['food_item']['name'])) {
                $foodItem['food_item']['name'] = $currentFoodItem ? $currentFoodItem->name : 'Unknown Item';
            }
            if (!isset($foodItem['food_item']['price'])) {
                $foodItem['food_item']['price'] = 0.0;
            } else {
                $foodItem['food_item']['price'] = (float)$foodItem['food_item']['price'];
            }
            
            // Always use current image for display consistency
            $foodItem['food_item']['image_url'] = $currentFoodItem ? $currentFoodItem->image_url : null;
            
            // Set category
            if (!isset($foodItem['food_item']['category']) && $currentFoodItem && $currentFoodItem->category) {
                $foodItem['food_item']['category'] = $currentFoodItem->category->name;
            }
        }

        // Process ingredients
        if (isset($foodItem['ingredients']) && is_array($foodItem['ingredients'])) {
            foreach ($foodItem['ingredients'] as &$ingredient) {
                if (isset($ingredient['price'])) {
                    $ingredient['price'] = (float)$ingredient['price'];
                } else {
                    $ingredient['price'] = 0.0;
                }
                if (!isset($ingredient['name'])) {
                    $ingredient['name'] = 'Customization';
                }
                if (!isset($ingredient['id'])) {
                    $ingredient['id'] = 'ing_' . mt_rand();
                }
            }
            unset($ingredient);
        } else {
            $foodItem['ingredients'] = [];
        }

        $foodItem['type'] = 'food_item';
        return $foodItem;
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
        Log::info('Order store request received:', $request->all());

        // Custom validation logic to handle both pizza and food item formats
        $request->validate([
            'items' => 'required|array',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Validate each item based on its type
        $items = $request->input('items');
        foreach ($items as $index => $item) {
            if (isset($item['pizza'])) {
                // Pizza item validation
                $request->validate([
                    "items.{$index}.pizza.id" => 'required|integer|exists:pizzas,id',
                    "items.{$index}.pizza.name" => 'required|string|max:255',
                    "items.{$index}.pizza.price" => 'required|numeric|min:0',
                    "items.{$index}.quantity" => 'required|integer|min:1',
                    "items.{$index}.selected_ingredients" => 'nullable|array',
                    "items.{$index}.selected_ingredients.*.id" => 'integer|exists:ingredients,id',
                    "items.{$index}.selected_ingredients.*.name" => 'string',
                    "items.{$index}.selected_ingredients.*.price" => 'numeric',
                ]);
            } elseif (isset($item['food_item'])) {
                // Food item validation
                $request->validate([
                    "items.{$index}.food_item.id" => 'required|integer|exists:food_items,id',
                    "items.{$index}.food_item.name" => 'required|string|max:255',
                    "items.{$index}.food_item.price" => 'required|numeric|min:0',
                    "items.{$index}.food_item.category" => 'nullable|string',
                    "items.{$index}.quantity" => 'required|integer|min:1',
                    "items.{$index}.ingredients" => 'nullable|array',
                    "items.{$index}.ingredients.*.id" => 'integer|exists:ingredients,id',
                    "items.{$index}.ingredients.*.name" => 'string',
                    "items.{$index}.ingredients.*.price" => 'numeric',
                ]);
            }
        }

        Log::info('Validated data for order creation:', $request->all());

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $request->input('total_amount'),
                'status' => 'pending',
                'pizzas' => json_encode($items), // Store all items (pizzas and food items) in the same column
            ]);

            Log::info('Order created successfully:', ['order_id' => $order->id]);

            return redirect()->route('cart.index')->with('success', 'Order placed successfully! ID: ' . $order->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating order:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating order:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Failed to place order. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
