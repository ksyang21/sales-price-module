<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {reactive} from "vue";

const props = defineProps({
    customer: {
        type: Object,
    },
    prices: {
        type: Object
    },
    drivers: {
        type: Object
    },
    driverCustomer: {
        type: Object
    },
    errors: {
        type: Object
    }
})

const form = reactive({
    customer_id: props.customer.id,
    driver_id: props.customer.driver ? props.customer.driver.id : -1
})

function updateDriver() {
    if(form.driver_id > 0 && form.customer_id > 0) {
        if(props.driverCustomer.id !== undefined) {
            router.put(`/driver_customer/${props.driverCustomer.id}`, form)
        } else {
            router.post('/driver_customer', form)
        }
    }
}

</script>

<template>
    <Head title="Customer Details - {{customer.name}}"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customer Details</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="errors.length" class="text-red-600">
                    <p class="text-xl" v-for="error in errors">{{ error }}</p>
                </div>
                <div class="bg-white rounded-md px-6 border-gray-200">
                    <div class="border-b-2 py-3 flex items-center">
                        <div class="flex flex-col justify-center">
                            <p class="text-2xl">Customer Name : {{ customer.name }}</p>
                            <p v-show="customer.driver.name">Driver : {{ customer.driver.name }}</p>
                        </div>
                        <p class=" ml-auto">Total {{ prices.length }} record(s) found</p>
                    </div>
                    <div class="py-3">
                        <div class="mt-3 mb-6">
                            <Link :href="route('price.customer.create', customer.id)"
                                  class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-600">Add Price
                            </Link>
                            <div class="mt-4">
                                <label for="driver-customer" class="mt-3">Assign Driver</label>
                                <select id="driver-customer" @change="updateDriver" v-model="form.driver_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="-1" selected disabled>{{ customer.driver.name ? 'Change Driver' : 'Assign Driver' }}</option>
                                    <option v-for="driver in drivers" :value="driver.id">{{ driver.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Product
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
                                <tr v-for="price in prices"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ price.product.name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        $ {{ parseFloat(price.price).toFixed(2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ price.max_stock }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">
                                            Edit
                                        </button>
                                        <Link
                                            as="button"
                                            method="delete"
                                            :href="route('price.destroy', price.id)"
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
