<script setup>

import {Head, Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {reactive, ref} from "vue";

const props = defineProps({
    product: {
        type: Object,
    },
    customers: {
        type: Object,
    },
})

let foc = ref(false)

const form = reactive({
    source: 'product',
    product: props.product.id,
    customer: 0,
    price: props.product.price,
    max_stock: 0,
    foc: false
})


let canSubmit = ref(true)

function resetPrice() {
    form.price = props.product.price
    form.foc = foc.value
    if (foc.value) {
        form.price = 0
        form.max_stock = 0
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
                <div class="flex items-center">
                    <p class="text-3xl">{{ product.name }}</p>
                    <p class="text-xl ml-auto">Original Price : {{ product.price.toFixed(2) }}</p>
                </div>
                <form class="w-full" @submit.prevent="handleSubmit">
                    <div class="p-6 lg:p-8 bg-white mt-2">
                        <div class="w-full px-3 mb-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="customer">Customer</label>
                            <select
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                v-model="form.customer"
                                id="customer"
                            >
                                <option v-for="customer in customers" :value="customer.id">{{ customer.name }}
                                </option>
                            </select>
                            <p class="text-red-600 text-xs italic" v-if="!canSubmit && !form.customer">Please select
                                customer</p>
                        </div>
                        <div class="w-full px-3 mb-3">
                            <div class="flex items-center">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold"
                                       for="price">Price</label>
                                <input type="checkbox" id="foc" class="ml-auto" v-model="foc" @change="resetPrice">
                                <label for="foc" class="ml-2">FOC (Buy 10 Free 1)</label>
                            </div>
                            <input
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 disabled:bg-gray-300"
                                id="price" type="number" placeholder="Product price"
                                v-model="form.price" step="0.01"
                                :disabled="foc"
                            >
                        </div>
                        <div class="w-full px-3 mb-3" v-if="!foc">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold"
                                   for="stock">Max Stock</label>
                            <p class="text-gray-300 text-sm">Original price will be used after this customer
                                purchase more than this number.</p>
                            <p class="text-gray-300 text-sm mb-2">Leave 0 if no limit to the discounted price.</p>
                            <input
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="stock" type="number" placeholder="Maximum count of stock for this product"
                                v-model="form.max_stock">
                        </div>
                        <div class="flex items-center mt-10">
                            <Link
                                as="button"
                                :href="route('product.show', product.id)"
                                class="rounded-md bg-blue-700 py-2 px-4 hover:bg-blue-600 text-white"
                            >
                                Back to {{ product.name }} Details
                            </Link>
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
