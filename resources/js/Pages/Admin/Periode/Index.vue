<template>
    <AppLayout>
        <Head title="Data Periode" />

        <!-- Main Container -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs transition-all border border-gray-200 dark:border-gray-700"
        >
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row gap-4 justify-between items-center mb-6"
            >
                <!-- Search -->
                <div class="relative w-full md:w-80">
                    <Search
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                    />
                    <input
                        v-model="filters.search"
                        @input="applyFilters"
                        placeholder="Cari periode / tahun..."
                        class="pl-10 pr-4 py-2 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 outline-none transition-all shadow-sm hover:shadow-md"
                    />
                </div>

                <div class="flex items-center gap-3">
                    <!-- Tombol Lihat Jabatan -->
                    <button
                        @click="goToJabatan"
                        class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 dark:hover:from-gray-700 dark:hover:to-gray-800 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95"
                    >
                        <span class="hidden md:block">Jabatan</span>
                        <ArrowUpRight
                            class="w-4 h-4 transition-transform duration-300"
                        />
                    </button>

                    <!-- Button Tambah -->
                    <button
                        @click="openCreateModal"
                        class="group relative flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                        ></div>
                        <Plus
                            class="h-5 w-5 transition-transform group-hover:rotate-90"
                        />
                        <span class="hidden md:block">Tambah Periode</span>
                    </button>
                </div>
            </div>

            <div
                class="md:bg-white md:dark:bg-gray-900 rounded-2xl md:border md:border-gray-200 md:dark:border-gray-700 md:shadow-lg overflow-hidden"
            >
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead
                            class="bg-gradient-to-r from-yellow-600 to-yellow-700 text-white uppercase text-xs tracking-wide"
                        >
                            <tr>
                                <th class="px-4 py-3">No</th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('periode')"
                                >
                                    Periode
                                    <SortIcon
                                        field="periode"
                                        :filters="filters"
                                    />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('tahun_mulai')"
                                >
                                    Tahun Mulai
                                    <SortIcon
                                        field="tahun_mulai"
                                        :filters="filters"
                                    />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('tahun_selesai')"
                                >
                                    Tahun Selesai
                                    <SortIcon
                                        field="tahun_selesai"
                                        :filters="filters"
                                    />
                                </th>

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
                                    {{ item.periode || "-" }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ item.tahun_mulai || "-" }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ item.tahun_selesai || "-" }}
                                </td>

                                <td class="px-4 py-3">
                                    <div
                                        class="relative inline-block text-left"
                                    >
                                        <!-- BUTTON -->
                                        <button
                                            @click.stop="
                                                toggleDropdown(item, $event)
                                            "
                                            class="flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-gray-800 hover:bg-yellow-100 dark:hover:bg-yellow-900/20 text-gray-900 dark:text-gray-100 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all"
                                        >
                                            Aksi
                                            <ChevronDown class="w-4 h-4" />
                                        </button>

                                        <!-- DROPDOWN -->
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
                                                    ref="dropdownRef"
                                                    class="absolute w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden dropdown-menu-active"
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
                                                                openEditModal(
                                                                    item
                                                                )
                                                            "
                                                            class="px-4 py-2 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 cursor-pointer"
                                                        >
                                                            Edit
                                                        </li>
                                                        <li
                                                            @click="
                                                                destroy(item.id)
                                                            "
                                                            class="px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 cursor-pointer"
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

                            <tr v-if="data.data.length === 0">
                                <td
                                    colspan="5"
                                    class="py-6 text-center text-gray-500"
                                >
                                    Tidak ada data jabatan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="data.data.length"
                class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
            >
                <!-- SHOW PER PAGE -->
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

                <!-- PAGINATION BUTTON -->
                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in data.links"
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
                            v-else-if="index === data.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Create / Edit Jabatan -->
        <teleport to="body">
            <!-- BACKDROP -->
            <Transition
                enter-active-class="transition-all duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-all duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="openModal"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40"
                    @click="closeModal"
                ></div>
            </Transition>

            <!-- MODAL -->
            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-4"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-4"
            >
                <div
                    v-if="openModal"
                    class="fixed inset-0 flex items-center justify-center z-50 p-4"
                    @click="closeModal"
                >
                    <div
                        class="relative bg-white dark:bg-gray-900 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg"
                        @click.stop
                    >
                        <!-- Header -->
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-100 to-transparent dark:from-green-950/30 rounded-t-xl"
                        >
                            <div class="flex items-center justify-between">
                                <h2
                                    class="text-xl dark:text-gray-300 font-bold"
                                >
                                    {{
                                        isEdit
                                            ? "Edit Periode"
                                            : "Tambah Periode"
                                    }}
                                </h2>
                                <button @click="closeModal">
                                    <X
                                        class="w-5 h-5 text-gray-400 hover:text-green-600"
                                    />
                                </button>
                            </div>
                        </div>

                        <!-- FORM -->
                        <form @submit.prevent="submit" class="p-6 space-y-4">
                            <!-- Periode -->
                            <div>
                                <label
                                    class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                >
                                    Periode
                                </label>
                                <input
                                    v-model="form.periode"
                                    type="text"
                                    required
                                    placeholder="Tulis menggunakan romawi"
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                />
                            </div>

                            <!-- Tahun Mulai -->
                            <div>
                                <label
                                    class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                >
                                    Tahun Mulai
                                </label>
                                <input
                                    v-model="form.tahun_mulai"
                                    type="number"
                                    min="1900"
                                    max="2100"
                                    required
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                />
                            </div>

                            <!-- Tahun Selesai -->
                            <div>
                                <label
                                    class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                >
                                    Tahun Selesai
                                </label>
                                <input
                                    v-model="form.tahun_selesai"
                                    type="number"
                                    min="1900"
                                    max="2100"
                                    required
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                />
                            </div>

                            <!-- SUBMIT -->
                            <button
                                type="submit"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-lg"
                            >
                                <Save class="w-5 h-5" />
                                <span>{{
                                    isEdit ? "Update Periode" : "Simpan Periode"
                                }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </Transition>
        </teleport>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import {
    Search,
    Plus,
    ArrowUpRight,
    ArrowUp,
    ArrowDown,
    ChevronLeft,
    ChevronRight,
    ChevronDown,
    X,
    Save,
} from "lucide-vue-next";

const props = defineProps({
    data: Object,
    filters: Object,
});

const goToJabatan = () => {
    router.visit(route("jabatan.index"));
};

/* MODAL STATE */
const openModal = ref(false);
const isEdit = ref(false);
const editId = ref(null);

/* FORM */
const form = ref({
    periode: "",
    tahun_mulai: "",
    tahun_selesai: "",
});

/* OPEN CREATE */
const openCreateModal = () => {
    isEdit.value = false;
    editId.value = null;

    form.value = {
        periode: "",
        tahun_mulai: "",
        tahun_selesai: "",
    };

    openModal.value = true;
};

/* OPEN EDIT */
const openEditModal = (item) => {
    isEdit.value = true;
    editId.value = item.id;

    form.value = {
        periode: item.periode,
        tahun_mulai: item.tahun_mulai,
        tahun_selesai: item.tahun_selesai,
    };

    openModal.value = true;
};

/* CLOSE */
const closeModal = () => {
    openModal.value = false;
};

const submit = () => {
    if (isEdit.value) {
        router.put(route("periode.update", editId.value), form.value, {
            onSuccess: closeModal,
        });
    } else {
        router.post(route("periode.store"), form.value, {
            onSuccess: closeModal,
        });
    }
};

// FILTERS AND SORTING
const filters = ref({
    search: props.filters?.search || "",
    per_page: props.filters?.per_page || 10,
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
});

function applyFilters() {
    router.get(route("periode.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

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

function goTo(url) {
    if (url) {
        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
        });
    }
}

// DROPDOWN ACTIONS
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });

function toggleDropdown(item, event) {
    const rect = event.currentTarget.getBoundingClientRect();
    const dropdownWidth = 224; // w-56

    if (openDropdown.value && selectedItem.value?.id === item.id) {
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
