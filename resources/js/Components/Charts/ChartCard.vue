<script setup>
import { onMounted, ref, watch } from "vue";
import Chart from "chart.js/auto";

const props = defineProps({
    title: String,
    amount: Number,
    color: { type: String, default: "#0d9488" },
    monthlyData: { type: Array, default: () => [] },
    type: { type: String, default: "line" },
});

const chartRef = ref(null);
let chartInstance = null;

// Format angka dengan kustomisasi
const formatCurrency = (value) => {
    if (value >= 1000000000) {
        return `Rp${(value / 1000000000).toFixed(1)}M`;
    } else if (value >= 1000000) {
        return `Rp${(value / 1000000).toFixed(1)}Jt`;
    } else if (value >= 1000) {
        return `Rp${(value / 1000).toFixed(1)}K`;
    }
    return `Rp${value}`;
};

// Generate random data jika tidak ada data
const generateMockData = () => {
    const data = [];
    const today = new Date();

    for (let i = 29; i >= 0; i--) {
        const date = new Date(today);
        date.setDate(date.getDate() - i);

        data.push({
            tanggal: date.toISOString().split("T")[0],
            nominal: Math.floor(Math.random() * 1000000) + 500000,
        });
    }

    return data;
};

onMounted(() => {
    initChart();
});

watch(
    () => props.monthlyData,
    () => {
        if (chartInstance) {
            chartInstance.destroy();
            initChart();
        }
    },
    { deep: true }
);

function initChart() {
    if (!chartRef.value) return;

    if (chartInstance) chartInstance.destroy();

    const ctx = chartRef.value.getContext("2d");

    // === GRADIENT PREMIUM ===
    const gradient = ctx.createLinearGradient(0, 0, 0, chartRef.value.height);
    gradient.addColorStop(0, `${props.color}33`);
    gradient.addColorStop(0.5, `${props.color}22`);
    gradient.addColorStop(1, `${props.color}00`);

    // === DATA PREPARATION ===
    const today = new Date();
    const lastMonth = new Date();
    lastMonth.setDate(today.getDate() - 30);

    let filteredData = props.monthlyData;

    // Jika tidak ada data, gunakan mock data untuk preview
    if (!filteredData.length) {
        filteredData = generateMockData();
    } else {
        filteredData = filteredData.filter((item) => {
            return new Date(item.tanggal) >= lastMonth;
        });
    }

    const labels = filteredData.map((d) =>
        new Date(d.tanggal).toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
        })
    );

    const dataPoints = filteredData.map((d) => d.nominal);

    // === ANIMATION CONFIG ===
    const animationConfig = {
        tension: 0.4,
        duration: 2000,
        easing: "easeInOutQuart",
    };

    // === CHART CONFIG ===
    chartInstance = new Chart(ctx, {
        type: props.type,
        data: {
            labels,
            datasets: [
                {
                    data: dataPoints,
                    borderColor: props.color,
                    borderWidth: props.type === "line" ? 3 : 0,
                    backgroundColor:
                        props.type === "bar" ? gradient : props.color + "33",
                    pointRadius: props.type === "line" ? 0 : 0,
                    pointBackgroundColor: props.color,
                    pointBorderColor: "#ffffff",
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: "#ffffff",
                    pointHoverBorderColor: props.color,
                    pointHoverBorderWidth: 2,
                    tension: props.type === "line" ? 0.4 : 0,
                    fill: props.type === "line",
                    barPercentage: 0.7,
                    categoryPercentage: 0.8,
                    shadowColor: props.color + "66",
                    shadowBlur: 20,
                    shadowOffsetX: 0,
                    shadowOffsetY: 5,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                ...animationConfig,
                onComplete: (ctx) => {
                    const chart = ctx.chart;
                    const { ctx: chartCtx } = chart;

                    if (props.type === "line") {
                        const dataset = chart.data.datasets[0];
                        const meta = chart.getDatasetMeta(0);

                        meta.data.forEach((point, index) => {
                            const { x, y } = point;

                            // Add glow effect to points
                            chartCtx.save();
                            chartCtx.beginPath();
                            chartCtx.arc(x, y, 8, 0, Math.PI * 2);
                            chartCtx.fillStyle = props.color + "33";
                            chartCtx.fill();
                            chartCtx.restore();

                            // Add main point
                            chartCtx.save();
                            chartCtx.beginPath();
                            chartCtx.arc(x, y, 4, 0, Math.PI * 2);
                            chartCtx.fillStyle = props.color;
                            chartCtx.fill();
                            chartCtx.restore();
                        });
                    }
                },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    mode: "index",
                    intersect: false,
                    backgroundColor: "rgba(17, 24, 39, 0.95)",
                    titleColor: "#ffffff",
                    titleFont: {
                        family: "'Inter', sans-serif",
                        size: 12,
                        weight: "600",
                    },
                    bodyColor: "#d1d5db",
                    bodyFont: {
                        family: "'Inter', sans-serif",
                        size: 14,
                        weight: "500",
                    },
                    padding: 16,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: (context) => {
                            const value = context.raw;
                            return `Rp ${value.toLocaleString("id-ID")}`;
                        },
                        title: (context) => {
                            return context[0].label;
                        },
                    },
                    boxPadding: 6,
                },
            },
            interaction: {
                intersect: false,
                mode: "index",
            },
            scales: {
                x: {
                    display: false,
                    grid: {
                        display: false,
                    },
                },
                y: {
                    display: false,
                    grid: {
                        display: false,
                    },
                },
            },
            elements: {
                line: {
                    borderJoinStyle: "round",
                    borderCapStyle: "round",
                },
            },
        },
    });
}
</script>

<template>
    <div
        class="group relative bg-gradient-to-br from-white/90 to-white/70 dark:from-gray-900/90 dark:to-gray-800/70 rounded-xl shadow-sm border p-6 flex flex-col gap-4 backdrop-blur-sm transition-all duration-500 border-gray-300/50 dark:border-gray-600/50 overflow-hidden"
    >
        <!-- Background pattern effect -->
        <div class="absolute inset-0 opacity-5">
            <div
                class="absolute inset-0"
                :style="{
                    backgroundImage: `radial-gradient(circle at 1px 1px, ${color} 1px, transparent 0)`,
                    backgroundSize: '24px 24px',
                }"
            ></div>
        </div>

        <!-- Glow effect -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div
                class="absolute -inset-1 bg-gradient-to-r from-transparent via-white/0 to-transparent blur-xl"
                :style="{ backgroundColor: color }"
            ></div>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p
                        class="text-xs tracking-widest text-gray-500 dark:text-gray-400 uppercase font-semibold mb-2"
                    >
                        {{ title }}
                    </p>
                    <h2
                        class="text-3xl font-black bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent"
                    >
                        {{ formatCurrency(amount) }}
                    </h2>
                </div>

                <!-- Icon indicator -->
                <div class="relative">
                    <div
                        class="w-10 h-10 rounded-xl flex items-center justify-center"
                        :style="{ backgroundColor: color + '20' }"
                    >
                        <div
                            class="w-4 h-4 rounded-full"
                            :style="{ backgroundColor: color }"
                        ></div>
                    </div>
                    <!-- Animated pulse -->
                    <div
                        class="absolute inset-0 w-10 h-10 rounded-xl animate-ping"
                        :style="{ backgroundColor: color + '30' }"
                    ></div>
                </div>
            </div>

            <!-- Chart container -->
            <div class="relative w-full h-24 -mx-2">
                <canvas ref="chartRef" class="relative z-10"></canvas>

                <!-- Chart grid lines (background) -->
                <div
                    class="absolute inset-0 flex flex-col justify-between pointer-events-none"
                >
                    <div
                        class="h-px bg-gradient-to-r from-transparent via-gray-200/50 dark:via-gray-700/30 to-transparent"
                    ></div>
                    <div
                        class="h-px bg-gradient-to-r from-transparent via-gray-200/50 dark:via-gray-700/30 to-transparent"
                    ></div>
                    <div
                        class="h-px bg-gradient-to-r from-transparent via-gray-200/50 dark:via-gray-700/30 to-transparent"
                    ></div>
                    <div
                        class="h-px bg-gradient-to-r from-transparent via-gray-200/50 dark:via-gray-700/30 to-transparent"
                    ></div>
                </div>
            </div>

            <!-- Stats footer -->
            <div
                class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200/50 dark:border-gray-700/30"
            >
                <div class="flex items-center gap-2">
                    <div
                        class="w-3 h-3 rounded-full"
                        :style="{ backgroundColor: color }"
                    ></div>
                    <span
                        class="text-sm font-medium text-gray-600 dark:text-gray-400"
                    >
                        Last 30 days
                    </span>
                </div>

                <div class="text-sm font-semibold" :style="{ color: color }">
                    {{
                        Math.round(amount / 1000000) > 0
                            ? Math.round(amount / 1000000) + "Jt"
                            : Math.round(amount / 1000) + "Rb"
                    }}
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

.shimmer-effect {
    animation: shimmer 2s infinite;
}

.chart-hover {
    transition: all 0.3s ease;
}

.chart-hover:hover {
    transform: translateY(-2px);
}
</style>
