<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { Booking } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { ref } from 'vue';

const props = defineProps<{
    booking: Booking;
}>();

const rejectReason = ref('');
const showRejectModal = ref(false);

function approve() {
    if (!confirm('Confirm this payment and approve the booking?')) return;
    router.patch(route('admin.bookings.approve', props.booking.id));
}

function reject() {
    router.patch(route('admin.bookings.reject', props.booking.id), { reason: rejectReason.value });
    showRejectModal.value = false;
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
            <div class="flex items-center gap-4">
                <Link :href="route('admin.bookings.index')" class="text-sm text-gray-500 hover:text-gray-800">
                    &larr; Bookings
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">{{ booking.booking_number }}</h1>
                <span :class="statusColors[booking.status]" class="px-3 py-1 rounded-full text-xs font-medium capitalize">
                    {{ booking.status.replace('_', ' ') }}
                </span>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: booking details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-800 mb-4">Customer Information</h2>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Name</dt>
                            <dd class="font-medium">{{ booking.customer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Email</dt>
                            <dd class="font-medium">{{ booking.customer_email }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Phone</dt>
                            <dd class="font-medium">{{ booking.customer_phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Vehicle</dt>
                            <dd class="font-medium">{{ booking.vehicle_model }} ({{ booking.vehicle_plate }})</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Appointment Date</dt>
                            <dd class="font-medium">{{ dayjs(booking.booking_date).format('dddd, D MMMM YYYY') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Package</dt>
                            <dd class="font-medium">{{ booking.package?.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Includes</dt>
                            <dd class="font-medium">{{ booking.package?.items.join(', ') }}</dd>
                        </div>
                        <div v-if="booking.notes">
                            <dt class="text-gray-500">Notes</dt>
                            <dd>{{ booking.notes }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Payment proof -->
                <div v-if="booking.payment" class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-800 mb-4">Payment</h2>
                    <dl class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <dt class="text-gray-500">Amount</dt>
                            <dd class="font-bold text-lg text-green-700">RM {{ Number(booking.payment.amount).toFixed(2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Method</dt>
                            <dd class="capitalize">{{ booking.payment.method.replace('_', ' ') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Payment Status</dt>
                            <dd class="capitalize font-medium">{{ booking.payment.status }}</dd>
                        </div>
                        <div v-if="booking.payment.paid_at">
                            <dt class="text-gray-500">Paid At</dt>
                            <dd>{{ dayjs(booking.payment.paid_at).format('D MMM YYYY HH:mm') }}</dd>
                        </div>
                    </dl>
                    <div v-if="booking.payment.payment_proof_path">
                        <p class="text-sm text-gray-500 mb-2">Payment Proof</p>
                        <a
                            :href="`/admin/bookings/${booking.id}/proof`"
                            target="_blank"
                            class="inline-block text-blue-600 hover:underline text-sm"
                        >
                            View proof document
                        </a>
                    </div>
                </div>
                <div v-else class="bg-white rounded-xl border border-gray-200 p-6 text-sm text-gray-400">
                    No payment submitted yet.
                </div>
            </div>

            <!-- Right: actions -->
            <div class="space-y-4">
                <div v-if="booking.status === 'payment_review'" class="bg-white rounded-xl border border-gray-200 p-6 space-y-3">
                    <h2 class="font-semibold text-gray-800 mb-2">Actions</h2>
                    <button
                        @click="approve"
                        class="w-full py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition text-sm"
                    >
                        Approve Payment
                    </button>
                    <button
                        @click="showRejectModal = true"
                        class="w-full py-2 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition text-sm"
                    >
                        Reject Payment
                    </button>
                </div>

                <div v-if="booking.status === 'confirmed'" class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-800 mb-2">Actions</h2>
                    <Link
                        :href="route('admin.bookings.destroy', booking.id)"
                        method="delete"
                        as="button"
                        class="w-full py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition text-sm"
                    >
                        Mark Cancelled
                    </Link>
                </div>
            </div>
        </div>

        <!-- Reject modal -->
        <div v-if="showRejectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                <h3 class="font-semibold text-gray-900 mb-4">Reject Payment</h3>
                <textarea
                    v-model="rejectReason"
                    rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-4"
                    placeholder="Reason for rejection (optional)"
                />
                <div class="flex justify-end gap-3">
                    <button @click="showRejectModal = false" class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="reject" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
