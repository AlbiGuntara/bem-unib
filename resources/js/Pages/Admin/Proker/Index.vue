<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted } from "vue";
import {
    Pencil,
    Trash2,
    Plus,
    ChevronDown,
    Eye,
    TableProperties,
    CalendarDays,
} from "lucide-vue-next";

import Swal from "sweetalert2";

import CreateProkerModal from "./Create.vue";
import EditProkerModal from "./Edit.vue";
import ViewProkerModal from "./View.vue";
import CompleteProkerModal from "./Complete.vue";
import CalendarView from "./Calendar.vue";

/* ---------------- MODAL STATE ---------------- */
const createModal = ref(false);
const editModal = ref(false);
const viewModal = ref(false);
const realizationModal = ref(false);
const selectedRealization = ref(null);

const selectedProker = ref(null);
const selectedStatuses = ref({});
const selectedPublics = ref({});

/* ---------------- VIEW STATE ---------------- */
const currentView = ref("table");

/* ---------------- FILTER ---------------- */
const filter = ref({
    status: "",
    departemen_id: "",
    is_public: "",
    date_from: "",
    date_to: "",
});

const dateFilterOpen = ref(false);

/* LABEL DATE RANGE */
const dateLabel = computed(() => {
    return "Tanggal";
});

/* ACTIVE FILTER CHECK (UI OPTIONAL) */
const hasActiveFilter = computed(() => {
    return (
        filter.value.status ||
        filter.value.departemen_id ||
        filter.value.is_public ||
        filter.value.date_from ||
        filter.value.date_to
    );
});

/* ---------------- PROPS ---------------- */
const props = defineProps({
    prokers: Object,
    filters: Object,
    departments: Array,
    pengurus: Array,
});

/* ---------------- TABLE COLUMNS ---------------- */
const columns = [
    { key: "title", label: "Program Kerja", sortable: true },
    { key: "penanggung_jawab", label: "Penanggung Jawab", sortable: false },
    { key: "tanggal", label: "Tanggal", sortable: false },
    { key: "status", label: "Status", sortable: true },
    { key: "is_public", label: "Publik", sortable: true },
];

/* ---------------- HELPERS ---------------- */
function formatDate(date) {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
}

function formatTime(time) {
    if (!time) return "";
    return time.substring(0, 5);
}

/* ---------------- REALIZATION ---------------- */
const realizationForm = useForm({
    realization_participants: "",
    realization_budget: "",
});

/* INIT STATUS & PUBLIC */
watch(
    () => props.prokers.data,
    (items) => {
        items.forEach((item) => {
            selectedStatuses.value[item.id] = item.status;
            selectedPublics.value[item.id] = item.is_public ? "1" : "0";
        });
    },
    { immediate: true }
);

/* ---------------- ACTIONS ---------------- */
function editProker(item) {
    selectedProker.value = item;
    editModal.value = true;
}

function viewProker(item) {
    selectedProker.value = item;
    viewModal.value = true;
}

/* STATUS UPDATE */
function updateStatus(item) {
    const status = selectedStatuses.value[item.id];

    if (status === "selesai") {
        selectedRealization.value = item;
        realizationForm.reset();

        realizationForm.realization_participants =
            item.realization_participants ?? "";
        realizationForm.realization_budget =
            item.realization_budget ?? "";

        realizationModal.value = true;
        return;
    }

    router.patch(route("proker.status", item.id), { status });
}

/* PUBLIC UPDATE */
function updatePublic(item) {
    router.patch(
        route("proker.public", item.id),
        {
            is_public: selectedPublics.value[item.id] === "1",
        },
        { preserveScroll: true, preserveState: true }
    );
}

/* REALIZATION SUBMIT */
function submitRealization() {
    realizationForm.patch(
        route("proker.complete", selectedRealization.value.id),
        {
            preserveScroll: true,
            onSuccess: () => {
                realizationModal.value = false;
                selectedStatuses.value[selectedRealization.value.id] = "selesai";
            },
        }
    );
}

/* DELETE */
function deleteProker(item) {
    const isDark = document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Hapus program ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc2626",
        cancelButtonColor: isDark ? "#374151" : "#6b7280",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("program-kerja.destroy", item.id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Terhapus!",
                        text: "Program kerja berhasil dihapus.",
                        icon: "success",
                    }),
            });
        }
    });
}

/* ---------------- VIEW SAVE ---------------- */
onMounted(() => {
    const saved = localStorage.getItem("proker-view");
    if (saved) currentView.value = saved;
});

watch(currentView, (val) => {
    localStorage.setItem("proker-view", val);
});

/* ---------------- FILTER WATCH (WITH SMALL IMPROVEMENT) ---------------- */
watch(
    filter,
    (val) => {
        router.get(route("program-kerja.index"), val, {
            preserveState: true,
            replace: true,
        });
    },
    { deep: true }
);

/* ---------------- OUTSIDE CLICK (clean version) ---------------- */
onMounted(() => {
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".date-filter")) {
            dateFilterOpen.value = false;
        }
    });
});
</script>

<template>
    <AppLayout>

        <Head title="Program Kerja" />

        <div class="mb-6 flex items-center justify-between">

            <div
                class="inline-flex gap-1 rounded-lg border border-slate-200 bg-white p-1 shadow-sm dark:border-slate-700 dark:bg-slate-800">

                <!-- TABLE -->
                <button @click="currentView = 'table'"
                    class="group relative flex items-center gap-2 rounded-sm px-5 py-2.5 text-sm font-medium transition-all duration-200"
                    :class="currentView === 'table'
                        ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white '
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700'
                        ">
                    <TableProperties class="h-4 w-4" />

                    <span>Tabel</span>
                </button>

                <!-- CALENDAR -->
                <button @click="currentView = 'calendar'"
                    class="group relative flex items-center gap-2 rounded-sm px-5 py-2.5 text-sm font-medium transition-all duration-200"
                    :class="currentView === 'calendar'
                        ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white '
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700'
                        ">
                    <CalendarDays class="h-4 w-4" />

                    <span>Kalender</span>
                </button>

            </div>

            <!-- INDICATOR -->
            <div
                class="hidden md:flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                <div class="h-2 w-2 rounded-full" :class="currentView === 'calendar'
                    ? 'bg-indigo-500'
                    : 'bg-blue-500'
                    "></div>

                {{
                    currentView === "calendar"
                        ? "Calendar View"
                        : "Table View"
                }}
            </div>

        </div>

        <Table v-if="currentView === 'table'" :data="prokers" :columns="columns" :filters="filters"
            route-name="program-kerja.index">
            <template #header-action>

                <!-- FILTER GROUP -->
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">

                    <!-- STATUS -->
                    <!-- FILTER ITEM -->
                    <div class="relative w-full">
                        <select v-model="filter.status"
                            class="peer w-full appearance-none rounded-lg border border-slate-200 bg-white px-3 py-2 pr-10 text-sm text-slate-700 shadow-sm transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700 dark:focus:border-blue-500 dark:focus:ring-blue-900/30">
                            <option value="">Status</option>
                            <option value="rencana">Rencana</option>
                            <option value="berjalan">Berjalan</option>
                            <option value="selesai">Selesai</option>
                            <option value="batal">Batal</option>
                        </select>

                        <ChevronDown
                            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-300" />
                    </div>

                    <!-- DEPARTEMEN -->
                    <div class="relative w-full">
                        <select v-model="filter.departemen_id"
                            class="peer w-full appearance-none rounded-lg border border-slate-200 bg-white px-3 py-2 pr-10 text-sm text-slate-700 shadow-sm transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700 dark:focus:border-blue-500 dark:focus:ring-blue-900/30">
                            <option value="">Departemen</option>
                            <option v-for="d in departments" :key="d.id" :value="d.id">
                                {{ d.name }}
                            </option>
                        </select>

                        <ChevronDown
                            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-300" />
                    </div>

                    <!-- PUBLIK -->
                    <div class="relative w-full">
                        <select v-model="filter.is_public"
                            class="peer w-full appearance-none rounded-lg border border-slate-200 bg-white px-3 py-2 pr-10 text-sm text-slate-700 shadow-sm transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700 dark:focus:border-blue-500 dark:focus:ring-blue-900/30">
                            <option value="">Publikasi</option>
                            <option value="1">Publik</option>
                            <option value="0">Privat</option>
                        </select>

                        <ChevronDown
                            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-300" />
                    </div>

                    <!-- DATE RANGE BUTTON -->
                    <div class="relative w-full date-filter">
                        <button @click="dateFilterOpen = !dateFilterOpen"
                            class="flex w-full items-center justify-between gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">
                            <div class="flex items-center gap-2">
                                <CalendarDays class="h-4 w-4 text-blue-500" />
                                <span>{{ dateLabel || "Filter Tanggal" }}</span>
                            </div>

                            <ChevronDown class="h-4 w-4 text-slate-400 transition"
                                :class="dateFilterOpen ? 'rotate-180' : ''" />
                        </button>

                        <!-- DROPDOWN -->
                        <div v-if="dateFilterOpen"
                            class="absolute right-0 z-50 mt-2 w-80 rounded-lg border border-slate-200 bg-white p-4 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                            <p class="mb-3 text-xs font-semibold text-slate-500">
                                Filter Rentang Tanggal
                            </p>

                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs text-slate-500">Tanggal Mulai</label>
                                    <input type="date" v-model="filter.date_from"
                                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white" />
                                </div>

                                <div>
                                    <label class="text-xs text-slate-500">Tanggal Selesai</label>
                                    <input type="date" v-model="filter.date_to"
                                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white" />
                                </div>

                                <div class="flex justify-between pt-2">
                                    <button @click="filter.date_from = ''; filter.date_to = ''"
                                        class="text-xs text-red-500 hover:underline">
                                        Reset
                                    </button>

                                    <button @click="dateFilterOpen = false"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT: ACTIONS -->
                <div class="flex items-center gap-2">

                    <!-- RESET BUTTON -->
                    <button @click="filter = {
                        status: '',
                        departemen_id: '',
                        is_public: '',
                        date_from: '',
                        date_to: ''
                    }"
                        class="flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 active:scale-95 transition">

                        <span>Reset</span>
                    </button>



                <button @click="createModal = true"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:scale-95 transition">
                    <Plus class="w-4 h-4" />
                    <span>Tambah</span>
                </button>
                </div>
            </template>

            <template #penanggung_jawab="{ item }">
                <div class="space-y-1">
                    <div class="font-medium text-slate-800 dark:text-slate-100">
                        {{ item.department?.name ?? "-" }}
                    </div>

                    <div class="text-xs text-slate-500 dark:text-slate-400">
                        Ketua:
                        {{ item.ketua_panitia?.name ?? "-" }}
                    </div>
                </div>
            </template>

            <template #tanggal="{ item }">
                <div class="text-sm">
                    <div>
                        <span v-if="item.start_date && item.end_date">
                            {{ formatDate(item.start_date) }}
                            -
                            {{ formatDate(item.end_date) }}
                        </span>

                        <span v-else-if="item.start_date">
                            {{ formatDate(item.start_date) }}
                        </span>

                        <span v-else>
                            -
                        </span>
                    </div>

                    <div v-if="item.time" class="text-xs text-slate-500 dark:text-slate-400">
                        Pukul {{ formatTime(item.time) }} WIB
                    </div>
                </div>
            </template>

            <template #status="{ item }">
                <div class="relative w-full max-w-[160px]">
                    <select v-model="selectedStatuses[item.id]" @change="updateStatus(item)"
                        class="w-full appearance-none rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 pr-8 text-sm text-gray-700 dark:text-gray-100 shadow-sm hover:border-blue-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-offset-0 focus:ring-blue-500/20 transition-all duration-200">
                        <option value="rencana">
                            Rencana
                        </option>

                        <option value="berjalan">
                            Berjalan
                        </option>

                        <option value="selesai">
                            Selesai
                        </option>

                        <option value="batal">
                            Batal
                        </option>
                    </select>

                    <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <ChevronDown class="w-4 h-4" />
                    </div>
                </div>
            </template>

            <template #is_public="{ item }">
                <div class="relative w-full max-w-[120px]">
                    <select v-model="selectedPublics[item.id]" @change="updatePublic(item)"
                        class="w-full appearance-none rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 pr-8 text-sm text-gray-700 dark:text-gray-100 shadow-sm hover:border-blue-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-offset-0 focus:ring-blue-500/20 transition-all duration-200">
                        <option value="1">
                            Publik
                        </option>

                        <option value="0">
                            Privat
                        </option>
                    </select>

                    <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <ChevronDown class="w-4 h-4" />
                    </div>
                </div>
            </template>

            <template #actions="{ item }">
                <div class="flex items-center gap-2">

                    <!-- View -->
                    <button @click="viewProker(item)" class="p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        <Eye class="w-4 h-4" />
                    </button>

                    <!-- Edit -->
                    <button @click="editProker(item)"
                        class="p-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
                        <Pencil class="w-4 h-4" />
                    </button>

                    <!-- Delete -->
                    <button @click="deleteProker(item)" class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        <Trash2 class="w-4 h-4" />
                    </button>

                </div>
            </template>
        </Table>

        <CalendarView v-else :prokers="prokers.data" />

        <!-- CRUD -->
        <CreateProkerModal :show="createModal" :departments="departments" :pengurus="pengurus"
            @close="createModal = false" />

        <EditProkerModal :show="editModal" :proker="selectedProker" :departments="departments" :pengurus="pengurus"
            @close="editModal = false" />

        <ViewProkerModal :show="viewModal" :proker="selectedProker" @close="viewModal = false" />

        <CompleteProkerModal :show="realizationModal" :proker="selectedRealization" @close="realizationModal = false" />
    </AppLayout>
</template>