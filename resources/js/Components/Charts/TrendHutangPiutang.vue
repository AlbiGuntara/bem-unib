<template>
    <div
        class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm"
    >
        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">
            Trend Hutang & Piutang
        </h3>

        <canvas ref="chart"></canvas>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import Chart from "chart.js/auto";

const props = defineProps({
    data: Array,
});

const chart = ref(null);

onMounted(() => {
    new Chart(chart.value, {
        type: "line",
        data: {
            labels: props.data.map((d) => d.bulan),
            datasets: [
                {
                    label: "Hutang",
                    data: props.data.map((d) => d.hutang),
                    borderColor: "#ef4444",
                    backgroundColor: "rgba(239,68,68,0.1)",
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: "Piutang",
                    data: props.data.map((d) => d.piutang),
                    borderColor: "#22c55e",
                    backgroundColor: "rgba(34,197,94,0.1)",
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: "bottom" },
            },
        },
    });
});
</script>
