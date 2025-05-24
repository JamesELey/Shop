<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function foodItems()
    {
        return $this->hasMany(FoodItem::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function availableFoodItems()
    {
        return $this->hasMany(FoodItem::class)->where('is_available', true);
    }

    public function availableIngredients()
    {
        return $this->hasMany(Ingredient::class)->where('is_available', true);
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered categories
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
