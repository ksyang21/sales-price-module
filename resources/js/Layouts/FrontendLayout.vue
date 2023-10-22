<script setup>
import {computed, inject, ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link, usePage} from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const Swal = inject('$swal')
// Notification messages
const successMessage = computed(() => usePage().props.alert.success)
if (successMessage.value) {
    Swal.fire({
        title: 'Done!',
        text: successMessage.value,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        allowEscapeKey: false,
        position: 'top',
        toast: true
    })
}
const errorMessage = computed(() => usePage().props.alert.error)
if (errorMessage.value) {
    Swal.fire({
        title: 'Unexpected error :(',
        text: errorMessage.value,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        allowEscapeKey: false,
        position: 'top',
        toast: true
    })
}
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>
                            <div class="fixed inset-x-0 bottom-0 p-4 bg-white text-gray-500">
                                <div class="container mx-auto">
                                    <div class="flex justify-between">
                                        <p>Home</p>
                                        <NavLink :href="route('frontend_customers')"
                                                 :active="route().current('frontend_customers')">
                                            Customers
                                        </NavLink>
                                        <NavLink :href="route('frontend_orders')"
                                                 :active="route().current('frontend_orders')">
                                            Delivery
                                        </NavLink>
                                        <p>Settings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"/>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot/>
            </main>
        </div>
    </div>
</template>

<style scoped>

</style>
