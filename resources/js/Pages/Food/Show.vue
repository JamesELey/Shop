<template>
    <AuthenticatedLayout>
        <Head :title="foodItem.name" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Navigation -->
                        <div class="mb-6">
                            <Link 
                                :href="route('menu.category', category.slug)"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800"
                            >
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Back to {{ category.name }}
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Food Item Image and Info -->
                            <div>
                                <div class="relative h-64 lg:h-80 mb-6">
                                    <img 
                                        :src="foodItem.image_url" 
                                        :alt="foodItem.name"
                                        class="w-full h-full object-cover rounded-lg"
                                        @error="handleImageError"
                                    />
                                </div>
                                
                                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ foodItem.name }}</h1>
                                <p class="text-gray-600 mb-4">{{ foodItem.description }}</p>
                                
                                <!-- Dietary Info -->
                                <div v-if="foodItem.dietary_info && foodItem.dietary_info.length > 0" class="flex flex-wrap gap-2 mb-6">
                                    <span 
                                        v-for="diet in foodItem.dietary_info" 
                                        :key="diet"
                                        class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium"
                                    >
                                        {{ diet }}
                                    </span>
                                </div>
                                
                                <!-- Base Price -->
                                <div class="text-2xl font-bold text-green-600 mb-6">
                                    Base Price: ${{ foodItem.price }}
                                </div>
                            </div>

                            <!-- Customization Options -->
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Customize Your {{ foodItem.name }}</h2>
                                
                                <!-- Ingredient Groups -->
                                <div class="space-y-6">
                                    <div 
                                        v-for="(ingredients, type) in availableIngredients" 
                                        :key="type"
                                        class="border border-gray-200 rounded-lg p-4"
                                    >
                                        <h3 class="text-lg font-semibold text-gray-800 mb-3 capitalize">
                                            {{ type }}{{ type === 'cheese' ? '' : 's' }}
                                        </h3>
                                        
                                        <div class="space-y-2">
                                            <label 
                                                v-for="ingredient in ingredients" 
                                                :key="ingredient.id"
                                                class="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer"
                                            >
                                                <div class="flex items-center">
                                                    <input 
                                                        type="checkbox" 
                                                        :value="ingredient.id"
                                                        v-model="selectedIngredients"
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                    />
                                                    <span class="ml-3 text-gray-700">{{ ingredient.name }}</span>
                                                </div>
                                                <span class="text-green-600 font-medium">+${{ ingredient.price }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Price and Add to Cart -->
                                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-lg font-semibold text-gray-800">Total Price:</span>
                                        <span class="text-2xl font-bold text-green-600">${{ totalPrice.toFixed(2) }}</span>
                                    </div>
                                    
                                    <button 
                                        @click="addToCart"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-150 ease-in-out"
                                    >
                                        Add to Cart
                                    </button>
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
import { ref, computed } from 'vue';
import { cart } from '@/Store/cartStore';

const props = defineProps({
    category: Object,
    foodItem: Object,
    availableIngredients: Object
});

const selectedIngredients = ref([]);

// Initialize with default ingredients
if (props.foodItem.default_ingredients) {
    selectedIngredients.value = props.foodItem.default_ingredients.map(ingredient => ingredient.id);
}

const totalPrice = computed(() => {
    let price = parseFloat(props.foodItem.price);
    
    // Add prices for selected ingredients
    Object.values(props.availableIngredients).flat().forEach(ingredient => {
        if (selectedIngredients.value.includes(ingredient.id)) {
            price += parseFloat(ingredient.price);
        }
    });
    
    return price;
});

const addToCart = () => {
    // Get selected ingredient details
    const selectedIngredientDetails = [];
    Object.values(props.availableIngredients).flat().forEach(ingredient => {
        if (selectedIngredients.value.includes(ingredient.id)) {
            selectedIngredientDetails.push({
                id: ingredient.id,
                name: ingredient.name,
                price: ingredient.price
            });
        }
    });

    // Create cart item in the format expected by the cart store
    const cartItem = {
        type: 'food_item',
        food_item_id: props.foodItem.id,
        name: props.foodItem.name,
        image_url: props.foodItem.image_url,
        description: props.foodItem.description,
        base_price: props.foodItem.price,
        ingredients: selectedIngredientDetails,
        total_price: totalPrice.value,
        category: props.category.name
    };

    console.log('Adding item to cart:', cartItem);
    console.log('Cart before adding:', JSON.parse(JSON.stringify(cart.items)));

    // Add to cart using the cart store
    cart.addItem(cartItem);

    console.log('Cart after adding:', JSON.parse(JSON.stringify(cart.items)));
    console.log('Cart count:', cart.itemCount);

    // Show success message (you can implement a toast notification here)
    alert(`${props.foodItem.name} added to cart! Cart now has ${cart.itemCount} items.`);
};

const handleImageError = (event) => {
    event.target.src = 'https://via.placeholder.com/400x300/f3f4f6/9ca3af?text=No+Image';
};
</script> 