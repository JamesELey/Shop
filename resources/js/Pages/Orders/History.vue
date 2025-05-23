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
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total: ${{ order.total_amount }}</p>
                                    
                                    <div class="mt-4">
                                        <h4 class="font-semibold text-md mb-1">Items:</h4>
                                        <ul class="list-disc list-inside pl-4 space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                            <li v-for="(item, index) in order.pizzas" :key="index">
                                                {{ item.quantity }} x {{ item.pizza.name }} (${{ item.pizza.price.toFixed(2) }} each)
                                                <div v-if="item.selected_ingredients && item.selected_ingredients.length > 0" class="pl-4">
                                                    <span class="text-xs italic">Customizations:</span>
                                                    <ul class="list-circle list-inside text-xs">
                                                        <li v-for="customIng in item.selected_ingredients" :key="customIng.id">
                                                            {{ customIng.name }} (+${{ customIng.price.toFixed(2) }})
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- Add a link to view order details if you have an order.show route -->
                                    <!-- <Link :href="route('orders.show', order.id)" class="mt-2 text-blue-500 hover:underline">View Details</Link> -->
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <p class="text-center text-gray-600 dark:text-gray-400">You have no past orders.</p>
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

const props = defineProps({
    orders: Array,
});
</script> 