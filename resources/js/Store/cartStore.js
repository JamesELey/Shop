import { reactive, watch, nextTick } from 'vue';

// Helper to create a signature for selected ingredients (sorted by ID)
function getIngredientsSignature(ingredients) {
    if (!ingredients || ingredients.length === 0) return 'no_ingredients';
    return ingredients.map(ing => ing.id).sort().join(',');
}

// Create the cart store
export const cart = reactive({
    items: [],
    isLoaded: false,
    
    get itemCount() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    },

    get totalPrice() {
        return this.items.reduce((total, item) => {
            return total + (item.unitPrice * item.quantity);
        }, 0).toFixed(2);
    },

    // Initialize cart from localStorage
    init() {
        if (typeof window !== 'undefined' && !this.isLoaded) {
            try {
                const stored = localStorage.getItem('cart_items');
                if (stored) {
                    const parsed = JSON.parse(stored);
                    this.items.splice(0, this.items.length, ...parsed);
                    console.log('Cart loaded from storage:', parsed.length, 'items');
                }
            } catch (error) {
                console.error('Error loading cart from storage:', error);
            }
            this.isLoaded = true;
            this.setupWatcher();
        }
    },

    // Set up watcher for localStorage persistence
    setupWatcher() {
        if (typeof window !== 'undefined') {
            watch(
                () => this.items,
                (newItems) => {
                    try {
                        localStorage.setItem('cart_items', JSON.stringify(newItems));
                        console.log('Cart saved to storage:', newItems.length, 'items');
                    } catch (error) {
                        console.error('Error saving cart to storage:', error);
                    }
                },
                { deep: true }
            );
        }
    },

    addItem(itemData) {
        // Ensure cart is initialized
        if (!this.isLoaded) {
            this.init();
        }

        // Handle both pizza format and new food item format
        if (itemData.type === 'food_item') {
            this.addFoodItem(itemData);
        } else {
            this.addPizzaItem(itemData);
        }
        console.log('Cart items after add:', JSON.parse(JSON.stringify(this.items)));
    },

    addPizzaItem(pizzaDataFromCard) {
        const ingredientsSignature = getIngredientsSignature(pizzaDataFromCard.selected_ingredients);
        
        const existingItem = this.items.find(item =>
            item.type === 'pizza' &&
            item.pizzaId === pizzaDataFromCard.id &&
            getIngredientsSignature(item.selected_ingredients) === ingredientsSignature
        );

        if (existingItem) {
            existingItem.quantity++;
        } else {
            // Ensure all ingredient prices are numbers
            const processedIngredients = (pizzaDataFromCard.selected_ingredients || []).map(ing => ({
                ...ing,
                price: parseFloat(ing.price || 0)
            }));

            this.items.push({
                cartLineId: Date.now().toString() + '-' + Math.random().toString(36).substring(2, 7),
                type: 'pizza',
                pizzaId: pizzaDataFromCard.id,
                name: pizzaDataFromCard.name,
                image_url: pizzaDataFromCard.image_url,
                description: pizzaDataFromCard.description,
                unitPrice: parseFloat(pizzaDataFromCard.price),
                quantity: 1,
                selected_ingredients: processedIngredients,
                original_base_price: parseFloat(pizzaDataFromCard.original_base_price)
            });
        }
    },

    addFoodItem(foodItemData) {
        const ingredientsSignature = getIngredientsSignature(foodItemData.ingredients);
        
        const existingItem = this.items.find(item =>
            item.type === 'food_item' &&
            item.food_item_id === foodItemData.food_item_id &&
            getIngredientsSignature(item.ingredients) === ingredientsSignature
        );

        if (existingItem) {
            existingItem.quantity++;
        } else {
            // Ensure all ingredient prices are numbers
            const processedIngredients = (foodItemData.ingredients || []).map(ing => ({
                ...ing,
                price: parseFloat(ing.price || 0)
            }));

            this.items.push({
                cartLineId: Date.now().toString() + '-' + Math.random().toString(36).substring(2, 7),
                type: 'food_item',
                food_item_id: foodItemData.food_item_id,
                name: foodItemData.name,
                image_url: foodItemData.image_url || null,
                description: foodItemData.description || '',
                unitPrice: parseFloat(foodItemData.total_price),
                quantity: 1,
                ingredients: processedIngredients,
                base_price: parseFloat(foodItemData.base_price),
                category: foodItemData.category
            });
        }
    },

    removeItem(cartLineIdToRemove) {
        const index = this.items.findIndex(item => item.cartLineId === cartLineIdToRemove);
        if (index !== -1) {
            this.items.splice(index, 1);
        }
        console.log('Cart items after remove:', JSON.parse(JSON.stringify(this.items)));
    },

    updateQuantity(cartLineIdToUpdate, newQuantity) {
        const item = this.items.find(item => item.cartLineId === cartLineIdToUpdate);
        if (item) {
            if (newQuantity > 0) {
                item.quantity = parseInt(newQuantity, 10);
            } else {
                this.removeItem(cartLineIdToUpdate);
            }
        }
        console.log('Cart items after update qty:', JSON.parse(JSON.stringify(this.items)));
    },

    clearCart() {
        this.items.splice(0);
        console.log('Cart cleared');
    }
});

// Auto-initialize in browser environment
if (typeof window !== 'undefined') {
    nextTick(() => {
        cart.init();
    });
} 