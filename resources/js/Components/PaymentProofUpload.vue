<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
    modelValue: File | null;
    error?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [file: File | null];
}>();

const dragOver = ref(false);
const preview = ref<string | null>(null);

function handleFile(file: File | null) {
    if (!file) return;
    emit('update:modelValue', file);
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => { preview.value = e.target?.result as string; };
        reader.readAsDataURL(file);
    } else {
        preview.value = null;
    }
}

function onInput(e: Event) {
    const input = e.target as HTMLInputElement;
    handleFile(input.files?.[0] ?? null);
}

function onDrop(e: DragEvent) {
    dragOver.value = false;
    handleFile(e.dataTransfer?.files?.[0] ?? null);
}
</script>

<template>
    <div>
        <label
            class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer transition"
            :class="dragOver ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100'"
            @dragover.prevent="dragOver = true"
            @dragleave="dragOver = false"
            @drop.prevent="onDrop"
        >
            <div v-if="!modelValue" class="text-center">
                <p class="text-sm text-gray-500">Drag & drop or <span class="text-blue-600 underline">browse</span></p>
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF — max 5 MB</p>
            </div>
            <div v-else class="text-center">
                <img v-if="preview" :src="preview" class="max-h-28 rounded mx-auto" />
                <p v-else class="text-sm text-green-700 font-medium">{{ modelValue.name }}</p>
                <p class="text-xs text-gray-400 mt-1">Click to change</p>
            </div>
            <input type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" @change="onInput" />
        </label>
        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>
