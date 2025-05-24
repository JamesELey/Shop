<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'price', 
        'category_id', 
        'type', 
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function foodItems()
    {
        return $this->belongsToMany(FoodItem::class)
                    ->withPivot('is_default')
                    ->withTimestamps();
    }

    /**
     * The pizzas that belong to the ingredient (for backward compatibility).
     */
    public function pizzas()
    {
        return $this->belongsToMany(Pizza::class, 'ingredient_pizza');
    }

    // Scope for available ingredients
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Scope by type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope by category
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
