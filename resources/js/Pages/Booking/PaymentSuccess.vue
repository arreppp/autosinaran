<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { Booking } from '@/types';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps<{
    booking: Booking;
}>();

function formatTime(time: string | null): string {
    if (!time) return '';
    const [h, m] = time.split(':').map(Number);
    const period = h >= 12 ? 'PM' : 'AM';
    const display = h > 12 ? h - 12 : h || 12;
    return `${display}:${m.toString().padStart(2, '0')} ${period}`;
}
</script>

<template>
    <PublicLayout>
        <div class="max-w-lg mx-auto text-center py-8">
            <!-- Success icon -->
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Payment Successful!</h1>
            <p class="text-gray-500 mb-8">
                Your booking is confirmed. A confirmation has been sent to
                <strong class="text-gray-700">{{ booking.customer_email }}</strong>.
            </p>

            <!-- Booking receipt card -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 text-left shadow-sm mb-6">
                <div class="text-center mb-5">
                    <span class="inline-block bg-green-100 text-green-800 text-xs font-bold px-4 py-1.5 rounded-full tracking-wide uppercase">
                        Confirmed
                    </span>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Booking No.</span>
                        <span class="font-bold text-gray-900">{{ booking.booking_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Package</span>
                        <span class="font-medium">{{ booking.package?.name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Includes</span>
                        <span class="text-right max-w-[200px]">{{ booking.package?.items.join(', ') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date</span>
                        <span class="font-medium">{{ dayjs(booking.booking_date).format('D MMMM YYYY') }}</span>
                    </div>
                    <div v-if="booking.booking_time" class="flex justify-between">
                        <span class="text-gray-500">Time</span>
                        <span class="font-medium">{{ formatTime(booking.booking_time) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Vehicle</span>
                        <span>{{ booking.vehicle_model }} ({{ booking.vehicle_plate }})</span>
                    </div>
                    <div class="flex justify-between border-t pt-3 mt-3">
                        <span class="font-semibold text-gray-900">Amount Paid</span>
                        <span class="text-xl font-bold text-green-700">
                            RM {{ Number(booking.package?.price).toFixed(2) }}
                        </span>
                    </div>
                    <div v-if="booking.payment?.transaction_ref" class="flex justify-between">
                        <span class="text-gray-500">Transaction Ref.</span>
                        <span class="font-mono text-xs text-gray-700">{{ booking.payment.transaction_ref }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a
                    :href="route('booking.receipt', booking.id)"
                    target="_blank"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Receipt
                </a>
                <Link
                    :href="route('home')"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition"
                >
                    Back to Home
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>
