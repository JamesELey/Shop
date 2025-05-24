<template>
    <AuthenticatedLayout>
        <Head title="Menu" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-3xl font-bold text-gray-900 mb-8">Our Menu</h1>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                            <div 
                                v-for="category in categories" 
                                :key="category.id"
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
                            >
                                <!-- Category Header -->
                                <div class="relative h-48">
                                    <img 
                                        :src="category.image_url" 
                                        :alt="category.name"
                                        class="w-full h-full object-cover"
                                        @error="handleImageError"
                                    />
                                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                        <h2 class="text-3xl font-bold text-white">{{ category.name }}</h2>
                                    </div>
                                </div>
                                
                                <!-- Category Description -->
                                <div class="p-6">
                                    <p class="text-gray-600 mb-4">{{ category.description }}</p>
                                    
                                    <!-- Featured Items -->
                                    <div v-if="category.available_food_items && category.available_food_items.length > 0" class="mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Featured Items</h3>
                                        <div class="space-y-2">
                                            <div 
                                                v-for="item in category.available_food_items" 
                                                :key="item.id"
                                                class="flex justify-between items-center p-2 bg-gray-50 rounded"
                                            >
                                                <div>
                                                    <span class="font-medium text-gray-800">{{ item.name }}</span>
                                                    <div v-if="item.dietary_info && item.dietary_info.length > 0" class="flex gap-1 mt-1">
                                                        <span 
                                                            v-for="diet in item.dietary_info" 
                                                            :key="diet"
                                                            class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full"
                                                        >
                                                            {{ diet }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="font-bold text-green-600">${{ item.price }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- View Category Button -->
                                    <Link 
                                        :href="route('menu.category', category.slug)"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                                    >
                                        View {{ category.name }}
                                        <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </Link>
                                </div>
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
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    categories: Array
});

const handleImageError = (event) => {
    event.target.src = 'https://via.placeholder.com/400x300/f3f4f6/9ca3af?text=No+Image';
};
</script> 