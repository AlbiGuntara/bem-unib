<script setup>
import { ref, computed, onMounted, nextTick, defineAsyncComponent } from "vue";
import SeoHead from "@/Components/SeoHead.vue";
import AppLayout from "@/Layouts/Public/AppLayouts.vue";

const FullCalendar = defineAsyncComponent(() => import("@fullcalendar/vue3"));
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

import {
    ArrowRight,
    CalendarDays,
    Calendar,
    MapPin,
    Building2,
    User,
    FileText,
    Download,
} from "lucide-vue-next";

/* ==========================================
 * PROPS
 * ========================================== */
const props = defineProps({
    prokers: {
        type: Array,
        default: () => [],
    },

    documentations: {
        type: Array,
        default: () => [],
    },
});

/* ==========================================
 * CALENDAR
 * ========================================== */
const selectedEvent = ref(null);

function handleEventClick(info) {
    selectedEvent.value = info.event.extendedProps.proker;
}

const calendarEvents = computed(() => {
    return props.prokers.map((item) => ({
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

        backgroundColor: "#3b82f6",
        borderColor: "#3b82f6",

        extendedProps: {
            proker: item,
        },
    }));
});

const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, interactionPlugin],

    initialView: "dayGridMonth",

    locale: "id",

    height: "auto",

    events: calendarEvents.value,

    eventClick: handleEventClick,

    eventDisplay: "block",

    eventContent(arg) {
        return {
            html: `
                <div class="fc-event-custom cursor-pointer">
                    <span class="title">
                        ${arg.event.title}
                    </span>
                </div>
            `,
        };
    },
}));

const upcomingEvent = computed(() => {
    const today = new Date();

    return (
        props.prokers
            .filter(
                (item) =>
                    item.start_date &&
                    new Date(item.start_date) > today
            )
            .sort(
                (a, b) =>
                    new Date(a.start_date) -
                    new Date(b.start_date)
            )[0] || null
    );
});

/* ==========================================
 * TIMELINE
 * ========================================== */
const timelineProkers = computed(() => {
    return [...props.prokers].sort(
        (a, b) => new Date(a.start_date) - new Date(b.start_date)
    );
});

function formatMonth(date) {
    return new Intl.DateTimeFormat("id-ID", {
        month: "long",
        year: "numeric",
    }).format(new Date(date));
}

const timelineContainer = ref(null);
const nearestTimelineIndex = computed(() => {
    const today = new Date();

    let nearestIndex = 0;
    let nearestDiff = Infinity;

    timelineProkers.value.forEach((proker, index) => {
        if (!proker.start_date) return;

        const diff = Math.abs(
            new Date(proker.start_date).getTime() -
            today.getTime()
        );

        if (diff < nearestDiff) {
            nearestDiff = diff;
            nearestIndex = index;
        }
    });

    return nearestIndex;
});
onMounted(async () => {
    await nextTick();

    if (!timelineContainer.value) return;

    const cards =
        timelineContainer.value.querySelectorAll(".timeline-card");

    const targetCard = cards[nearestTimelineIndex.value];

    if (!targetCard) return;

    timelineContainer.value.scrollTo({
        left:
            targetCard.offsetLeft -
            timelineContainer.value.clientWidth / 2 +
            targetCard.clientWidth / 2,
        behavior: "smooth",
    });
});
</script>

<template>
    <AppLayout>

        <SeoHead title="Program Kerja" description="Daftar program kerja dan agenda strategis BEM Universitas Ibrahimy. Lihat kalender kegiatan, timeline, dan dokumentasi program." />

        <!-- HERO SECTION -->
        <section class="relative min-h-[85vh] overflow-hidden bg-slate-50">
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#e5e7eb_1px,transparent_1px),linear-gradient(to_bottom,#e5e7eb_1px,transparent_1px)] bg-[size:60px_60px] opacity-40">
            </div>

            <div
                class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-[85vh] flex items-center py-20 lg:py-0">
                <div class="grid lg:grid-cols-[1.3fr_0.7fr] gap-14 lg:gap-20 items-center w-full">
                    <!-- LEFT -->
                    <div>
                        <!-- Heading -->
                        <h1 class="text-[2.75rem] sm:text-5xl md:text-6xl lg:text-6xl font-black leading-[1.05] tracking-tight text-slate-900"
                            data-aos="fade-right" data-aos-duration="1000" data-aos-easing="ease-out-cubic">
                            Membangun
                            <span class="bg-gradient-to-r from-blue-700 to-green-600 bg-clip-text text-transparent">
                                Dampak
                            </span>
                            Menciptakan
                            <span class="bg-gradient-to-r from-blue-700 to-green-600 bg-clip-text text-transparent">
                                Perubahan
                            </span>
                        </h1>

                        <!-- Desc -->
                        <p class="mt-6 lg:mt-8 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl"
                            data-aos="fade-right" data-aos-delay="150" data-aos-duration="1000">
                            Rangkaian program strategis yang dirancang untuk
                            mengembangkan potensi mahasiswa, memperkuat kolaborasi,
                            dan menghadirkan transformasi nyata bagi kampus serta
                            masyarakat.
                        </p>

                        <!-- CTA -->
                        <div class="flex flex-col sm:flex-row gap-4 mt-8 lg:mt-10" data-aos="zoom-in-up"
                            data-aos-delay="300" data-aos-duration="800">
                            <a href="#kalender"
                                class="w-full sm:w-auto group inline-flex items-center gap-3 px-7 py-4 rounded-lg bg-gradient-to-r from-blue-700 to-blue-600 text-white font-semibold shadow-[0_15px_40px_rgba(37,99,235,0.25)] hover:-translate-y-1 transition-all duration-300">
                                Lihat Kalender Kerja

                                <ArrowRight class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                            </a>

                            <a href="#timeline"
                                class="px-7 py-4 rounded-lg border border-slate-300 bg-white text-slate-700 font-semibold hover:border-blue-600 hover:text-blue-700 transition-all duration-300">
                                Lihat Timeline
                            </a>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="relative w-full max-w-[420px] mx-auto mt-12 lg:mt-0" data-aos="fade-left"
                        data-aos-duration="1200">

                        <div class="grid grid-cols-2 gap-5">
                            <!-- Card 1 -->
                            <div class="aspect-square rounded-2xl lg:rounded-[2rem] bg-gradient-to-br from-slate-900 via-blue-950 to-blue-900 border border-blue-800/20 p-5 sm:p-6 lg:p-8 flex flex-col justify-between text-white shadow-[0_20px_50px_rgba(15,23,42,0.15)]"
                                data-aos="flip-left" data-aos-delay="100">
                                <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black">
                                    25+
                                </h3>

                                <p class="text-slate-300">
                                    Program Aktif
                                </p>
                            </div>

                            <!-- Card 2 -->
                            <div class="aspect-square rounded-2xl lg:rounded-[2rem] bg-gradient-to-br from-slate-900 via-blue-950 to-blue-900 border border-blue-800/20 p-5 sm:p-6 lg:p-8 flex flex-col justify-between text-white shadow-[0_20px_50px_rgba(15,23,42,0.15)] lg:translate-y-5"
                                data-aos="flip-left" data-aos-delay="200">
                                <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black">
                                    12
                                </h3>

                                <p class="text-slate-300">
                                    Departemen
                                </p>
                            </div>

                            <!-- Card 3 -->
                            <div class="aspect-square rounded-2xl lg:rounded-[2rem] bg-gradient-to-br from-slate-900 via-blue-950 to-blue-900 border border-blue-800/20 p-5 sm:p-6 lg:p-8 flex flex-col justify-between text-white shadow-[0_20px_50px_rgba(15,23,42,0.15)]"
                                data-aos="flip-left" data-aos-delay="300">
                                <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black">
                                    100+
                                </h3>

                                <p class="text-slate-300">
                                    Kolaborasi
                                </p>
                            </div>

                            <!-- Card 4 -->
                            <div class="aspect-square rounded-2xl lg:rounded-[2rem] bg-gradient-to-br from-slate-900 via-blue-950 to-blue-900 border border-blue-800/20 p-5 sm:p-6 lg:p-8 flex flex-col justify-between text-white shadow-[0_20px_50px_rgba(15,23,42,0.15)] lg:translate-y-5"
                                data-aos="flip-left" data-aos-delay="400">
                                <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black">
                                    2026
                                </h3>

                                <p class="text-slate-300">
                                    Kabinet
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CALENDAR SECTION -->
        <section id="kalender" class="relative py-28 bg-white overflow-hidden">
            <!-- BG -->
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-100 rounded-lg blur-3xl opacity-40"></div>

            <div class="absolute bottom-0 left-0 w-[350px] h-[350px] bg-green-100 rounded-lg blur-3xl opacity-40"></div>

            <div class="relative max-w-7xl mx-auto px-4">
                <!-- Heading -->
                <div class="text-center mb-20" data-aos="fade-up" data-aos-duration="900">
                    <span class="text-green-600 font-semibold tracking-[0.25em] uppercase text-sm">
                        Kalender Kegiatan
                    </span>

                    <h2 class="text-4xl lg:text-5xl font-black text-blue-950 mt-4">
                        Daftar Lengkap Acara
                    </h2>

                    <div class="w-28 h-1.5 rounded-lg bg-gradient-to-r from-green-500 to-blue-600 mx-auto mt-6"></div>
                </div>

                <div v-if="upcomingEvent"
                    class="mb-8 bg-gradient-to-r from-blue-700 to-green-600 text-white rounded-lg p-8 lg:p-10 shadow-xl relative overflow-hidden"
                    data-aos="fade-up">
                    <!-- Decoration -->
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full"></div>

                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/10 rounded-full"></div>

                    <div class="relative grid lg:grid-cols-3 gap-8 items-center">
                        <!-- LEFT -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center gap-2 mb-3">
                                <Calendar class="w-5 h-5" />

                                <span class="uppercase tracking-wider text-sm font-semibold text-blue-100">
                                    Kegiatan Mendatang
                                </span>
                            </div>

                            <h3 class="text-3xl lg:text-4xl font-bold leading-tight mb-4">
                                {{ upcomingEvent.title }}
                            </h3>

                            <p class="flex items-center gap-2 text-blue-100 mb-2">
                                <MapPin class="w-4 h-4" />

                                {{
                                    upcomingEvent.location ||
                                    "Lokasi belum ditentukan"
                                }}
                            </p>

                            <p class="text-blue-100 mb-6">
                                {{ upcomingEvent.start_date }}
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="bg-white/10 backdrop-blur-md rounded-lg p-6 text-center border border-white/20">
                            <p class="text-sm uppercase tracking-wider mb-4 text-blue-100">
                                Agenda Terdekat
                            </p>

                            <div class="w-20 h-20 mx-auto rounded-full bg-white/10 flex items-center justify-center">
                                <CalendarDays class="w-10 h-10 text-white" />
                            </div>

                            <p class="mt-4 text-sm text-blue-100">
                                Program kerja berikutnya akan
                                segera dilaksanakan.
                            </p>
                        </div>
                    </div>
                </div>

                <div v-else class="mb-8 rounded-lg border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                    Belum ada kegiatan mendatang.
                </div>

                <div class="grid lg:grid-cols-4 gap-6">

                    <div class="lg:col-span-3 bg-white rounded-lg shadow-lg border border-slate-100 p-6">
                        <FullCalendar :options="calendarOptions" />
                    </div>

                    <div class="rounded-lg bg-white border border-slate-200/70 shadow-xl overflow-hidden"
                        data-aos="fade-left" data-aos-delay="250">
                        <!-- Header -->
                        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-lg bg-green-100 text-green-700 flex items-center justify-center">
                                    <FileText class="w-5 h-5" />
                                </div>

                                <div>
                                    <h3 class="font-bold text-slate-900">Detail Kegiatan</h3>

                                    <p class="text-xs text-slate-500">Klik agenda pada kalender</p>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="!selectedEvent" class="px-6 py-12 text-center">
                            <div
                                class="mx-auto w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                <CalendarDays class="w-8 h-8 text-slate-400" />
                            </div>

                            <p class="text-slate-500">
                                Pilih kegiatan pada kalender untuk melihat informasi lengkap.
                            </p>
                        </div>

                        <!-- Content -->
                        <div v-else class="p-6 space-y-6">
                            <!-- Title -->
                            <div>
                                <div>
                                    <h4 class="text-2xl font-black text-slate-900 leading-tight mb-2">
                                        {{ selectedEvent.title }}
                                    </h4>
                                </div>
                            </div>

                            <!-- Info Grid -->
                            <div class="grid grid-cols-1 gap-3">
                                <div class="flex items-center gap-3 rounded-lg bg-slate-50 p-4">
                                    <Calendar class="w-5 h-5 text-green-600" />

                                    <div>
                                        <p class="text-xs text-slate-500">Tanggal</p>

                                        <p class="font-medium text-slate-800">
                                            {{ selectedEvent.start_date }}
                                            <span v-if="
                                                selectedEvent.end_date
                                            ">
                                                — {{ selectedEvent.end_date }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 rounded-lg bg-slate-50 p-4">
                                    <MapPin class="w-5 h-5 text-green-600" />

                                    <div>
                                        <p class="text-xs text-slate-500">Lokasi</p>

                                        <p class="font-medium text-slate-800">
                                            {{ selectedEvent.location || "-" }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 rounded-lg bg-slate-50 p-4">
                                    <Building2 class="w-5 h-5 text-green-600" />

                                    <div>
                                        <p class="text-xs text-slate-500">Departemen</p>

                                        <p class="font-medium text-slate-800">
                                            {{ selectedEvent.department?.name || "-" }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div v-if="
                                selectedEvent.description
                            " class="rounded-lg bg-green-50 border border-green-100 p-5">
                                <p class="text-xs uppercase tracking-wider text-green-600 mb-2">
                                    Deskripsi
                                </p>

                                <p class="text-sm leading-relaxed text-slate-700">
                                    {{ selectedEvent.description }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- TIMELINE SECTION -->
        <section id="timeline"
            class="relative py-28 overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900">
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-3xl"></div>

            <div class="relative max-w-6xl mx-auto px-4">
                <!-- Heading -->
                <div class="text-center mb-24" data-aos="fade-up">
                    <span
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/10 border border-white/10 text-white/80 tracking-[0.2em] uppercase text-xs backdrop-blur-md">
                        Timeline Program
                    </span>

                    <h2 class="text-4xl lg:text-5xl font-black text-white mt-6">
                        Agenda Strategis 2026
                    </h2>

                    <div class="w-24 h-1.5 rounded-full bg-gradient-to-r from-green-400 to-cyan-400 mx-auto mt-6"></div>
                </div>

                <!-- TIMELINE -->
                <div class="relative">
                    <div ref="timelineContainer" class="overflow-x-auto overflow-y-hidden pb-8 hide-scrollbar">
                        <div class="relative flex items-start gap-16 min-w-max px-10 py-10">
                            <!-- Line -->
                            <div class="absolute top-[92px] left-0 right-0 h-1 bg-white/10"></div>

                            <!-- Item -->
                            <div v-for="(proker, index) in timelineProkers" :key="proker.id" :data-aos="index % 2 === 0
                                ? 'fade-up-right'
                                : 'fade-up-left'
                                " :data-aos-delay="index * 100" data-aos-duration="800"
                                class="timeline-card relative w-[320px] flex-shrink-0">
                                <!-- Dot -->
                                <div class="relative z-10 mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-blue-500 border-4 border-blue-950 shadow-2xl flex items-center justify-center"
                                    data-aos="zoom-in">
                                    <CalendarDays class="w-7 h-7 text-white" />
                                </div>

                                <!-- Card -->
                                <div
                                    class="mt-8 rounded-lg border border-white/10 bg-white/5 backdrop-blur-xl p-6 shadow-xl hover:-translate-y-2 transition duration-300">
                                    <!-- Month -->
                                    <span class="text-green-300 text-xs tracking-[0.2em] uppercase">
                                        {{ formatMonth(proker.start_date) }}
                                    </span>

                                    <!-- Title -->
                                    <h3 class="text-xl font-black text-white mt-3 line-clamp-2">
                                        {{ proker.title }}
                                    </h3>

                                    <!-- Department -->
                                    <div class="mt-3 text-sm text-white/70">
                                        {{ proker.department?.name }}
                                    </div>

                                    <!-- Location -->
                                    <div class="mt-1 text-sm text-white/60">
                                        {{ proker.location || "Lokasi belum ditentukan" }}
                                    </div>

                                    <!-- Description -->
                                    <p class="mt-4 text-sm text-white/70 leading-relaxed line-clamp-4">
                                        {{ proker.description }}
                                    </p>

                                    <!-- Footer -->
                                    <div class="mt-5 text-xs text-white/50">
                                        {{ proker.start_date }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- DOCUMENTATION SECTION -->
        <section class="relative py-28 bg-gradient-to-b from-slate-50 to-white overflow-hidden">
            <!-- Decoration -->
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-100 rounded-full blur-3xl opacity-50"></div>

            <div class="absolute bottom-0 right-0 w-96 h-96 bg-green-100 rounded-full blur-3xl opacity-50"></div>

            <div class="relative max-w-7xl mx-auto px-4 lg:px-8">
                <!-- Heading -->
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-14" data-aos="fade-up"
                    data-aos-duration="900">
                    <div>
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold tracking-[0.25em] uppercase">
                            Dokumentasi
                        </span>

                        <h2 class="mt-5 text-4xl lg:text-5xl font-black text-slate-900">
                            Jejak Kegiatan &
                            <span class="text-blue-700">
                                Program Kerja
                            </span>
                        </h2>

                        <p class="mt-4 text-slate-600 max-w-2xl">
                            Dokumentasi berbagai kegiatan yang telah dilaksanakan
                            sebagai bentuk kontribusi nyata BEM Universitas Ibrahimy
                            kepada mahasiswa dan masyarakat.
                        </p>
                    </div>

                    <div class="text-sm text-slate-500">
                        Geser untuk melihat dokumentasi →
                    </div>
                </div>

                <div v-if="props.documentations.length === 0" class="text-center py-16 text-slate-500">
                    Belum ada dokumentasi yang dipublikasikan.
                </div>

                <!-- HORIZONTAL SCROLL -->
                <div v-else class="flex gap-6 overflow-x-auto snap-x snap-mandatory py-10 scrollbar-hide">
                    <!-- CARD -->
                    <a v-for="(item, index) in props.documentations" :key="item.id" :href="item.drive" target="_blank"
                        :data-aos="index % 4 === 0
                            ? 'fade-up'
                            : index % 4 === 1
                                ? 'fade-down'
                                : index % 4 === 2
                                    ? 'fade-right'
                                    : 'fade-left'
                            " :data-aos-delay="index * 80" data-aos-duration="900" :class="[
                                index % 2 === 0 ? 'lg:-translate-y-6' : 'lg:translate-y-6'
                            ]"
                        class="group relative z-10 shrink-0 w-[240px] sm:w-[260px] lg:w-[280px] aspect-[2/3] overflow-hidden rounded-lg snap-start shadow-xl hover:shadow-2xl hover:shadow-blue-500/20 transition-all duration-500">
                        <!-- IMAGE -->
                        <img :src="item.image" :alt="item.title"
                            class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110" loading="lazy" />

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                        <!-- Download Button -->
                        <div
                            class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div
                                class="w-12 h-12 rounded-lg bg-white/15 backdrop-blur-xl border border-white/20 flex items-center justify-center text-white translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                <Download class="w-5 h-5" />
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                            <span class="text-xs uppercase tracking-[0.2em] text-white/70">
                                {{ item.year }}
                            </span>

                            <h3 class="mt-2 text-xl font-bold leading-tight">
                                {{ item.title }}
                            </h3>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
.fc-event-custom {
    padding: 2px 4px;
}

.fc-event-custom .title {
    font-size: 11px;
    font-weight: 600;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;

    overflow: hidden;
}

:deep(.fc-event) {
    border: none !important;
    border-radius: 7px;
}

:deep(.fc-day-today) {
    background: rgba(59, 130, 246, 0.08) !important;
}

/* Horizontal Timeline Scrollbar */
.timeline-scroll::-webkit-scrollbar {
    height: 8px;
}

.timeline-scroll::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.timeline-scroll::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 999px;
}

/* Hide scrollbar */
.hide-scrollbar {
    -ms-overflow-style: none;
    /* IE & Edge */
    scrollbar-width: none;
    /* Firefox */
}

.hide-scrollbar::-webkit-scrollbar {
    display: none;
    /* Chrome, Safari */
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>