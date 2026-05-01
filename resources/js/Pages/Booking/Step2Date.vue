<script setup lang="ts">
import BookingCalendar from '@/Components/BookingCalendar.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { Package } from '@/types';
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps<{
    package: Package;
}>();

const form = useForm({
    package_id:     props.package.id,
    booking_date:   '',
    customer_name:  '',
    customer_email: '',
    customer_phone: '',
    vehicle_plate:  '',
    vehicle_model:  '',
    notes:          '',
});

function submit() {
    form.post(route('booking.store'));
}
</script>

<template>
    <PublicLayout>
        <div class="max-w-3xl mx-auto">
            <!-- Step indicator -->
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-8">
                <span class="text-gray-400">1. Choose Package</span>
                <span>&rsaquo;</span>
                <span class="font-semibold text-blue-600">2. Select Date &amp; Details</span>
                <span>&rsaquo;</span>
                <span>3. Payment</span>
            </div>

            <!-- Selected package summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-center justify-between">
                <div>
                    <p class="font-semibold text-blue-900">{{ package.name }}</p>
                    <p class="text-sm text-blue-600">{{ package.items.join(', ') }}</p>
                </div>
                <p class="text-xl font-bold text-blue-700">RM {{ Number(package.price).toFixed(2) }}</p>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Calendar -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Select Appointment Date</h2>
                    <BookingCalendar
                        :package-id="package.id"
                        v-model="form.booking_date"
                    />
                    <p v-if="form.errors.booking_date" class="text-red-500 text-sm mt-1">
                        {{ form.errors.booking_date }}
                    </p>
                    <p v-if="form.booking_date" class="text-sm text-green-700 mt-2 font-medium">
                        Selected: {{ dayjs(form.booking_date).format('dddd, D MMMM YYYY') }}
                    </p>
                </div>

                <!-- Customer info -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input
                                v-model="form.customer_name"
                                type="text"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ahmad bin Ali"
                            />
                            <p v-if="form.errors.customer_name" class="text-red-500 text-xs mt-1">{{ form.errors.customer_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input
                                v-model="form.customer_email"
                                type="email"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="ahmad@email.com"
                            />
                            <p v-if="form.errors.customer_email" class="text-red-500 text-xs mt-1">{{ form.errors.customer_email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input
                                v-model="form.customer_phone"
                                type="tel"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="01X-XXXXXXX"
                            />
                            <p v-if="form.errors.customer_phone" class="text-red-500 text-xs mt-1">{{ form.errors.customer_phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Plate</label>
                            <input
                                v-model="form.vehicle_plate"
                                type="text"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase"
                                placeholder="ABC 1234"
                            />
                            <p v-if="form.errors.vehicle_plate" class="text-red-500 text-xs mt-1">{{ form.errors.vehicle_plate }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Model</label>
                            <input
                                v-model="form.vehicle_model"
                                type="text"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Perodua Myvi 2020"
                            />
                            <p v-if="form.errors.vehicle_model" class="text-red-500 text-xs mt-1">{{ form.errors.vehicle_model }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                            <textarea
                                v-model="form.notes"
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Any special requests or notes…"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <a :href="route('booking.packages')" class="px-6 py-3 border border-gray-300 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition">
                        &larr; Back
                    </a>
                    <button
                        type="submit"
                        :disabled="form.processing || !form.booking_date"
                        class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 disabled:opacity-50 transition"
                    >
                        {{ form.processing ? 'Processing…' : 'Continue to Payment →' }}
                    </button>
                </div>
            </form>
        </div>
    </PublicLayout>
</template>
