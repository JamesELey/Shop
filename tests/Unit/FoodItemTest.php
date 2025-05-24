<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodItemTest extends TestCase
{
    use RefreshDatabase;

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test category for testing'
        ]);
    }

    public function test_food_item_can_be_created()
    {
        $foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Test Burger',
            'description' => 'A delicious test burger',
            'price' => 15.99,
            'image_url' => 'test-burger.jpg'
        ]);

        $this->assertInstanceOf(FoodItem::class, $foodItem);
        $this->assertEquals('Test Burger', $foodItem->name);
        $this->assertEquals('A delicious test burger', $foodItem->description);
        $this->assertEquals(15.99, $foodItem->price);
        $this->assertEquals('test-burger.jpg', $foodItem->image_url);
    }

    public function test_food_item_belongs_to_category()
    {
        $foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Test Food',
            'description' => 'Test food item',
            'price' => 10.00,
            'image_url' => 'test.jpg'
        ]);

        $this->assertInstanceOf(Category::class, $foodItem->category);
        $this->assertEquals($this->category->id, $foodItem->category->id);
        $this->assertEquals('Test Category', $foodItem->category->name);
    }

    public function test_food_item_has_ingredients_relationship()
    {
        $foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Test Pizza',
            'description' => 'Test pizza with ingredients',
            'price' => 12.99,
            'image_url' => 'test-pizza.jpg'
        ]);

        $ingredient1 = Ingredient::create([
            'name' => 'Cheese',
            'price' => 2.00,
            'category_id' => $this->category->id
        ]);

        $ingredient2 = Ingredient::create([
            'name' => 'Pepperoni',
            'price' => 3.00,
            'category_id' => $this->category->id
        ]);

        $foodItem->ingredients()->attach([
            $ingredient1->id,
            $ingredient2->id
        ]);

        $this->assertCount(2, $foodItem->ingredients);
        $this->assertTrue($foodItem->ingredients->contains($ingredient1));
        $this->assertTrue($foodItem->ingredients->contains($ingredient2));
    }

    public function test_food_item_price_is_numeric()
    {
        $foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Test Item',
            'description' => 'Test item with price',
            'price' => '25.50', // String price should be converted
            'image_url' => 'test.jpg'
        ]);

        $this->assertIsNumeric($foodItem->price);
        $this->assertEquals(25.50, (float) $foodItem->price);
    }

    public function test_food_item_has_default_ingredients()
    {
        $foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Test Pizza',
            'description' => 'Test pizza with default ingredients',
            'price' => 14.99,
            'image_url' => 'test-pizza.jpg'
        ]);

        $defaultIngredient = Ingredient::create([
            'name' => 'Mozzarella',
            'price' => 0.00,
            'category_id' => $this->category->id
        ]);

        $foodItem->ingredients()->attach($defaultIngredient->id, ['is_default' => true]);

        $defaultIngredients = $foodItem->ingredients()->wherePivot('is_default', true)->get();
        
        $this->assertCount(1, $defaultIngredients);
        $this->assertEquals('Mozzarella', $defaultIngredients->first()->name);
    }

    public function test_food_item_requires_category()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        FoodItem::create([
            'name' => 'Test Item',
            'description' => 'Test without category',
            'price' => 10.00,
            'image_url' => 'test.jpg'
            // Missing category_id
        ]);
    }

    public function test_food_item_requires_name_and_price()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        FoodItem::create([
            'category_id' => $this->category->id,
            'description' => 'Test without name and price',
            'image_url' => 'test.jpg'
            // Missing name and price
        ]);
    }

    public function test_food_item_can_calculate_total_price_with_ingredients()
    {
        $foodItem = FoodItem::create([
            'category_id' => $this->category->id,
            'name' => 'Test Burger',
            'description' => 'Test burger for price calculation',
            'price' => 10.00,
            'image_url' => 'test-burger.jpg'
        ]);

        $ingredient1 = Ingredient::create([
            'name' => 'Extra Cheese',
            'price' => 2.50,
            'category_id' => $this->category->id
        ]);

        $ingredient2 = Ingredient::create([
            'name' => 'Bacon',
            'price' => 3.00,
            'category_id' => $this->category->id
        ]);

        $selectedIngredients = collect([$ingredient1, $ingredient2]);
        $totalPrice = $foodItem->price + $selectedIngredients->sum('price');

        $this->assertEquals(15.50, $totalPrice);
    }
} 