<script setup>

import {Head, Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {reactive, ref} from "vue";

const props = defineProps({
    errors: {
        type: Object,
    },
})

const form = reactive({
    name: '',
    price: 0.00,
})

let canSubmit = ref(true)

function validateForm() {
    canSubmit.value = (form.name.trim() !== '' && form.price)
}

function handleSubmit() {
    validateForm()
    if (canSubmit.value) {
        router.post('/product', form)
    }
}

</script>

<template>

    <Head title="Add Product"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Product</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="errors.length" class="text-red-600">
                    <p class="text-xl" v-for="error in errors">{{ error }}</p>
                </div>
                <form class="w-full" @submit.prevent="handleSubmit">
                    <div class="p-6 lg:p-8 bg-white">
                        <div class="w-full px-3 mb-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="productName">Name</label>
                            <input
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="productName" type="text" placeholder="Product Name" v-model="form.name">
                            <p class="text-red-600 text-xs italic" v-if="!canSubmit && !form.name">Please enter
                                product name</p>
                        </div>
                        <div class="w-full px-3 mb-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="productPrice">Price</label>
                            <input
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="productPrice" type="number" placeholder="Product original price" v-model="form.price" step="0.01">
                            <p class="text-red-600 text-xs italic" v-if="!canSubmit && !form.price">Please enter price</p>
                        </div>
                        <div class="flex items-center mt-10">
                            <Link
                                as="button"
                                :href="route('products')"
                                class="rounded-md bg-blue-700 py-2 px-4 hover:bg-blue-600 text-white"
                            >
                                Back to Product Listing
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
