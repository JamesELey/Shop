<template>
    <div class="bg-white dark:bg-gray-700 shadow-lg rounded-lg overflow-hidden flex flex-col">
        <img v-if="pizza.image_url" :src="pizza.image_url" :alt="pizza.name" class="w-full h-48 object-cover">
        <div v-else class="w-full h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
            <span class="text-gray-500 dark:text-gray-400">No Image</span>
        </div>
        <div class="p-4 flex flex-col flex-grow">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ pizza.name }}</h3>
            <p class="text-gray-600 dark:text-gray-300 mt-1 h-16 overflow-y-auto">{{ pizza.description }}</p>
            
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">Starts at: ${{ pizza.price_with_defaults.toFixed(2) }}</p>
                <p v-if="currentPrice.toFixed(2) !== pizza.price_with_defaults.toFixed(2)" class="text-sm font-semibold text-blue-600 dark:text-blue-400">Current Price: ${{ currentPrice.toFixed(2) }}</p>
            </div>

            <div class="mt-2" v-if="pizza.default_ingredients && pizza.default_ingredients.length > 0">
                <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300">Includes:</h4>
                <ul class="list-disc list-inside pl-4 text-xs text-gray-600 dark:text-gray-400">
                    <li v-for="ingredient in pizza.default_ingredients" :key="ingredient.id">{{ ingredient.name }} <span v-if="ingredient.price > 0">(+${{ ingredient.price.toFixed(2) }})</span></li>
                </ul>
            </div>
            
            <div class="mt-auto pt-4 flex items-center justify-between">
                <button 
                    class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                    @click="openCustomizeModal">
                    Customize
                </button>
                <button 
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-offset-gray-800"
                    @click="handleAddToCart">
                    Add to Cart (${{ currentPrice.toFixed(2) }})
                </button>
            </div>
        </div>

        <!-- Customization Modal -->
        <div v-if="showCustomizeModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md max-h-[90vh] flex flex-col">
                <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Customize {{ pizza.name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Base Price: ${{ pizza.base_price.toFixed(2) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Default Toppings Included. Select additional or unselect defaults.</p>
                
                <div class="overflow-y-auto flex-grow mb-4 pr-2">
                    <h4 class="text-md font-semibold mb-2 text-gray-700 dark:text-gray-300">Customize Ingredients:</h4>
                    <div v-for="ingredient in allIngredients" :key="ingredient.id" class="mb-2 flex items-center justify-between">
                        <div>
                            <input type="checkbox" :id="'ing-' + pizza.id + '-' + ingredient.id" :value="ingredient" v-model="selectedIngredientsInModal" @change="updateModalPrice" class="form-checkbox h-5 w-5 text-blue-600 rounded dark:bg-gray-700 dark:border-gray-600">
                            <label :for="'ing-' + pizza.id + '-' + ingredient.id" class="ml-2 text-gray-700 dark:text-gray-300">{{ ingredient.name }}</label>
                        </div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">+${{ ingredient.price.toFixed(2) }}</span>
                    </div>
                </div>
                
                <div class="mt-auto border-t dark:border-gray-700 pt-4">
                    <p class="text-lg font-bold text-gray-900 dark:text-white mb-4">Final Price: ${{ modalPrice.toFixed(2) }}</p>
                    <div class="flex justify-end space-x-2">
                        <button @click="closeCustomizeModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">Cancel</button>
                        <button @click="applyCustomizationAndAddToCart" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">Apply & Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { cart } from '@/Store/cartStore';

const props = defineProps({
    pizza: Object, // Expected to have: id, name, description, base_price, price_with_defaults, image_url, default_ingredients (array of {id, name, price})
    allIngredients: Array, // Expected array of {id, name, price}
});

const emit = defineEmits(['add-to-cart']);

const showCustomizeModal = ref(false);
// For the modal's independent selection state
const selectedIngredientsInModal = ref([]); 
// For the modal's price calculation
const modalPrice = ref(0);

// currentPrice on the card, reflects applied customizations or defaults
const currentPrice = ref(props.pizza.price_with_defaults);
// currentSelectedIngredients on the card, reflects applied customizations or defaults
const currentSelectedIngredients = ref(JSON.parse(JSON.stringify(props.pizza.default_ingredients || [])));

function calculatePriceFromSelection(basePrice, ingredientsSelection) {
    let price = parseFloat(basePrice);
    ingredientsSelection.forEach(ingredient => {
        price += parseFloat(ingredient.price);
    });
    return price;
}

function updateModalPrice() {
    modalPrice.value = calculatePriceFromSelection(props.pizza.base_price, selectedIngredientsInModal.value);
}

onMounted(() => {
    currentPrice.value = parseFloat(props.pizza.price_with_defaults);
    currentSelectedIngredients.value = JSON.parse(JSON.stringify(props.pizza.default_ingredients || []));
});

watch(() => props.pizza, (newPizza) => {
    currentPrice.value = parseFloat(newPizza.price_with_defaults);
    currentSelectedIngredients.value = JSON.parse(JSON.stringify(newPizza.default_ingredients || []));
}, { deep: true });

function openCustomizeModal() {
    // Initialize modal selections with current applied/default ingredients
    selectedIngredientsInModal.value = JSON.parse(JSON.stringify(currentSelectedIngredients.value));
    updateModalPrice(); // Calculate initial price for modal
    showCustomizeModal.value = true;
}

function closeCustomizeModal() {
    showCustomizeModal.value = false;
}

function handleAddToCart() {
    // This adds the pizza with its *currently displayed* price and ingredients (either default or last applied customization)
    const cartItem = {
        id: props.pizza.id, // Keep original pizza ID
        name: props.pizza.name,
        image_url: props.pizza.image_url,
        description: props.pizza.description, // Keep original description
        price: currentPrice.value, // Final price for this specific item in cart
        selected_ingredients: JSON.parse(JSON.stringify(currentSelectedIngredients.value)),
        original_base_price: props.pizza.base_price 
    };
    cart.addItem(cartItem);
    emit('add-to-cart', cartItem);
}

function applyCustomizationAndAddToCart() {
    // Update the card's displayed price and ingredients
    currentPrice.value = modalPrice.value;
    currentSelectedIngredients.value = JSON.parse(JSON.stringify(selectedIngredientsInModal.value));
    
    handleAddToCart(); // Use the updated currentPrice and currentSelectedIngredients
    closeCustomizeModal();
}

// Watch for changes in modal selections to update modal price
watch(selectedIngredientsInModal, updateModalPrice, { deep: true });

</script>

<style scoped>
.h-16 { height: 4rem; }
.max-h-[90vh] { max-height: 90vh; }
.pr-2 { padding-right: 0.5rem; }
</style> 