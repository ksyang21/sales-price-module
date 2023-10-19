<script setup>

import {Head, Link} from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {ref} from "vue";

const props = defineProps({
    orders: {
        type: Object,
    },
    driver: {
        type: Object,
    },
})

let todayDate = new Date().toLocaleDateString()
let isDelivering = ref(false)

function changeDeliveryStatus() {
    isDelivering.value = !isDelivering.value
}
</script>

<template>
    <Head title="Dashboard"/>
    <FrontendLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">{{ todayDate }}</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="px-4 mb-6">
                    <p class="text-2xl">Delivery order</p>
                </div>
                <div class="px-4">
                    <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                        <li v-for="order in orders" class="pt-3 pb-0 sm:pt-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex flex-col flex-shrink-0">
                                    <p class="text-gray-500">#{{ order.id }}</p>
                                    <p>{{ order.customer.name }}</p>
                                    <p>{{ order.customer.address }}</p>
                                </div>
                                <div class="items-center flex"
                                     style="margin-left: auto">
                                    <p class="text-base font-semibold text-gray-900 dark:text-black">${{ parseFloat(order.total_price).toFixed(2)}}</p>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4" v-show="isDelivering">Default</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="flex justify-center mt-6">
                        <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white" v-if="!isDelivering" @click="changeDeliveryStatus">
                            Start Trip
                        </button>
                        <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white" v-else @click="changeDeliveryStatus">
                            Stop Trip
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped>

</style>
