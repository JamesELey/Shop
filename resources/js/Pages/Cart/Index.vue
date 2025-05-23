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
                        <div v-if="cart.items.length > 0">
                            <!-- Cart Items -->
                            <div v-for="item in cart.items" :key="item.cartLineId" class="flex flex-col sm:flex-row items-start sm:items-center justify-between py-4 border-b dark:border-gray-700">
                                <div class="flex items-center mb-2 sm:mb-0">
                                    <img v-if="item.image_url" :src="item.image_url" :alt="item.name" class="h-20 w-20 object-cover rounded mr-4">
                                    <div v-else class="h-20 w-20 bg-gray-200 dark:bg-gray-600 rounded mr-4 flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ item.name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Unit Price: ${{ item.unitPrice.toFixed(2) }}</p>
                                        <div v-if="item.selected_ingredients && item.selected_ingredients.length > 0" class="mt-1">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Customizations:</p>
                                            <ul class="list-disc list-inside pl-3 text-xs text-gray-500 dark:text-gray-400">
                                                <li v-for="ing in item.selected_ingredients" :key="ing.id">{{ ing.name }} (+${{ ing.price.toFixed(2) }})</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center self-end sm:self-center">
                                    <input type="number" min="1" :value="item.quantity" @change="cart.updateQuantity(item.cartLineId, parseInt($event.target.value))" class="w-16 text-center border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                    <button @click="cart.removeItem(item.cartLineId)" class="ml-4 text-red-500 hover:text-red-700 dark:hover:text-red-400">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Cart Summary -->
                            <div class="mt-6 text-right">
                                <p class="text-xl font-semibold">Total: ${{ cart.totalPrice }}</p>
                                <button @click="proceedToCheckout" class="mt-4 px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-offset-gray-800">
                                    Proceed to Checkout
                                </button>
                                <button @click="cart.clearCart()" class="mt-4 ml-2 px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-md dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-center text-gray-600 dark:text-gray-400">Your cart is empty.</p>
                            <div class="text-center mt-4">
                                <Link :href="route('pizzas.index')" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-semibold">
                                    Continue Shopping
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
import { cart } from '@/Store/cartStore';

const checkoutForm = useForm({
    items: [], // This will be an array of objects { pizza_id, name, quantity, unit_price, selected_ingredients }
    total_amount: '0.00'
});

function proceedToCheckout() {
    if (cart.items.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    // Transform cart items for the backend
    checkoutForm.items = cart.items.map(item => ({
        pizza: { // Corresponds to items.*.pizza in validation
            id: item.pizzaId, // Original pizza ID
            name: item.name, // Name of the pizza
            price: item.unitPrice // The final price for this customized pizza instance
        },
        quantity: item.quantity,
        selected_ingredients: item.selected_ingredients, // Pass along the selected ingredients
        // The backend will take this 'items' array and store it in the 'pizzas' JSON column of the order.
    }));
    checkoutForm.total_amount = cart.totalPrice;

    checkoutForm.post(route('orders.store'), {
        preserveScroll: true,
        onSuccess: () => {
            cart.clearCart();
        },
        onError: (errors) => {
            console.error('Order submission error details:', errors);
        },
        onFinish: () => {
            checkoutForm.reset(); 
        }
    });
}
</script> 