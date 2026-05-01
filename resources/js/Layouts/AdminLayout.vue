<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const flash = computed(() => (page.props as any).flash);
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col shrink-0">
            <div class="px-6 py-5 border-b border-gray-700">
                <span class="text-lg font-bold text-white">Auto Sinaran</span>
                <p class="text-xs text-gray-400">Admin Panel</p>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <Link
                    :href="route('admin.dashboard')"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-700 transition"
                    :class="{ 'bg-blue-600': route().current('admin.dashboard') }"
                >
                    Dashboard
                </Link>
                <Link
                    :href="route('admin.bookings.index')"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-700 transition"
                    :class="{ 'bg-blue-600': route().current('admin.bookings.*') }"
                >
                    Bookings
                </Link>
                <Link
                    :href="route('admin.packages.index')"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-700 transition"
                    :class="{ 'bg-blue-600': route().current('admin.packages.*') }"
                >
                    Packages
                </Link>
                <Link
                    :href="route('admin.blocked-dates.index')"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-700 transition"
                    :class="{ 'bg-blue-600': route().current('admin.blocked-dates.*') }"
                >
                    Blocked Dates
                </Link>
            </nav>
            <div class="px-4 py-4 border-t border-gray-700">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm text-gray-400 hover:text-white"
                >
                    Sign Out
                </Link>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                <slot name="header" />
            </header>

            <!-- Flash -->
            <div v-if="flash?.success" class="mx-6 mt-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ flash.success }}
            </div>
            <div v-if="flash?.error" class="mx-6 mt-4 bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ flash.error }}
            </div>

            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
