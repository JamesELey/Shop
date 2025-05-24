# 🍕🍔🍝🥗 Comprehensive Restaurant Ordering System

A full-featured restaurant ordering system built with Laravel and Vue.js, featuring multiple food categories, real-time customization, robust shopping cart functionality, and comprehensive order management.

## ✨ Enhanced Features

### 🛒 Customer Features
- **Multi-Category Menu**: Browse through pizzas, burgers, pasta, and salads with detailed descriptions and high-quality images
- **Real-time Customization**: Add or remove ingredients with live price updates across all food categories
- **Unified Shopping Cart**: Add multiple items from different categories with various customizations
- **Enhanced Checkout**: Secure order placement with detailed order summaries for mixed item types
- **Comprehensive Order History**: View past orders with full customization details for all food types
- **User Authentication**: Secure login and registration system with persistent cart state

### 🍕 Pizza Varieties
- **Margherita**: Classic Italian pizza with fresh mozzarella, tomato sauce, and fresh basil leaves
- **Pepperoni**: America's favorite with spicy pepperoni slices and melted mozzarella cheese
- **Vegetarian**: Garden fresh vegetables including bell peppers, mushrooms, onions, and black olives
- **Hawaiian**: Tropical delight with ham, pineapple chunks, and mozzarella cheese
- **Meat Lovers**: Ultimate meat feast with pepperoni, sausage, ham, and bacon
- **BBQ Chicken**: Grilled chicken with BBQ sauce, red onions, and cilantro

### 🍔 Burger Selection
- **Classic Cheeseburger**: Juicy beef patty with cheese, lettuce, tomato, and pickles
- **Bacon Burger**: Classic burger elevated with crispy bacon strips
- **Veggie Burger**: Plant-based patty with fresh vegetables and special sauce
- **BBQ Burger**: Smoky BBQ sauce with onion rings and cheddar cheese
- **Spicy Jalapeño Burger**: Heat lovers' choice with jalapeños and pepper jack cheese

### 🍝 Pasta Dishes
- **Spaghetti Carbonara**: Creamy egg-based sauce with pancetta and parmesan
- **Penne Arrabbiata**: Spicy tomato sauce with garlic and red pepper flakes
- **Fettuccine Alfredo**: Rich and creamy white sauce with butter and parmesan
- **Lasagna Bolognese**: Layered pasta with meat sauce and three cheeses
- **Vegetarian Pasta Primavera**: Fresh seasonal vegetables in light garlic oil

### 🥗 Fresh Salads
- **Caesar Salad**: Crisp romaine with parmesan, croutons, and classic dressing
- **Greek Salad**: Mediterranean mix with feta, olives, and olive oil dressing
- **Garden Salad**: Fresh mixed greens with seasonal vegetables
- **Quinoa Power Bowl**: Protein-packed quinoa with superfoods and tahini dressing
- **Caprese Salad**: Fresh mozzarella, tomatoes, and basil with balsamic glaze

### 🎯 Enhanced Core Functionality
- **Dynamic Multi-Category Pricing**: Automatic price calculation for all food types with ingredient customizations
- **Category-Specific Ingredients**: Specialized ingredient systems for each food category (pizza toppings, burger add-ons, pasta enhancements, salad extras)
- **Visual Differentiation**: Modern, responsive design with category-specific styling and emojis
- **Robust Cart Persistence**: Advanced shopping cart with localStorage backup and error recovery
- **Cross-Category Order Tracking**: Complete order history supporting mixed orders from all categories
- **Advanced Debugging**: Comprehensive cart debugging tools for troubleshooting

## 🚀 Quick Start

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL/MariaDB
- XAMPP (recommended for local development)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/JamesELey/Shop.git
   cd Shop
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Configuration**
   
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=restaurant_ordering
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run Migrations and Seed Database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Start Development Servers**
   
   Terminal 1 (Laravel):
   ```bash
   php artisan serve
   ```
   
   Terminal 2 (Vite for frontend assets):
   ```bash
   npm run dev
   ```

8. **Access the Application**
   - Frontend: http://localhost:8000
   - Register a new account or login
   - Start ordering from our comprehensive menu! 🍕🍔🍝🥗

## 🏗️ Enhanced Technical Stack

### Backend
- **Framework**: Laravel 10
- **Database**: MySQL with Eloquent ORM
- **Authentication**: Laravel Breeze
- **API**: RESTful API endpoints for all food categories

### Frontend
- **Framework**: Vue.js 3 with Composition API
- **UI**: Tailwind CSS for styling with category-specific themes
- **State Management**: Enhanced Vue reactivity with robust cart store
- **Build Tool**: Vite with hot module replacement

### Enhanced Database Schema
- **Users**: Authentication and user management
- **Categories**: Food category management (Pizza, Burger, Pasta, Salad)
- **FoodItems**: Comprehensive food catalog with category associations
- **Pizzas**: Legacy pizza system (maintained for compatibility)
- **Ingredients**: Category-specific ingredients with individual pricing
- **Orders**: Enhanced order history supporting mixed item types
- **Pivot Tables**: Many-to-many relationships for food-ingredient associations

## 📁 Enhanced Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── FoodController.php       # Multi-category food management
│   │   ├── PizzaController.php      # Legacy pizza catalog
│   │   ├── OrderController.php      # Enhanced order processing
│   │   └── CartController.php       # Shopping cart endpoints
│   └── Models/
│       ├── Category.php            # Food category model
│       ├── FoodItem.php            # Multi-category food model
│       ├── Pizza.php               # Legacy pizza model
│       ├── Order.php               # Enhanced order model
│       └── Ingredient.php          # Category-aware ingredient model
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Food/
│   │   │   │   ├── Index.vue       # Main menu page
│   │   │   │   ├── Category.vue    # Category-specific listings
│   │   │   │   └── Show.vue        # Individual food item customization
│   │   │   ├── Pizzas/Index.vue    # Legacy pizza catalog
│   │   │   ├── Cart/Index.vue      # Enhanced shopping cart
│   │   │   └── Orders/History.vue  # Enhanced order history
│   │   ├── Components/
│   │   │   ├── PizzaCard.vue       # Pizza display component
│   │   │   └── FoodCard.vue        # Multi-category food display
│   │   └── Store/
│   │       └── cartStore.js        # Robust cart state management
│   └── views/
│       └── app.blade.php           # Main application layout
├── database/
│   ├── migrations/
│   │   ├── *_create_categories_table.php
│   │   ├── *_create_food_items_table.php
│   │   ├── *_create_food_item_ingredient_table.php
│   │   └── *_add_category_to_ingredients_table.php
│   └── seeders/
│       ├── CategorySeeder.php      # Food category data
│       ├── FoodItemSeeder.php      # Multi-category food data
│       └── FoodItemIngredientSeeder.php # Category-specific ingredients
└── public/
    └── images/
        ├── pizzas/                 # Pizza image assets
        ├── burgers/                # Burger image assets
        ├── pasta/                  # Pasta image assets
        └── salads/                 # Salad image assets
```

## 🔧 Enhanced API Endpoints

### Food Management
- `GET /api/categories` - List all food categories
- `GET /api/food-categories/{slug}` - Get category-specific food items
- `GET /api/food/{id}` - Get specific food item with customization options

### Legacy Pizza Management
- `GET /api/pizzas` - List all pizzas with ingredients (legacy support)
- `GET /api/pizzas/{id}` - Get specific pizza details

### Enhanced Order Management
- `POST /orders` - Create new order (supports mixed item types)
- `GET /orders` - Get user's comprehensive order history

### Cart Management
- `GET /cart` - View shopping cart
- Enhanced client-side cart management with localStorage persistence

## 🎨 Enhanced Features in Detail

### Multi-Category Food System
- Unified browsing experience across all food categories
- Category-specific ingredient systems (toppings, add-ons, enhancements, extras)
- Consistent pricing calculation across all food types
- Visual category differentiation with emojis and color coding

### Enhanced Shopping Cart
- Support for mixed orders (pizzas + burgers + pasta + salads)
- Category-specific customization display
- Robust error handling and recovery
- localStorage persistence with cart store backup
- Advanced debugging tools for troubleshooting

### Enhanced Order System
- Mixed item type order processing
- Category-aware order validation
- Comprehensive order history display
- Enhanced order confirmation with category details

### Robust Cart Implementation
- Dual-layer persistence (cart store + localStorage)
- Automatic error recovery and fallback mechanisms
- Real-time debugging information
- Price formatting safeguards for all data types

## 🆕 Recent Major Updates

### Cart System Overhaul
- **Fixed cart initialization**: Prioritized localStorage loading for reliability
- **Enhanced error handling**: Comprehensive price formatting fixes
- **Dual persistence**: Cart store with localStorage backup
- **Advanced debugging**: Multiple debugging tools and status indicators

### Food Category Integration
- **Multi-category support**: Full integration of burgers, pasta, and salads
- **Unified cart experience**: Single cart for all food types
- **Category-specific ingredients**: Specialized customization options
- **Visual enhancements**: Category badges and emoji indicators

### Technical Improvements
- **Price data handling**: Robust number conversion and formatting
- **Import path fixes**: Resolved module resolution issues
- **Component optimization**: Enhanced Vue.js component performance
- **Database migrations**: Comprehensive schema updates

## 🚀 Future Enhancements

- [ ] Real-time order tracking across all categories
- [ ] Payment integration (Stripe/PayPal)
- [ ] Food size variations and portion options
- [ ] Delivery address management
- [ ] Admin dashboard for comprehensive menu management
- [ ] Email notifications with order details
- [ ] Mobile app development
- [ ] Nutritional information display
- [ ] Customer reviews and ratings
- [ ] Loyalty program integration

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- Pizza images provided by [Unsplash](https://unsplash.com/)
- Laravel framework for robust backend development
- Vue.js for reactive frontend experience
- Tailwind CSS for beautiful styling
- The open-source community for inspiration

---

**Happy Pizza Ordering!** 🍕✨
