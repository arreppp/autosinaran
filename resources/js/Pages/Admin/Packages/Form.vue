<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { Package } from '@/types';
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    package: Package | null;
}>();

const isEdit = !!props.package;

const form = useForm({
    name:        props.package?.name ?? '',
    description: props.package?.description ?? '',
    price:       props.package?.price ?? '',
    items:       props.package?.items ?? [''],
    is_active:   props.package?.is_active ?? true,
});

function addItem() {
    form.items.push('');
}

function removeItem(i: number) {
    if (form.items.length <= 1) return;
    form.items.splice(i, 1);
}

function submit() {
    if (isEdit) {
        form.put(route('admin.packages.update', props.package!.id));
    } else {
        form.post(route('admin.packages.store'));
    }
}
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('admin.packages.index')" class="text-sm text-gray-500 hover:text-gray-800">
                    &larr; Packages
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">{{ isEdit ? 'Edit Package' : 'New Package' }}</h1>
            </div>
        </template>

        <div class="max-w-xl">
            <form @submit.prevent="submit" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Package Name</label>
                    <input v-model="form.name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="e.g. Basic Service" />
                    <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
                    <textarea v-model="form.description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (RM)</label>
                    <input v-model="form.price" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00" />
                    <p v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">What's Included</label>
                    <div v-for="(item, i) in form.items" :key="i" class="flex gap-2 mb-2">
                        <input
                            v-model="form.items[i]"
                            type="text"
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="e.g. Engine Oil"
                        />
                        <button
                            type="button"
                            @click="removeItem(i)"
                            class="px-3 py-2 text-red-400 hover:text-red-600 disabled:opacity-30"
                            :disabled="form.items.length <= 1"
                        >
                            &times;
                        </button>
                    </div>
                    <button
                        type="button"
                        @click="addItem"
                        class="text-sm text-blue-600 hover:underline"
                    >
                        + Add item
                    </button>
                    <p v-if="form.errors.items" class="text-red-500 text-xs mt-1">{{ form.errors.items }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <input v-model="form.is_active" type="checkbox" id="is_active" class="rounded" />
                    <label for="is_active" class="text-sm text-gray-700">Active (visible to customers)</label>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <Link :href="route('admin.packages.index')" class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving…' : (isEdit ? 'Update Package' : 'Create Package') }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
