<template>
    <Head title="Our Pizzas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Our Pizzas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="pizzas.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <PizzaCard 
                        v-for="pizza in pizzas" 
                        :key="pizza.id" 
                        :pizza="pizza"
                        :allIngredients="allIngredients"
                        @add-to-cart="handleAddToCart"
                    />
                </div>
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p>No pizzas available at the moment.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PizzaCard from '@/Components/PizzaCard.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  pizzas: Array,
  allIngredients: Array,
});

function handleAddToCart(pizza) {
    console.log('Pizza added to cart in Index.vue:', pizza.name);
    alert(`${pizza.name} added to cart!`);
}
</script> 