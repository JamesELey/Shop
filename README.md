# 🍕 Pizza Shop Ordering System

A full-featured pizza ordering system built with Laravel and Vue.js, featuring real-time pizza customization, shopping cart functionality, and order management.

## ✨ Features

### 🛒 Customer Features
- **Pizza Catalog**: Browse through a variety of pizzas with detailed descriptions and pricing
- **Real-time Customization**: Add or remove ingredients with live price updates
- **Shopping Cart**: Add multiple pizzas with different customizations
- **Order Checkout**: Secure order placement with detailed order summaries
- **Order History**: View past orders with full customization details
- **User Authentication**: Secure login and registration system

### 🎯 Core Functionality
- **Dynamic Pricing**: Automatic price calculation based on base pizza price and selected ingredients
- **Ingredient Management**: Comprehensive ingredient system with individual pricing
- **Order Tracking**: Order status management (pending, processing, completed, cancelled)
- **Responsive Design**: Mobile-friendly interface with modern UI/UX

## 🚀 Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Vue.js 3 with Inertia.js
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Styling**: Tailwind CSS
- **Build Tool**: Vite
- **Development Server**: PHP built-in server

## 📦 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & npm
- MySQL

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
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

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seed data**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Start development servers**
   ```bash
   # Terminal 1: Laravel backend
   php artisan serve

   # Terminal 2: Vite development server
   npm run dev
   ```

8. **Access the application**
   Open your browser and navigate to `http://localhost:8000`

## 🗄️ Database Schema

### Main Tables

- **users**: User authentication and profile data
- **pizzas**: Pizza catalog with base prices and descriptions
- **ingredients**: Available ingredients with individual pricing
- **orders**: Customer orders with status tracking
- **ingredient_pizza**: Pivot table for pizza-ingredient relationships

### Key Relationships

- User hasMany Orders
- Order belongsTo User
- Pizza belongsToMany Ingredients
- Ingredient belongsToMany Pizzas

## 🛣️ API Endpoints

### Web Routes (Protected by Auth)
```
GET    /pizzas           - View pizza catalog
GET    /cart             - View shopping cart
POST   /orders           - Create new order
GET    /orders           - View order history
```

### API Routes
```
GET    /api/pizzas       - Get all pizzas (JSON)
```

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── PizzaController.php      # Pizza catalog management
│   │   ├── OrderController.php      # Order processing
│   │   └── CartController.php       # Shopping cart views
│   └── Models/
│       ├── User.php                 # User model
│       ├── Pizza.php                # Pizza model with ingredients
│       ├── Ingredient.php           # Ingredient model
│       └── Order.php                # Order model
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   └── PizzaCard.vue        # Pizza display component
│   │   ├── Pages/
│   │   │   ├── Pizzas/Index.vue     # Pizza catalog page
│   │   │   ├── Cart/Index.vue       # Shopping cart page
│   │   │   └── Orders/History.vue   # Order history page
│   │   └── Store/
│   │       └── cartStore.js         # Shopping cart state management
│   └── views/                       # Blade templates
├── database/
│   ├── migrations/                  # Database migrations
│   └── seeders/                     # Database seeders
└── routes/
    ├── web.php                      # Web routes
    └── api.php                      # API routes
```

## 🎮 Usage

### For Customers

1. **Register/Login**: Create an account or login to existing account
2. **Browse Pizzas**: View available pizzas on the catalog page
3. **Customize Pizza**: Click "Customize" to modify ingredients
4. **Add to Cart**: Add customized pizzas to your shopping cart
5. **Checkout**: Review your order and complete the purchase
6. **Track Orders**: View your order history and status

### For Developers

1. **Add New Pizzas**: Use the `PizzaSeeder` or create via database
2. **Manage Ingredients**: Add ingredients via `IngredientSeeder`
3. **Customize UI**: Modify Vue components in `resources/js/`
4. **Extend Features**: Add new controllers and routes as needed

## 🔧 Key Features Implementation

### Pizza Customization
- Real-time price calculation using Vue.js reactivity
- Ingredient selection with visual feedback
- Modal-based customization interface

### Shopping Cart
- Client-side state management with Vue reactivity
- Support for multiple customized pizzas
- Unique cart line identification for different customizations

### Order Management
- JSON storage of order details including customizations
- Comprehensive order history with ingredient details
- Order status tracking system

## 🎨 Customization

### Adding New Pizza Types
1. Add pizza data via `PizzaSeeder` or database
2. Associate with ingredients in `IngredientSeeder`
3. Update placeholder images as needed

### Modifying Ingredients
1. Update `IngredientSeeder` with new ingredients
2. Run `php artisan db:seed --class=IngredientSeeder`
3. Ingredients automatically appear in customization modal

### UI Customization
- Modify Tailwind classes in Vue components
- Update component styles in `resources/js/Components/`
- Customize layout in `resources/js/Layouts/`

## 🧪 Testing

Run the application tests:
```bash
php artisan test
```

## 🚀 Deployment

For production deployment:

1. **Environment setup**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Build assets**
   ```bash
   npm run build
   ```

3. **Set appropriate permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

If you encounter any issues or have questions:

1. Check the Laravel documentation: https://laravel.com/docs
2. Check the Vue.js documentation: https://vuejs.org/
3. Review the Inertia.js documentation: https://inertiajs.com/
4. Open an issue in this repository

## 🎯 Future Enhancements

- [ ] Payment gateway integration
- [ ] Real-time order tracking
- [ ] Admin dashboard for order management
- [ ] Email notifications
- [ ] Pizza reviews and ratings
- [ ] Delivery tracking
- [ ] Loyalty program
- [ ] Multi-language support

---

**Built with ❤️ using Laravel and Vue.js**
