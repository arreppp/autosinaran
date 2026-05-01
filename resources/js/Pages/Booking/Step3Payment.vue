<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { Booking } from '@/types';
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps<{
    booking: Booking;
}>();

const form = useForm({});

function pay() {
    form.post(route('booking.payment.initiate', props.booking.id));
}

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
        <div class="max-w-2xl mx-auto">
            <!-- Step indicator -->
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-8">
                <span class="text-gray-400">1. Choose Package</span>
                <span>&rsaquo;</span>
                <span class="text-gray-400">2. Select Date</span>
                <span>&rsaquo;</span>
                <span class="font-semibold text-blue-600">3. Payment</span>
            </div>

            <!-- Booking summary -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking Summary</h2>
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
                        <span class="text-gray-500">Name</span>
                        <span>{{ booking.customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Vehicle</span>
                        <span>{{ booking.vehicle_model }} ({{ booking.vehicle_plate }})</span>
                    </div>
                    <div class="flex justify-between border-t pt-3 mt-3">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="text-xl font-bold text-blue-700">
                            RM {{ Number(booking.package?.price).toFixed(2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Billplz info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6 flex items-start gap-4">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-blue-900 mb-1">Secure Payment via Billplz</p>
                    <p class="text-sm text-blue-700">
                        You will be redirected to Billplz to complete your payment securely.
                        Supports FPX online banking, credit/debit card, and more.
                    </p>
                </div>
            </div>

            <!-- Pay button -->
            <div class="flex justify-between items-center">
                <a
                    :href="route('booking.packages')"
                    class="px-6 py-3 border border-gray-300 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition"
                >
                    &larr; Back
                </a>
                <button
                    type="button"
                    :disabled="form.processing"
                    @click="pay"
                    class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 disabled:opacity-50 transition flex items-center gap-2"
                >
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    {{ form.processing ? 'Redirecting to Billplz…' : 'Pay Now with Billplz →' }}
                </button>
            </div>
        </div>
    </PublicLayout>
</template>
