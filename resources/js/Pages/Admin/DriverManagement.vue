<script setup>
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {inject} from "vue";

const props = defineProps({
    drivers: {
        type: Object,
    },
});

const Swal = inject('$swal')

function removeDriver(driver) {
    Swal.fire({
        title: `Remove ${driver.name}?`,
        icon: 'info',
        showCancelButton: true
    }).then((result) => {
        if(result.isConfirmed) {
            router.delete(`/driver/${driver.id}`)
        }
    })
}

function editDriver(driver) {
    router.get(`/edit_driver/${driver.id}`)
}
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
                    <Link :href="route('driver.create')"
                          class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-600">Add Driver
                    </Link>
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
                                Customers
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
                                {{ driver.customers.length }}
                            </td>
                            <td class="px-6 py-4 flex">
                                <Link
                                    :href="route('driver.show', driver.id)"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500"
                                >
                                    View
                                </Link>
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 ml-3" @click="editDriver(driver)">Edit</button>
                                <button class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-500 ml-3" @click="removeDriver(driver)">Remove</button>
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
