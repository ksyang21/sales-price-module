<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
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
                            <div class="fixed inset-x-0 bottom-0 p-4 bg-gray-900 text-white">
                                <div class="container mx-auto">
                                    <div class="flex justify-between">
                                        <button
                                            class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white disabled:bg-gray-500"
                                            disabled>
                                            Home
                                        </button>
                                        <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white">
                                            Delivery
                                        </button>
                                        <button
                                            class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white disabled:bg-gray-500"
                                            disabled>
                                            Settings
                                        </button>
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
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>

</style>
