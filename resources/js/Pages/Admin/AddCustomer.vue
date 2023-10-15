<script setup>

import {Head, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {reactive, ref} from "vue";

const form = reactive({
    name: ''
});

let canSubmit = ref(true)

function validateForm() {
    canSubmit.value = form.name !== ''
}

function handleSubmit() {
    validateForm()
    if(canSubmit.value) {
        router.post('/customer', form)
    }
}

function goBack() {
    window.history.back()
}
</script>

<template>

    <Head title="Driver Management"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Customer</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form class="w-full" @submit.prevent="handleSubmit">
                    <div class="p-6 lg:p-8 bg-white">
                        <div class="w-full px-3 mb-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="customerName">Name</label>
                            <input
                                class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="customerName" type="text" placeholder="Customer Name" v-model="form.name">
                            <p class="text-red-600 text-xs italic" v-if="!canSubmit && !form.name">Please enter customer name</p>
                        </div>
                        <div class="flex items-center mt-10">
                            <button :href="route('customer_management')"
                                    class="rounded-md bg-blue-700 py-2 px-4 hover:bg-blue-600 text-white" type="button"
                                    @click="goBack">
                                Back
                            </button>
                            <button
                                class="ml-auto px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-600"
                                type="submit"
                            >
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
