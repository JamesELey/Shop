<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
    ];

    /**
     * The ingredients that belong to the pizza.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_pizza');
    }
}
