<script setup lang="ts">
import PaymentProofUpload from '@/Components/PaymentProofUpload.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { Booking } from '@/types';
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps<{
    booking: Booking;
}>();

const form = useForm<{ payment_proof: File | null }>({
    payment_proof: null,
});

function submit() {
    form.post(route('booking.payment.upload', props.booking.id), {
        forceFormData: true,
    });
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
                        <span class="font-semibold text-gray-900">{{ booking.booking_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Package</span>
                        <span class="font-medium">{{ booking.package?.name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Includes</span>
                        <span class="text-right">{{ booking.package?.items.join(', ') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date</span>
                        <span class="font-medium">{{ dayjs(booking.booking_date).format('D MMMM YYYY') }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3 mt-3">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="text-xl font-bold text-blue-700">
                            RM {{ Number(booking.package?.price).toFixed(2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bank transfer info -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-6">
                <h3 class="font-semibold text-amber-900 mb-3">Bank Transfer Details</h3>
                <div class="space-y-2 text-sm text-amber-800">
                    <div class="flex justify-between">
                        <span>Bank</span>
                        <span class="font-semibold">Maybank</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Account Name</span>
                        <span class="font-semibold">Auto Sinaran Workshop</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Account Number</span>
                        <span class="font-semibold tracking-wider">1234 5678 9012</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Reference</span>
                        <span class="font-semibold">{{ booking.booking_number }}</span>
                    </div>
                </div>
                <p class="text-xs text-amber-700 mt-3">
                    Please use your booking number as the transfer reference.
                </p>
            </div>

            <!-- Upload proof -->
            <form @submit.prevent="submit">
                <h3 class="font-semibold text-gray-900 mb-3">Upload Payment Proof</h3>
                <PaymentProofUpload
                    v-model="form.payment_proof"
                    :error="form.errors.payment_proof"
                />

                <div class="mt-6 flex justify-end">
                    <button
                        type="submit"
                        :disabled="form.processing || !form.payment_proof"
                        class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 disabled:opacity-50 transition"
                    >
                        {{ form.processing ? 'Uploading…' : 'Submit Payment Proof →' }}
                    </button>
                </div>
            </form>
        </div>
    </PublicLayout>
</template>
