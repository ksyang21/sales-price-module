<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {inject} from "vue";

const props = defineProps({
    product: {
        type: Object,
    },
    prices: {
        type: Object
    }
})

const Swal = inject('$swal')

function removePrice(price) {
    Swal.fire({
        title: `Remove ${props.product.name} for ${price.customer.name}?`,
        icon: 'info',
        showCancelButton: true
    }).then((result) => {
        if(result.isConfirmed) {
            router.delete(`/price/${price.id}`)
        }
    })
}
function editPrice(price) {
    router.get(`/edit_price/${price.id}`)
}
</script>

<template>
    <Head title="Product Details - {{product.name}}"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Details</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-md px-6 border-gray-200">
                    <div class="border-b-2 py-3 flex items-center">
                        <p class="text-2xl">Product Name : {{ product.name }}</p>
                        <p class="text-xl ml-auto">Original Price : $ {{product.price.toFixed(2)}}</p>
                    </div>
                    <div class="py-3">
                        <div class="mt-3 mb-6">
                            <Link :href="route('price.product.create', product.id)"
                                  class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-600">Add Price</Link>
                        </div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Customer
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Maximum Quantity
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="price in prices" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ price.customer.name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-200" v-if="parseFloat(price.price) === 0.00">FOC</span>
                                        <span v-else>$ {{ parseFloat(price.price).toFixed(2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{
                                            price.type === 'special price module' ? price.max_stock : `Buy ${price.foc_quantity} Free ${price.foc_gift}`
                                        }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500" @click="editPrice(price)">
                                            Edit
                                        </button>
                                        <button class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-500 ml-3" @click="removePrice(price)">Remove</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
