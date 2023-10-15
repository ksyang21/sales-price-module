<script setup>

import {Head, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {reactive, ref} from "vue";

const props = defineProps({
    customer: {
        type: Object,
    },
    products: {
        type: Object,
    },
})

const form = reactive({
    source: 'customer',
    customer: props.customer.id,
    product: 0,
    price: 0.00,
    max_stock: 0
})

let foc = ref(false)

let canSubmit = ref(true)

let currentProduct = {}

function getProduct() {
    let productID = form.product
    currentProduct = props.products.find((product) => {
        return product.id === productID
    })
    form.price = currentProduct.price
}

function resetPrice() {
    if(foc.value) {
        form.price = 0.00
    } else {
        if(currentProduct) {
            form.price = currentProduct.price
        } else {
            form.price = 0.00
        }
    }
}


function validateForm() {
    canSubmit.value = (form.customer > 0 && form.product > 0)
}

function handleSubmit() {
    validateForm()
    if (canSubmit.value) {
        router.post('/price', form)
    }
}

</script>

<template>

    <Head title="Add Price"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Price</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <p class="text-3xl">{{ customer.name }}</p>
                <form class="w-full" @submit.prevent="handleSubmit">
                    <div class="p-6 lg:p-8 bg-white mt-2">
                        <div class="w-full px-3 mb-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="products">Products</label>
                            <select
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                v-model="form.product"
                                @change="getProduct"
                                id="products"
                            >
                                <option v-for="product in products" :value="product.id">{{ product.name }}
                                </option>
                            </select>
                            <p class="text-red-600 text-xs italic" v-if="!canSubmit && !form.product">Please select
                                product</p>
                        </div>
                        <div class="w-full px-3 mb-3">
                            <div class="flex items-center">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold"
                                       for="price">Price</label>
                                <input type="checkbox" id="foc" class="ml-auto" v-model="foc" @change="resetPrice">
                                <label for="foc" class="ml-2">FOC</label>
                            </div>
                            <input
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 disabled:bg-gray-300"
                                id="price" type="number" placeholder="Product price"
                                v-model="form.price" step="0.01"
                                :disabled="foc"
                            >
                        </div>
                        <div class="w-full px-3 mb-3" v-if="form.price === 0.00">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold"
                                   for="stock">Max Stock</label>
                            <p class="text-gray-300 text-sm mb-2">Original price will be used after this customer
                                purchased more than this number.</p>
                            <input
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="stock" type="number" placeholder="Maximum count of stock for this product"
                                v-model="form.max_stock">
                        </div>
                        <div class="flex items-center mt-10">
                            <button
                                class="rounded-md bg-blue-700 py-2 px-4 hover:bg-blue-600 text-white" type="button"
                                @click="goBack"
                            >
                                Back
                            </button>
                            <button
                                class="ml-auto px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-600"
                                type="submit"
                            >
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
