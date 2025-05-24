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
                    'image_url' => 'https://picsum.photos/400/300?random=1',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Pepperoni',
                    'description' => 'America\'s favorite with spicy pepperoni slices and melted mozzarella cheese',
                    'price' => 12.99,
                    'image_url' => 'https://picsum.photos/400/300?random=2',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Vegetarian',
                    'description' => 'Garden fresh vegetables including bell peppers, mushrooms, onions, and black olives',
                    'price' => 11.99,
                    'image_url' => 'https://picsum.photos/400/300?random=3',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Hawaiian',
                    'description' => 'Tropical delight with ham, pineapple chunks, and mozzarella cheese',
                    'price' => 13.49,
                    'image_url' => 'https://picsum.photos/400/300?random=4',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'Meat Lovers',
                    'description' => 'Ultimate meat feast with pepperoni, sausage, ham, and bacon',
                    'price' => 15.99,
                    'image_url' => 'https://picsum.photos/400/300?random=5',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => 'BBQ Chicken',
                    'description' => 'Grilled chicken with BBQ sauce, red onions, and cilantro',
                    'price' => 14.49,
                    'image_url' => 'https://picsum.photos/400/300?random=6',
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
                    'image_url' => 'https://picsum.photos/400/300?random=20',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $burgerCategory->id,
                    'name' => 'Bacon BBQ Burger',
                    'description' => 'Beef patty with crispy bacon, BBQ sauce, onion rings, and cheddar cheese',
                    'price' => 15.49,
                    'image_url' => 'https://picsum.photos/400/300?random=21',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $burgerCategory->id,
                    'name' => 'Veggie Burger',
                    'description' => 'Plant-based patty with avocado, sprouts, tomato, and herb mayo',
                    'price' => 11.99,
                    'image_url' => 'https://picsum.photos/400/300?random=22',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => ['vegetarian', 'vegan']
                ],
                [
                    'category_id' => $burgerCategory->id,
                    'name' => 'Spicy Chicken Burger',
                    'description' => 'Crispy chicken breast with spicy mayo, jalapeños, and pepper jack cheese',
                    'price' => 13.99,
                    'image_url' => 'https://picsum.photos/400/300?random=23',
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
                    'image_url' => 'https://picsum.photos/400/300?random=30',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $pastaCategory->id,
                    'name' => 'Fettuccine Alfredo',
                    'description' => 'Rich and creamy fettuccine pasta with parmesan cheese and butter sauce',
                    'price' => 13.49,
                    'image_url' => 'https://picsum.photos/400/300?random=31',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $pastaCategory->id,
                    'name' => 'Penne Arrabbiata',
                    'description' => 'Spicy penne pasta with tomatoes, garlic, red chilies, and fresh basil',
                    'price' => 12.99,
                    'image_url' => 'https://picsum.photos/400/300?random=32',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => ['vegetarian', 'vegan']
                ],
                [
                    'category_id' => $pastaCategory->id,
                    'name' => 'Chicken Pesto Pasta',
                    'description' => 'Grilled chicken with basil pesto, sun-dried tomatoes, and parmesan',
                    'price' => 16.49,
                    'image_url' => 'https://picsum.photos/400/300?random=33',
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
                    'image_url' => 'https://picsum.photos/400/300?random=40',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $saladCategory->id,
                    'name' => 'Greek Salad',
                    'description' => 'Mixed greens with feta cheese, olives, tomatoes, and Mediterranean dressing',
                    'price' => 11.49,
                    'image_url' => 'https://picsum.photos/400/300?random=41',
                    'is_available' => true,
                    'is_featured' => true,
                    'dietary_info' => ['vegetarian']
                ],
                [
                    'category_id' => $saladCategory->id,
                    'name' => 'Grilled Chicken Salad',
                    'description' => 'Mixed greens with grilled chicken, avocado, cherry tomatoes, and ranch dressing',
                    'price' => 13.99,
                    'image_url' => 'https://picsum.photos/400/300?random=42',
                    'is_available' => true,
                    'is_featured' => false,
                    'dietary_info' => []
                ],
                [
                    'category_id' => $saladCategory->id,
                    'name' => 'Quinoa Power Bowl',
                    'description' => 'Quinoa with roasted vegetables, chickpeas, avocado, and tahini dressing',
                    'price' => 12.99,
                    'image_url' => 'https://picsum.photos/400/300?random=43',
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
