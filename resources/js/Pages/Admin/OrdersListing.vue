<script setup>
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed, inject} from "vue";

const props = defineProps({
    orders: {
        type: Object,
    },
});

// Notification messages
const successMessage = computed(() => usePage().props.alert.success)
if(successMessage.value) {
    alert(successMessage.value)
}
const errorMessage = computed(() => usePage().props.alert.error)
if(errorMessage.value) {
    alert(errorMessage.value)
}
</script>

<template>
    <Head title="Order Listing"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order Listing</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link :href="route('driver.create')"
                          class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-600">
                        New Order
                    </Link>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Order No.
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Driver
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Customer
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Products
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody v-if="orders.length">
                        <tr v-for="order in orders" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ order.driver.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ order.customer.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ order.details.length }}
                            </td>
                            <td class="px-6 py-4">
                                {{ order.total_prices.toFixed(2) }}
                            </td>
                            <td class="px-6 py-4 flex">
                                <Link
                                    as="button"
                                    :href="route('order.show', order.id)"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500"
                                >
                                    View
                                </Link>
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 ml-3">Edit
                                </button>
                                <Link
                                    as="button"
                                    method="delete"
                                    :href="route('driver.destroy', order.id)"
                                    class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-500 ml-3"
                                >
                                    Cancel
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                        <tbody v-else>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="5" class="px-6 py-4">No order</td>
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
