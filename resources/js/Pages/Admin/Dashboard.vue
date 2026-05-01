<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { Booking } from '@/types';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';

defineProps<{
    todayBookings: number;
    pendingPayments: number;
    totalRevenue: number;
    recentBookings: Booking[];
}>();

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
            <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
        </template>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <p class="text-sm text-gray-500">Today's Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ todayBookings }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <p class="text-sm text-gray-500">Pending Payments</p>
                <p class="text-3xl font-bold text-amber-600 mt-1">{{ pendingPayments }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <p class="text-sm text-gray-500">Total Revenue</p>
                <p class="text-3xl font-bold text-green-600 mt-1">RM {{ Number(totalRevenue).toFixed(2) }}</p>
            </div>
        </div>

        <!-- Recent bookings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Recent Bookings</h2>
                <Link :href="route('admin.bookings.index')" class="text-sm text-blue-600 hover:underline">
                    View all
                </Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="text-left px-6 py-3">Booking #</th>
                            <th class="text-left px-6 py-3">Customer</th>
                            <th class="text-left px-6 py-3">Package</th>
                            <th class="text-left px-6 py-3">Date</th>
                            <th class="text-left px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="b in recentBookings" :key="b.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-medium">
                                <Link :href="route('admin.bookings.show', b.id)" class="text-blue-600 hover:underline">
                                    {{ b.booking_number }}
                                </Link>
                            </td>
                            <td class="px-6 py-4">{{ b.customer_name }}</td>
                            <td class="px-6 py-4">{{ b.package?.name }}</td>
                            <td class="px-6 py-4">{{ dayjs(b.booking_date).format('D MMM YYYY') }}</td>
                            <td class="px-6 py-4">
                                <span :class="statusColors[b.status]" class="px-2 py-1 rounded-full text-xs font-medium capitalize">
                                    {{ b.status.replace('_', ' ') }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="!recentBookings.length">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No bookings yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
