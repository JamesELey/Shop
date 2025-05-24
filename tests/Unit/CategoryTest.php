<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created()
    {
        $category = Category::create([
            'name' => 'Test Burgers',
            'slug' => 'test-burgers',
            'description' => 'Delicious test burgers for everyone'
        ]);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Burgers', $category->name);
        $this->assertEquals('test-burgers', $category->slug);
        $this->assertEquals('Delicious test burgers for everyone', $category->description);
    }

    public function test_category_has_food_items_relationship()
    {
        $category = Category::create([
            'name' => 'Test Pizza',
            'slug' => 'test-pizza',
            'description' => 'Test pizza category'
        ]);

        $foodItem = FoodItem::create([
            'category_id' => $category->id,
            'name' => 'Test Margherita',
            'description' => 'Test pizza with tomato and mozzarella',
            'price' => 12.99,
            'image_url' => 'test-image.jpg'
        ]);

        $this->assertCount(1, $category->foodItems);
        $this->assertInstanceOf(FoodItem::class, $category->foodItems->first());
        $this->assertEquals('Test Margherita', $category->foodItems->first()->name);
    }

    public function test_category_slug_is_unique()
    {
        Category::create([
            'name' => 'First Category',
            'slug' => 'unique-slug',
            'description' => 'First category'
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Category::create([
            'name' => 'Second Category',
            'slug' => 'unique-slug', // This should fail due to unique constraint
            'description' => 'Second category'
        ]);
    }

    public function test_category_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        // Try to create category without required fields
        Category::create([]);
    }

    public function test_category_can_be_found_by_slug()
    {
        $category = Category::create([
            'name' => 'Pizza Category',
            'slug' => 'pizza-category',
            'description' => 'All about pizzas'
        ]);

        $foundCategory = Category::where('slug', 'pizza-category')->first();
        
        $this->assertNotNull($foundCategory);
        $this->assertEquals($category->id, $foundCategory->id);
        $this->assertEquals('Pizza Category', $foundCategory->name);
    }
} 