<script setup>
import {Head, Link} from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

const props = defineProps({
    customer: Object,
    pendingOrders: Object
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
                    <p class="text-2xl">Customer Details</p>
                </div>
                <div class="px-4">
                    <p class="text-xl">Name : {{customer.name}}</p>
                    <p class="text-xl">Address : {{customer.address}}</p>
                    <p class="text-xl">Pending Orders: {{pendingOrders.length}}</p>
                </div>
                <div class="flex justify-center mt-6 mb-12">
                    <Link :href="route('order.create', customer.id)" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white">
                        New Order
                    </Link>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped>

</style>
