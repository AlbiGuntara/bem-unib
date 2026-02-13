<template>
    <AppLayout>
        <Head title="Data Warga" />

        <div class="space-y-4 mb-4">
            <!-- Bagian Grafik Pendidikan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Grafik Pendidikan Pagi -->
                <div
                    class="bg-white dark:bg-gray-900 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700 flex flex-col items-center"
                >
                    <h4
                        class="font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center"
                    >
                        Grafik Pendidikan Pagi
                    </h4>
                    <div class="w-full aspect-square max-w-xs mx-auto">
                        <Doughnut
                            :data="chartData.pagi"
                            :options="chartOptions"
                        />
                    </div>
                </div>

                <!-- Grafik Pendidikan Sore -->
                <div
                    class="bg-white dark:bg-gray-900 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700 flex flex-col items-center"
                >
                    <h4
                        class="font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center"
                    >
                        Grafik Pendidikan Sore
                    </h4>
                    <div class="w-full aspect-square max-w-xs mx-auto">
                        <Doughnut
                            :data="chartData.sore"
                            :options="chartOptions"
                        />
                    </div>
                </div>

                <!-- Grafik Pendidikan Malam -->
                <div
                    class="bg-white dark:bg-gray-900 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700 flex flex-col items-center"
                >
                    <h4
                        class="font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center"
                    >
                        Grafik Pendidikan Malam
                    </h4>
                    <div class="w-full aspect-square max-w-xs mx-auto">
                        <Doughnut
                            :data="chartData.malam"
                            :options="chartOptions"
                        />
                    </div>
                </div>
            </div>

            <!-- Filter Status -->
            <div
                class="md:px-50 bg-white dark:bg-gray-900 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700"
            >
                <div class="flex flex-wrap justify-between gap-2">
                    <button
                        v-for="status in ['Semua', 'Santri', 'Alumni']"
                        :key="status"
                        @click="filterByStatus(status)"
                        :class="[
                            'px-4 py-2 rounded-lg transition-all duration-200 text-sm font-medium',
                            filters.status === status ||
                            (status === 'Semua' && !filters.status)
                                ? 'bg-green-600 text-white shadow-md'
                                : 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-green-500 dark:hover:bg-green-900/20',
                        ]"
                    >
                        {{ status }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 transition-all duration-300"
        >
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row flex-wrap gap-4 justify-between items-center mb-6"
            >
                <div class="flex gap-3">
                    <!-- Input Pencarian -->
                    <input
                        type="text"
                        v-model="filters.search"
                        @input="search"
                        placeholder="Cari nama atau alamat..."
                        class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl w-full max-w-xs md:max-w-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none placeholder-gray-400 transition-all duration-300 shadow-sm hover:shadow-md"
                    />
                </div>

                <div class="flex items-center gap-3">
                    <!-- Tombol Export -->
                    <div class="relative" ref="exportRef">
                        <button
                            @click.stop="toggleExportDropdown"
                            class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 dark:hover:from-gray-700 dark:hover:to-gray-800 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95"
                        >
                            <Download class="h-6.5 md:h-5" />
                            <span class="hidden md:block">Export</span>
                            <ChevronDown
                                class="w-4 h-4 transition-transform duration-300"
                                :class="{ 'rotate-180': showExport }"
                            />
                        </button>

                        <!-- Dropdown Menu Export -->
                        <Transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-2"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 translate-y-2"
                        >
                            <div
                                v-if="showExport"
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden z-50"
                            >
                                <ul
                                    class="py-1 text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <li
                                        @click.stop="exportToPDF"
                                        class="flex items-center gap-2 px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer transition-all"
                                    >
                                        <FileText
                                            class="w-4 h-4 text-green-600 dark:text-green-400"
                                        />
                                        Export ke PDF
                                    </li>
                                    <li
                                        @click.stop="exportToExcel"
                                        class="flex items-center gap-2 px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer transition-all"
                                    >
                                        <FileSpreadsheet
                                            class="w-4 h-4 text-green-600 dark:text-green-400"
                                        />
                                        Export ke Excel
                                    </li>
                                </ul>
                            </div>
                        </Transition>
                    </div>

                    <!-- Tombol Tambah -->
                    <Link
                        :href="route('warga.create')"
                        class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 active:scale-95"
                    >
                        <UserPlus class="h-6.5 md:h-5" />
                        <span class="hidden md:block">Tambah Warga</span>
                    </Link>
                </div>
            </div>

            <!-- Table Container -->
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden transition-all duration-300"
            >
                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-green-600 to-green-700 text-white uppercase text-xs tracking-wide"
                            >
                                <th class="px-4 py-3">No</th>
                                <th
                                    class="px-4 py-3 cursor-pointer select-none"
                                    @click="sort('nama')"
                                >
                                    Nama
                                    <SortIcon
                                        :field="'nama'"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer select-none"
                                    @click="sort('asrama')"
                                >
                                    Asrama
                                    <SortIcon
                                        :field="'asrama'"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer select-none"
                                    @click="sort('status')"
                                >
                                    Status
                                    <SortIcon
                                        :field="'status'"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer select-none"
                                    @click="sort('kecamatan')"
                                >
                                    Alamat
                                    <SortIcon
                                        :field="'kecamatan'"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer select-none"
                                    @click="sort('tahun_mondok')"
                                >
                                    Mondok / Lulus
                                    <SortIcon
                                        :field="'tahun_mondok'"
                                        :filters="filters"
                                    />
                                </th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(warga, index) in wargas.data"
                                :key="warga.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                            >
                                <td class="px-4 py-3">
                                    {{ (wargas.from || 0) + index }}
                                </td>
                                <td class="px-4 py-3 flex items-center gap-2">
                                    <img
                                        v-if="warga.foto"
                                        :src="`/storage/${warga.foto}`"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />
                                    <div>
                                        <p class="font-semibold">
                                            {{ warga.nama }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ warga.nis }}
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-800 dark:text-gray-200"
                                >
                                    {{ warga.asrama || "-" }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        :class="[
                                            'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                                            warga.status === 'Santri'
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                                : warga.status === 'Alumni'
                                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
                                                : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                        ]"
                                    >
                                        {{ warga.status }}
                                    </span>
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-800 dark:text-gray-200"
                                >
                                    {{ warga.kecamatan }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-800 dark:text-gray-200"
                                >
                                    {{ warga.tahun_mondok || "-" }} |
                                    <span class="text-sm text-gray-500">
                                        {{ warga.tahun_lulus || "-" }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div
                                        class="relative inline-block text-left"
                                    >
                                        <button
                                            @click.stop="
                                                toggleDropdown(warga, $event)
                                            "
                                            class="flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 text-gray-900 dark:text-gray-100 dark:hover:text-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all duration-300 group/btn"
                                        >
                                            <span class="font-medium"
                                                >Aksi</span
                                            >
                                            <ChevronDown
                                                class="w-4 h-4 transition-transform duration-300 group-hover/btn:rotate-180"
                                            />
                                        </button>

                                        <!-- Dropdown Actions -->
                                        <teleport to="body">
                                            <Transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-from-class="opacity-0 translate-y-2"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-from-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-2"
                                            >
                                                <div
                                                    v-if="
                                                        openDropdown &&
                                                        selectedWarga.id ===
                                                            warga.id
                                                    "
                                                    class="absolute z-50 w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden"
                                                    :style="{
                                                        position: 'absolute',
                                                        top:
                                                            dropdownPosition.top +
                                                            'px',
                                                        left:
                                                            dropdownPosition.left +
                                                            'px',
                                                    }"
                                                >
                                                    <ul
                                                        class="text-sm text-gray-900 dark:text-gray-100"
                                                    >
                                                        <li
                                                            @click="
                                                                viewWarga(warga)
                                                            "
                                                            class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer transition-all"
                                                        >
                                                            Lihat
                                                        </li>
                                                        <li
                                                            @click="
                                                                editWarga(warga)
                                                            "
                                                            class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer transition-all"
                                                        >
                                                            Edit
                                                        </li>
                                                        <li
                                                            @click="
                                                                deleteWarga(
                                                                    warga.id
                                                                )
                                                            "
                                                            class="px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 cursor-pointer transition-all"
                                                        >
                                                            Hapus
                                                        </li>
                                                    </ul>
                                                </div>
                                            </Transition>
                                        </teleport>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile & Tablet Cards -->
                <div class="lg:hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">
                        <div
                            v-for="(warga, index) in wargas.data"
                            :key="warga.id"
                            class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-all duration-300 group"
                        >
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img
                                            :src="
                                                warga.foto
                                                    ? `/storage/${warga.foto}`
                                                    : '/images/default-avatar.jpg'
                                            "
                                            alt="foto"
                                            class="w-12 h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 object-cover"
                                        />
                                        <div
                                            class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-900"
                                        ></div>
                                    </div>
                                    <div>
                                        <h3
                                            class="font-semibold text-gray-900 dark:text-gray-100 text-sm"
                                        >
                                            {{ warga.nama }}
                                        </h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            NIS: {{ warga.nis }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                                        warga.status === 'Santri'
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                            : warga.status === 'Alumni'
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
                                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                    ]"
                                >
                                    {{ warga.status }}
                                </span>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-xs">
                                    <Building
                                        class="w-3 h-3 text-green-600 dark:text-green-400"
                                    />
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >Asrama:</span
                                    >
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        {{ warga.asrama || "-" }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <Calendar
                                        class="w-3 h-3 text-green-600 dark:text-green-400"
                                    />
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >Jabatan:</span
                                    >
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        {{ warga.jabatan || "-" }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <IdCard
                                        class="w-3 h-3 text-green-600 dark:text-green-400"
                                    />
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >Alamat:</span
                                    >
                                    <span
                                        class="font-mono font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        {{ warga.kecamatan }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button
                                    @click="viewWarga(warga)"
                                    class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200"
                                >
                                    <Eye class="w-3 h-3" />
                                    <span>Lihat</span>
                                </button>
                                <button
                                    @click="editWarga(warga)"
                                    class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-lg text-xs font-medium hover:bg-green-100 dark:hover:bg-green-900/30 transition-all duration-200"
                                >
                                    <Edit class="w-3 h-3" />
                                    <span>Edit</span>
                                </button>
                                <button
                                    @click="deleteWarga(warga.id)"
                                    class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg text-xs font-medium hover:bg-red-100 dark:hover:bg-red-900/30 transition-all duration-200"
                                >
                                    <Trash2 class="w-3 h-3" />
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!wargas.data.length" class="text-center py-6">
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2"
                    >
                        Tidak ada warga ditemukan
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        Coba ubah pencarian atau tambah data baru
                    </p>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="wargas.data.length"
                class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
            >
                <!-- Show per page (kiri bawah) -->
                <div class="flex items-center gap-2">
                    <label
                        for="per_page"
                        class="text-sm text-gray-600 dark:text-gray-300"
                    >
                        Show
                    </label>
                    <select
                        id="per_page"
                        v-model="filters.per_page"
                        @change="applyFilters"
                        class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 outline-none transition-all duration-200"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <!-- Pagination buttons (kanan bawah) -->
                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in wargas.links"
                        :key="index"
                        @click="goTo(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200 font-medium"
                        :class="[
                            link.active
                                ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-green-600 shadow-lg scale-105'
                                : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 hover:shadow-md',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="index === wargas.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, Head, router } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount, reactive, computed } from "vue";
import {
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    UserPlus,
    Users,
    ArrowUp,
    ArrowDown,
    Download,
    FileSpreadsheet,
    FileText,
    Eye,
    Edit,
    Trash2,
    Calendar,
    Building,
    IdCard,
} from "lucide-vue-next";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Swal from "sweetalert2";

// ====== IMPORT CHART ======
import { Doughnut } from "vue-chartjs";
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from "chart.js";
ChartJS.register(Title, Tooltip, Legend, ArcElement);

// ====== PROPS ======
const props = defineProps({
    wargas: Object,
    filters: Object,
    chartData: Object,
});

// ====== STATE ======
const filters = ref({
    search: props.filters?.search || "",
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
    per_page: props.filters?.per_page || 10,
    status: props.filters?.status || "",
});

// ====== DROPDOWN ======
const openDropdown = ref(false);
const selectedWarga = ref({});
const dropdownPosition = ref({ top: 0, left: 0 });

// ====== SEARCH REALTIME ======
function search() {
    router.get(route("warga.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

// ====== FILTER / PER PAGE ======
function applyFilters() {
    router.get(route("warga.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

// ====== FILTER STATUS (BARU) ======
function filterByStatus(status) {
    filters.value.status = status === "Semua" ? "" : status;
    applyFilters();

    router.get(route("warga.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

// ====== SORT KOLOM ======
function sort(column) {
    if (filters.value.sort_by === column) {
        if (filters.value.sort_direction === "asc") {
            filters.value.sort_direction = "desc";
        } else if (filters.value.sort_direction === "desc") {
            filters.value.sort_by = "";
            filters.value.sort_direction = "";
        } else {
            filters.value.sort_direction = "asc";
        }
    } else {
        filters.value.sort_by = column;
        filters.value.sort_direction = "asc";
    }

    router.get(route("warga.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

// ====== DROPDOWN AKSI ======
function toggleDropdown(warga, event) {
    selectedWarga.value = warga;
    openDropdown.value = !openDropdown.value;

    const rect = event.target.getBoundingClientRect();
    dropdownPosition.value = {
        top: rect.bottom + window.scrollY,
        left: rect.left,
    };
}

function goTo(url) {
    if (url) router.visit(url, { preserveScroll: true, preserveState: true });
}

function viewWarga(warga) {
    router.visit(route("warga.show", warga.id));
}

function editWarga(warga) {
    router.visit(route("warga.edit", warga.id));
}

function deleteWarga(id) {
    openDropdown.value = false;
    Swal.fire({
        title: "Hapus user ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#e3342f",
        cancelButtonColor: "#6b7280",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("users.destroy", id), {
                onSuccess: () =>
                    Swal.fire("Terhapus!", "User berhasil dihapus.", "success"),
            });
        }
    });
}

// ====== ICON SORT KOMPONEN ======
const SortIcon = {
    props: ["field", "filters"],
    components: { ArrowUp, ArrowDown },
    template: `
        <span class="inline-flex ml-1">
            <ArrowUp
                v-if="filters.sort_by === field && filters.sort_direction === 'asc'"
                class="w-3 h-3 inline-block"
            />
            <ArrowDown
                v-else-if="filters.sort_by === field && filters.sort_direction === 'desc'"
                class="w-3 h-3 inline-block"
            />
            <span v-else class="text-gray-300 dark:text-gray-500 ml-0.5">↕</span>
        </span>
    `,
};

// ====== EXPORT ======
const exportRef = ref(null);
const showExport = ref(false);

function toggleExportDropdown() {
    showExport.value = !showExport.value;
}

function handleClickOutside(event) {
    if (
        showExport.value &&
        exportRef.value &&
        !exportRef.value.contains(event.target)
    ) {
        showExport.value = false;
    }

    if (openDropdown.value && !event.target.closest(".aksi-dropdown-trigger")) {
        openDropdown.value = false;
        selectedWarga.value = {};
    }
}

// === Export ke PDF dengan filter aktif ===
function exportToPDF() {
    showExport.value = false;
    const params = new URLSearchParams(filters.value).toString();
    window.open(route("warga.export.pdf") + "?" + params, "_blank");
}

// === Export ke Excel dengan filter aktif ===
function exportToExcel() {
    showExport.value = false;
    const params = new URLSearchParams(filters.value).toString();
    window.open(route("warga.export.excel") + "?" + params, "_blank");
}

onMounted(() => {
    window.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    window.removeEventListener("click", handleClickOutside);
});

// ====== CHART DATA ======
const chartData = reactive({
    pagi: {
        labels: ["MI", "MTs", "MA", "MTI", "MDI", "Maly", "Lulus"],
        datasets: [
            {
                data: props.chartData
                    ? [
                          props.chartData.pagi.MI,
                          props.chartData.pagi.MTs,
                          props.chartData.pagi.MA,
                          props.chartData.pagi.MTI,
                          props.chartData.pagi.MDI,
                          props.chartData.pagi.Maly,
                          props.chartData.pagi.Lulus,
                      ]
                    : [],
                backgroundColor: [
                    "#16a34a",
                    "#22c55e",
                    "#4ade80",
                    "#86efac",
                    "#bbf7d0",
                    "#a7f3d0",
                    "#6ee7b7",
                ],
            },
        ],
    },
    sore: {
        labels: ["SD", "SMP", "SMA", "SMK", "PT", "Lulus"],
        datasets: [
            {
                data: props.chartData
                    ? [
                          props.chartData.sore.SD,
                          props.chartData.sore.SMP,
                          props.chartData.sore.SMA,
                          props.chartData.sore.SMK,
                          props.chartData.sore.PT,
                          props.chartData.sore.Lulus,
                      ]
                    : [],
                backgroundColor: [
                    "#2563eb",
                    "#3b82f6",
                    "#60a5fa",
                    "#93c5fd",
                    "#bfdbfe",
                    "#dbeafe",
                ],
            },
        ],
    },
    malam: {
        labels: ["Qiraati", "Amtsilati", "Pengajian"],
        datasets: [
            {
                data: props.chartData
                    ? [
                          props.chartData.malam.Qiraati,
                          props.chartData.malam.Amtsilati,
                          props.chartData.malam.Pengajian,
                      ]
                    : [],
                backgroundColor: ["#f97316", "#fb923c", "#fdba74"],
            },
        ],
    },
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
            labels: { color: "#6b7280" },
        },
    },
};
</script>
