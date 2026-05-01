<script setup lang="ts">
import type { AvailabilityMap, DateAvailability } from '@/types';
import dayjs from 'dayjs';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    packageId: number;
    modelValue: string | null;
}>();

const emit = defineEmits<{
    'update:modelValue': [date: string];
}>();

const currentMonth = ref(dayjs().startOf('month'));
const availability = ref<AvailabilityMap>({});
const loading = ref(false);

const monthLabel = computed(() => currentMonth.value.format('MMMM YYYY'));

const daysInMonth = computed(() => {
    const days: { date: string; dayNum: number; status: DateAvailability }[] = [];
    const start = currentMonth.value.startOf('month');
    const total = currentMonth.value.daysInMonth();
    for (let i = 0; i < total; i++) {
        const d = start.add(i, 'day');
        const dateStr = d.format('YYYY-MM-DD');
        days.push({
            date: dateStr,
            dayNum: d.date(),
            status: availability.value[dateStr] ?? 'available',
        });
    }
    return days;
});

const startPadding = computed(() => {
    // Sunday = 0, make Monday = 0
    const dow = currentMonth.value.day();
    return dow === 0 ? 6 : dow - 1;
});

async function fetchAvailability() {
    loading.value = true;
    try {
        const res = await fetch(
            `/api/availability/${props.packageId}?month=${currentMonth.value.format('YYYY-MM')}`
        );
        availability.value = await res.json();
    } finally {
        loading.value = false;
    }
}

function prevMonth() {
    const prev = currentMonth.value.subtract(1, 'month');
    if (prev.isBefore(dayjs().startOf('month'))) return;
    currentMonth.value = prev;
}

function nextMonth() {
    currentMonth.value = currentMonth.value.add(1, 'month');
}

function selectDate(day: { date: string; status: DateAvailability }) {
    if (day.status !== 'available') return;
    emit('update:modelValue', day.date);
}

function dayClasses(day: { date: string; status: DateAvailability }) {
    const base = 'h-10 w-full rounded-lg text-sm font-medium flex items-center justify-center transition-all ';
    if (day.status === 'past' || day.status === 'blocked') {
        return base + 'bg-gray-100 text-gray-400 cursor-not-allowed line-through';
    }
    if (day.status === 'full') {
        return base + 'bg-red-100 text-red-400 cursor-not-allowed';
    }
    if (props.modelValue === day.date) {
        return base + 'bg-blue-600 text-white shadow';
    }
    return base + 'bg-green-50 text-green-800 hover:bg-green-100 cursor-pointer border border-green-200';
}

watch(() => [props.packageId, currentMonth.value], fetchAvailability, { immediate: true });
</script>

<template>
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <button
                type="button"
                class="p-2 rounded-lg hover:bg-gray-100 transition text-gray-600"
                @click="prevMonth"
            >
                &#8249;
            </button>
            <span class="font-semibold text-gray-800">{{ monthLabel }}</span>
            <button
                type="button"
                class="p-2 rounded-lg hover:bg-gray-100 transition text-gray-600"
                @click="nextMonth"
            >
                &#8250;
            </button>
        </div>

        <!-- Day labels -->
        <div class="grid grid-cols-7 mb-2">
            <div
                v-for="d in ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']"
                :key="d"
                class="text-center text-xs text-gray-400 font-medium py-1"
            >
                {{ d }}
            </div>
        </div>

        <!-- Loading overlay -->
        <div v-if="loading" class="text-center py-8 text-gray-400 text-sm">Loading…</div>

        <!-- Days grid -->
        <div v-else class="grid grid-cols-7 gap-1">
            <div v-for="_ in startPadding" :key="'pad-' + _" />
            <div
                v-for="day in daysInMonth"
                :key="day.date"
                :class="dayClasses(day)"
                @click="selectDate(day)"
            >
                {{ day.dayNum }}
            </div>
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap gap-4 mt-4 text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <span class="inline-block w-3 h-3 rounded bg-green-100 border border-green-200" /> Available
            </span>
            <span class="flex items-center gap-1">
                <span class="inline-block w-3 h-3 rounded bg-red-100" /> Full
            </span>
            <span class="flex items-center gap-1">
                <span class="inline-block w-3 h-3 rounded bg-gray-100" /> Blocked / Past
            </span>
        </div>
    </div>
</template>
