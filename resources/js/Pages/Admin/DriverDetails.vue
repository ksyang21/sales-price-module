<script setup>

import {Head, Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    driver: {
        type: Object,
    },
    customers: {
        type: Object
    },
    orders: {
        type: Object
    }
})
</script>

<template>

    <Head title="Customer Management"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Driver Details</h2>
                <h2 class="ml-auto">Driver Name : {{ driver.name }}</h2>
            </div>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <p class="text-xl">Customers</p>
                <p class="text-sm">Total: {{ customers.length }}</p>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Customer
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Address
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="customer in customers"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ customer.name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ customer.address }}
                            </td>
                            <td class="px-6 py-4">
                                <Link
                                    as="button"
                                    :href="route('customer.show', customer.id)"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500"
                                >
                                    View
                                </Link>
                                <Link
                                    as="button"
                                    method="delete"
                                    :href="route('customer.destroy', customer.id)"
                                    class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-500 ml-3"
                                >
                                    Remove
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <p class="text-xl">Orders</p>
                    <p class="text-sm">Total : {{ orders.length }}</p>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Order No.
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Customer
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Address
                                </th>
                                <td class="px-6 py-4">
                                    Status
                                </td>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    #{{ order.id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ order.customer.name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ order.customer.address }}
                                </td>
                                <td class="px-6 py-4">
                                  <span
                                      class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4"
                                      v-if="order.status === 'completed'">{{
                                          order.status.toUpperCase()
                                      }}</span>
                                    <span
                                        class="bg-gray-300 text-black text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4"
                                        v-if="order.status === 'pending'">{{
                                            order.status.toUpperCase()
                                        }}</span>
                                    <span
                                        class="bg-red-300 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4"
                                        v-if="order.status === 'cancelled'">{{
                                            order.status.toUpperCase()
                                        }}</span>
                                    <span
                                        class="bg-red-300 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4"
                                        v-if="order.status === 'cart'">TBC</span>
                                </td>
                                <td class="px-6 py-4">
                                    <Link
                                        as="button"
                                        :href="route('order.show', order.id)"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500"
                                    >
                                        View
                                    </Link>
                                    <Link
                                        as="button"
                                        method="delete"
                                        :href="route('order.destroy', order.id)"
                                        class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-500 ml-3"
                                    >
                                        Cancel
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
