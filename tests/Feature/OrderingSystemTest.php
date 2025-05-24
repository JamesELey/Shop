<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Pizza;
use App\Models\Ingredient;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderingSystemTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $category;
    private $foodItem;
    private $pizza;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->category = Category::create([
            'name' => 'Burgers',
            'slug' => 'burgers',
            'description' => 'Delicious burgers for everyone'
        ]);

        $this->foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Classic Burger',
            'description' => 'Juicy beef patty with lettuce and tomato',
            'price' => 12.99,
            'image_url' => 'classic-burger.jpg'
        ]);

        $this->pizza = Pizza::create([
            'name' => 'Margherita Pizza',
            'description' => 'Classic pizza with tomato and mozzarella',
            'price' => 14.99,
            'image_url' => 'margherita.jpg'
        ]);
    }

    public function test_user_can_browse_food_categories()
    {
        $response = $this->actingAs($this->user)
                         ->get('/menu');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Food/Index')
            ->has('categories')
        );
    }

    public function test_user_can_view_category_specific_food_items()
    {
        $response = $this->actingAs($this->user)
                         ->get('/menu/burgers');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Food/Category')
            ->has('category')
            ->has('foodItems')
            ->where('category.slug', 'burgers')
        );
    }

    public function test_user_can_view_individual_food_item_with_customization_options()
    {
        // Create some ingredients for the food item
        $ingredient1 = Ingredient::create([
            'name' => 'Extra Cheese',
            'price' => 2.00,
            'category_id' => $this->category->id
        ]);

        $ingredient2 = Ingredient::create([
            'name' => 'Bacon',
            'price' => 3.00,
            'category_id' => $this->category->id
        ]);

        $this->foodItem->ingredients()->attach([$ingredient1->id, $ingredient2->id]);

        $response = $this->actingAs($this->user)
                         ->get('/menu/burgers/' . $this->foodItem->id);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Food/Show')
            ->has('foodItem')
            ->has('availableIngredients')
            ->where('foodItem.id', $this->foodItem->id)
        );
    }

    public function test_user_can_access_cart_page()
    {
        $response = $this->actingAs($this->user)
                         ->get('/cart');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Cart/Index')
        );
    }

    public function test_user_can_place_order_with_food_items()
    {
        $orderData = [
            'items' => [
                [
                    'food_item' => [
                        'id' => $this->foodItem->id,
                        'name' => $this->foodItem->name,
                        'price' => $this->foodItem->price,
                        'category' => $this->category->name
                    ],
                    'quantity' => 2,
                    'ingredients' => []
                ]
            ],
            'total_amount' => 25.98
        ];

        $response = $this->actingAs($this->user)
                         ->post('/orders', $orderData);

        $response->assertRedirect('/cart');
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'total_amount' => 25.98,
            'status' => 'pending'
        ]);
    }

    public function test_user_can_place_mixed_order_with_pizzas_and_food_items()
    {
        $ingredient = Ingredient::create([
            'name' => 'Pepperoni',
            'price' => 2.50,
            'category_id' => $this->category->id
        ]);

        $mixedOrderData = [
            'items' => [
                [
                    'pizza' => [
                        'id' => $this->pizza->id,
                        'name' => $this->pizza->name,
                        'price' => $this->pizza->price
                    ],
                    'quantity' => 1,
                    'selected_ingredients' => [
                        [
                            'id' => $ingredient->id,
                            'name' => $ingredient->name,
                            'price' => $ingredient->price
                        ]
                    ]
                ],
                [
                    'food_item' => [
                        'id' => $this->foodItem->id,
                        'name' => $this->foodItem->name,
                        'price' => $this->foodItem->price,
                        'category' => $this->category->name
                    ],
                    'quantity' => 1,
                    'ingredients' => []
                ]
            ],
            'total_amount' => 30.48 // 14.99 + 2.50 + 12.99
        ];

        $response = $this->actingAs($this->user)
                         ->post('/orders', $mixedOrderData);

        $response->assertRedirect('/cart');
        
        $order = Order::where('user_id', $this->user->id)->first();
        $items = json_decode($order->pizzas, true);
        
        $this->assertCount(2, $items);
        $this->assertArrayHasKey('pizza', $items[0]);
        $this->assertArrayHasKey('food_item', $items[1]);
        $this->assertEquals(30.48, $order->total_amount);
    }

    public function test_user_can_view_order_history()
    {
        // Create a test order
        Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode([
                [
                    'food_item' => [
                        'id' => $this->foodItem->id,
                        'name' => $this->foodItem->name,
                        'price' => $this->foodItem->price,
                        'category' => $this->category->name
                    ],
                    'quantity' => 1,
                    'ingredients' => []
                ]
            ]),
            'total_amount' => 12.99,
            'status' => 'delivered'
        ]);

        $response = $this->actingAs($this->user)
                         ->get('/orders');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Orders/History')
            ->has('orders')
        );
    }

    public function test_order_validation_requires_items()
    {
        $response = $this->actingAs($this->user)
                         ->post('/orders', [
                             'items' => [],
                             'total_amount' => 0
                         ]);

        $response->assertSessionHasErrors('items');
    }

    public function test_order_validation_requires_total_amount()
    {
        $response = $this->actingAs($this->user)
                         ->post('/orders', [
                             'items' => [
                                 [
                                     'food_item' => [
                                         'id' => $this->foodItem->id,
                                         'name' => $this->foodItem->name,
                                         'price' => $this->foodItem->price
                                     ],
                                     'quantity' => 1
                                 ]
                             ]
                             // Missing total_amount
                         ]);

        $response->assertSessionHasErrors('total_amount');
    }

    public function test_user_cannot_access_protected_routes_without_authentication()
    {
        // Test menu access
        $response = $this->get('/menu');
        $response->assertRedirect('/login');

        // Test cart access
        $response = $this->get('/cart');
        $response->assertRedirect('/login');

        // Test order creation
        $response = $this->post('/orders', []);
        $response->assertRedirect('/login');

        // Test order history
        $response = $this->get('/orders');
        $response->assertRedirect('/login');
    }

    public function test_user_can_access_legacy_pizza_system()
    {
        $response = $this->actingAs($this->user)
                         ->get('/pizzas');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Pizzas/Index')
            ->has('pizzas')
        );
    }

    public function test_food_item_not_found_returns_404()
    {
        $response = $this->actingAs($this->user)
                         ->get('/food/999999');

        $response->assertStatus(404);
    }

    public function test_category_not_found_returns_404()
    {
        $response = $this->actingAs($this->user)
                         ->get('/menu/nonexistent-category');

        $response->assertStatus(404);
    }

    public function test_order_creation_with_invalid_total_amount()
    {
        $response = $this->actingAs($this->user)
                         ->post('/orders', [
                             'items' => [
                                 [
                                     'food_item' => [
                                         'id' => $this->foodItem->id,
                                         'name' => $this->foodItem->name,
                                         'price' => $this->foodItem->price
                                     ],
                                     'quantity' => 1,
                                     'ingredients' => []
                                 ]
                             ],
                             'total_amount' => 'invalid_amount'
                         ]);

        $response->assertSessionHasErrors('total_amount');
    }

    public function test_user_can_customize_food_item_with_ingredients()
    {
        $ingredient1 = Ingredient::create([
            'name' => 'Extra Cheese',
            'price' => 2.00,
            'category_id' => $this->category->id
        ]);

        $ingredient2 = Ingredient::create([
            'name' => 'Bacon',
            'price' => 3.00,
            'category_id' => $this->category->id
        ]);

        $this->foodItem->ingredients()->attach([$ingredient1->id, $ingredient2->id]);

        $customOrderData = [
            'items' => [
                [
                    'food_item' => [
                        'id' => $this->foodItem->id,
                        'name' => $this->foodItem->name,
                        'price' => $this->foodItem->price,
                        'category' => $this->category->name
                    ],
                    'quantity' => 1,
                    'ingredients' => [
                        [
                            'id' => $ingredient1->id,
                            'name' => $ingredient1->name,
                            'price' => $ingredient1->price
                        ],
                        [
                            'id' => $ingredient2->id,
                            'name' => $ingredient2->name,
                            'price' => $ingredient2->price
                        ]
                    ]
                ]
            ],
            'total_amount' => 17.99 // 12.99 + 2.00 + 3.00
        ];

        $response = $this->actingAs($this->user)
                         ->post('/orders', $customOrderData);

        $response->assertRedirect('/cart');
        
        $order = Order::where('user_id', $this->user->id)->first();
        $items = json_decode($order->pizzas, true);
        
        $this->assertCount(2, $items[0]['ingredients']);
        $this->assertEquals('Extra Cheese', $items[0]['ingredients'][0]['name']);
        $this->assertEquals('Bacon', $items[0]['ingredients'][1]['name']);
    }
} 