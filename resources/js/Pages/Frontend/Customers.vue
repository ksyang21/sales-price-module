<script setup>

import {Head, Link} from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {ref} from "vue";

const props = defineProps({
    customers: {
        type: Object,
    },
    driver: {
        type: Object,
    },
})

let todayDate = new Date().toLocaleDateString()

</script>

<template>
    <Head title="Dashboard"/>
    <FrontendLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">{{ todayDate }}</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="px-4 mb-6 flex items-center">
                    <p class="text-2xl">Customers</p>
                    <p class="text-sm text-gray-500 ml-auto">Total {{customers.length}} customers</p>
                </div>
                <div class="px-4">
                    <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                        <li v-for="customer in customers" class="pt-3 pb-0 sm:pt-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex flex-col flex-shrink-0">
                                    <p>{{ customer.name }}</p>
                                    <p class="text-gray-700">{{ customer.address }}</p>
                                </div>
                                <div class="items-center flex"
                                     style="margin-left: auto">
                                    <Link :href="route('frontend_customer_details', customer.id)" class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4">View</Link>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped>

</style>
