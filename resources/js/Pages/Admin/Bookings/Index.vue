<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { Booking, Package, PaginatedData } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { ref } from 'vue';

const props = defineProps<{
    bookings: PaginatedData<Booking>;
    filters: { status?: string; date?: string; package_id?: string };
}>();

const statusFilter = ref(props.filters.status ?? '');
const dateFilter   = ref(props.filters.date ?? '');

function applyFilters() {
    router.get(route('admin.bookings.index'), {
        status: statusFilter.value || undefined,
        date:   dateFilter.value || undefined,
    }, { preserveState: true, replace: true });
}

function clearFilters() {
    statusFilter.value = '';
    dateFilter.value   = '';
    router.get(route('admin.bookings.index'));
}

const statusColors: Record<string, string> = {
    pending_payment: 'bg-yellow-100 text-yellow-800',
    payment_review:  'bg-blue-100 text-blue-800',
    confirmed:       'bg-green-100 text-green-800',
    cancelled:       'bg-red-100 text-red-800',
    completed:       'bg-gray-100 text-gray-800',
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Bookings</h1>
        </template>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select v-model="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All</option>
                    <option value="pending_payment">Pending Payment</option>
                    <option value="payment_review">Payment Review</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Date</label>
                <input v-model="dateFilter" type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm" />
            </div>
            <button @click="applyFilters" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                Filter
            </button>
            <button @click="clearFilters" class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                Clear
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="text-left px-6 py-3">Booking #</th>
                            <th class="text-left px-6 py-3">Customer</th>
                            <th class="text-left px-6 py-3">Package</th>
                            <th class="text-left px-6 py-3">Date</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-left px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="b in bookings.data" :key="b.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-medium">{{ b.booking_number }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium">{{ b.customer_name }}</p>
                                <p class="text-xs text-gray-400">{{ b.customer_phone }}</p>
                            </td>
                            <td class="px-6 py-4">{{ b.package?.name }}</td>
                            <td class="px-6 py-4">{{ dayjs(b.booking_date).format('D MMM YYYY') }}</td>
                            <td class="px-6 py-4">
                                <span :class="statusColors[b.status]" class="px-2 py-1 rounded-full text-xs font-medium capitalize">
                                    {{ b.status.replace('_', ' ') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <Link :href="route('admin.bookings.show', b.id)" class="text-blue-600 hover:underline text-xs">
                                    View
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="!bookings.data.length">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">No bookings found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="bookings.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Showing {{ bookings.from }}–{{ bookings.to }} of {{ bookings.total }}</span>
                <div class="flex gap-1">
                    <template v-for="link in bookings.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="px-3 py-1 rounded border"
                            :class="link.active ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 hover:bg-gray-50'"
                            v-html="link.label"
                        />
                        <span v-else class="px-3 py-1 text-gray-300" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
