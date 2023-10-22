<script setup>

import {Head, Link, router} from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {inject, reactive, ref} from "vue";

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
let deliveryProgress = 0
let allOrders = ref(props.orders)

calculateProgress()

const Swal = inject('$swal')
function changeDeliveryStatus() {
  isDelivering.value = !isDelivering.value
}

function confirmComplete(order) {
  if (isDelivering.value && order.status === 'pending') {
    Swal.fire({
      title: `Complete order #${order.id}?`,
      icon: 'info',
      showCancelButton: true
    }).then((result) => {
      if(result.isConfirmed) {
        completeOrder(order).then((response) => {
          let data = response.data.data
          alert(data.msg)
          allOrders.value = data.orders
        })
      }
    })
  } else if (isDelivering.value && order.status === 'cart') {
      router.get(`/confirm_order/${order.id}`)
  }
}

async function completeOrder(order) {
  try {
    return await axios.put(`/api/order/${order.id}`);
  } catch (error) {
    console.log({Error: error});
  }
}

function calculateProgress() {
  for (let order of props.orders) {
    if (order.status === 'completed') {
      deliveryProgress += (1 / props.orders.length) * 100
    }
  }
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
          <p class="text-xl" v-if="isDelivering">Progress: {{ deliveryProgress.toFixed(0) }} %</p>
        </div>
        <div class="px-4">
          <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
            <li v-for="order in allOrders" class="pt-3 pb-0 sm:pt-4" @click="confirmComplete(order)">
              <div class="flex items-center space-x-4">
                <div class="flex flex-col flex-shrink-0">
                  <p class="text-gray-500">#{{ order.id }}</p>
                  <p>{{ order.customer.name }}</p>
                  <p>{{ order.customer.address }}</p>
                </div>
                <div class="items-center flex"
                     style="margin-left: auto">
                  <p class="text-base font-semibold text-gray-900 dark:text-black">
                    ${{ parseFloat(order.total_price).toFixed(2) }}</p>
                  <span
                      class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4"
                      v-if="isDelivering && order.status === 'completed'">{{
                      order.status.toUpperCase()
                    }}</span>
                  <span
                      class="bg-gray-300 text-black text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4"
                      v-if="isDelivering && order.status === 'pending'">{{
                      order.status.toUpperCase()
                    }}</span>
                  <span
                      class="bg-red-300 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4"
                      v-if="isDelivering && order.status === 'cancelled'">{{
                      order.status.toUpperCase()
                    }}</span>
                  <span
                      class="bg-red-300 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-4"
                      v-if="isDelivering && order.status === 'cart'">TBC</span>
                </div>
              </div>
            </li>
          </ul>
          <div class="flex justify-center mt-6 mb-12">
            <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white" v-if="!isDelivering"
                    @click="changeDeliveryStatus">
              Start Trip
            </button>
            <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white" v-else
                    @click="changeDeliveryStatus">
              End Trip
            </button>
          </div>
        </div>

      </div>
    </div>
  </FrontendLayout>
</template>

<style scoped>

</style>
