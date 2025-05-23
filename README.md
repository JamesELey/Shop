# 🍕 Pizza Shop Ordering System

A full-featured pizza ordering system built with Laravel and Vue.js, featuring real-time pizza customization, shopping cart functionality, and order management.

## ✨ Features

### 🛒 Customer Features
- **Pizza Catalog**: Browse through a variety of pizzas with detailed descriptions and high-quality images
- **Real-time Customization**: Add or remove ingredients with live price updates
- **Shopping Cart**: Add multiple pizzas with different customizations
- **Order Checkout**: Secure order placement with detailed order summaries
- **Order History**: View past orders with full customization details
- **User Authentication**: Secure login and registration system

### 🍕 Pizza Varieties
- **Margherita**: Classic Italian pizza with fresh mozzarella, tomato sauce, and fresh basil leaves
- **Pepperoni**: America's favorite with spicy pepperoni slices and melted mozzarella cheese
- **Vegetarian**: Garden fresh vegetables including bell peppers, mushrooms, onions, and black olives
- **Hawaiian**: Tropical delight with ham, pineapple chunks, and mozzarella cheese
- **Meat Lovers**: Ultimate meat feast with pepperoni, sausage, ham, and bacon
- **BBQ Chicken**: Grilled chicken with BBQ sauce, red onions, and cilantro

### 🎯 Core Functionality
- **Dynamic Pricing**: Automatic price calculation based on selected ingredients
- **Ingredient Management**: Comprehensive ingredient system with individual pricing
- **Visual Interface**: Modern, responsive design with beautiful pizza images
- **Cart Persistence**: Shopping cart maintains state across sessions
- **Order Tracking**: Complete order history with status tracking

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
   DB_DATABASE=pizza_shop
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
   - Start ordering pizzas! 🍕

## 🏗️ Technical Stack

### Backend
- **Framework**: Laravel 10
- **Database**: MySQL with Eloquent ORM
- **Authentication**: Laravel Breeze
- **API**: RESTful API endpoints

### Frontend
- **Framework**: Vue.js 3 with Composition API
- **UI**: Tailwind CSS for styling
- **State Management**: Vue reactivity system
- **Build Tool**: Vite

### Database Schema
- **Users**: Authentication and user management
- **Pizzas**: Pizza catalog with base prices and descriptions
- **Ingredients**: Available toppings with individual pricing
- **Orders**: Customer order history and details
- **Pivot Tables**: Many-to-many relationships for pizza-ingredient associations

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── PizzaController.php      # Pizza catalog management
│   │   ├── OrderController.php      # Order processing
│   │   └── CartController.php       # Shopping cart
│   └── Models/
│       ├── Pizza.php               # Pizza model with ingredients
│       ├── Order.php               # Order model
│       └── Ingredient.php          # Ingredient model
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Pizzas/Index.vue    # Pizza catalog page
│   │   │   ├── Cart/Index.vue      # Shopping cart page
│   │   │   └── Orders/History.vue  # Order history page
│   │   ├── Components/
│   │   │   └── PizzaCard.vue       # Individual pizza display
│   │   └── Store/
│   │       └── cartStore.js        # Cart state management
│   └── views/
│       └── app.blade.php           # Main application layout
├── database/
│   ├── migrations/                 # Database schema
│   └── seeders/                    # Sample data
└── public/
    └── images/
        └── pizzas/                 # Pizza image assets
```

## 🔧 API Endpoints

### Pizza Management
- `GET /api/pizzas` - List all pizzas with ingredients
- `GET /api/pizzas/{id}` - Get specific pizza details

### Order Management
- `POST /orders` - Create new order
- `GET /orders` - Get user's order history

### Cart Management
- `GET /cart` - View shopping cart
- Client-side cart management with Vue.js

## 🎨 Features in Detail

### Pizza Customization
- Each pizza has default ingredients included in base price
- Additional ingredients can be added/removed
- Real-time price calculation
- Visual ingredient selection interface

### Shopping Cart
- Add multiple pizzas with different customizations
- Modify quantities
- Remove items
- Persistent cart state
- Clear cart functionality

### Order System
- Secure order placement
- Order confirmation
- Order history with full details
- Order status tracking

### Image System
- High-quality pizza images from Unsplash
- Responsive image loading
- Fallback image handling
- Optimized image sizes (400x300)

## 🚀 Future Enhancements

- [ ] Real-time order tracking
- [ ] Payment integration (Stripe/PayPal)
- [ ] Pizza size variations
- [ ] Delivery address management
- [ ] Admin dashboard for order management
- [ ] Email notifications
- [ ] Mobile app development
- [ ] Loyalty program
- [ ] Review and rating system

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
