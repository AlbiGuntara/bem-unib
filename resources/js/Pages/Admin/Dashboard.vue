<script setup>
import { Head, usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { computed } from "vue";
import {
    Users,
    Building2,
    CalendarCheck,
    Newspaper,
    Image,
    MessageSquare,
    HelpCircle,
    Mail,
    MailOpen,
    FileText,
    ArrowRight,
    Clock,
    TrendingUp,
    PieChart,
    BarChart3,
    Inbox,
    AlertCircle,
    CheckCircle2,
    Loader2,
} from "lucide-vue-next";

import { Bar, Doughnut, Line } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Filler,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Filler,
);

const page = usePage();
const p = page.props;

const stats = computed(() => p.stats || {});
const prokerByDepartemen = computed(() => p.prokerByDepartemen || []);
const programKerjaByStatusLabels = computed(
    () => p.programKerjaByStatusLabels || [],
);
const programKerjaByStatusData = computed(
    () => p.programKerjaByStatusData || [],
);
const messageCategoryLabels = computed(() => p.messageCategoryLabels || []);
const messageCategoryData = computed(() => p.messageCategoryData || []);
const messageStatusLabels = computed(() => p.messageStatusLabels || []);
const messageStatusData = computed(() => p.messageStatusData || []);
const beritaByMonth = computed(() => p.beritaByMonth || []);
const recentBerita = computed(() => p.recentBerita || []);
const unreadMessagesList = computed(() => p.unreadMessages || []);
const prokerTerdekat = computed(() => p.prokerTerdekat || []);

const currentDate = new Date().toLocaleDateString("id-ID", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
});

function formatDate(d) {
    return d
        ? new Date(d).toLocaleDateString("id-ID", {
              day: "numeric",
              month: "short",
              year: "numeric",
          })
        : "-";
}

const statCards = computed(() => [
    {
        icon: Building2,
        label: "Departemen",
        value: stats.value.totalDepartemen,
        bg: "bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400",
    },
    {
        icon: Users,
        label: "Pengurus",
        value: stats.value.totalPengurus,
        bg: "bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400",
    },
    {
        icon: CalendarCheck,
        label: "Program Kerja",
        value: stats.value.totalProgramKerja,
        bg: "bg-violet-100 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400",
    },
    {
        icon: Newspaper,
        label: "Berita",
        value: stats.value.totalBerita,
        bg: "bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400",
    },
    {
        icon: Image,
        label: "Dokumentasi",
        value: stats.value.totalDokumentasi,
        bg: "bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400",
    },
    {
        icon: MessageSquare,
        label: "Pesan Masuk",
        value: stats.value.totalPesan,
        bg: "bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400",
    },
    {
        icon: HelpCircle,
        label: "FAQ",
        value: stats.value.totalFaq,
        bg: "bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400",
    },
    {
        icon: Mail,
        label: "Surat Masuk",
        value: stats.value.totalSuratMasuk,
        bg: "bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400",
    },
]);

// Chart defaults
const textColor = "#94a3b8";
const gridColor = "rgba(148,163,184,0.1)";

function baseOpts(extra = {}) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: textColor,
                    font: { size: 11 },
                    padding: 16,
                    usePointStyle: true,
                },
            },
            tooltip: {
                backgroundColor: "rgba(15,23,42,0.9)",
                titleColor: "#fff",
                bodyColor: "#fff",
                cornerRadius: 8,
                padding: 12,
            },
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: textColor, font: { size: 10 } },
            },
            y: {
                beginAtZero: true,
                grid: { color: gridColor },
                ticks: { color: textColor, font: { size: 10 } },
            },
        },
        ...extra,
    };
}

// 1 - Proker per Departemen (Bar)
const prokerDeptData = computed(() => ({
    labels: prokerByDepartemen.value.map((d) =>
        d.name.length > 14 ? d.name.substring(0, 14) + ".." : d.name,
    ),
    datasets: [
        {
            label: "Program Kerja",
            data: prokerByDepartemen.value.map((d) => d.total),
            backgroundColor: [
                "#3b82f6",
                "#10b981",
                "#eab308",
                "#8b5cf6",
                "#ec4899",
                "#06b6d4",
                "#f97316",
                "#6366f1",
            ],
            borderRadius: 6,
            borderSkipped: false,
        },
    ],
}));
const prokerDeptOpts = baseOpts({
    plugins: {
        legend: { display: false },
        tooltip: baseOpts().plugins.tooltip,
    },
    scales: {
        ...baseOpts().scales,
        y: {
            ...baseOpts().scales.y,
            ticks: { ...baseOpts().scales.y.ticks, stepSize: 1 },
        },
    },
});

// 2 - Kategori Pesan (Doughnut)
const msgCatData = computed(() => ({
    labels: messageCategoryLabels.value,
    datasets: [
        {
            data: messageCategoryData.value,
            backgroundColor: ["#3b82f6", "#eab308", "#10b981", "#ef4444"],
            borderWidth: 0,
            hoverOffset: 8,
        },
    ],
}));
const msgCatOpts = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: "65%",
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                color: textColor,
                padding: 16,
                usePointStyle: true,
                font: { size: 11 },
            },
        },
        tooltip: {
            backgroundColor: "rgba(15,23,42,0.9)",
            titleColor: "#fff",
            bodyColor: "#fff",
            cornerRadius: 8,
            padding: 12,
            callbacks: {
                label: (ctx) => {
                    const t = ctx.dataset.data.reduce((a, b) => a + b, 0);
                    return ` ${ctx.label}: ${ctx.parsed} (${t > 0 ? ((ctx.parsed / t) * 100).toFixed(1) : 0}%)`;
                },
            },
        },
    },
};

// 3 - Status Program Kerja (horizontal Bar)
const prokerStatusData = computed(() => ({
    labels: programKerjaByStatusLabels.value,
    datasets: [
        {
            data: programKerjaByStatusData.value,
            backgroundColor: ["#3b82f6", "#10b981", "#6366f1", "#ef4444"],
            borderRadius: 6,
            borderSkipped: false,
        },
    ],
}));
const prokerStatusOpts = baseOpts({
    indexAxis: "y",
    plugins: { legend: { display: false } },
    scales: {
        x: {
            ...baseOpts().scales.y,
            ticks: { ...baseOpts().scales.y.ticks, stepSize: 1 },
        },
        y: {
            grid: { display: false },
            ticks: { color: textColor, font: { size: 11 } },
        },
    },
});

// 4 - Status Pesan (Doughnut)
const msgStatusData = computed(() => ({
    labels: messageStatusLabels.value,
    datasets: [
        {
            data: messageStatusData.value,
            backgroundColor: ["#3b82f6", "#64748b", "#10b981", "#f59e0b"],
            borderWidth: 0,
            hoverOffset: 8,
        },
    ],
}));
const msgStatusOpts = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: "65%",
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                color: textColor,
                padding: 16,
                usePointStyle: true,
                font: { size: 11 },
            },
        },
    },
};

// 5 - Trend Berita (Line)
const monthNames = [
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
const beritaTrendData = computed(() => ({
    labels: beritaByMonth.value.map((b) => {
        const [y, m] = b.month.split("-");
        return `${monthNames[parseInt(m) - 1]} ${y}`;
    }),
    datasets: [
        {
            label: "Berita",
            data: beritaByMonth.value.map((b) => b.total),
            borderColor: "#3b82f6",
            backgroundColor: "rgba(59,130,246,0.1)",
            fill: true,
            tension: 0.4,
            pointBackgroundColor: "#3b82f6",
            pointBorderColor: "#fff",
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            borderWidth: 3,
        },
    ],
}));
const beritaTrendOpts = baseOpts({
    plugins: { legend: { display: false } },
    scales: {
        ...baseOpts().scales,
        y: {
            ...baseOpts().scales.y,
            ticks: { ...baseOpts().scales.y.ticks, stepSize: 1 },
        },
    },
});
</script>

<template>
    <AppLayout>
        <Head title="Dashboard" />

        <div class="relative overflow-hidden rounded-lg mb-6">
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-700 via-blue-600 to-emerald-500 opacity-95"
            ></div>
            <div
                class="absolute -top-24 -right-24 w-96 h-96 bg-yellow-400/15 rounded-full blur-3xl"
            ></div>
            <div
                class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl"
            ></div>
            <div class="relative z-10 px-6 py-8 sm:px-8">
                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                >
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">
                            Dashboard BEM UNIB
                        </h1>
                        <p class="text-blue-100 mt-1 text-sm">
                            Ringkasan data dan aktivitas Badan Eksekutif
                            Mahasiswa Universitas Ibrahimy
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="px-4 py-2 bg-white/15 backdrop-blur-md rounded-lg border border-white/20"
                        >
                            <p
                                class="text-white text-sm font-medium flex items-center gap-2"
                            >
                                <Clock class="w-4 h-4" /> {{ currentDate }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 mb-6">
            <div
                v-for="card in statCards"
                :key="card.label"
                class="group rounded-lg bg-white dark:bg-gray-900 p-4 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 cursor-pointer"
                @click="
                    card.label === 'Pesan Masuk' && card.value > 0
                        ? router.visit(route('messages.index'))
                        : null
                "
            >
                <div class="flex flex-col items-center text-center gap-2">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg transition-transform duration-300 group-hover:scale-110"
                        :class="card.bg"
                    >
                        <component :is="card.icon" class="h-4 w-4" />
                    </div>
                    <div>
                        <p
                            class="text-lg font-bold text-slate-800 dark:text-white"
                        >
                            {{ card.value ?? 0 }}
                        </p>
                        <p
                            class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            {{ card.label }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS ROW 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div
                class="rounded-lg bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600"
                        >
                            <BarChart3 class="h-4 w-4 text-white" />
                        </div>
                        <div>
                            <h3
                                class="text-sm font-bold text-slate-800 dark:text-white"
                            >
                                Program Kerja per Departemen
                            </h3>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Distribusi proker setiap departemen
                            </p>
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-full"
                        >{{ prokerByDepartemen.length }} departemen</span
                    >
                </div>
                <div class="p-5">
                    <div class="h-64">
                        <Bar :data="prokerDeptData" :options="prokerDeptOpts" />
                    </div>
                </div>
            </div>

            <div
                class="rounded-lg bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600"
                        >
                            <PieChart class="h-4 w-4 text-white" />
                        </div>
                        <div>
                            <h3
                                class="text-sm font-bold text-slate-800 dark:text-white"
                            >
                                Kategori Pesan
                            </h3>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Saran, Kritik, Aspirasi, Aduan
                            </p>
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2.5 py-1 rounded-full"
                        >{{
                            messageCategoryData.reduce((a, b) => a + b, 0)
                        }}
                        total</span
                    >
                </div>
                <div class="p-5">
                    <div class="h-64 flex items-center justify-center">
                        <Doughnut :data="msgCatData" :options="msgCatOpts" />
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS ROW 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div
                class="rounded-lg bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-amber-500 to-amber-600"
                        >
                            <CalendarCheck class="h-4 w-4 text-white" />
                        </div>
                        <div>
                            <h3
                                class="text-sm font-bold text-slate-800 dark:text-white"
                            >
                                Status Program Kerja
                            </h3>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Rencana / Berjalan / Selesai / Batal
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-56">
                        <Bar
                            :data="prokerStatusData"
                            :options="prokerStatusOpts"
                        />
                    </div>
                </div>
            </div>

            <div
                class="rounded-lg bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-rose-500 to-rose-600"
                        >
                            <MessageSquare class="h-4 w-4 text-white" />
                        </div>
                        <div>
                            <h3
                                class="text-sm font-bold text-slate-800 dark:text-white"
                            >
                                Status Pesan
                            </h3>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Unread / Read / Replied
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-56 flex items-center justify-center">
                        <Doughnut
                            :data="msgStatusData"
                            :options="msgStatusOpts"
                        />
                    </div>
                </div>
            </div>

            <div
                class="rounded-lg bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-violet-500 to-violet-600"
                        >
                            <TrendingUp class="h-4 w-4 text-white" />
                        </div>
                        <div>
                            <h3
                                class="text-sm font-bold text-slate-800 dark:text-white"
                            >
                                Trend Berita
                            </h3>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Publikasi per bulan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-56">
                        <Line
                            :data="beritaTrendData"
                            :options="beritaTrendOpts"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- RECENT ITEMS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div
                class="rounded-lg bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-emerald-500"
                        >
                            <CalendarCheck class="h-4 w-4 text-white" />
                        </div>
                        <h3
                            class="text-sm font-bold text-slate-800 dark:text-white"
                        >
                            Program Kerja Terdekat
                        </h3>
                    </div>
                    <button
                        @click="router.visit(route('program-kerja.index'))"
                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 transition"
                    >
                        <ArrowRight class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    <div v-if="!prokerTerdekat.length" class="py-8 text-center">
                        <div
                            class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800"
                        >
                            <Inbox class="h-6 w-6 text-slate-400" />
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Belum ada program kerja terdekat
                        </p>
                    </div>
                    <div
                        v-for="proker in prokerTerdekat"
                        :key="proker.id"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition cursor-pointer group"
                        @click="router.visit(route('program-kerja.index'))"
                    >
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shrink-0"
                        >
                            <Loader2 class="h-4 w-4" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p
                                class="text-sm font-medium text-slate-800 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition"
                            >
                                {{ proker.title }}
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ formatDate(proker.start_date) }}
                            </p>
                        </div>
                        <ArrowRight
                            class="w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 transition"
                        />
                    </div>
                </div>
            </div>

            <div
                class="rounded-lg bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-amber-500 to-orange-500"
                        >
                            <Newspaper class="h-4 w-4 text-white" />
                        </div>
                        <h3
                            class="text-sm font-bold text-slate-800 dark:text-white"
                        >
                            Berita Terbaru
                        </h3>
                    </div>
                    <button
                        @click="router.visit(route('berita.index'))"
                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 transition"
                    >
                        <ArrowRight class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    <div v-if="!recentBerita.length" class="py-8 text-center">
                        <div
                            class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800"
                        >
                            <Inbox class="h-6 w-6 text-slate-400" />
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Belum ada berita
                        </p>
                    </div>
                    <div
                        v-for="berita in recentBerita"
                        :key="berita.id"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition cursor-pointer group"
                        @click="router.visit(route('berita.edit', berita.id))"
                    >
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 shrink-0"
                        >
                            <FileText class="h-4 w-4" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p
                                class="text-sm font-medium text-slate-800 dark:text-white truncate group-hover:text-amber-600 dark:group-hover:text-amber-400 transition"
                            >
                                {{ berita.title }}
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ formatDate(berita.created_at) }} ·
                                {{ berita.category }}
                            </p>
                        </div>
                        <ArrowRight
                            class="w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 transition"
                        />
                    </div>
                </div>
            </div>

            <div
                class="rounded-lg bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-rose-500 to-pink-500"
                        >
                            <MailOpen class="h-4 w-4 text-white" />
                        </div>
                        <div>
                            <h3
                                class="text-sm font-bold text-slate-800 dark:text-white"
                            >
                                Pesan Belum Dibaca
                            </h3>
                            <p
                                v-if="stats.unreadMessages > 0"
                                class="text-xs text-rose-500 font-semibold"
                            >
                                {{ stats.unreadMessages }} pesan
                            </p>
                        </div>
                    </div>
                    <button
                        @click="router.visit(route('messages.index'))"
                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 transition"
                    >
                        <ArrowRight class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    <div
                        v-if="!unreadMessagesList.length"
                        class="py-8 text-center"
                    >
                        <div
                            class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800"
                        >
                            <CheckCircle2 class="h-6 w-6 text-emerald-400" />
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Semua pesan sudah dibaca
                        </p>
                    </div>
                    <div
                        v-for="msg in unreadMessagesList"
                        :key="msg.id"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/10 transition cursor-pointer group"
                        @click="router.visit(route('messages.show', msg.id))"
                    >
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 shrink-0"
                        >
                            <AlertCircle class="h-4 w-4" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p
                                class="text-sm font-medium text-slate-800 dark:text-white truncate group-hover:text-rose-600 dark:group-hover:text-rose-400 transition"
                            >
                                {{ msg.subject }}
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ msg.name }} ·
                                {{ formatDate(msg.created_at) }}
                            </p>
                        </div>
                        <ArrowRight
                            class="w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 transition"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
