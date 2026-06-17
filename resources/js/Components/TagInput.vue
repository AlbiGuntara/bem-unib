<script setup>
import { ref } from "vue";
import { X, Hash } from "lucide-vue-next";

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["update:modelValue"]);

const tag = ref("");

function addTag() {
    const value = tag.value.trim();

    if (!value) return;

    if (props.modelValue.includes(value)) {
        tag.value = "";
        return;
    }

    emit("update:modelValue", [...props.modelValue, value]);

    tag.value = "";
}

function handleKeydown(e) {
    if (e.key === "Enter" || e.key === ",") {
        e.preventDefault();

        addTag();
    }
}

function remove(index) {
    const tags = [...props.modelValue];

    tags.splice(index, 1);

    emit("update:modelValue", tags);
}
</script>

<template>
    <div class="space-y-4">
        <!-- INPUT -->

        <div
            class="border border-gray-200 dark:border-slate-700 rounded-xl p-3 bg-gray-50 dark:bg-slate-800"
        >
            <div class="flex flex-wrap gap-2">
                <div
                    v-for="(item, index) in modelValue"
                    :key="index"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm"
                >
                    <Hash class="w-3 h-3" />

                    {{ item }}

                    <button
                        type="button"
                        @click="remove(index)"
                        class="hover:text-red-500"
                    >
                        <X class="w-3 h-3" />
                    </button>
                </div>

                <input
                    v-model="tag"
                    @keydown="handleKeydown"
                    placeholder="Tambah label..."
                    class="flex-1 min-w-[120px] bg-transparent dark:text-white border-none focus:ring-0 focus:outline-none"
                />
            </div>
        </div>

        <!-- INFO -->

        <div class="text-xs text-gray-500">
            <span>Press Enter or comma to add a label.</span>
        </div>
    </div>
</template>
