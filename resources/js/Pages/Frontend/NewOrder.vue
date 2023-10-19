<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import {reactive, ref} from "vue";

const props = defineProps({
    customer: Object,
    products: Object
})

const order = reactive({
    products: []
});

let canSubmit = ref(true)

function confirmQuantity(product) {
    let quantity = window.prompt(`How many pieces of ${product.name} ?` );
    quantity = parseInt(quantity)
    order.products.push({
        id: product.id,
        name: product.name,
        quantity: quantity,
    })
}

function validateForm() {
    canSubmit.value = (form.name !== '' && form.address !== '')
}

function handleSubmit() {
    validateForm()
    if (canSubmit.value) {
        router.post('/customer', form)
    }
}

function placeOrder() {
    console.log(order)
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
                <p class="text-xl px-4">Customer : {{ customer.name}}</p>
                <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700 px-4">
                    <li v-for="product in products" class="pt-3 pb-0 sm:pt-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex flex-col flex-shrink-0">
                                <p class="text-xl">{{ product.name }}</p>
                                <div v-if="product.special_price.id !== undefined">
                                    <p class="text-gray-700 text-sm">Your price : $ {{product.special_price.price}}</p>
                                    <p class="text-gray-700 text-sm"> Original Price : ${{ product.price }}</p>
                                    <p class="text-gray-700 text-sm">Remaining stocks: {{ product.special_price.max_stock}}</p>
                                </div>
                                <p v-else>Price : ${{ product.price }}</p>
                            </div>
                            <div class="items-center flex" style="margin-left: auto">
                                <button @click="confirmQuantity(product)"
                                    class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-2 rounded ml-4">
                                    Add To Cart
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="flex items-center justify-center mt-6 mb-12">
                    <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white" @click="placeOrder">Basket : </button>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped>

</style>
