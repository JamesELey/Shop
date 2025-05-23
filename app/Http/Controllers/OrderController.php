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

        // Get all current pizzas for display data
        $currentPizzas = Pizza::all()->keyBy('id');

        return Inertia::render('Orders/History', [
            'orders' => $orders->map(function ($order) use ($currentPizzas) {
                $decodedPizzas = json_decode($order->pizzas, true);
                $processedPizzas = [];
                if (is_array($decodedPizzas)) {
                    foreach ($decodedPizzas as $pizzaItem) {
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

                        // Ensure selected ingredient prices are float and ingredients have names
                        if (isset($pizzaItem['selected_ingredients']) && is_array($pizzaItem['selected_ingredients'])) {
                            foreach ($pizzaItem['selected_ingredients'] as &$selectedIngredient) { // Use reference
                                if (isset($selectedIngredient['price'])) {
                                    $selectedIngredient['price'] = (float)$selectedIngredient['price'];
                                } else {
                                    $selectedIngredient['price'] = 0.0;
                                }
                                if (!isset($selectedIngredient['name'])) {
                                    $selectedIngredient['name'] = 'Customization';
                                }
                                 if (!isset($selectedIngredient['id'])) { // Add id for keying if missing
                                    $selectedIngredient['id'] = 'ing_' . mt_rand();
                                }
                            }
                            unset($selectedIngredient); // Unset reference
                        } else {
                            // Ensure selected_ingredients is an empty array if not present or not an array
                            $pizzaItem['selected_ingredients'] = [];
                        }
                        $processedPizzas[] = $pizzaItem;
                    }
                }

                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'total_amount' => (float)$order->total_amount,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'pizzas' => $processedPizzas
                ];
            }),
        ]);
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

        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.pizza.id' => 'required|integer|exists:pizzas,id',
            'items.*.pizza.name' => 'required|string|max:255',
            'items.*.pizza.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.selected_ingredients' => 'nullable|array',
            'items.*.selected_ingredients.*.id' => 'integer|exists:ingredients,id',
            'items.*.selected_ingredients.*.name' => 'string',
            'items.*.selected_ingredients.*.price' => 'numeric',
            'total_amount' => 'required|numeric|min:0',
        ]);

        Log::info('Validated data for order creation:', $validatedData);

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $validatedData['total_amount'],
                'status' => 'pending',
                'pizzas' => json_encode($validatedData['items']),
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
