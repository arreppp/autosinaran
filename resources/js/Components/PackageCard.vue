<script setup lang="ts">
import type { Package } from '@/types';

const props = defineProps<{
    package: Package;
    selected?: boolean;
}>();

const emit = defineEmits<{
    select: [pkg: Package];
}>();
</script>

<template>
    <button
        type="button"
        class="w-full text-left border-2 rounded-xl p-6 transition-all focus:outline-none"
        :class="selected
            ? 'border-blue-600 bg-blue-50 shadow-md'
            : 'border-gray-200 bg-white hover:border-blue-400 hover:shadow'"
        @click="emit('select', package)"
    >
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ package.name }}</h3>
                <p v-if="package.description" class="text-sm text-gray-500 mt-1">{{ package.description }}</p>
                <ul class="mt-3 space-y-1">
                    <li
                        v-for="item in package.items"
                        :key="item"
                        class="flex items-center gap-2 text-sm text-gray-700"
                    >
                        <span class="text-green-500 font-bold">✓</span>
                        {{ item }}
                    </li>
                </ul>
            </div>
            <div class="text-right shrink-0">
                <p class="text-2xl font-bold text-blue-700">RM {{ Number(package.price).toFixed(2) }}</p>
                <div v-if="selected" class="mt-2 inline-block bg-blue-600 text-white text-xs px-3 py-1 rounded-full">
                    Selected
                </div>
            </div>
        </div>
    </button>
</template>
