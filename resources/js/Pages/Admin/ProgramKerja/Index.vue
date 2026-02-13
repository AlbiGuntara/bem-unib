<template>
    <AppLayout>
        <Head title="Program Kerja" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <!-- KIRI : CALENDAR -->
            <div class="lg:col-span-2">
                <ProgramKerjaCalendar :events="events" />
            </div>

            <!-- KANAN : PROGRAM KERJA MENDATANG -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-all duration-300"
            >
                <!-- Header -->
                <div
                    class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 rounded-t-2xl"
                >
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <div
                            class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg"
                        >
                            <Calendar
                                class="w-5 h-5 text-green-600 dark:text-green-400"
                            />
                        </div>

                        <!-- Title -->
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-gray-100"
                            >
                                Program Kerja Mendatang
                            </h3>
                            <p
                                class="text-sm text-gray-600 dark:text-gray-300 mt-0.5"
                            >
                                Agenda terdekat yang akan dilaksanakan
                            </p>
                        </div>
                    </div>
                </div>

                <!-- List -->
                <div
                    class="divide-y divide-gray-100 dark:divide-gray-800 max-h-[440px] overflow-y-auto"
                >
                    <div
                        v-for="(item, index) in upcomingPrograms"
                        :key="item.id"
                        class="p-5 flex gap-4 hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-all duration-300 group cursor-pointer"
                        :class="{
                            'border-l-4 border-l-green-500 dark:border-l-green-400':
                                index === 0,
                        }"
                    >
                        <!-- Tanggal Box -->
                        <div class="flex-shrink-0 w-20 text-center">
                            <div
                                class="bg-green-50 dark:bg-green-900/20 rounded-xl p-2.5"
                            >
                                <template v-if="item.tanggal_mulai">
                                    <div
                                        class="text-green-700 dark:text-green-300 font-bold text-lg"
                                    >
                                        {{
                                            new Date(
                                                item.tanggal_mulai
                                            ).getDate()
                                        }}
                                    </div>
                                    <div
                                        class="text-green-600 dark:text-green-400 text-xs font-medium uppercase"
                                    >
                                        {{
                                            new Date(
                                                item.tanggal_mulai
                                            ).toLocaleDateString("id-ID", {
                                                month: "short",
                                            })
                                        }}
                                    </div>
                                </template>
                                <template v-else>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 text-sm"
                                    >
                                        -
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Date Range -->
                            <p
                                class="text-xs text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-1.5"
                            >
                                <Clock class="w-3.5 h-3.5" />

                                <template
                                    v-if="
                                        item.tanggal_mulai &&
                                        item.tanggal_selesai &&
                                        item.tanggal_mulai !==
                                            item.tanggal_selesai
                                    "
                                >
                                    {{ tanggal(item.tanggal_mulai) }} –
                                    {{ tanggal(item.tanggal_selesai) }}
                                </template>

                                <template v-else-if="item.tanggal_mulai">
                                    {{ tanggal(item.tanggal_mulai) }}
                                </template>

                                <template v-else>-</template>
                            </p>

                            <!-- Nama Program -->
                            <h4
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100 leading-snug group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300"
                            >
                                {{ item.nama_kegiatan }}
                            </h4>

                            <!-- Status Indicator -->
                            <div class="flex items-center gap-2 mt-2.5">
                                <span
                                    class="block w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"
                                ></span>
                                <span
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    Akan datang
                                </span>
                            </div>
                        </div>

                        <!-- Chevron -->
                        <div
                            class="flex-shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        >
                            <ChevronRight class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="!upcomingPrograms.length"
                        class="p-8 text-center"
                    >
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full mb-4"
                        >
                            <Calendar class="w-8 h-8 text-gray-400" />
                        </div>
                        <h4
                            class="text-gray-700 dark:text-gray-300 font-medium mb-1"
                        >
                            Belum ada program kerja mendatang
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Program kerja yang akan datang akan muncul di sini
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Container -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs transition-all border border-gray-200 dark:border-gray-700"
        >
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row gap-4 justify-between items-center mb-6"
            >
                <div class="flex gap-4 items-center justify-center">
                    <!-- Search -->
                    <div class="relative w-full md:w-80">
                        <Search
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                        />
                        <input
                            v-model="filters.search"
                            @input="applyFilters"
                            placeholder="Cari program kerja..."
                            class="pl-10 pr-4 py-2 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 outline-none transition-all shadow-sm hover:shadow-md"
                        />
                    </div>

                    <!-- FILTER BUTTON -->
                    <div class="relative">
                        <button
                            @click="openFilter = !openFilter"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 shadow-sm hover:shadow-md hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                        >
                            <Filter class="w-5 h-5 text-gray-500" />
                            <span class="text-md font-medium">Filter</span>

                            <span
                                v-if="filters.jabatan_id"
                                class="ml-1 px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300"
                            >
                                Aktif
                            </span>
                        </button>
                        <!-- DROPDOWN -->
                        <div
                            v-if="openFilter"
                            @click.outside="openFilter = false"
                            class="absolute right-0 mt-3 w-72 z-50 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl overflow-hidden"
                        >
                            <!-- Header -->
                            <div
                                class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200"
                            >
                                Filter Penanggung Jawab
                            </div>

                            <!-- Option: Semua -->
                            <button
                                @click="
                                    filters.jabatan_id = '';
                                    applyFilters();
                                    openFilter = false;
                                "
                                class="w-full text-left px-4 py-3 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                                :class="
                                    !filters.jabatan_id
                                        ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300'
                                        : 'text-gray-700 dark:text-gray-200'
                                "
                            >
                                Semua Penanggung Jawab
                            </button>

                            <!-- Jabatan List -->
                            <button
                                v-for="jabatan in jabatans"
                                :key="jabatan.id"
                                @click="
                                    filters.jabatan_id = jabatan.id;
                                    applyFilters();
                                    openFilter = false;
                                "
                                class="w-full text-left px-4 py-3 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                                :class="
                                    filters.jabatan_id === jabatan.id
                                        ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300'
                                        : 'text-gray-700 dark:text-gray-200'
                                "
                            >
                                <div class="flex items-center gap-2">
                                    <div class="font-medium">
                                        {{ jabatan.departemen }}
                                    </div>
                                    <span>•</span>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ jabatan.IKSASS }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <button
                        @click="exportExcel"
                        class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 dark:hover:from-gray-700 dark:hover:to-gray-800 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95"
                    >
                        <Download class="h-5 w-5" />
                        <span class="hidden md:block">Export</span>
                    </button>
                </div>

                <!-- Action -->
                <div class="flex items-center gap-3">
                    <button
                        @click="router.visit(route('program-kerja.create'))"
                        class="group relative flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                        ></div>
                        <Plus
                            class="h-5 w-5 transition-transform group-hover:rotate-90"
                        />
                        <span class="hidden md:block">Tambah Program</span>
                    </button>
                </div>
            </div>

            <!-- TABLE -->
            <div
                class="md:bg-white md:dark:bg-gray-900 rounded-2xl md:border md:border-gray-200 md:dark:border-gray-700 md:shadow-lg overflow-hidden"
            >
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead
                            class="bg-gradient-to-r from-green-600 to-green-700 text-white uppercase text-xs tracking-wide"
                        >
                            <tr>
                                <th class="px-4 py-3">No</th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('nama_kegiatan')"
                                >
                                    Nama Kegiatan
                                    <SortIcon
                                        field="nama_kegiatan"
                                        :filters="filters"
                                    />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('tanggal_mulai')"
                                >
                                    Pelaksanaan
                                    <SortIcon
                                        field="tanggal_mulai"
                                        :filters="filters"
                                    />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('jabatan_id')"
                                >
                                    Penanggung Jawab
                                    <SortIcon
                                        field="jabatan_id"
                                        :filters="filters"
                                    />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('peserta')"
                                >
                                    Peserta
                                    <SortIcon
                                        field="peserta"
                                        :filters="filters"
                                    />
                                </th>

                                <th class="px-4 py-3">Anggaran</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(item, index) in data.data"
                                :key="item.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                            >
                                <td class="px-4 py-3">
                                    {{ data.from + index }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ item.nama_kegiatan }}
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        v-if="
                                            item.tanggal_mulai &&
                                            item.tanggal_selesai
                                        "
                                    >
                                        <span
                                            v-if="
                                                item.tanggal_mulai ===
                                                item.tanggal_selesai
                                            "
                                        >
                                            {{ tanggal(item.tanggal_mulai) }}
                                        </span>
                                        <span v-else>
                                            {{ tanggal(item.tanggal_mulai) }} -
                                            {{ tanggal(item.tanggal_selesai) }}
                                        </span>
                                    </span>
                                    <span v-else-if="item.tanggal_mulai">
                                        {{ tanggal(item.tanggal_mulai) }}
                                    </span>
                                    <span v-else> - </span>
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ring-1 ring-inset transition"
                                        :class="
                                            item.jabatan?.IKSASS ===
                                            'IKSASS Alumni'
                                                ? 'bg-green-100 text-green-700 ring-green-300 dark:bg-green-900/30 dark:text-green-300 dark:ring-green-700'
                                                : 'bg-red-100 text-red-700 ring-red-300 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-700'
                                        "
                                    >
                                        <!-- Dot indicator -->
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="
                                                item.jabatan?.IKSASS ===
                                                'IKSASS Alumni'
                                                    ? 'bg-green-500'
                                                    : 'bg-red-500'
                                            "
                                        ></span>

                                        {{ item.jabatan?.departemen ?? "—" }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    {{ item.peserta ?? "-" }}
                                </td>

                                <td class="px-4 py-3">
                                    Rp
                                    {{
                                        Number(
                                            item.anggaran || 0
                                        ).toLocaleString("id-ID")
                                    }}
                                </td>

                                <!-- AKSI -->
                                <td class="px-4 py-3">
                                    <div class="relative inline-block">
                                        <button
                                            @click.stop="
                                                toggleDropdown(item, $event)
                                            "
                                            class="flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-gray-800 hover:bg-green-100 dark:hover:bg-green-900/20 rounded-xl border border-gray-200 dark:border-gray-700"
                                        >
                                            Aksi
                                            <ChevronDown class="w-4 h-4" />
                                        </button>

                                        <teleport to="body">
                                            <Transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-from-class="opacity-0 translate-y-2"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-from-class="opacity-100"
                                                leave-to-class="opacity-0 translate-y-2"
                                            >
                                                <div
                                                    v-if="
                                                        openDropdown &&
                                                        selectedItem?.id ===
                                                            item.id
                                                    "
                                                    class="absolute w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden"
                                                    :style="dropdownPosition"
                                                >
                                                    <ul class="text-sm">
                                                        <li
                                                            @click="
                                                                router.visit(
                                                                    route(
                                                                        'program-kerja.edit',
                                                                        item.id
                                                                    )
                                                                )
                                                            "
                                                            class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer"
                                                        >
                                                            Edit
                                                        </li>
                                                        <li
                                                            @click="
                                                                destroy(item.id)
                                                            "
                                                            class="px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 cursor-pointer"
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

                            <tr v-if="!data.data.length">
                                <td
                                    colspan="6"
                                    class="py-6 text-center text-gray-500"
                                >
                                    Tidak ada data program kerja
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PAGINATION -->
            <div
                v-if="data.data.length"
                class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
            >
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

                <div class="flex gap-1">
                    <button
                        v-for="(link, i) in data.links"
                        :key="i"
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
                        <ChevronLeft v-if="i === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="i === data.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <Dokumentasi
            :dokumentasis="props.dokumentasis"
            :programKerjas="props.data.data"
        />
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { tanggal } from "@/helpers";
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import {
    Search,
    Filter,
    Download,
    Plus,
    ChevronDown,
    ArrowUp,
    ArrowDown,
    ChevronLeft,
    ChevronRight,
    Calendar,
    Clock,
} from "lucide-vue-next";
import ProgramKerjaCalendar from "@/Components/ProgramKerja/Calendar.vue";
import Dokumentasi from "@/Components/ProgramKerja/Dokumentasi.vue";

// Props
const props = defineProps({
    data: Object,
    events: Array,
    filters: Object,
    jabatans: {
        type: Array,
        default: () => [],
    },
    dokumentasis: {
        type: Array,
        default: () => [],
    },
});

/* UPCOMING PROGRAMS */
const upcomingPrograms = computed(() => {
    if (!props.data?.data) return [];

    return [...props.data.data]
        .filter((item) => item.tanggal_mulai) // pastikan ada tanggal
        .sort((a, b) => {
            return (
                new Date(a.tanggal_mulai).getTime() -
                new Date(b.tanggal_mulai).getTime()
            );
        })
        .slice(0, 5);
});

/* FILTER STATE */
const filters = ref({
    search: props.filters?.search || "",
    per_page: props.filters?.per_page || 10,
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
    jabatan_id: props.filters?.jabatan_id || "",
});

/* APPLY FILTER */
function applyFilters() {
    router.get(route("program-kerja.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

const openFilter = ref(false);

const exportExcel = () => {
    window.location.href = route("program-kerja.export");
};

/* SORT 3 ARAH */
function sort(column) {
    if (filters.value.sort_by !== column) {
        filters.value.sort_by = column;
        filters.value.sort_direction = "asc";
    } else {
        filters.value.sort_direction =
            filters.value.sort_direction === "asc"
                ? "desc"
                : filters.value.sort_direction === "desc"
                ? ""
                : "asc";

        if (!filters.value.sort_direction) {
            filters.value.sort_by = "";
        }
    }

    applyFilters();
}

/* SORT ICON */
const SortIcon = {
    props: ["field", "filters"],
    components: { ArrowUp, ArrowDown },
    template: `
        <span class="inline-flex ml-1">
            <ArrowUp v-if="filters.sort_by === field && filters.sort_direction === 'asc'" class="w-3 h-3" />
            <ArrowDown v-else-if="filters.sort_by === field && filters.sort_direction === 'desc'" class="w-3 h-3" />
            <span v-else class="text-xs">↕</span>
        </span>
    `,
};

/* DROPDOWN */
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = ref({});

function toggleDropdown(item, event) {
    const rect = event.currentTarget.getBoundingClientRect();

    if (openDropdown.value && selectedItem.value?.id === item.id) {
        openDropdown.value = false;
        selectedItem.value = null;
        return;
    }

    selectedItem.value = item;
    openDropdown.value = true;

    dropdownPosition.value = {
        top: rect.bottom + window.scrollY + "px",
        left: rect.left + "px",
    };
}

/* DELETE */
function destroy(id) {
    if (confirm("Yakin hapus program kerja?")) {
        router.delete(route("program-kerja.destroy", id));
    }
}

/* PAGINATION */
function goTo(url) {
    if (url) {
        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
        });
    }
}

/* CLOSE DROPDOWN ON OUTSIDE CLICK */
function handleGlobalClick(e) {
    if (!e.target.closest(".relative")) {
        openDropdown.value = false;
        selectedItem.value = null;
    }
}

onMounted(() => {
    document.addEventListener("click", handleGlobalClick);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleGlobalClick);
});
</script>
