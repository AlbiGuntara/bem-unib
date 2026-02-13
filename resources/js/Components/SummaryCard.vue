<template>
    <div
        class="relative p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-lg transition-all"
    >
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ title }}
                </p>
                <p class="text-2xl font-black mt-1" :class="textColor">
                    {{ suffix ? value : rupiah(value) }}
                    <span v-if="suffix" class="text-sm font-semibold">
                        {{ suffix }}
                    </span>
                </p>
            </div>

            <div
                class="w-12 h-12 rounded-xl flex items-center justify-center"
                :class="bgColor"
            >
                <component :is="iconComponent" class="w-6 h-6 text-white" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { DollarSign, Clock, Calendar, Wallet } from "lucide-vue-next";
import { rupiah } from "@/helpers";

const props = defineProps({
    title: String,
    value: Number,
    color: String,
    icon: String,
    suffix: String,
});

const iconComponent = {
    DollarSign,
    Clock,
    Calendar,
    Wallet,
}[props.icon];

const colors = {
    red: "bg-red-600",
    green: "bg-green-600",
    yellow: "bg-yellow-500",
    emerald: "bg-emerald-600",
};

const bgColor = colors[props.color];
const textColor = `text-${props.color}-600 dark:text-${props.color}-400`;
</script>
