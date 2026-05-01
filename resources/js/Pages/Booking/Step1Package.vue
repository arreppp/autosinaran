<script setup lang="ts">
import PackageCard from '@/Components/PackageCard.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { Package } from '@/types';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    packages: Package[];
}>();

const selected = ref<Package | null>(null);

function proceed() {
    if (!selected.value) return;
    router.get(route('booking.date', selected.value.id));
}
</script>

<template>
    <PublicLayout>
        <div class="max-w-2xl mx-auto">
            <!-- Step indicator -->
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-8">
                <span class="font-semibold text-blue-600">1. Choose Package</span>
                <span>&rsaquo;</span>
                <span>2. Select Date</span>
                <span>&rsaquo;</span>
                <span>3. Payment</span>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Choose a Service Package</h1>
            <p class="text-gray-500 mb-6">Select the service that best fits your vehicle needs.</p>

            <div class="space-y-4">
                <PackageCard
                    v-for="pkg in packages"
                    :key="pkg.id"
                    :package="pkg"
                    :selected="selected?.id === pkg.id"
                    @select="selected = pkg"
                />
            </div>

            <div class="mt-8 flex justify-end">
                <button
                    type="button"
                    class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 disabled:opacity-50 transition"
                    :disabled="!selected"
                    @click="proceed"
                >
                    Continue &rarr;
                </button>
            </div>
        </div>
    </PublicLayout>
</template>
