<script setup>
import {
    Chart as ChartJS,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler,
} from "chart.js";

import { Line } from "vue-chartjs";
import { computed, ref, onMounted, onUnmounted } from "vue";

import { TrendingUp } from "lucide-vue-next";

ChartJS.register(
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler
);

const props = defineProps({
    monthlyData: Object,
});

const labels = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agu",
    "Sep",
    "Okt",
    "Nov",
    "Des",
];

// Reactive dark mode detection
const isDark = ref(false);

const updateDarkMode = () => {
    isDark.value = window.matchMedia("(prefers-color-scheme: dark)").matches;
};

onMounted(() => {
    updateDarkMode();
    const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)");
    mediaQuery.addEventListener("change", updateDarkMode);
});

onUnmounted(() => {
    const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)");
    mediaQuery.removeEventListener("change", updateDarkMode);
});

const chartData = computed(() => ({
    labels,
    datasets: [
        {
            label: "Rata-rata Skor Pendidikan",
            data: labels.map((l, index) => {
                const fullMonth = [
                    "Januari",
                    "Februari",
                    "Maret",
                    "April",
                    "Mei",
                    "Juni",
                    "Juli",
                    "Agustus",
                    "September",
                    "Oktober",
                    "November",
                    "Desember",
                ][index];
                return props.monthlyData[fullMonth] || 0;
            }),
            borderWidth: 4,
            tension: 0.4,
            fill: true,

            // Gradient colors based on dark mode
            borderColor: isDark.value ? "#10b981" : "#059669",
            backgroundColor: isDark.value
                ? "rgba(16, 185, 129, 0.15)"
                : "rgba(5, 150, 105, 0.1)",
            pointBackgroundColor: isDark.value ? "#10b981" : "#059669",
            pointBorderColor: isDark.value ? "#ffffff" : "#ffffff",
            pointBorderWidth: 2,
            pointHoverBackgroundColor: isDark.value ? "#34d399" : "#10b981",
            pointHoverBorderColor: isDark.value ? "#ffffff" : "#ffffff",
            pointHoverBorderWidth: 3,

            pointRadius: 5,
            pointHoverRadius: 8,
        },
    ],
}));

// chart options
const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,

    interaction: {
        intersect: false,
        mode: "index",
    },

    plugins: {
        legend: {
            position: "top",
            align: "end",
            labels: {
                color: isDark.value ? "#e5e7eb" : "#374151",
                font: {
                    size: 13,
                    family: "'Inter', sans-serif",
                    weight: "500",
                },
                usePointStyle: true,
                pointStyle: "circle",
                padding: 20,
            },
        },
        tooltip: {
            backgroundColor: isDark.value
                ? "rgba(17, 24, 39, 0.9)"
                : "rgba(255, 255, 255, 0.95)",
            titleColor: isDark.value ? "#f3f4f6" : "#111827",
            bodyColor: isDark.value ? "#f3f4f6" : "#374151",
            borderColor: isDark.value
                ? "rgba(75, 85, 99, 0.5)"
                : "rgba(209, 213, 219, 0.8)",
            borderWidth: 1,
            padding: 12,
            cornerRadius: 8,
            displayColors: true,
            usePointStyle: true,
            callbacks: {
                label: function (context) {
                    return `Rata-rata: ${context.parsed.y.toFixed(2)}`;
                },
            },
        },
    },

    scales: {
        x: {
            grid: {
                color: isDark.value
                    ? "rgba(75, 85, 99, 0.2)"
                    : "rgba(229, 231, 235, 0.8)",
                drawBorder: false,
            },
            ticks: {
                color: isDark.value ? "#9ca3af" : "#6b7280",
                font: {
                    size: 11,
                    family: "'Inter', sans-serif",
                },
                maxRotation: 0,
            },
        },
        y: {
            beginAtZero: true,
            grid: {
                color: isDark.value
                    ? "rgba(75, 85, 99, 0.2)"
                    : "rgba(229, 231, 235, 0.8)",
                drawBorder: false,
            },
            ticks: {
                color: isDark.value ? "#9ca3af" : "#6b7280",
                font: {
                    size: 11,
                    family: "'Inter', sans-serif",
                },
                callback: function (value) {
                    return value % 1 === 0 ? value : value.toFixed(1);
                },
            },
            border: {
                dash: [4, 4],
            },
        },
    },

    elements: {
        line: {
            borderJoinStyle: "round",
            borderCapStyle: "round",
        },
        point: {
            hoverBackgroundColor: "#ffffff",
        },
    },

    animation: {
        duration: 1000,
        easing: "easeOutQuart",
    },
}));
</script>

<template>
    <div class="h-80 relative">
        <div
            class="absolute inset-0 flex items-center justify-center"
            v-if="!monthlyData"
        >
            <div class="text-center">
                <div
                    class="w-16 h-16 mx-auto mb-3 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center"
                >
                    <TrendingUp class="w-8 h-8 text-gray-400" />
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Memuat data grafik...
                </p>
            </div>
        </div>
        <Line
            v-else
            :data="chartData"
            :options="chartOptions"
            :key="isDark.toString()"
        />
    </div>
</template>
