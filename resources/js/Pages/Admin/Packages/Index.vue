<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { Package } from '@/types';
import { Link, router } from '@inertiajs/vue3';

defineProps<{
    packages: Package[];
}>();

function destroy(pkg: Package) {
    if (!confirm(`Deactivate "${pkg.name}"?`)) return;
    router.delete(route('admin.packages.destroy', pkg.id));
}
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <h1 class="text-lg font-semibold text-gray-800">Packages</h1>
                <Link
                    :href="route('admin.packages.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"
                >
                    + New Package
                </Link>
            </div>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="pkg in packages"
                :key="pkg.id"
                class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm"
                :class="{ 'opacity-60': !pkg.is_active }"
            >
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ pkg.name }}</h3>
                        <p v-if="pkg.description" class="text-xs text-gray-500 mt-1">{{ pkg.description }}</p>
                    </div>
                    <span
                        class="text-xs px-2 py-1 rounded-full"
                        :class="pkg.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                    >
                        {{ pkg.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <p class="text-2xl font-bold text-blue-700 mb-3">RM {{ Number(pkg.price).toFixed(2) }}</p>
                <ul class="space-y-1 mb-4">
                    <li v-for="item in pkg.items" :key="item" class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-500">✓</span> {{ item }}
                    </li>
                </ul>
                <div class="flex gap-2">
                    <Link
                        :href="route('admin.packages.edit', pkg.id)"
                        class="flex-1 text-center py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition"
                    >
                        Edit
                    </Link>
                    <button
                        @click="destroy(pkg)"
                        class="flex-1 py-2 bg-red-50 text-red-600 rounded-lg text-sm hover:bg-red-100 transition"
                    >
                        Deactivate
                    </button>
                </div>
            </div>

            <div v-if="!packages.length" class="col-span-full text-center py-12 text-gray-400">
                No packages yet.
                <Link :href="route('admin.packages.create')" class="text-blue-600 hover:underline ml-1">Create one.</Link>
            </div>
        </div>
    </AdminLayout>
</template>
