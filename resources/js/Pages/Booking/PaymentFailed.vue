<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { Booking } from '@/types';
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps<{
    booking: Booking;
}>();

const form = useForm({});

function retryPayment() {
    form.post(route('booking.payment.initiate', props.booking.id));
}
</script>

<template>
    <PublicLayout>
        <div class="max-w-lg mx-auto text-center py-8">
            <!-- Failed icon -->
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Payment Failed</h1>
            <p class="text-gray-500 mb-8">
                Your payment was not completed. Your booking slot is still reserved — you can try again.
            </p>

            <!-- Booking summary card -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 text-left shadow-sm mb-6">
                <div class="text-center mb-5">
                    <span class="inline-block bg-red-100 text-red-800 text-xs font-bold px-4 py-1.5 rounded-full tracking-wide uppercase">
                        Payment Incomplete
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
                        <span class="text-gray-500">Date</span>
                        <span class="font-medium">{{ dayjs(booking.booking_date).format('D MMMM YYYY') }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3 mt-3">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="text-xl font-bold text-gray-700">
                            RM {{ Number(booking.package?.price).toFixed(2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button
                    type="button"
                    :disabled="form.processing"
                    @click="retryPayment"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 disabled:opacity-50 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ form.processing ? 'Redirecting…' : 'Try Again' }}
                </button>
                <a
                    :href="route('home')"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition"
                >
                    Back to Home
                </a>
            </div>
        </div>
    </PublicLayout>
</template>
