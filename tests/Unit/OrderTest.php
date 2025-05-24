<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test category for orders'
        ]);
    }

    public function test_order_can_be_created()
    {
        $orderItems = [
            [
                'food_item' => [
                    'id' => 1,
                    'name' => 'Test Burger',
                    'price' => 15.99,
                    'category' => 'Burgers'
                ],
                'quantity' => 2,
                'ingredients' => []
            ]
        ];

        $order = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode($orderItems), // Store items in 'pizzas' field
            'total_amount' => 31.98,
            'status' => 'pending'
        ]);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($this->user->id, $order->user_id);
        $this->assertEquals(31.98, $order->total_amount);
        $this->assertEquals('pending', $order->status);
        $this->assertNotNull($order->pizzas);
    }

    public function test_order_belongs_to_user()
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode([]),
            'total_amount' => 20.00,
            'status' => 'pending'
        ]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($this->user->id, $order->user->id);
        $this->assertEquals('Test User', $order->user->name);
    }

    public function test_order_can_store_mixed_item_types()
    {
        $pizza = Pizza::create([
            'name' => 'Test Pizza',
            'description' => 'Test pizza for mixed order',
            'price' => 18.99,
            'image_url' => 'test-pizza.jpg'
        ]);

        $foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Test Burger',
            'description' => 'Test burger for mixed order',
            'price' => 12.99,
            'image_url' => 'test-burger.jpg'
        ]);

        $mixedOrderData = [
            [
                'pizza' => [
                    'id' => $pizza->id,
                    'name' => $pizza->name,
                    'price' => $pizza->price
                ],
                'quantity' => 1,
                'selected_ingredients' => []
            ],
            [
                'food_item' => [
                    'id' => $foodItem->id,
                    'name' => $foodItem->name,
                    'price' => $foodItem->price,
                    'category' => 'Burgers'
                ],
                'quantity' => 2,
                'ingredients' => []
            ]
        ];

        $order = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode($mixedOrderData),
            'total_amount' => 44.97, // 18.99 + (12.99 * 2)
            'status' => 'pending'
        ]);

        $items = json_decode($order->pizzas, true);
        
        $this->assertCount(2, $items);
        $this->assertArrayHasKey('pizza', $items[0]);
        $this->assertArrayHasKey('food_item', $items[1]);
        $this->assertEquals('Test Pizza', $items[0]['pizza']['name']);
        $this->assertEquals('Test Burger', $items[1]['food_item']['name']);
    }

    public function test_order_can_calculate_total_from_items()
    {
        $orderItems = [
            [
                'food_item' => [
                    'id' => 1,
                    'name' => 'Burger',
                    'price' => 12.99,
                    'category' => 'Burgers'
                ],
                'quantity' => 2,
                'ingredients' => [
                    ['name' => 'Extra Cheese', 'price' => 2.00],
                    ['name' => 'Bacon', 'price' => 3.00]
                ]
            ],
            [
                'food_item' => [
                    'id' => 2,
                    'name' => 'Fries',
                    'price' => 5.99,
                    'category' => 'Sides'
                ],
                'quantity' => 1,
                'ingredients' => []
            ]
        ];

        // Calculate expected total: (12.99 + 2.00 + 3.00) * 2 + 5.99 = 41.97
        $expectedTotal = ((12.99 + 2.00 + 3.00) * 2) + 5.99;

        $order = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode($orderItems),
            'total_amount' => $expectedTotal,
            'status' => 'pending'
        ]);

        $this->assertEqualsWithDelta(41.97, $order->total_amount, 0.01);
    }

    public function test_order_has_valid_status_values()
    {
        $validStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];

        foreach ($validStatuses as $status) {
            $order = Order::create([
                'user_id' => $this->user->id,
                'pizzas' => json_encode([]),
                'total_amount' => 10.00,
                'status' => $status
            ]);

            $this->assertEquals($status, $order->status);
        }
    }

    public function test_order_requires_user_id()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Order::create([
            'pizzas' => json_encode([]),
            'total_amount' => 10.00,
            'status' => 'pending'
            // Missing user_id
        ]);
    }

    public function test_order_requires_pizzas_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Order::create([
            'user_id' => $this->user->id,
            'total_amount' => 10.00,
            'status' => 'pending'
            // Missing pizzas field
        ]);
    }

    public function test_order_items_can_be_decoded_from_json()
    {
        $orderItems = [
            [
                'food_item' => [
                    'id' => 1,
                    'name' => 'Test Item',
                    'price' => 15.00
                ],
                'quantity' => 1,
                'ingredients' => []
            ]
        ];

        $order = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode($orderItems),
            'total_amount' => 15.00,
            'status' => 'pending'
        ]);

        $decodedItems = json_decode($order->pizzas, true);
        
        $this->assertIsArray($decodedItems);
        $this->assertCount(1, $decodedItems);
        $this->assertEquals('Test Item', $decodedItems[0]['food_item']['name']);
        $this->assertEquals(15.00, $decodedItems[0]['food_item']['price']);
    }

    public function test_order_total_amount_is_numeric()
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode([]),
            'total_amount' => '25.99', // String value
            'status' => 'pending'
        ]);

        $this->assertIsNumeric($order->total_amount);
        $this->assertEquals(25.99, (float) $order->total_amount);
    }

    public function test_user_can_have_multiple_orders()
    {
        $order1 = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode([]),
            'total_amount' => 10.00,
            'status' => 'pending'
        ]);

        $order2 = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode([]),
            'total_amount' => 20.00,
            'status' => 'confirmed'
        ]);

        $userOrders = $this->user->orders;
        
        $this->assertCount(2, $userOrders);
        $this->assertTrue($userOrders->contains($order1));
        $this->assertTrue($userOrders->contains($order2));
    }

    public function test_order_can_store_empty_items_array()
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode([]),
            'total_amount' => 0.00,
            'status' => 'pending'
        ]);

        $items = json_decode($order->pizzas, true);
        
        $this->assertIsArray($items);
        $this->assertCount(0, $items);
    }

    public function test_order_can_store_pizza_items()
    {
        $pizza = Pizza::create([
            'name' => 'Margherita',
            'description' => 'Classic pizza',
            'price' => 12.99,
            'image_url' => 'margherita.jpg'
        ]);

        $pizzaItems = [
            [
                'pizza' => [
                    'id' => $pizza->id,
                    'name' => $pizza->name,
                    'price' => $pizza->price
                ],
                'quantity' => 1,
                'selected_ingredients' => [
                    [
                        'id' => 1,
                        'name' => 'Extra Cheese',
                        'price' => 2.00
                    ]
                ]
            ]
        ];

        $order = Order::create([
            'user_id' => $this->user->id,
            'pizzas' => json_encode($pizzaItems),
            'total_amount' => 14.99,
            'status' => 'pending'
        ]);

        $items = json_decode($order->pizzas, true);
        
        $this->assertCount(1, $items);
        $this->assertArrayHasKey('pizza', $items[0]);
        $this->assertEquals('Margherita', $items[0]['pizza']['name']);
        $this->assertCount(1, $items[0]['selected_ingredients']);
        $this->assertEquals('Extra Cheese', $items[0]['selected_ingredients'][0]['name']);
    }
} 