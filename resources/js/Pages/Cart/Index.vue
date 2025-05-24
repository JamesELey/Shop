<template>
    <Head title="Your Shopping Cart" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Your Shopping Cart</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- Debug info -->
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">Cart Status: {{ cartStatus }}</p>
                            <p class="text-xs text-blue-700">Items: {{ cartItems.length }}</p>
                            <p class="text-xs text-blue-700">Total: ${{ cartTotal }}</p>
                            <p class="text-xs text-blue-700">Store Loaded: {{ cart.isLoaded }}</p>
                            <p class="text-xs text-blue-700">Store Items: {{ cart.items.length }}</p>
                            <div class="mt-2 space-x-2">
                                <button @click="refreshCart" class="px-3 py-1 bg-blue-200 text-blue-800 text-xs rounded">
                                    Refresh Cart
                                </button>
                                <button @click="reinitializeCart" class="px-3 py-1 bg-green-200 text-green-800 text-xs rounded">
                                    Reinitialize Store
                                </button>
                                <button @click="checkLocalStorage" class="px-3 py-1 bg-purple-200 text-purple-800 text-xs rounded">
                                    Check Storage
                                </button>
                            </div>
                        </div>
                        
                        <div v-if="cartItems.length > 0">
                            <h3 class="text-lg font-semibold mb-4">Cart Items ({{ cartItems.length }})</h3>
                            
                            <!-- Cart Items -->
                            <div v-for="item in cartItems" :key="item.cartLineId" class="flex flex-col sm:flex-row items-start sm:items-center justify-between py-4 border-b dark:border-gray-700">
                                <div class="flex items-center mb-2 sm:mb-0">
                                    <img v-if="item.image_url" :src="item.image_url" :alt="item.name" class="h-20 w-20 object-cover rounded mr-4">
                                    <div v-else class="h-20 w-20 bg-gray-200 dark:bg-gray-600 rounded mr-4 flex items-center justify-center text-2xl">
                                        {{ item.type === 'pizza' ? '🍕' : getCategoryEmoji(item.category) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ item.name }}</h3>
                                        <p v-if="item.category" class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ item.category }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Unit Price: ${{ parseFloat(item.unitPrice || 0).toFixed(2) }}</p>
                                        
                                        <!-- Pizza customizations -->
                                        <div v-if="item.type === 'pizza' && item.selected_ingredients && item.selected_ingredients.length > 0" class="mt-1">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Pizza Toppings:</p>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                <span v-for="ing in item.selected_ingredients" :key="ing.id" class="inline-block px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                                    {{ ing.name }} (+${{ parseFloat(ing.price || 0).toFixed(2) }})
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Food item customizations -->
                                        <div v-if="item.type === 'food_item' && item.ingredients && item.ingredients.length > 0" class="mt-1">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Customizations:</p>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                <span v-for="ing in item.ingredients" :key="ing.id" class="inline-block px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                                    {{ ing.name }} (+${{ parseFloat(ing.price || 0).toFixed(2) }})
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center self-end sm:self-center">
                                    <input type="number" min="1" :value="item.quantity" @change="updateQuantity(item.cartLineId, parseInt($event.target.value))" class="w-16 text-center border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                    <button @click="removeItem(item.cartLineId)" class="ml-4 text-red-500 hover:text-red-700 dark:hover:text-red-400">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Cart Summary -->
                            <div class="mt-6 text-right">
                                <p class="text-xl font-semibold">Total: ${{ cartTotal }}</p>
                                <button @click="proceedToCheckout" class="mt-4 px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-offset-gray-800">
                                    Proceed to Checkout
                                </button>
                                <button @click="clearCart" class="mt-4 ml-2 px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-md dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                        
                        <div v-else>
                            <p class="text-center text-gray-600 mb-4">Your cart is empty.</p>
                            <div class="text-center space-x-4">
                                <Link :href="route('menu.index')" class="text-blue-500 hover:text-blue-700 underline">
                                    Browse Menu
                                </Link>
                                <Link :href="route('pizzas.index')" class="text-blue-500 hover:text-blue-700 underline">
                                    View Pizzas
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { cart } from '@/Store/cartStore';

// Cart state
const cartItems = ref([]);
const cartStatus = ref('Loading...');

const cartTotal = computed(() => {
    return cart.totalPrice;
});

onMounted(async () => {
    console.log('Cart page mounted');
    console.log('Cart object:', cart);
    console.log('Cart isLoaded:', cart.isLoaded);
    console.log('Cart items before init:', cart.items);
    
    // Wait a moment for any pending initialization
    await new Promise(resolve => setTimeout(resolve, 100));
    
    cart.init();
    
    console.log('Cart isLoaded after init:', cart.isLoaded);
    console.log('Cart items after init:', cart.items);
    
    refreshCart();
    
    // Retry if cart is still not loaded
    if (!cart.isLoaded) {
        console.log('Cart not loaded, retrying in 500ms...');
        setTimeout(() => {
            cart.init();
            refreshCart();
        }, 500);
    }
});

function refreshCart() {
    // First try to load directly from localStorage (since this works)
    try {
        const stored = localStorage.getItem('cart_items');
        console.log('Direct localStorage check:', stored);
        if (stored) {
            cartItems.value = JSON.parse(stored);
            cartStatus.value = `Loaded from localStorage (${cartItems.value.length} items)`;
            return; // Exit early if localStorage worked
        }
    } catch (error) {
        console.error('Error loading directly from localStorage:', error);
    }

    // Fallback: try to use cart store
    try {
        console.log('Refreshing cart...');
        console.log('Cart store state:', {
            isLoaded: cart.isLoaded,
            itemCount: cart.itemCount,
            totalPrice: cart.totalPrice,
            items: cart.items
        });
        
        if (cart.items && cart.items.length > 0) {
            cartItems.value = cart.items;
            cartStatus.value = `Cart loaded from store (${cart.items.length} items) - Loaded: ${cart.isLoaded}`;
        } else {
            cartItems.value = [];
            cartStatus.value = 'No items in cart store or storage';
        }
    } catch (error) {
        console.error('Error refreshing cart:', error);
        cartStatus.value = 'Error loading cart: ' + error.message;
        cartItems.value = [];
        cartStatus.value = 'No items found';
    }
}

function updateQuantity(cartLineId, newQuantity) {
    cart.updateQuantity(cartLineId, newQuantity);
    refreshCart();
}

function removeItem(cartLineId) {
    cart.removeItem(cartLineId);
    refreshCart();
}

function clearCart() {
    cart.clearCart();
    refreshCart();
}

const checkoutForm = useForm({
    items: [],
    total_amount: '0.00'
});

function proceedToCheckout() {
    if (cartItems.value.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    checkoutForm.items = cartItems.value.map(item => {
        if (item.type === 'pizza') {
            return {
                pizza: {
                    id: item.pizzaId,
                    name: item.name,
                    price: item.unitPrice
                },
                quantity: item.quantity,
                selected_ingredients: item.selected_ingredients,
            };
        } else if (item.type === 'food_item') {
            return {
                food_item: {
                    id: item.food_item_id,
                    name: item.name,
                    price: item.unitPrice,
                    category: item.category
                },
                quantity: item.quantity,
                ingredients: item.ingredients,
            };
        }
    });
    checkoutForm.total_amount = cartTotal.value;

    checkoutForm.post(route('orders.store'), {
        preserveScroll: true,
        onSuccess: () => {
            clearCart();
        },
        onError: (errors) => {
            console.error('Order submission error:', errors);
        },
        onFinish: () => {
            checkoutForm.reset(); 
        }
    });
}

function getCategoryEmoji(category) {
    if (!category) return '🍽️';
    const cat = category.toLowerCase();
    if (cat.includes('burger')) return '🍔';
    if (cat.includes('pasta')) return '🍝';
    if (cat.includes('salad')) return '🥗';
    return '🍽️';
}

function reinitializeCart() {
    cart.init();
    refreshCart();
}

function checkLocalStorage() {
    console.log('Manual localStorage check triggered');
    refreshCart(); // Just call refreshCart since it now prioritizes localStorage
}
</script> 