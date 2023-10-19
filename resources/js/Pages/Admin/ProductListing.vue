<script setup>

import {Head, Link, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed} from "vue";

const props = defineProps({
    products: {
        type: Object,
    },
});

const successMessage = computed(() => usePage().props.alert.success)
if (successMessage.value) {
    alert(successMessage.value)
}

</script>

<template>
    <Head title="Product Listing"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Listing</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link :href="route('product.create')"
                          class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-600">Add Product
                    </Link>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Product
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in products" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ product.name }}
                            </th>
                            <td class="px-6 py-4">
                                $ {{ parseFloat(product.price).toFixed(2) }}
                            </td>
                            <td class="px-6 py-4">
                                <Link
                                    as="button"
                                    :href="route('product.show', product.id)"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500"
                                >
                                    View
                                </Link>
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 ml-3">
                                    Edit
                                </button>
                                <Link
                                    as="button"
                                    method="delete"
                                    :href="route('product.destroy', product.id)"
                                    class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-500 ml-3"
                                >
                                    Remove
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
