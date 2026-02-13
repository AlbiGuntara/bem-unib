<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from "vue";
import { Calendar, ChevronDown, X } from "lucide-vue-next";

const props = defineProps({
    start: String,
    end: String,
});

const emit = defineEmits(["update"]);

const isOpen = ref(false);
const localStart = ref(props.start || "");
const localEnd = ref(props.end || "");
const datepickerRef = ref(null);

// Format tanggal untuk display
const displayText = computed(() => {
    if (!localStart.value && !localEnd.value) return "Filter tanggal";
    
    const formatDate = (dateStr) => {
        if (!dateStr) return "";
        const date = new Date(dateStr);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric"
        });
    };
    
    if (localStart.value && localEnd.value) {
        return `${formatDate(localStart.value)} - ${formatDate(localEnd.value)}`;
    } else if (localStart.value) {
        return `Dari ${formatDate(localStart.value)}`;
    } else if (localEnd.value) {
        return `Sampai ${formatDate(localEnd.value)}`;
    }
    return "Filter tanggal";
});

// Toggle datepicker
const toggleDatepicker = () => {
    isOpen.value = !isOpen.value;
    if (!isOpen.value) {
        applyFilter();
    }
};

// Apply filter
const applyFilter = () => {
    emit("update", { 
        start: localStart.value, 
        end: localEnd.value 
    });
};

// Clear filter
const clearFilter = (e) => {
    e.stopPropagation();
    localStart.value = "";
    localEnd.value = "";
    applyFilter();
};

// Reset local values when props change
watch(() => props.start, (newVal) => {
    localStart.value = newVal;
});

watch(() => props.end, (newVal) => {
    localEnd.value = newVal;
});

// Close datepicker when clicking outside
const handleClickOutside = (event) => {
    if (datepickerRef.value && !datepickerRef.value.contains(event.target)) {
        isOpen.value = false;
        applyFilter();
    }
};

onMounted(() => {
    document.addEventListener("mousedown", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("mousedown", handleClickOutside);
});
</script>

<template>
    <div class="relative" ref="datepickerRef">
        <!-- Button trigger -->
        <button
            @click="toggleDatepicker"
            class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            :class="{ 'ring-2 ring-green-600': isOpen }"
        >
            <Calendar class="w-4 h-4 text-gray-500 dark:text-gray-400" />
            <span class="text-gray-700 dark:text-gray-300">
                {{ displayText }}
            </span>
            <ChevronDown 
                class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform"
                :class="{ 'rotate-180': isOpen }"
            />
            <button
                v-if="start || end"
                @click="clearFilter"
                class="ml-1 p-0.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded"
            >
                <X class="w-3 h-3 text-gray-500" />
            </button>
        </button>

        <!-- Datepicker popup -->
        <div
            v-if="isOpen"
            class="absolute top-full mt-2 left-0 z-50 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 min-w-[300px]"
        >
            <div class="flex gap-4 mb-4">
                <!-- Start Date -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Dari
                    </label>
                    <input
                        type="date"
                        v-model="localStart"
                        class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-gray-700 dark:text-gray-300 outline-none focus:ring-2 focus:ring-green-600"
                    />
                </div>

                <!-- End Date -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Sampai
                    </label>
                    <input
                        type="date"
                        v-model="localEnd"
                        :min="localStart"
                        class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-gray-700 dark:text-gray-300 outline-none focus:ring-2 focus:ring-green-600"
                    />
                </div>
            </div>

            <!-- Quick actions -->
            <div class="flex gap-2">
                <button
                    @click="clearFilter"
                    class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                    Hapus
                </button>
                <button
                    @click="applyFilter"
                    class="flex-1 px-3 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700"
                >
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</template>