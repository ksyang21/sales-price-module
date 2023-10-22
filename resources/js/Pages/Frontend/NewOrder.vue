<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import {computed, inject, reactive, ref, watch} from "vue";

const props = defineProps({
    customer: Object,
    products: Object,
    errors: Object
})

const cart = reactive({
    products: [],
    customer_id: parseInt(props.customer.id),
    driver_id: usePage().props.auth.user.id
})

let cartTotalPrice = computed(() => {
    let total = 0.00;
    for (const item of cart.products) {
        total += (item.discount.quantity * item.discount.price) + (item.original.quantity * item.original.price);
    }
    return total;
})

watch(() => cart.products, () => {
    cartTotalPrice.value = calculateCartTotalPrice()
})

function calculateCartTotalPrice() {
    let total = 0.00
    for (const item of cart.products) {
        total += (item.discount.quantity * item.discount.price) + (item.original.quantity * item.original.price)
    }
    return total
}

const Swal = inject('$swal')

async function addToCart(product) {
    let {value: quantity} = await Swal.fire({
        title: `How many pieces of ${product.name}?`,
        input: 'number',
        inputLabel: 'Quantity',
        inputPlaceholder: `Enter quantity of ${product.name}`
    })
    // let quantity = window.prompt(`How many pieces of ${product.name} ?`);
    quantity = parseInt(quantity)

    // Check current product added to cart
    let itemAdded = cart.products.find((item) => {
        return item.id === product.id
    })
    if (itemAdded) {
        if (product.special_price.id !== undefined) {
            if (product.special_price.type === 'special price module') {
                let maxStockAllowed = parseInt(product.special_price.max_stock)
                if (quantity + itemAdded.discount.quantity <= maxStockAllowed) {
                    itemAdded.discount.quantity = quantity + itemAdded.discount.quantity
                } else {
                    itemAdded.discount.quantity = maxStockAllowed
                    itemAdded.original.quantity = quantity + itemAdded.discount.quantity - maxStockAllowed
                }
            } else {
                let focQuantity = parseInt(product.special_price.foc_quantity)
                let focGift = parseInt(product.special_price.foc_gift)
                let totalQuantity = quantity + itemAdded.discount.quantity
                itemAdded.discount.quantity = totalQuantity
                itemAdded.free = parseInt(totalQuantity / focQuantity) * focGift
            }
        } else {
            let currentItemQuantity = product.original.quantity
            itemAdded.quantity = quantity + parseInt(currentItemQuantity)
        }
    } else {
        if (product.special_price.id !== undefined) {
            if (product.special_price.type === 'special price module') {
                let maxStockAllowed = parseInt(product.special_price.max_stock)
                if (quantity <= maxStockAllowed) {
                    cart.products.push({
                        id: product.id,
                        name: product.name,
                        discount: {
                            quantity: quantity,
                            price: parseFloat(product.special_price.price),

                        },
                        original: {
                            quantity: 0,
                            price: parseFloat(product.price)
                        },
                        free: 0
                    })
                } else {
                    cart.products.push({
                        id: product.id,
                        name: product.name,
                        discount: {
                            quantity: maxStockAllowed,
                            price: parseFloat(product.special_price.price),

                        },
                        original: {
                            quantity: quantity - maxStockAllowed,
                            price: parseFloat(product.price)
                        },
                        free: 0
                    })
                }
            } else {
                let focQuantity = parseInt(product.special_price.foc_quantity)
                let focGift = parseInt(product.special_price.foc_gift)
                cart.products.push({
                    id: product.id,
                    name: product.name,
                    discount: {
                        quantity: quantity,
                        price: parseFloat(product.price),
                    },
                    original: {
                        quantity: 0,
                        price: parseFloat(product.price)
                    },
                    free: parseInt(quantity / focQuantity) * focGift
                })
            }
        } else {
            cart.products.push({
                id: product.id,
                name: product.name,
                discount: {
                    quantity: 0,
                    price: parseFloat(product.price),

                },
                original: {
                    quantity: quantity,
                    price: parseFloat(product.price)
                },
                free: 0
            })
        }
    }

    // Calculate total price
    cartTotalPrice.value = calculateCartTotalPrice();
}

function placeOrder() {
    if (cart.products.length) {
        router.post('/place_order', cart)
    } else {
        Swal.fire('Please add product first!', '', 'error')
    }
}

let todayDate = new Date().toLocaleDateString()
</script>

<template>
    <Head title="New Order"/>
    <FrontendLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">{{ todayDate }}</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="errors.length" class="text-red-600">
                    <p class="text-xl" v-for="error in errors">{{ error }}</p>
                    <p class="text-xl">Try again!</p>
                </div>
                <p class="text-xl px-4">Customer : {{ customer.name }}</p>
                <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700 px-4">
                    <li v-for="product in products" class="pt-3 pb-0 sm:pt-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex flex-col flex-shrink-0">
                                <p class="text-xl">{{ product.name }}</p>
                                <div v-if="product.special_price.id !== undefined">
                                    <div v-if="product.special_price.type === 'special price module'">
                                        <p class="text-gray-700 text-sm">Your price : $
                                            {{ parseFloat(product.special_price.price).toFixed(2) }}</p>
                                        <p class="text-gray-700 text-sm"> Original Price :
                                            ${{ parseFloat(product.price).toFixed(2) }}</p>
                                        <p class="text-gray-700 text-sm">Remaining stocks:
                                            {{ parseInt(product.special_price.max_stock) }}</p>
                                    </div>
                                    <div v-else>
                                        <p class="text-gray-700 text-sm">Buy
                                            {{ parseInt(product.special_price.foc_quantity) }} Free
                                            {{ parseInt(product.special_price.foc_gift) }}</p>
                                        <p class="text-gray-700 text-sm">Price :
                                            ${{ parseFloat(product.price).toFixed(2) }}</p>
                                    </div>
                                </div>
                                <p v-else>Price : ${{ parseFloat(product.price).toFixed(2) }}</p>
                            </div>
                            <div class="items-center flex" style="margin-left: auto">
                                <button @click="addToCart(product)"
                                        class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-2 rounded ml-4">
                                    Add To Cart
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="flex items-center justify-center mt-6 mb-12">
                    <div
                        class="flex items-center bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white w-full mx-6 cursor-pointer"
                        @click="placeOrder">
                        <p>Basket : {{ cart.products.length }} Item</p>
                        <p class="ml-auto">$ {{ cartTotalPrice.toFixed(2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped>

</style>
