<script setup>

import {Head, router, usePage} from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {inject} from "vue";

const props = defineProps({
    order: Object,
    details: Object,
    customer: Object,
    total: Number
})

const form = {
    id: props.order.id,
}

const Swal = inject('$swal')

let todayDate = new Date().toLocaleDateString()

function confirmOrder() {
    router.put(`/pay_order/${props.order.id}`)
}

function deleteItem(detail) {
    Swal.fire({
        title: 'Delete item?',
        html: `Product : ${detail.product.name}<br>Quantity : ${detail.quantity}`,
        icon: 'warning',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/order_detail/${detail.id}`)
        }
    })
}

async function editItem(detail) {
    let totalQuantity = 0
    let items = props.details.filter((item) => {
        return item.product_id === parseInt(detail.product_id)
    })
    for(let item of items) {
        totalQuantity += parseInt(item.quantity)
    }
    let {value: quantity} = await Swal.fire({
        title: `How many pieces of ${detail.product.name}?`,
        text: `Current quantity : ${totalQuantity}`,
        input: 'number',
        inputPlaceholder: `Enter quantity of ${detail.product.name}`
    })
    if(quantity) {
        quantity = parseInt(quantity)
        let form = {
            id: detail.id,
            quantity: quantity
        }
        router.put('/order_detail', form)
    }
}

function deleteOrder() {
    Swal.fire({
        title: 'Cancel order?',
        icon: 'info',
        showCancelButton: true
    }).then((result)=> {
        if(result.isConfirmed) {
            router.delete(`/order/${props.order.id}`)
        }
    })
}

</script>

<template>
    <Head title="New Order"/>
    <FrontendLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">{{ todayDate }}</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center px-2">
                    <div class="flex flex-col">
                        <p class="text-xl">Order Summary</p>
                        <p class="text-sm text-gray-700">Customer: {{ customer.name }}</p>
                    </div>
                    <span class="bg-red-300 text-red-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded ml-auto">Order ID : #{{
                            order.id
                        }}</span>
                </div>
                <div class="border-t-2 border-b-2 mt-3">
                    <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700 px-4">
                        <li v-for="detail in details" class="pt-3 pb-3 sm:pt-4">
                            <div class="flex items-center">
                                <span
                                    class="bg-gray-300 text-gray-800 text-xl font-medium px-2.5 py-0.5 rounded ">{{
                                        detail.quantity
                                    }}X</span>
                                <div class="flex flex-col ml-4">
                                    <p class="text-xl">{{ detail.product.name }}</p>
                                    <p class="text-blue-700" @click="editItem(detail)">Edit</p>
                                </div>
                                <div class="flex items-center ml-auto">
                                    <p class="text-xl">$ {{ detail.price }}</p>
                                    <p class="text-red-700 ml-3" @click="deleteItem(detail)">Delete</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="mt-6">
                    <p class="text-2xl mx-6">Total : $ {{ total }}</p>
                </div>
                <div class="flex flex-col items-center justify-center mt-6 mb-12 mx-6">
                    <button class="bg-blue-700 text-white py-2 hover:bg-blue-800 rounded w-full" @click="confirmOrder">
                        Confirm
                    </button>
                    <button class="bg-red-700 text-white py-2 hover:bg-red-800 rounded w-full mt-2"
                            @click="deleteOrder">Cancel
                    </button>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped>

</style>
