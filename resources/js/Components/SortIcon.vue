<script setup>
import { ArrowUp, ArrowDown, ArrowUpDown } from "lucide-vue-next";

defineProps({
    field: String,
    filters: Object,
});
</script>

<template>
    <span class="inline-flex ml-1 align-middle">
        <!-- ASC -->
        <ArrowUp
            v-if="filters.sort_by === field && filters.sort_direction === 'asc'"
            class="w-4 h-4 text-white"
        />

        <!-- DESC -->
        <ArrowDown
            v-else-if="
                filters.sort_by === field && filters.sort_direction === 'desc'
            "
            class="w-4 h-4 text-white"
        />

        <!-- NONE -->
        <ArrowUpDown v-else class="w-4 h-4 text-white/50" />
    </span>
</template>
