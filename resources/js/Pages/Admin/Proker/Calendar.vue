<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
const FullCalendar = defineAsyncComponent(() => import("@fullcalendar/vue3"));
import ViewProkerModal from "@/Pages/Admin/Proker/View.vue";
import {
    CalendarDays,
    Clock,
    FileText,
    Building2,
    User,
    MapPin,
    Calendar,
    Users,
    Wallet,
} from "lucide-vue-next";

const props = defineProps({
    prokers: {
        type: Array,
        default: () => [],
    },
});

/* =========================
   STATE
========================= */
const selectedEvent = ref(null);
const selectedDept = ref("");

/* =========================
   HELPERS
========================= */
function getStatusColor(status) {
    switch (status) {
        case "selesai":
            return "#10b981";
        case "berjalan":
            return "#f59e0b";
        case "batal":
            return "#ef4444";
        default:
            return "#3b82f6";
    }
}

function handleEventClick(info) {
    selectedEvent.value = info.event.extendedProps.proker;
}

/* =========================
   FILTERED EVENTS (SOURCE UTAMA)
========================= */
const calendarEvents = computed(() => {
    return props.prokers
        .filter((p) => {
            if (!selectedDept.value) return true;
            return p.departemen_id === selectedDept.value;
        })
        .map((item) => ({
            id: item.id,
            title: item.title,
            start: item.start_date,
            end: item.end_date
                ? new Date(
                    new Date(item.end_date).setDate(
                        new Date(item.end_date).getDate() + 1
                    )
                )
                    .toISOString()
                    .split("T")[0]
                : item.start_date,
            backgroundColor: getStatusColor(item.status),
            borderColor: getStatusColor(item.status),
            extendedProps: {
                proker: item,
            },
        }));
});

/* =========================
   CALENDAR OPTIONS
========================= */
const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    locale: "id",
    height: "auto",

    dayMaxEvents: false,
    dayMaxEventRows: false,

    events: calendarEvents.value,

    eventClick: handleEventClick,
    eventDisplay: "block",

    eventContent(arg) {
        const p = arg.event.extendedProps.proker;
        const color = getStatusColor(p.status);

        return {
            html: `
            <div class="fc-event-custom">
                <div class="flex items-center gap-1 cursor-pointer">
                    <span class="w-2 h-2 rounded-full" style="background:${color}"></span>
                    <span class="title">
                        ${arg.event.title}
                    </span>
                </div>
            </div>
        `,
        };
    },
}));
/* =========================
   TODAY & UPCOMING
========================= */
const today = new Date().toISOString().split("T")[0];

const upcomingEvents = computed(() => {
    return props.prokers
        .filter((e) => e.status === "rencana" && e.start_date)
        .sort((a, b) => new Date(a.start_date) - new Date(b.start_date))[0] || null;
});

// Format rupiah
function formatRupiah(value) {
    if (!value) return "Rp 0";

    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
}

// Warna preview
function getStatusGradient(status) {
    switch (status) {
        case "selesai":
            return "from-emerald-600 to-emerald-500";
        case "berjalan":
            return "from-amber-600 to-amber-500";
        case "batal":
            return "from-red-600 to-red-500";
        default:
            return "from-blue-600 to-indigo-600";
    }
}
</script>

<template>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">

        <!-- CALENDAR -->
        <div
            class="lg:col-span-3 rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <FullCalendar :options="calendarOptions" />
        </div>

        <!-- SIDEBAR -->
        <div class="space-y-4">

            <!-- UPCOMING -->
            <div
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h3 class="mb-3 flex items-center gap-2 font-semibold text-slate-800 dark:text-white">
                    <CalendarDays class="h-4 w-4 text-blue-500" />
                    Program Mendatang
                </h3>

                <div v-if="!upcomingEvents" class="text-sm text-slate-500">
                    Tidak ada program rencana
                </div>

                <div v-else
                    class="rounded-lg border border-slate-100 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800">
                    <p class="line-clamp-1 text-sm font-semibold text-slate-800 dark:text-white">
                        {{ upcomingEvents.title }}
                    </p>

                    <div class="mt-1 flex items-center justify-between text-xs text-slate-500">
                        <span class="truncate">
                            {{ upcomingEvents.location ?? "-" }}
                        </span>

                        <span>
                            {{ upcomingEvents.start_date }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- RIGHT LIVE PREVIEW PANEL -->
            <div
                class="lg:col-span-1 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h3 class="mb-4 flex items-center gap-2 font-semibold text-slate-800 dark:text-white">
                    <FileText class="h-4 w-4 text-blue-500" />
                    Detail Program
                </h3>

                <!-- EMPTY STATE -->
                <div v-if="!selectedEvent" class="text-sm text-slate-500">
                    Klik program di kalender untuk melihat detail
                </div>

                <!-- CONTENT -->
                <div v-else class="space-y-4">

                    <!-- HERO -->
                    <div class="rounded-lg p-4 text-white shadow"
                        :class="`bg-gradient-to-r ${getStatusGradient(selectedEvent.status)}`">
                        <div class="flex items-center gap-2 text-xs text-white/80">
                            <Building2 class="h-3 w-3" />
                            {{ selectedEvent.department?.name ?? "-" }}
                        </div>

                        <p class="mt-2 text-lg font-bold leading-snug">
                            {{ selectedEvent.title }}
                        </p>

                        <div class="mt-2 flex items-center gap-2 text-xs">
                            <span class="rounded-full bg-white/20 px-2 py-1">
                                {{ selectedEvent.status }}
                            </span>
                        </div>
                    </div>

                    <!-- GRID INFO -->
                    <div class="grid grid-cols-2 gap-3">

                        <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-800">
                            <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                <User class="h-3 w-3" />
                                Ketua
                            </div>

                            <p class="mt-1 text-sm font-semibold">
                                {{ selectedEvent.ketua_panitia?.name ?? "-" }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-800">
                            <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                <MapPin class="h-3 w-3" />
                                Lokasi
                            </div>

                            <p class="mt-1 text-sm font-semibold">
                                {{ selectedEvent.location ?? "-" }}
                            </p>
                        </div>

                        <div class="col-span-2 rounded-lg bg-slate-50 p-3 dark:bg-slate-800">
                            <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                <CalendarDays class="h-3 w-3" />
                                Waktu Pelaksanaan
                            </div>

                            <p class="mt-1 text-sm font-semibold">
                                {{ selectedEvent.start_date ?? "-" }}
                                →
                                {{ selectedEvent.end_date ?? selectedEvent.start_date ?? "-" }}
                            </p>
                        </div>
                    </div>

                    <!-- KPI -->
                    <div class="grid grid-cols-2 gap-3">

                        <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-950/30">
                            <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                <Users class="h-3 w-3" />
                                Peserta
                            </div>

                            <p class="text-lg font-bold text-blue-700 dark:text-blue-300">
                                {{ selectedEvent.participants ?? 0 }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-emerald-50 p-3 dark:bg-emerald-950/30">
                            <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                <Wallet class="h-3 w-3" />
                                Budget
                            </div>

                            <p class="text-sm font-bold text-emerald-700 dark:text-emerald-300">
                                {{ formatRupiah(selectedEvent.budget) }}
                            </p>
                        </div>
                    </div>

                    <!-- PROGRESS -->
                    <div class="space-y-3">

                        <!-- peserta -->
                        <div>
                            <div class="mb-1 flex items-center justify-between text-[11px] text-slate-500">
                                <span class="flex items-center gap-1">
                                    <Users class="h-3 w-3" />
                                    Realisasi Peserta
                                </span>

                                <span>
                                    {{ selectedEvent.realization_participants ?? 0 }} /
                                    {{ selectedEvent.participants ?? 0 }}
                                </span>
                            </div>

                            <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                                <div class="h-2 rounded-full bg-blue-600" :style="{
                                    width:
                                        selectedEvent.participants
                                            ? Math.min(
                                                100,
                                                (selectedEvent.realization_participants /
                                                    selectedEvent.participants) *
                                                100
                                            ) + '%'
                                            : '0%',
                                }" />
                            </div>
                        </div>

                        <!-- budget -->
                        <div>
                            <div class="mb-1 flex items-center justify-between text-[11px] text-slate-500">
                                <span class="flex items-center gap-1">
                                    <Wallet class="h-3 w-3" />
                                    Realisasi Budget
                                </span>

                                <span>
                                    {{ formatRupiah(selectedEvent.realization_budget) }}
                                </span>
                            </div>

                            <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                                <div class="h-2 rounded-full bg-emerald-600" :style="{
                                    width:
                                        selectedEvent.budget
                                            ? Math.min(
                                                100,
                                                (selectedEvent.realization_budget /
                                                    selectedEvent.budget) *
                                                100
                                            ) + '%'
                                            : '0%',
                                }" />
                            </div>
                        </div>
                    </div>

                    <!-- CLOSE -->
                    <button @click="selectedEvent = null"
                        class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg bg-slate-100 py-2 text-sm hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700">
                        <Calendar class="h-4 w-4" />
                        Tutup Preview
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(.fc) {
    font-family: ui-sans-serif, system-ui;

    --fc-border-color: rgba(148, 163, 184, 0.25);
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: #ffffff;
}

/* DARK MODE */
:global(.dark) :deep(.fc) {
    --fc-border-color: rgba(148, 163, 184, 0.15);
    color: #e2e8f0;
}

/* TOOLBAR */
:deep(.fc-toolbar-title) {
    font-size: 1.1rem;
    font-weight: 700;
}

:global(.dark) :deep(.fc-toolbar-title) {
    color: #f1f5f9;
}

/* BUTTON */
:deep(.fc-button) {
    background: #3b82f6 !important;
    border: none !important;
}

:global(.dark) :deep(.fc-button) {
    background: rgba(59, 130, 246, 0.2) !important;
    color: #e2e8f0 !important;
}

/* DAY HOVER */
:deep(.fc-daygrid-day:hover) {
    background: rgba(59, 130, 246, 0.05);
}

:global(.dark) :deep(.fc-daygrid-day:hover) {
    background: rgba(59, 130, 246, 0.12);
}

/* EVENT */
/* EVENT TEXT FIX */
:deep(.fc-event) {
    border-radius: 10px;
    border: none !important;
    padding: 2px 3px;

    overflow: hidden;
}

/* isi event */
.fc-event-custom {
    display: flex;
    align-items: center;
    gap: 4px;

    white-space: nowrap;
    overflow: hidden;
}

/* judul program */
.fc-event-custom .title {
    font-size: 10px;
    font-weight: 600;
    line-height: 1.2;

    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* TODAY */
:deep(.fc-day-today) {
    background: rgba(59, 130, 246, 0.08) !important;
}

:global(.dark) :deep(.fc-day-today) {
    background: rgba(59, 130, 246, 0.15) !important;
}
</style>