<script setup>
import { ref, onMounted, watch } from "vue";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    BarController,
} from "chart.js";

// Register ChartJS components
ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    BarController
);

const props = defineProps({
    chartData: {
        type: Object,
        required: true,
        default: () => ({
            labels: [],
            datasets: [],
        }),
    },
    chartOptions: {
        type: Object,
        default: () => ({}),
    },
});

const chartRef = ref(null);
const chartInstance = ref(null);

onMounted(() => {
    renderChart();
});

watch(
    () => props.chartData,
    () => {
        if (chartInstance.value) {
            chartInstance.value.destroy();
        }
        renderChart();
    },
    { deep: true }
);

function renderChart() {
    if (!chartRef.value) return;

    const ctx = chartRef.value.getContext("2d");

    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: "top",
                labels: {
                    color: "#6b7280",
                    font: {
                        size: 12,
                    },
                    padding: 20,
                },
            },
            tooltip: {
                backgroundColor: "rgba(17, 24, 39, 0.9)",
                titleColor: "#ffffff",
                bodyColor: "#ffffff",
                borderColor: "rgba(255, 255, 255, 0.1)",
                borderWidth: 1,
                cornerRadius: 8,
                padding: 12,
            },
        },
        scales: {
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    color: "#6b7280",
                    font: {
                        size: 11,
                    },
                },
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: "rgba(209, 213, 219, 0.2)",
                },
                ticks: {
                    color: "#6b7280",
                    font: {
                        size: 11,
                    },
                    stepSize: 50,
                },
            },
        },
    };

    const options = { ...defaultOptions, ...props.chartOptions };

    chartInstance.value = new ChartJS(ctx, {
        type: "bar",
        data: props.chartData,
        options: options,
    });
}

import { onUnmounted } from "vue";
onUnmounted(() => {
    if (chartInstance.value) {
        chartInstance.value.destroy();
    }
});
</script>

<template>
    <div class="relative h-full">
        <canvas ref="chartRef"></canvas>
    </div>
</template>
