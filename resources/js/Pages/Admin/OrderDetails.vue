<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link} from "@inertiajs/vue3";

const props = defineProps({
    order: {
        type: Object
    },
    details: {
        type: Object
    },
});

// calculate total price and quantity
let totalPrice = 0
let totalQuantity = 0
for(let detail of props.details) {
    totalPrice += detail.price
    totalQuantity += detail.quantity
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
                    <Link :href="route('orders')"
                          class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500">
                        Back to Order Listing
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
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody >
                        <tr v-for="detail in details" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ detail.product.name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ detail.price.toFixed(2) }}
                            </td>
                            <td class="px-6 py-4 ">
                                {{ detail.quantity }}
                            </td>
                            <td class="px-6 py-4 ">
                                {{ (detail.price * detail.quantity).toFixed(2) }}
                            </td>
                            <td class="px-6 py-4 flex">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 ml-3">Edit
                                </button>
                                <Link
                                    as="button"
                                    method="delete"
                                    :href="route('driver.destroy', detail.id)"
                                    class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-500 ml-3"
                                >
                                    Remove
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr class="bg-gray-500 font-semibold text-gray-900 dark:text-white">
                            <th scope="row" class="px-6 py-4 text-base">Total</th>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4">{{totalQuantity}}</td>
                            <td class="px-6 py-4">{{totalPrice.toFixed(2)}}</td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
