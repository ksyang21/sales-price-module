<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    drivers: {
        type: Object,
    },
});
</script>

<template>
    <Head title="Driver Management"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Driver Management</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <button class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-600">Add Driver</button>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Driver
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="driver in drivers" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ driver.name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ driver.email }}
                            </td>
                            <td class="px-6 py-4">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">Edit
                                </button>
                                <button class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-500 ml-3">Delete
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
