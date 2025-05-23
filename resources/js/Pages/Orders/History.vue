<template>
    <Head title="My Order History" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">My Order History</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="orders.length > 0">
                            <ul class="space-y-6">
                                <li v-for="order in orders" :key="order.id" class="p-4 border dark:border-gray-700 rounded-lg shadow">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-semibold">Order #{{ order.id }}</h3>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="{
                                                'bg-yellow-200 text-yellow-800': order.status === 'pending',
                                                'bg-blue-200 text-blue-800': order.status === 'processing',
                                                'bg-green-200 text-green-800': order.status === 'completed',
                                                'bg-red-200 text-red-800': order.status === 'cancelled'
                                              }">
                                            {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Date: {{ order.created_at }}</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">Total: ${{ order.total_amount }}</p>
                                    
                                    <div class="mt-4">
                                        <h4 class="font-semibold text-md mb-3">Items:</h4>
                                        <div class="space-y-4">
                                            <div v-for="(item, index) in order.pizzas" :key="index" class="flex items-start space-x-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <!-- Pizza Image -->
                                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center flex-shrink-0 relative overflow-hidden">
                                                    <img 
                                                        v-if="item.pizza.image_url && !getImageError(order.id, index)" 
                                                        :src="item.pizza.image_url" 
                                                        :alt="item.pizza.name" 
                                                        @load="setImageLoaded(order.id, index)"
                                                        @error="setImageError(order.id, index)"
                                                        class="w-full h-full object-cover"
                                                        :class="{ 'opacity-0': !getImageLoaded(order.id, index) }"
                                                    >
                                                    <div v-if="!item.pizza.image_url || getImageError(order.id, index)" class="flex flex-col items-center justify-center text-center">
                                                        <div class="text-2xl">🍕</div>
                                                    </div>
                                                    <div v-if="item.pizza.image_url && !getImageLoaded(order.id, index) && !getImageError(order.id, index)" class="absolute inset-0 flex items-center justify-center">
                                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Pizza Details -->
                                                <div class="flex-grow">
                                                    <div class="flex items-center justify-between">
                                                        <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ item.pizza.name }}</h5>
                                                        <span class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ item.quantity }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">${{ item.pizza.price.toFixed(2) }} each</p>
                                                    
                                                    <div v-if="item.selected_ingredients && item.selected_ingredients.length > 0" class="mt-2">
                                                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Customizations:</p>
                                                        <div class="flex flex-wrap gap-1">
                                                            <span v-for="customIng in item.selected_ingredients" 
                                                                  :key="customIng.id"
                                                                  class="inline-block px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                                                {{ customIng.name }} (+${{ customIng.price.toFixed(2) }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Item Total -->
                                                <div class="text-right flex-shrink-0">
                                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                                        ${{ (item.pizza.price * item.quantity).toFixed(2) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-center py-12">
                            <div class="text-6xl mb-4">🍕</div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No orders yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">You haven't placed any orders yet. Time to order some delicious pizza!</p>
                            <Link :href="route('pizzas.index')" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md">
                                Browse Pizzas
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    orders: Array,
});

// Image loading states - using a Map to track per order/item
const imageLoadStates = ref(new Map());
const imageErrorStates = ref(new Map());

function getImageKey(orderId, itemIndex) {
    return `${orderId}-${itemIndex}`;
}

function getImageLoaded(orderId, itemIndex) {
    return imageLoadStates.value.get(getImageKey(orderId, itemIndex)) || false;
}

function getImageError(orderId, itemIndex) {
    return imageErrorStates.value.get(getImageKey(orderId, itemIndex)) || false;
}

function setImageLoaded(orderId, itemIndex) {
    imageLoadStates.value.set(getImageKey(orderId, itemIndex), true);
}

function setImageError(orderId, itemIndex) {
    imageErrorStates.value.set(getImageKey(orderId, itemIndex), true);
    imageLoadStates.value.set(getImageKey(orderId, itemIndex), false);
}
</script> 