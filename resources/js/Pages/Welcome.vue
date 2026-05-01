<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { Package } from '@/types';
import { Link } from '@inertiajs/vue3';

defineProps<{
    packages: Package[];
}>();
</script>

<template>
    <PublicLayout>
        <!-- Hero -->
        <section class="text-center py-16 px-4">
            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-sm font-medium px-4 py-2 rounded-full mb-6">
                <span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                Trusted Workshop in Sinaran
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">
                Professional Car Service<br />
                <span class="text-blue-600">You Can Trust</span>
            </h1>
            <p class="text-lg text-gray-500 max-w-xl mx-auto mb-8">
                Book your vehicle service online in minutes. Quality workmanship, transparent pricing, and convenient scheduling.
            </p>
            <Link
                :href="route('booking.packages')"
                class="inline-block px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200"
            >
                Book a Service Now &rarr;
            </Link>
        </section>

        <!-- How It Works -->
        <section class="py-12 border-t border-gray-100">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-10">How It Works</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="text-center p-6">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-5xl font-black text-blue-50 leading-none mb-2">01</p>
                    <h3 class="font-semibold text-gray-900 mb-1">Choose a Package</h3>
                    <p class="text-sm text-gray-500">Pick the service package that suits your vehicle's needs.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-5xl font-black text-blue-50 leading-none mb-2">02</p>
                    <h3 class="font-semibold text-gray-900 mb-1">Select Date &amp; Time</h3>
                    <p class="text-sm text-gray-500">Choose your preferred date and appointment slot.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <p class="text-5xl font-black text-blue-50 leading-none mb-2">03</p>
                    <h3 class="font-semibold text-gray-900 mb-1">Pay Securely</h3>
                    <p class="text-sm text-gray-500">Complete payment online via Billplz. Instant confirmation.</p>
                </div>
            </div>
        </section>

        <!-- Packages Preview -->
        <section class="py-12 border-t border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Our Service Packages</h2>
                <Link :href="route('booking.packages')" class="text-sm text-blue-600 hover:underline font-medium">
                    View all &rarr;
                </Link>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                    v-for="pkg in packages"
                    :key="pkg.id"
                    class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition"
                >
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-semibold text-gray-900 text-lg">{{ pkg.name }}</h3>
                        <span class="text-blue-700 font-bold text-lg whitespace-nowrap ml-4">
                            RM {{ Number(pkg.price).toFixed(2) }}
                        </span>
                    </div>
                    <p v-if="pkg.description" class="text-sm text-gray-500 mb-3">{{ pkg.description }}</p>
                    <div class="flex flex-wrap gap-1">
                        <span
                            v-for="item in pkg.items"
                            :key="item"
                            class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-full"
                        >
                            {{ item }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <Link
                    :href="route('booking.packages')"
                    class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition"
                >
                    Book Now &rarr;
                </Link>
            </div>
        </section>

        <!-- Why Us -->
        <section class="py-12 border-t border-gray-100">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">Why Choose Auto Sinaran?</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Certified Mechanics</p>
                    <p class="text-xs text-gray-400 mt-1">Trained &amp; experienced</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Transparent Pricing</p>
                    <p class="text-xs text-gray-400 mt-1">No hidden charges</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Fast Service</p>
                    <p class="text-xs text-gray-400 mt-1">On-time completion</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Customer First</p>
                    <p class="text-xs text-gray-400 mt-1">Your satisfaction guaranteed</p>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="py-12 border-t border-gray-100">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">Find Us</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 text-sm">Address</p>
                    <p class="text-xs text-gray-500 mt-1">Jalan Sinaran, Kuala Lumpur</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 text-sm">Operating Hours</p>
                    <p class="text-xs text-gray-500 mt-1">Mon – Sat: 8:00 AM – 5:00 PM</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 text-sm">Phone</p>
                    <p class="text-xs text-gray-500 mt-1">+60 3-XXXX XXXX</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
