<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { Booking } from '@/types';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';

defineProps<{
    booking: Booking;
}>();
</script>

<template>
    <PublicLayout>
        <div class="max-w-lg mx-auto text-center py-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Submitted!</h1>
            <p class="text-gray-500 mb-8">
                Our team will review your payment and confirm your booking within 24 hours.
                Check your email at <strong>{{ booking.customer_email }}</strong>.
            </p>

            <!-- Booking card -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 text-left shadow-sm mb-6">
                <div class="text-center mb-4">
                    <span class="inline-block bg-amber-100 text-amber-800 text-xs font-semibold px-4 py-1 rounded-full">
                        Pending Review
                    </span>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Booking No.</span>
                        <span class="font-bold text-gray-900">{{ booking.booking_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Package</span>
                        <span>{{ booking.package?.name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Includes</span>
                        <span class="text-right">{{ booking.package?.items.join(', ') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date</span>
                        <span>{{ dayjs(booking.booking_date).format('D MMMM YYYY') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Vehicle</span>
                        <span>{{ booking.vehicle_model }} ({{ booking.vehicle_plate }})</span>
                    </div>
                </div>
            </div>

            <Link
                :href="route('home')"
                class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition"
            >
                Make Another Booking
            </Link>
        </div>
    </PublicLayout>
</template>
