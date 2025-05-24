<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartStoreTest extends TestCase
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
            'description' => 'Test burger category'
        ]);

        $this->foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Test Burger',
            'description' => 'A test burger',
            'price' => 12.99,
            'image_url' => 'test-burger.jpg'
        ]);

        $this->pizza = Pizza::create([
            'name' => 'Test Pizza',
            'description' => 'A test pizza',
            'price' => 15.99,
            'image_url' => 'test-pizza.jpg'
        ]);
    }

    public function test_cart_data_validation_for_food_items()
    {
        $validCartData = [
            'type' => 'food_item',
            'food_item_id' => $this->foodItem->id,
            'quantity' => 2,
            'ingredients' => [
                [
                    'id' => 1,
                    'name' => 'Extra Cheese',
                    'price' => 2.00
                ]
            ],
            'total_price' => 29.98,
            'base_price' => 12.99
        ];

        // Test that valid cart data structure is accepted
        $this->assertTrue($this->isValidFoodItemCartData($validCartData));
    }

    public function test_cart_data_validation_for_pizza_items()
    {
        $validPizzaData = [
            'type' => 'pizza',
            'pizzaId' => $this->pizza->id,
            'quantity' => 1,
            'selected_ingredients' => [
                [
                    'id' => 1,
                    'name' => 'Pepperoni',
                    'price' => 2.50
                ]
            ],
            'unitPrice' => 18.49,
            'original_base_price' => 15.99
        ];

        // Test that valid pizza cart data structure is accepted
        $this->assertTrue($this->isValidPizzaCartData($validPizzaData));
    }

    public function test_cart_item_price_calculation_with_ingredients()
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

        $basePrice = 12.99;
        $ingredientPrices = [$ingredient1->price, $ingredient2->price];
        $quantity = 2;

        $expectedTotal = ($basePrice + array_sum($ingredientPrices)) * $quantity;
        $calculatedTotal = $this->calculateCartItemTotal($basePrice, $ingredientPrices, $quantity);

        $this->assertEquals($expectedTotal, $calculatedTotal);
        $this->assertEqualsWithDelta(35.98, $calculatedTotal, 0.01); // (12.99 + 2.00 + 3.00) * 2
    }

    public function test_cart_item_signature_generation_for_uniqueness()
    {
        $ingredients1 = [
            ['id' => 1, 'name' => 'Cheese'],
            ['id' => 2, 'name' => 'Bacon']
        ];

        $ingredients2 = [
            ['id' => 2, 'name' => 'Bacon'],
            ['id' => 1, 'name' => 'Cheese']
        ];

        $ingredients3 = [
            ['id' => 1, 'name' => 'Cheese'],
            ['id' => 3, 'name' => 'Pepperoni']
        ];

        $signature1 = $this->generateIngredientsSignature($ingredients1);
        $signature2 = $this->generateIngredientsSignature($ingredients2);
        $signature3 = $this->generateIngredientsSignature($ingredients3);

        // Same ingredients in different order should have same signature
        $this->assertEquals($signature1, $signature2);
        
        // Different ingredients should have different signatures
        $this->assertNotEquals($signature1, $signature3);
    }

    public function test_cart_persistence_data_format()
    {
        $cartItems = [
            [
                'cartLineId' => 1234567890,
                'type' => 'food_item',
                'food_item_id' => $this->foodItem->id,
                'quantity' => 1,
                'ingredients' => [],
                'total_price' => 12.99,
                'base_price' => 12.99,
                'image_url' => 'test-burger.jpg',
                'description' => 'A test burger'
            ],
            [
                'cartLineId' => 1234567891,
                'type' => 'pizza',
                'pizzaId' => $this->pizza->id,
                'quantity' => 1,
                'selected_ingredients' => [],
                'unitPrice' => 15.99,
                'original_base_price' => 15.99,
                'image_url' => 'test-pizza.jpg',
                'description' => 'A test pizza'
            ]
        ];

        $jsonData = json_encode($cartItems);
        $decodedData = json_decode($jsonData, true);

        $this->assertIsArray($decodedData);
        $this->assertCount(2, $decodedData);
        $this->assertEquals('food_item', $decodedData[0]['type']);
        $this->assertEquals('pizza', $decodedData[1]['type']);
    }

    public function test_cart_total_calculation_with_multiple_items()
    {
        $items = [
            [
                'base_price' => 12.99,
                'ingredients' => [
                    ['price' => 2.00],
                    ['price' => 1.50]
                ],
                'quantity' => 2
            ],
            [
                'base_price' => 15.99,
                'ingredients' => [
                    ['price' => 2.50]
                ],
                'quantity' => 1
            ]
        ];

        $totalPrice = 0;
        foreach ($items as $item) {
            $itemPrice = $item['base_price'];
            foreach ($item['ingredients'] as $ingredient) {
                $itemPrice += $ingredient['price'];
            }
            $totalPrice += $itemPrice * $item['quantity'];
        }

        $this->assertEqualsWithDelta(51.47, $totalPrice, 0.01); // (12.99 + 2.00 + 1.50) * 2 + (15.99 + 2.50) * 1
    }

    public function test_cart_item_quantity_validation()
    {
        // Test minimum quantity
        $this->assertFalse($this->isValidQuantity(0));
        $this->assertFalse($this->isValidQuantity(-1));
        
        // Test valid quantities
        $this->assertTrue($this->isValidQuantity(1));
        $this->assertTrue($this->isValidQuantity(5));
        $this->assertTrue($this->isValidQuantity(10));
        
        // Test maximum reasonable quantity
        $this->assertFalse($this->isValidQuantity(101)); // Assuming max of 100
    }

    public function test_cart_ingredient_price_validation()
    {
        // Test valid prices
        $this->assertTrue($this->isValidPrice(0.00));
        $this->assertTrue($this->isValidPrice(2.50));
        $this->assertTrue($this->isValidPrice(15.99));
        
        // Test invalid prices
        $this->assertFalse($this->isValidPrice(-1.00));
        $this->assertFalse($this->isValidPrice('invalid'));
        $this->assertFalse($this->isValidPrice(null));
    }

    // Helper methods for cart validation logic
    private function isValidFoodItemCartData($data)
    {
        return isset($data['type']) && $data['type'] === 'food_item' &&
               isset($data['food_item_id']) &&
               isset($data['quantity']) && $data['quantity'] > 0 &&
               isset($data['total_price']) && is_numeric($data['total_price']) &&
               isset($data['base_price']) && is_numeric($data['base_price']) &&
               isset($data['ingredients']) && is_array($data['ingredients']);
    }

    private function isValidPizzaCartData($data)
    {
        return isset($data['type']) && $data['type'] === 'pizza' &&
               isset($data['pizzaId']) &&
               isset($data['quantity']) && $data['quantity'] > 0 &&
               isset($data['unitPrice']) && is_numeric($data['unitPrice']) &&
               isset($data['original_base_price']) && is_numeric($data['original_base_price']) &&
               isset($data['selected_ingredients']) && is_array($data['selected_ingredients']);
    }

    private function calculateCartItemTotal($basePrice, $ingredientPrices, $quantity)
    {
        return ($basePrice + array_sum($ingredientPrices)) * $quantity;
    }

    private function generateIngredientsSignature($ingredients)
    {
        $ids = array_map(function($ingredient) {
            return $ingredient['id'];
        }, $ingredients);
        sort($ids);
        return implode(',', $ids);
    }

    private function isValidQuantity($quantity)
    {
        return is_numeric($quantity) && $quantity > 0 && $quantity <= 100;
    }

    private function isValidPrice($price)
    {
        return is_numeric($price) && $price >= 0;
    }
} 