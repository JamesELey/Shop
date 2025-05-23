import { reactive } from 'vue';

// Helper to create a signature for selected ingredients (sorted by ID)
function getIngredientsSignature(ingredients) {
    if (!ingredients || ingredients.length === 0) return 'no_ingredients';
    return ingredients.map(ing => ing.id).sort().join(',');
}

export const cart = reactive({
    items: [], // Array of { cartLineId, pizzaId, name, image_url, description, unitPrice, quantity, selected_ingredients, original_base_price }
    
    get itemCount() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    },

    get totalPrice() {
        return this.items.reduce((total, item) => {
            return total + (item.unitPrice * item.quantity);
        }, 0).toFixed(2);
    },

    addItem(pizzaDataFromCard) { // pizzaDataFromCard is the object sent from PizzaCard.vue
        const ingredientsSignature = getIngredientsSignature(pizzaDataFromCard.selected_ingredients);
        
        const existingItem = this.items.find(item =>
            item.pizzaId === pizzaDataFromCard.id && // pizzaDataFromCard still has original pizza id as 'id'
            getIngredientsSignature(item.selected_ingredients) === ingredientsSignature
        );

        if (existingItem) {
            existingItem.quantity++;
        } else {
            this.items.push({
                cartLineId: Date.now().toString() + '-' + Math.random().toString(36).substring(2, 7), // Unique ID for this cart line
                pizzaId: pizzaDataFromCard.id,
                name: pizzaDataFromCard.name,
                image_url: pizzaDataFromCard.image_url,
                description: pizzaDataFromCard.description, // Keep original description if needed
                unitPrice: parseFloat(pizzaDataFromCard.price), // This is the currentPrice (customized) from PizzaCard
                quantity: 1,
                selected_ingredients: JSON.parse(JSON.stringify(pizzaDataFromCard.selected_ingredients || [])),
                original_base_price: parseFloat(pizzaDataFromCard.original_base_price)
            });
        }
        console.log('Cart items after add:', JSON.parse(JSON.stringify(this.items)));
    },

    removeItem(cartLineIdToRemove) { // Now using cartLineId
        const index = this.items.findIndex(item => item.cartLineId === cartLineIdToRemove);
        if (index !== -1) {
            this.items.splice(index, 1);
        }
        console.log('Cart items after remove:', JSON.parse(JSON.stringify(this.items)));
    },

    updateQuantity(cartLineIdToUpdate, newQuantity) { // Now using cartLineId
        const item = this.items.find(item => item.cartLineId === cartLineIdToUpdate);
        if (item) {
            if (newQuantity > 0) {
                item.quantity = parseInt(newQuantity, 10);
            } else {
                this.removeItem(cartLineIdToUpdate); // Remove if quantity is 0 or less
            }
        }
        console.log('Cart items after update qty:', JSON.parse(JSON.stringify(this.items)));
    },

    clearCart() {
        this.items = [];
        console.log('Cart cleared');
    }
}); 