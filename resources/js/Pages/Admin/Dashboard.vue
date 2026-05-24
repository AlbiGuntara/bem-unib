<template>
    <AppLayout>
        <Head title="Dashboard" />

        <!-- Header -->
        <div class="relative overflow-hidden rounded-xl">
            <!-- Banner Gradient -->
            <div
                class="absolute inset-0 bg-gradient-to-r from-blue-700 via-blue-600 to-green-500 opacity-95"
            ></div>

            <!-- Accent Glow -->
            <div
                class="absolute -top-20 -right-20 w-72 h-72 bg-yellow-400/20 rounded-full blur-3xl"
            ></div>

            <div class="relative z-10 px-6 py-8 sm:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Top Bar -->
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8"
                    >
                        <div>
                            <h1
                                class="text-2xl sm:text-3xl font-bold text-white"
                            >
                                Halo,
                                <span class="hidden md:inline"
                                    >Rayon IKSASS Banten</span
                                ><br />
                                <span class="md:hidden"
                                    >Rayon IKSASS Banten</span
                                >
                            </h1>

                            <p class="text-blue-100 mt-2">
                                Selamat datang di sistem manajemen data warga
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div
                                class="px-4 py-2 bg-white/15 backdrop-blur-md rounded-xl border border-white/20"
                            >
                                <p class="text-white text-sm font-medium">
                                    {{ currentDate }}
                                </p>
                            </div>

                            <button
                                class="p-2.5 bg-yellow-400/20 hover:bg-yellow-400/30 backdrop-blur-md rounded-xl border border-yellow-300/20 transition-all duration-300 group"
                            >
                                <Bell
                                    class="w-5 h-5 text-yellow-200 group-hover:animate-bell"
                                />
                            </button>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Card -->
                        <div
                            class="bg-white/15 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-lg"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-blue-500/30 flex items-center justify-center"
                                >
                                    <Users class="w-5 h-5 text-white" />
                                </div>

                                <div>
                                    <p class="text-sm text-blue-100">
                                        Total Warga
                                    </p>

                                    <p class="text-2xl font-bold text-white">
                                        {{ stats.warga }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card -->
                        <div
                            class="bg-white/15 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-lg"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-green-500/30 flex items-center justify-center"
                                >
                                    <GraduationCap class="w-5 h-5 text-white" />
                                </div>

                                <div>
                                    <p class="text-sm text-blue-100">
                                        Total Santri
                                    </p>

                                    <p class="text-2xl font-bold text-white">
                                        {{ stats.santri }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card -->
                        <div
                            class="bg-white/15 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-lg"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-yellow-400/30 flex items-center justify-center"
                                >
                                    <UserCheck class="w-5 h-5 text-white" />
                                </div>

                                <div>
                                    <p class="text-sm text-blue-100">
                                        Total Alumni
                                    </p>

                                    <p class="text-2xl font-bold text-white">
                                        {{ stats.alumni }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card -->
                        <div
                            class="bg-white/15 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-lg"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-yellow-500/30 flex items-center justify-center"
                                >
                                    <Award class="w-5 h-5 text-white" />
                                </div>

                                <div>
                                    <p class="text-sm text-blue-100">
                                        Total Prestasi
                                    </p>

                                    <p class="text-2xl font-bold text-white">
                                        {{ stats.prestasi }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mt-4">
            <div class="max-w-7xl mx-auto space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Left -->
                    <div class="lg:col-span-2 space-y-4">
                        <!-- Chart -->
                        <div
                            class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-blue-100 dark:border-gray-800 overflow-hidden"
                        >
                            <div
                                class="p-6 border-b border-gray-100 dark:border-gray-800"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-green-500 flex items-center justify-center shadow-lg"
                                        >
                                            <TrendingUp
                                                class="w-5 h-5 text-white"
                                            />
                                        </div>

                                        <div>
                                            <h3
                                                class="text-lg font-bold text-gray-900 dark:text-white"
                                            >
                                                Pertumbuhan Warga
                                            </h3>

                                            <p
                                                class="text-sm text-gray-500 dark:text-gray-400"
                                            >
                                                Trend 10 tahun terakhir
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div
                                            class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                                        >
                                            {{ growthPercentage }}%
                                        </div>

                                        <div
                                            class="text-sm text-green-600 dark:text-green-400"
                                        >
                                            Peningkatan
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Chart -->
                                <div class="h-80">
                                    <BarChart
                                        :chartData="chartData"
                                        :chartOptions="chartOptions"
                                    />
                                </div>

                                <!-- Stats -->
                                <div class="grid grid-cols-2 gap-4 mt-8">
                                    <!-- Santri -->
                                    <div
                                        class="p-4 rounded-2xl bg-gradient-to-r from-blue-50 to-green-50 dark:from-blue-900/10 dark:to-green-900/10"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"
                                            >
                                                <UserPlus
                                                    class="w-5 h-5 text-blue-600 dark:text-blue-400"
                                                />
                                            </div>

                                            <div>
                                                <div
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                >
                                                    Santri Aktif
                                                </div>

                                                <div
                                                    class="text-xl font-bold text-gray-900 dark:text-white"
                                                >
                                                    {{ stats.santri }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alumni -->
                                    <div
                                        class="p-4 rounded-2xl bg-gradient-to-r from-yellow-50 to-blue-50 dark:from-yellow-900/10 dark:to-blue-900/10"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center"
                                            >
                                                <UserCheck
                                                    class="w-5 h-5 text-yellow-600 dark:text-yellow-400"
                                                />
                                            </div>

                                            <div>
                                                <div
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                >
                                                    Alumni Terdaftar
                                                </div>

                                                <div
                                                    class="text-xl font-bold text-gray-900 dark:text-white"
                                                >
                                                    {{ stats.alumni }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-blue-100 dark:border-gray-800 overflow-hidden"
                    >
                        <div
                            class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-blue-700 via-blue-600 to-green-500"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"
                                >
                                    <Calendar class="w-5 h-5 text-white" />
                                </div>

                                <div>
                                    <h3 class="text-lg font-bold text-white">
                                        Program Kerja Terdekat
                                    </h3>

                                    <p class="text-blue-100">
                                        Yang Akan Datang
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Countdown -->
                            <div
                                class="text-center mb-6"
                                v-if="programTerdekat"
                            >
                                <div
                                    class="text-sm text-gray-500 dark:text-gray-400 mb-2"
                                >
                                    Dimulai dalam
                                </div>

                                <div class="flex justify-center gap-2">
                                    <div
                                        class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex flex-col items-center justify-center"
                                    >
                                        <span
                                            class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                                        >
                                            {{ countdown.hari }}
                                        </span>

                                        <span
                                            class="text-xs text-blue-500/70 dark:text-blue-400/70"
                                        >
                                            Hari
                                        </span>
                                    </div>

                                    <div
                                        class="w-16 h-16 rounded-2xl bg-green-50 dark:bg-green-900/20 flex flex-col items-center justify-center"
                                    >
                                        <span
                                            class="text-2xl font-bold text-green-600 dark:text-green-400"
                                        >
                                            {{ countdown.jam }}
                                        </span>

                                        <span
                                            class="text-xs text-green-500/70 dark:text-green-400/70"
                                        >
                                            Jam
                                        </span>
                                    </div>

                                    <div
                                        class="w-16 h-16 rounded-2xl bg-yellow-50 dark:bg-yellow-900/20 flex flex-col items-center justify-center"
                                    >
                                        <span
                                            class="text-2xl font-bold text-yellow-600 dark:text-yellow-400"
                                        >
                                            {{ countdown.menit }}
                                        </span>

                                        <span
                                            class="text-xs text-yellow-500/70 dark:text-yellow-400/70"
                                        >
                                            Menit
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prestasi -->
                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-blue-100 dark:border-gray-800 overflow-hidden"
                >
                    <div
                        class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-r from-yellow-400 to-blue-600 flex items-center justify-center"
                            >
                                <Award class="w-5 h-5 text-white" />
                            </div>

                            <div>
                                <h3
                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    Prestasi Terbaru
                                </h3>

                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Pencapaian warga
                                </p>
                            </div>
                        </div>

                        <button
                            @click="$inertia.visit(route('prestasi.index'))"
                            class="text-blue-600 dark:text-blue-400 hover:text-green-500 transition-colors"
                        >
                            <ChevronRight class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="p-6">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                        >
                            <div
                                v-for="(prestasi, index) in recentPrestasi"
                                :key="index"
                                class="p-4 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-300 group shadow-sm hover:shadow-lg"
                            >
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gradient-to-r from-yellow-100 to-blue-100 dark:from-yellow-900/20 dark:to-blue-900/20 flex items-center justify-center flex-shrink-0"
                                    >
                                        <Medal
                                            class="w-5 h-5 text-yellow-500 dark:text-yellow-400"
                                        />
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="font-semibold text-gray-900 dark:text-white text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                                        >
                                            {{ prestasi.title }}
                                        </h4>

                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                        >
                                            {{ prestasi.person }}
                                        </p>

                                        <div
                                            class="flex items-center gap-3 mt-2"
                                        >
                                            <span
                                                class="text-xs px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 font-medium"
                                            >
                                                {{ prestasi.level }}
                                            </span>

                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ prestasi.date }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button
                            @click="$inertia.visit(route('prestasi.index'))"
                            class="w-full mt-6 px-4 py-2.5 rounded-xl border border-blue-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-800 text-blue-700 dark:text-blue-300 transition-all duration-300 text-sm font-semibold"
                        >
                            Lihat Semua Prestasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Hover Animation */
.hover-lift {
    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15);
}

.dark .hover-lift:hover {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
}

/* Line Clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #eff6ff;
    border-radius: 10px;
}

.dark ::-webkit-scrollbar-track {
    background: #1f2937;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #2563eb, #16a34a);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #1d4ed8, #15803d);
}
</style>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import {
    Users,
    GraduationCap,
    UserCheck,
    Award,
    Bell,
    TrendingUp,
    Calendar,
    CalendarDays,
    ChevronRight,
    Medal,
    UserPlus,
    MapPin,
} from "lucide-vue-next";
import { ref, computed, onMounted, onUnmounted } from "vue";
import BarChart from "@/Components/Charts/BarChart.vue";

// Ambil data dari Inertia dengan fallback
const page = usePage();
const stats = page.props.stats || {
    warga: 0,
    santri: 0,
    alumni: 0,
    prestasi: 0,
};
const programTerdekat = page.props.programTerdekat || null;
const recentPrestasi = page.props.recentPrestasi || [];
const trendData = page.props.trendData || {
    years: [],
    santri: [],
    alumni: [],
};

// Current Date
const currentDate = computed(() => {
    const now = new Date();
    return now.toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

// Format date function
const formatDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

// Chart Data
const chartData = computed(() => ({
    labels: trendData.years,
    datasets: [
        {
            label: "Santri",
            data: trendData.santri,
            backgroundColor: "#10b981",
            borderRadius: 6,
            borderSkipped: false,
        },
        {
            label: "Alumni",
            data: trendData.alumni,
            backgroundColor: "#3b82f6",
            borderRadius: 6,
            borderSkipped: false,
        },
    ],
}));

// Chart Options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "top",
            labels: {
                color: "#6b7280",
                font: {
                    size: 12,
                    family: "'Inter', sans-serif",
                },
                padding: 20,
                usePointStyle: true,
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
            titleFont: {
                family: "'Inter', sans-serif",
                size: 12,
            },
            bodyFont: {
                family: "'Inter', sans-serif",
                size: 11,
            },
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
                    family: "'Inter', sans-serif",
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
                    family: "'Inter', sans-serif",
                },
                callback: function (value) {
                    return value.toLocaleString("id-ID");
                },
            },
        },
    },
};

// Growth percentage calculation
const growthPercentage = computed(() => {
    if (trendData.santri.length < 2) return 0;
    const firstYear = trendData.santri[0];
    const lastYear = trendData.santri[trendData.santri.length - 1];
    if (firstYear === 0) return 100;
    return (((lastYear - firstYear) / firstYear) * 100).toFixed(1);
});

// Countdown ke jam 00.00 tanggal mulai program kerja
const countdown = ref({ hari: 0, jam: 0, menit: 0 });

function updateCountdown() {
    if (!programTerdekat || !programTerdekat.tanggal_mulai) {
        countdown.value = { hari: 0, jam: 0, menit: 0 };
        return;
    }

    const now = new Date();
    const start = new Date(programTerdekat.tanggal_mulai);
    start.setHours(0, 0, 0, 0); // Set ke jam 00.00

    const diff = start.getTime() - now.getTime();

    if (diff <= 0) {
        countdown.value = { hari: 0, jam: 0, menit: 0 };
        return;
    }

    countdown.value.hari = Math.floor(diff / (1000 * 60 * 60 * 24));
    countdown.value.jam = Math.floor(
        (diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
    );
    countdown.value.menit = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
}

// Update countdown setiap menit
let countdownInterval = null;

onMounted(() => {
    updateCountdown();
    countdownInterval = setInterval(updateCountdown, 60000);
});

onUnmounted(() => {
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
});
</script>
