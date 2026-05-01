<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { BlockedDate } from '@/types';
import { useForm, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

defineProps<{
    blockedDates: BlockedDate[];
}>();

const form = useForm({
    date:   '',
    reason: '',
});

function submit() {
    form.post(route('admin.blocked-dates.store'), {
        onSuccess: () => form.reset(),
    });
}

function remove(bd: BlockedDate) {
    if (!confirm(`Unblock ${dayjs(bd.date).format('D MMMM YYYY')}?`)) return;
    router.delete(route('admin.blocked-dates.destroy', bd.id));
}
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Blocked Dates</h1>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add form -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="font-semibold text-gray-800 mb-4">Block a Date</h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input
                            v-model="form.date"
                            type="date"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        />
                        <p v-if="form.errors.date" class="text-red-500 text-xs mt-1">{{ form.errors.date }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason (optional)</label>
                        <input
                            v-model="form.reason"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="e.g. Public Holiday, Workshop Closed"
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing || !form.date"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 disabled:opacity-50"
                    >
                        Block Date
                    </button>
                </form>
            </div>

            <!-- List -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Blocked Dates</h2>
                </div>
                <ul class="divide-y divide-gray-100">
                    <li
                        v-for="bd in blockedDates"
                        :key="bd.id"
                        class="flex items-center justify-between px-6 py-4 text-sm"
                    >
                        <div>
                            <p class="font-medium text-gray-900">{{ dayjs(bd.date).format('dddd, D MMMM YYYY') }}</p>
                            <p v-if="bd.reason" class="text-xs text-gray-500">{{ bd.reason }}</p>
                        </div>
                        <button
                            @click="remove(bd)"
                            class="text-red-500 hover:text-red-700 text-xs px-3 py-1 rounded border border-red-200 hover:bg-red-50 transition"
                        >
                            Unblock
                        </button>
                    </li>
                    <li v-if="!blockedDates.length" class="px-6 py-8 text-center text-gray-400">
                        No blocked dates.
                    </li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
