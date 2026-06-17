<script setup>
import { ref } from "vue";

import { ImagePlus, Upload, Trash2 } from "lucide-vue-next";

const props = defineProps({
    modelValue: File,
    preview: String,
});

const emit = defineEmits(["update:modelValue"]);

const imageUrl = ref(props.preview ?? null);

const fileInput = ref(null);

const isDragging = ref(false);

function processFile(file) {
    if (!file) return;

    emit("update:modelValue", file);

    imageUrl.value = URL.createObjectURL(file);
}

function handleChange(e) {
    processFile(e.target.files?.[0]);
}

function handleDrop(e) {
    isDragging.value = false;

    processFile(e.dataTransfer.files?.[0]);
}

function removeImage() {
    emit("update:modelValue", null);

    imageUrl.value = null;

    if (fileInput.value) {
        fileInput.value.value = "";
    }
}
</script>

<template>
    <div>
        <input
            ref="fileInput"
            type="file"
            accept="image/*"
            class="hidden"
            @change="handleChange"
        />

        <!-- HAS IMAGE -->

        <div
            v-if="imageUrl"
            class="group relative overflow-hidden rounded-lg border border-gray-200 dark:border-slate-700"
        >
            <img :src="imageUrl" class="w-full h-60 object-cover" />

            <!-- Overlay -->

            <div
                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3"
            >
                <button
                    @click="fileInput.click()"
                    class="px-4 py-2 rounded-lg bg-white text-gray-900 text-sm font-medium"
                >
                    Ganti
                </button>

                <button
                    @click="removeImage"
                    class="p-2 rounded-lg bg-red-600 text-white"
                >
                    <Trash2 class="w-4 h-4" />
                </button>
            </div>
        </div>

        <!-- EMPTY STATE -->

        <div
            v-else
            @click="fileInput.click()"
            @dragover.prevent="isDragging = true"
            @dragleave="isDragging = false"
            @drop.prevent="handleDrop"
            class="cursor-pointer border-2 border-dashed rounded-lg transition-all p-8 text-center"
            :class="
                isDragging
                    ? 'border-blue-500 bg-blue-50 dark:bg-blue-950/20'
                    : 'border-gray-300 dark:border-slate-700 hover:border-blue-500'
            "
        >
            <div class="flex flex-col items-center">
                <div
                    class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4"
                >
                    <ImagePlus class="w-8 h-8 text-blue-600" />
                </div>

                <h4 class="font-semibold text-gray-900 dark:text-white">
                    Upload Thumbnail
                </h4>

                <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">
                    Drag & drop gambar atau klik untuk memilih
                </p>

                <div
                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white"
                >
                    <Upload class="w-4 h-4" />

                    Pilih Gambar
                </div>

                <p class="mt-4 text-xs text-gray-400">
                    JPG, PNG, WEBP • Maks 2 MB
                </p>
            </div>
        </div>
    </div>
</template>
