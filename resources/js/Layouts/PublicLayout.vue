<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
                <Link :href="route('home')" class="flex items-center gap-2">
                    <span class="text-xl font-bold text-blue-700">Auto Sinaran</span>
                    <span class="text-sm text-gray-500">Workshop</span>
                </Link>
                <Link :href="route('booking.packages')" class="text-sm text-blue-600 hover:underline font-medium">
                    Book Now
                </Link>
            </div>
        </nav>

        <main class="max-w-4xl mx-auto px-4 py-8">
            <slot />
        </main>

        <footer class="text-center text-sm text-gray-400 py-8">
            &copy; {{ new Date().getFullYear() }} Auto Sinaran Workshop. All rights reserved.
        </footer>
    </div>
</template>
