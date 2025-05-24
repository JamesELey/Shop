<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FoodItem;
use App\Models\Category;

class FoodItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodItem::query()->delete();

        $categories = Category::all()->keyBy('slug');

        // Pizza items (converting from existing pizza data)
        if ($categories->has('pizza')) {
            $pizzaCategory = $categories->get('pizza');
            
            $pizzas = [
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Margherita',
                    'description' => 'Classic Italian pizza with fresh mozzarella, tomato sauce, and fresh basil leaves',
                    'price' => 10.99,
                    'image_url' => 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Pepperoni',
                    'description' => 'America\'s favorite with spicy pepperoni slices and melted mozzarella cheese',
                    'price' => 12.99,
                    'image_url' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Vegetarian',
                    'description' => 'Garden fresh vegetables including bell peppers, mushrooms, onions, and black olives',
                    'price' => 11.99,
                    'image_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Hawaiian',
                    'description' => 'Tropical delight with ham, pineapple chunks, and mozzarella cheese',
                    'price' => 13.49,
                    'image_url' => 'https://images.unsplash.com/photo-1576458088443-04a19d13da0c?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Meat Lovers',
                    'description' => 'Ultimate meat feast with pepperoni, sausage, ham, and bacon',
                    'price' => 15.99,
                    'image_url' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'BBQ Chicken',
                    'description' => 'Grilled chicken with BBQ sauce, red onions, and cilantro',
                    'price' => 14.49,
                    'image_url' => 'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => []
                ]
            ];

            foreach ($pizzas as $pizza) {
                FoodItem::create($pizza);
            }
        }

        // Burger items
        if ($categories->has('burgers')) {
            $burgerCategory = $categories->get('burgers');
            
            $burgers = [
                [
                    'category_id' => $burgerCategory->id,
                    'name' => 'Classic Cheeseburger',
                    'description' => 'Juicy beef patty with cheddar cheese, lettuce, tomato, and special sauce',
                    'price' => 12.99,
                    'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $burgerCategory->id,
                    'name' => 'Bacon BBQ Burger',
                    'description' => 'Beef patty with crispy bacon, BBQ sauce, onion rings, and cheddar cheese',
                    'price' => 15.49,
                    'image_url' => 'https://images.unsplash.com/photo-1553979459-d2229ba7433a?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $burgerCategory->id,
                    'name' => 'Veggie Burger',
                    'description' => 'Plant-based patty with avocado, sprouts, tomato, and herb mayo',
                    'price' => 11.99,
                    'image_url' => 'https://images.unsplash.com/photo-1525059696034-4967a729002e?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => ['vegetarian', 'vegan']
                ],
                [
                    'category_id' => $burgerCategory->id,
                    'name' => 'Spicy Chicken Burger',
                    'description' => 'Crispy chicken breast with spicy mayo, jalapeños, and pepper jack cheese',
                    'price' => 13.99,
                    'image_url' => 'https://images.unsplash.com/photo-1606755962773-d324e614eaff?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => []
                ]
            ];

            foreach ($burgers as $burger) {
                FoodItem::create($burger);
            }
        }

        // Pasta items
        if ($categories->has('pasta')) {
            $pastaCategory = $categories->get('pasta');
            
            $pastas = [
                [
                    'category_id' => $pastaCategory->id,
                    'name' => 'Spaghetti Carbonara',
                    'description' => 'Classic Italian pasta with eggs, cheese, pancetta, and black pepper',
                    'price' => 14.99,
                    'image_url' => 'https://images.unsplash.com/photo-1551892374-ecf8754cf8b0?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $pastaCategory->id,
                    'name' => 'Fettuccine Alfredo',
                    'description' => 'Rich and creamy fettuccine pasta with parmesan cheese and butter sauce',
                    'price' => 13.49,
                    'image_url' => 'https://images.unsplash.com/photo-1645112411341-6c4fd023714a?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $pastaCategory->id,
                    'name' => 'Penne Arrabbiata',
                    'description' => 'Spicy penne pasta with tomatoes, garlic, red chilies, and fresh basil',
                    'price' => 12.99,
                    'image_url' => 'https://images.unsplash.com/photo-1585325701165-bc351c1fd325?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => ['vegetarian', 'vegan']
                ],
                [
                    'category_id' => $pastaCategory->id,
                    'name' => 'Chicken Pesto Pasta',
                    'description' => 'Grilled chicken with basil pesto, sun-dried tomatoes, and parmesan',
                    'price' => 16.49,
                    'image_url' => 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => []
                ]
            ];

            foreach ($pastas as $pasta) {
                FoodItem::create($pasta);
            }
        }

        // Salad items
        if ($categories->has('salads')) {
            $saladCategory = $categories->get('salads');
            
            $salads = [
                [
                    'category_id' => $saladCategory->id,
                    'name' => 'Caesar Salad',
                    'description' => 'Crisp romaine lettuce with parmesan cheese, croutons, and Caesar dressing',
                    'price' => 9.99,
                    'image_url' => 'https://images.unsplash.com/photo-1546793665-c74683f339c1?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $saladCategory->id,
                    'name' => 'Greek Salad',
                    'description' => 'Mixed greens with feta cheese, olives, tomatoes, and Mediterranean dressing',
                    'price' => 11.49,
                    'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $saladCategory->id,
                    'name' => 'Grilled Chicken Salad',
                    'description' => 'Mixed greens with grilled chicken, avocado, cherry tomatoes, and ranch dressing',
                    'price' => 13.99,
                    'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $saladCategory->id,
                    'name' => 'Quinoa Power Bowl',
                    'description' => 'Quinoa with roasted vegetables, chickpeas, avocado, and tahini dressing',
                    'price' => 12.99,
                    'image_url' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&h=300&fit=crop&auto=format',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => ['vegetarian', 'vegan', 'gluten-free']
                ]
            ];

            foreach ($salads as $salad) {
                FoodItem::create($salad);
            }
        }
    }
}
