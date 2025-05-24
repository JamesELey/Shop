<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image_url',
        'is_available',
        'is_featured',
        'dietary_info'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'dietary_info' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->withPivot('is_default')
                    ->withTimestamps();
    }

    public function defaultIngredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->wherePivot('is_default', true)
                    ->withPivot('is_default')
                    ->withTimestamps();
    }

    public function availableIngredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->where('ingredients.is_available', true)
                    ->withPivot('is_default')
                    ->withTimestamps();
    }

    // Scope for available items
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Scope for featured items
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Calculate price with default ingredients
    public function getPriceWithDefaultsAttribute()
    {
        $basePrice = $this->price;
        $defaultIngredientsPrice = $this->defaultIngredients->sum('price');
        return $basePrice + $defaultIngredientsPrice;
    }

    // Get base price (without any ingredients)
    public function getBasePriceAttribute()
    {
        return $this->attributes['price'];
    }
}
