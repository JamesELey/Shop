<template>
    <AuthenticatedLayout>
        <Head :title="category.name" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Category Header -->
                        <div class="mb-8">
                            <Link 
                                :href="route('menu.index')"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4"
                            >
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Back to Menu
                            </Link>
                            <h1 class="text-3xl font-bold text-gray-900">{{ category.name }}</h1>
                            <p class="text-gray-600 mt-2">{{ category.description }}</p>
                        </div>

                        <!-- Food Items Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div 
                                v-for="item in foodItems" 
                                :key="item.id"
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
                            >
                                <!-- Food Item Image -->
                                <div class="relative h-48">
                                    <img 
                                        :src="item.image_url" 
                                        :alt="item.name"
                                        class="w-full h-full object-cover"
                                        @error="handleImageError"
                                    />
                                    <div v-if="item.is_featured" class="absolute top-2 right-2">
                                        <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">
                                            Featured
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Food Item Details -->
                                <div class="p-4">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ item.name }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ item.description }}</p>
                                    
                                    <!-- Dietary Info -->
                                    <div v-if="item.dietary_info && item.dietary_info.length > 0" class="flex flex-wrap gap-1 mb-3">
                                        <span 
                                            v-for="diet in item.dietary_info" 
                                            :key="diet"
                                            class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full"
                                        >
                                            {{ diet }}
                                        </span>
                                    </div>
                                    
                                    <!-- Price and Action -->
                                    <div class="flex justify-between items-center">
                                        <span class="text-2xl font-bold text-green-600">${{ item.price }}</span>
                                        <Link 
                                            :href="route('menu.show', [category.slug, item.id])"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                                        >
                                            Customize
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="!foodItems || foodItems.length === 0" class="text-center py-12">
                            <div class="text-gray-400 text-6xl mb-4">🍽️</div>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No items available</h3>
                            <p class="text-gray-500">Check back later for delicious options!</p>
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
    category: Object,
    foodItems: Array
});

const handleImageError = (event) => {
    event.target.src = 'https://via.placeholder.com/400x300/f3f4f6/9ca3af?text=No+Image';
};
</script> 