<template>
    <div
        class="relative bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-4"
    >
        <FullCalendar :options="calendarOptions" />

        <!-- TOOLTIP -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 translate-y-1 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="tooltip.visible"
                class="fixed z-50 pointer-events-none w-80"
                :style="{
                    top: tooltip.y + 'px',
                    left: tooltip.x + 'px',
                    transform: 'translateX(-50%) translateY(-100%)',
                }"
            >
                <!-- Pointer arrow -->
                <div class="flex justify-center mb-[-6px]">
                    <div
                        class="w-3 h-3 rotate-45 bg-white dark:bg-gray-800 border-r border-b border-gray-200 dark:border-gray-700"
                    ></div>
                </div>

                <div
                    class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95"
                >
                    <!-- Header dengan gradient -->
                    <div
                        class="px-4 py-3 bg-blue-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 rounded-t-lg"
                    >
                        <p
                            class="font-semibold text-sm text-gray-800 dark:text-gray-100 truncate"
                        >
                            {{ tooltip.nama_kegiatan }}
                        </p>
                        <p
                            class="text-xs text-gray-600 dark:text-gray-300 mt-0.5"
                        >
                            {{ tooltip.tanggal_mulai }} →
                            {{
                                tooltip.tanggal_selesai || tooltip.tanggal_mulai
                            }}
                        </p>
                    </div>

                    <!-- Content area -->
                    <div class="p-3 space-y-2">
                        <!-- Tujuan dengan icon -->
                        <div class="flex items-start gap-2">
                            <Info
                                class="w-3.5 h-3.5 mt-0.5 text-gray-400 dark:text-gray-500 flex-shrink-0"
                            />

                            <p
                                class="text-xs text-gray-600 dark:text-gray-300 flex-1"
                            >
                                <span
                                    class="font-medium text-gray-700 dark:text-gray-200"
                                    >Tujuan:</span
                                >
                                {{ tooltip.tujuan || "-" }}
                            </p>
                        </div>

                        <!-- Penanggung Jawab -->
                        <div class="flex items-start gap-2">
                            <User
                                class="w-3.5 h-3.5 mt-0.5 text-gray-400 dark:text-gray-500 flex-shrink-0"
                            />

                            <p
                                class="text-xs text-gray-600 dark:text-gray-300 flex-1"
                            >
                                <span
                                    class="font-medium text-gray-700 dark:text-gray-200"
                                >
                                    Penanggung Jawab:
                                </span>
                                {{ tooltip.penanggung_jawab || "-" }}
                            </p>
                        </div>

                        <!-- Peserta dengan icon -->
                        <div class="flex items-start gap-2">
                            <UserPlus
                                class="w-3.5 h-3.5 mt-0.5 text-gray-400 dark:text-gray-500 flex-shrink-0"
                            />

                            <p
                                class="text-xs text-gray-600 dark:text-gray-300 flex-1"
                            >
                                <span
                                    class="font-medium text-gray-700 dark:text-gray-200"
                                    >Peserta:</span
                                >
                                {{ tooltip.peserta || "-" }}
                            </p>
                        </div>

                        <!-- Anggaran dengan icon -->
                        <div class="flex items-start gap-2">
                            <DollarSign
                                class="w-3.5 h-3.5 mt-0.5 text-gray-400 dark:text-gray-500 flex-shrink-0"
                            />

                            <p
                                class="text-xs text-gray-600 dark:text-gray-300 flex-1"
                            >
                                <span
                                    class="font-medium text-gray-700 dark:text-gray-200"
                                    >Anggaran:</span
                                >
                                {{
                                    tooltip.anggaran
                                        ? "Rp " +
                                          Number(
                                              tooltip.anggaran
                                          ).toLocaleString()
                                        : "-"
                                }}
                            </p>
                        </div>

                        <!-- Deskripsi (jika ada) -->
                        <div
                            v-if="tooltip.deskripsi"
                            class="pt-2 border-t border-gray-100 dark:border-gray-700 mt-2"
                        >
                            <p
                                class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2"
                            >
                                {{ tooltip.deskripsi }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import { computed, reactive } from "vue";
import { DollarSign, UserPlus, User, Info } from "lucide-vue-next";

const props = defineProps({
    events: {
        type: Array,
        default: () => [],
    },
});

/* TOOLTIP STATE */
const tooltip = reactive({
    visible: false,
    nama_kegiatan: "",
    tujuan: "",
    tanggal_mulai: "",
    tanggal_selesai: "",
    penanggung_jawab: "",
    peserta: "",
    anggaran: null,
    deskripsi: "",
    x: 0,
    y: 0,
});

const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    height: "auto",
    headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,dayGridWeek",
    },
    events: props.events,
    eventColor: "#16a34a",
    eventTextColor: "#ffffff",
    dayMaxEvents: true,

    /* DISABLE CLICK */
    eventClick() {
        return false;
    },

    /* SHOW TOOLTIP */
    eventMouseEnter(info) {
        const rect = info.el.getBoundingClientRect();
        const event = info.event.extendedProps;

        tooltip.nama_kegiatan = event.nama_kegiatan;
        tooltip.tujuan = event.tujuan;
        tooltip.tanggal_mulai = event.tanggal_mulai;
        tooltip.tanggal_selesai = event.tanggal_selesai;
        tooltip.penanggung_jawab = event.penanggung_jawab;
        tooltip.peserta = event.peserta;
        tooltip.anggaran = event.anggaran;
        tooltip.deskripsi = event.deskripsi;

        tooltip.x = rect.left + rect.width / 2;
        tooltip.y = rect.top - 10;

        tooltip.visible = true;
    },

    eventMouseLeave() {
        tooltip.visible = false;
    },
}));
</script>
