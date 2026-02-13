<template>
    <AppLayout>
        <Head title="Prestasi" />

        <Alert />

        <!-- Banner Prestasi -->
        <div
            class="md:w-2/3 mb-4 p-4 md:p-6 rounded-2xl relative shadow-lg bg-gradient-to-r from-green-600 via-lime-600 to-yellow-600 text-white flex flex-col md:flex-row justify-between items-left"
        >
            <!-- Dekorasi Lingkaran -->
            <div
                class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-xl"
            ></div>
            <div
                class="hidden md:block absolute top-0 -right-10 md:right-10 w-52 h-52 bg-white/50 rounded-full blur-2xl"
            ></div>

            <!-- Text -->
            <div class="relative z-50">
                <p class="text-sm opacity-80">
                    Prestasi Santri - Edisi Terbaru
                </p>
                <h2
                    class="text-shadow-xs text-shadow-black/50 text-2xl md:text-3xl font-bold mt-1"
                >
                    Kumpulan Prestasi Santri!
                </h2>

                <div
                    class="w-fit mt-4 px-5 py-2 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md transition-all text-white font-medium shadow-md"
                >
                    Rayon IKSASS Banten
                </div>
            </div>

            <!-- Trophy Image -->
            <div
                class="absolute z-10 right-0 md:right-20 bottom-0 mt-6 md:mt-0"
            >
                <img
                    src="/public/images/piala.png"
                    alt="Trophy"
                    class="w-28 drop-shadow-xl"
                />
            </div>
        </div>

        <!-- main tabel -->
        <div
            class="p-4 sm:p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 transition-all duration-500 ease-in-out"
        >
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row flex-wrap gap-4 justify-between items-center mb-6"
            >
                <div class="flex gap-3 w-full md:w-auto">
                    <input
                        type="text"
                        v-model="filters.search"
                        @input="applyFilters"
                        placeholder="Cari..."
                        class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none placeholder-gray-400 transition-all duration-300 shadow-sm hover:shadow-md"
                    />
                </div>

                <button
                    @click="goToCreate"
                    class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 active:scale-95 w-full md:w-auto"
                >
                    <PlusCircle class="w-5 h-5" />
                    <span>Tambah Prestasi</span>
                </button>
            </div>

            <!-- Desktop Table (tetap sama) -->
            <div
                class="hidden lg:block bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden transition-all duration-300"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-green-600 to-green-700 text-white uppercase text-xs tracking-wide"
                            >
                                <th class="px-4 py-3">No</th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('nama')"
                                >
                                    Santri
                                    <SortIcon field="nama" :filters="filters" />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('prestasi')"
                                >
                                    Prestasi
                                    <SortIcon
                                        field="prestasi"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('tingkat')"
                                >
                                    Tingkat
                                    <SortIcon
                                        field="tingkat"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('tempat')"
                                >
                                    Tempat
                                    <SortIcon
                                        field="tempat"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('tanggal')"
                                >
                                    Tanggal
                                    <SortIcon
                                        field="tanggal"
                                        :filters="filters"
                                    />
                                </th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(item, index) in prestasi.data"
                                :key="item.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                            >
                                <td class="px-4 py-3">
                                    {{ prestasi.from + index }}
                                </td>
                                <td class="px-4 py-3">
                                    <button
                                        @click="
                                            router.visit(
                                                route(
                                                    'prestasi.byWarga',
                                                    item.id_warga
                                                )
                                            )
                                        "
                                        class="flex items-center gap-2 text-left hover:opacity-80 transition"
                                    >
                                        <img
                                            v-if="item.warga.foto"
                                            :src="`/storage/${item.warga.foto}`"
                                            class="w-10 h-10 rounded-full object-cover"
                                        />
                                        <div>
                                            <p
                                                class="font-semibold hover:underline"
                                            >
                                                {{ item.warga.nama }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 hover:underline"
                                            >
                                                {{ item.warga.nis }}
                                            </p>
                                        </div>
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    {{ item.prestasi ?? "-" }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"
                                    >
                                        {{ item.tingkat ?? "-" }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ item.tempat }}</td>
                                <td class="px-4 py-3">{{ item.tanggal }}</td>
                                <td class="px-4 py-3">
                                    <div
                                        class="relative inline-block text-left"
                                    >
                                        <button
                                            @click.stop="
                                                toggleDropdown(item, $event)
                                            "
                                            class="aksi-button flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 text-gray-900 dark:text-gray-100 dark:hover:text-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all duration-300 group/btn"
                                        >
                                            <span class="font-medium"
                                                >Aksi</span
                                            >
                                            <ChevronDown
                                                class="w-4 h-4 transition-transform duration-300 group-hover/btn:rotate-180"
                                            />
                                        </button>

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
                                                        selectedItem?.id ===
                                                            item.id
                                                    "
                                                    ref="dropdownRef"
                                                    class="absolute z-50 w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden dropdown-menu-active"
                                                    :style="{
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
                                                                goToEdit(
                                                                    item.id
                                                                )
                                                            "
                                                            class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer transition-all"
                                                        >
                                                            Edit
                                                        </li>
                                                        <li
                                                            @click="
                                                                destroy(item.id)
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
            </div>

            <!-- Mobile Cards (untuk layar kecil) -->
            <div class="lg:hidden space-y-4">
                <div
                    v-for="(item, index) in prestasi.data"
                    :key="item.id"
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300 p-4"
                >
                    <!-- Header dengan foto dan nama -->
                    <div class="flex items-start justify-between mb-3">
                        <button
                            @click="
                                router.visit(
                                    route('prestasi.byWarga', item.id_warga)
                                )
                            "
                            class="flex items-center gap-3 text-left hover:opacity-80 transition flex-1"
                        >
                            <img
                                v-if="item.warga.foto"
                                :src="`/storage/${item.warga.foto}`"
                                class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-600"
                            />
                            <div class="flex-1 min-w-0">
                                <p
                                    class="font-semibold text-gray-900 dark:text-gray-100 truncate hover:underline"
                                >
                                    {{ item.warga.nama }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400 truncate"
                                >
                                    {{ item.warga.nis }}
                                </p>
                            </div>
                        </button>

                        <!-- Badge Tingkat -->
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 whitespace-nowrap ml-2"
                        >
                            {{ item.tingkat ?? "-" }}
                        </span>
                    </div>

                    <!-- Prestasi -->
                    <div class="mb-3">
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 mb-1"
                        >
                            Prestasi
                        </p>
                        <p
                            class="font-medium text-gray-900 dark:text-gray-100 line-clamp-2"
                        >
                            {{ item.prestasi ?? "-" }}
                        </p>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs">
                                Tempat
                            </p>
                            <p
                                class="font-medium text-gray-900 dark:text-gray-100 truncate"
                            >
                                {{ item.tempat || "-" }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs">
                                Tanggal
                            </p>
                            <p
                                class="font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ item.tanggal }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700"
                    >
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            #{{ prestasi.from + index }}
                        </span>
                        <div class="flex items-center gap-2">
                            <button
                                @click="goToEdit(item.id)"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200"
                            >
                                <Edit class="w-3 h-3" />
                                Edit
                            </button>
                            <button
                                @click="destroy(item.id)"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200"
                            >
                                <Trash2 class="w-3 h-3" />
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!prestasi.data.length" class="text-center py-12">
                <div
                    class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center"
                >
                    <Award class="w-10 h-10 text-gray-400" />
                </div>
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2"
                >
                    Tidak ada data prestasi
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Coba ubah pencarian atau tambah prestasi baru
                </p>
                <button
                    @click="goToCreate"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300"
                >
                    <PlusCircle class="w-5 h-5" />
                    <span>Tambah Prestasi Pertama</span>
                </button>
            </div>

            <!-- PAGINATION -->
            <div
                v-if="prestasi.data.length"
                class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
            >
                <div class="flex items-center gap-2">
                    <label
                        for="per_page"
                        class="text-sm text-gray-600 dark:text-gray-300"
                        >Show</label
                    >
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
                        v-for="(link, index) in prestasi.links"
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
                            v-else-if="index === prestasi.links.length - 1"
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
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { router, Head } from "@inertiajs/vue3";
import Alert from "@/Components/Alert.vue";
import {
    ChevronLeft,
    ChevronRight,
    ChevronDown,
    ArrowUp,
    ArrowDown,
    PlusCircle,
    Edit,
    Trash2,
    Award,
} from "lucide-vue-next";

import { ref, onMounted, onBeforeUnmount, computed } from "vue";

const props = defineProps({
    prestasi: Object,
    warga: Array,
    filters: Object,
});

// === FILTERS ===
const filters = ref({
    search: props.filters?.search || "",
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
    per_page: props.filters?.per_page || 10,
});

function applyFilters() {
    router.get(route("prestasi.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

// === SORTING ===
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

    applyFilters();
}

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

// === DROPDOWN AKSI ===
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });
const dropdownRef = ref(null);

// Open dropdown
function toggleDropdown(item, event) {
    const rect = event.target.getBoundingClientRect();
    const isSame = selectedItem.value?.id === item.id && openDropdown.value;

    if (isSame) {
        openDropdown.value = false;
        selectedItem.value = null;
        return;
    }

    selectedItem.value = item;
    openDropdown.value = true;

    dropdownPosition.value = {
        top: rect.bottom + window.scrollY,
        left: rect.left,
    };
}

// Close dropdown on outside click
function handleGlobalClick(event) {
    const target = event.target;
    const dropdownEl = document.querySelector(".dropdown-menu-active");

    if (
        openDropdown.value &&
        dropdownEl &&
        !dropdownEl.contains(target) &&
        !target.closest(".aksi-button")
    ) {
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

// === PAGINATION ===
function goTo(url) {
    if (url) router.visit(url, { preserveScroll: true });
}

const goToCreate = () => {
    router.visit(route("prestasi.create"));
};

const goToEdit = (id) => {
    router.visit(route("prestasi.edit", id));
};

// === DELETE ===
const destroy = (id) => {
    if (confirm("Yakin ingin menghapus data ini?")) {
        router.delete(route("prestasi.destroy", id));
    }
};
</script>
