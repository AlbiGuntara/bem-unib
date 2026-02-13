<template>
    <AppLayout>
        <Head title="Data Pengurus" />

        <!-- MAIN CONTAINER -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs transition-all border border-gray-200 dark:border-gray-700"
        >
            <!-- HEADER -->
            <div
                class="flex flex-col md:flex-row gap-4 justify-between items-center mb-6"
            >
                <!-- SEARCH -->
                <div class="relative w-full md:w-96">
                    <Search
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                    />
                    <input
                        v-model="filters.search"
                        @input="applyFilters"
                        placeholder="Cari nama / jabatan / periode..."
                        class="pl-10 pr-4 py-2 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 outline-none transition-all shadow-sm hover:shadow-md"
                    />
                </div>

                <!-- ACTION BUTTON -->
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
                    <span class="hidden md:block">Tambah Pengurus</span>
                </button>
            </div>

            <!-- TABLE -->
            <div
                class="hidden lg:block overflow-x-auto rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg"
            >
                <table class="min-w-full text-sm text-left">
                    <thead
                        class="bg-gradient-to-r from-green-600 to-green-700 text-white uppercase text-xs tracking-wide"
                    >
                        <tr>
                            <th class="px-4 py-3">No</th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('nama')"
                            >
                                Nama
                                <SortIcon field="nama" :filters="filters" />
                            </th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('jabatan')"
                            >
                                Jabatan
                                <SortIcon field="jabatan" :filters="filters" />
                            </th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('periode')"
                            >
                                Periode
                                <SortIcon field="periode" :filters="filters" />
                            </th>

                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(item, index) in pengurus.data"
                            :key="item.id"
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                        >
                            <td class="px-4 py-3">
                                {{ pengurus.from + index }}
                            </td>

                            <td class="px-4 py-3">
                                {{ item.warga?.nama || "-" }}
                            </td>

                            <td class="px-4 py-3">
                                {{ item.jabatan?.nama_jabatan || "-" }}
                            </td>

                            <td class="px-4 py-3">
                                {{ item.periode?.periode || "-" }}
                            </td>

                            <!-- DROPDOWN AKSI -->
                            <td class="px-4 py-3">
                                <div class="relative inline-block text-left">
                                    <button
                                        @click.stop="
                                            toggleDropdown(item, $event)
                                        "
                                        class="flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-gray-800 hover:bg-green-100 dark:hover:bg-green-900/20 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all"
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
                                                    selectedItem?.id === item.id
                                                "
                                                class="absolute w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden"
                                                :style="{
                                                    top:
                                                        dropdownPosition.top +
                                                        'px',
                                                    left:
                                                        dropdownPosition.left +
                                                        'px',
                                                }"
                                            >
                                                <ul class="text-sm">
                                                    <li
                                                        @click="
                                                            openEditModal(item)
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

                        <tr v-if="pengurus.data.length === 0">
                            <td
                                colspan="5"
                                class="py-6 text-center text-gray-500"
                            >
                                Tidak ada data pengurus
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div
                v-if="pengurus.data.length"
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
                        v-for="(link, i) in pengurus.links"
                        :key="i"
                        @click="goTo(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200"
                        :class="[
                            link.active
                                ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                                : 'bg-white dark:bg-gray-900',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <ChevronLeft v-if="i === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="i === pengurus.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL CREATE / EDIT PENGURUS -->
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
                        <!-- HEADER -->
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-100 to-transparent dark:from-green-950/30 rounded-t-xl"
                        >
                            <div class="flex items-center justify-between">
                                <h2
                                    class="text-xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    {{
                                        isEdit
                                            ? "Edit Pengurus"
                                            : "Tambah Pengurus"
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
                            <!-- WARGA -->
                            <div>
                                <label
                                    class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                >
                                    Warga
                                </label>
                                <select
                                    v-model="form.id_warga"
                                    required
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                >
                                    <option value="">Pilih Warga</option>
                                    <option
                                        v-for="w in wargas"
                                        :key="w.id"
                                        :value="w.id"
                                    >
                                        {{ w.nama }} ({{ w.status }})
                                    </option>
                                </select>
                            </div>

                            <!-- ERROR STATUS WARGA -->
                            <div
                                v-if="wargaStatusError"
                                class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-lg px-4 py-2"
                            >
                                {{ wargaStatusError }}
                            </div>

                            <!-- JABATAN -->
                            <div>
                                <label
                                    class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                >
                                    Jabatan
                                </label>
                                <select
                                    v-model="form.id_jabatan"
                                    :disabled="wargaStatusError"
                                    required
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed"
                                >
                                    <option value="">Pilih Jabatan</option>
                                    <option
                                        v-for="j in filteredJabatans"
                                        :key="j.id"
                                        :value="j.id"
                                    >
                                        {{ j.nama_jabatan }}
                                    </option>
                                </select>
                            </div>

                            <!-- PERIODE -->
                            <div>
                                <label
                                    class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                >
                                    Periode
                                </label>
                                <select
                                    v-model="form.id_periode"
                                    required
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                >
                                    <option value="">Pilih Periode</option>
                                    <option
                                        v-for="p in periodes"
                                        :key="p.id"
                                        :value="p.id"
                                    >
                                        {{ p.periode }}
                                    </option>
                                </select>
                            </div>

                            <!-- SUBMIT -->
                            <button
                                type="submit"
                                :disabled="wargaStatusError"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <Save class="w-5 h-5" />
                                <span>{{
                                    isEdit
                                        ? "Update Pengurus"
                                        : "Simpan Pengurus"
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
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted, onBeforeUnmount } from "vue";
import {
    Plus,
    X,
    Save,
    ArrowUp,
    ArrowDown,
    Search,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
} from "lucide-vue-next";

const props = defineProps({
    pengurus: Object,
    jabatans: Array,
    periodes: Array,
    wargas: Array,
});

/* FORM */
const form = ref({
    id_warga: "",
    id_jabatan: "",
    id_periode: "",
});

const selectedWarga = ref(null);
const wargaStatusError = ref(null);

watch(
    () => form.value.id_warga,
    (newVal) => {
        if (!newVal) {
            selectedWarga.value = null;
            wargaStatusError.value = null;
            form.value.id_jabatan = "";
            return;
        }

        selectedWarga.value = props.wargas.find((w) => w.id === newVal);

        if (selectedWarga.value.status === "Tidak Jelas") {
            wargaStatusError.value =
                "Status warga tidak jelas, tidak dapat dimasukkan sebagai pengurus.";
            form.value.id_jabatan = "";
        } else {
            wargaStatusError.value = null;
        }
    }
);

watch(
    () => selectedWarga.value?.status,
    () => {
        form.value.id_jabatan = "";
    }
);

const filteredJabatans = computed(() => {
    const warga = selectedWarga.value;

    if (!warga) return [];
    if (warga.status === "Tidak Jelas") return [];

    return props.jabatans.filter((j) => {
        if (warga.status === "Santri") {
            return j.IKSASS === "IKSASS Santri";
        }

        if (warga.status === "Alumni") {
            return j.IKSASS === "IKSASS Alumni";
        }

        return false;
    });
});

/* MODAL STATE */
const openModal = ref(false);
const isEdit = ref(false);
const editId = ref(null);

/* OPEN CREATE */
const openCreateModal = () => {
    isEdit.value = false;
    editId.value = null;
    form.value = {
        id_warga: "",
        id_jabatan: "",
        id_periode: "",
    };
    openModal.value = true;
};

/* OPEN EDIT */
const openEditModal = (item) => {
    isEdit.value = true;
    editId.value = item.id;

    form.value = {
        id_warga: item.id_warga,
        id_jabatan: item.id_jabatan,
        id_periode: item.id_periode,
    };

    openModal.value = true;
};

/* CLOSE */
const closeModal = () => {
    openModal.value = false;
};

/* SUBMIT */
const submit = () => {
    if (isEdit.value) {
        router.put(route("pengurus.update", editId.value), form.value, {
            onSuccess: closeModal,
        });
    } else {
        router.post(route("pengurus.store"), form.value, {
            onSuccess: closeModal,
        });
    }
};

/* DELETE */
const destroy = (id) => {
    if (confirm("Yakin hapus pengurus ini?")) {
        router.delete(route("pengurus.destroy", id));
    }
};

const filters = ref({
    search: props.filters?.search || "",
    per_page: props.filters?.per_page || 10,
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
});

function applyFilters() {
    router.get(route("pengurus.index"), filters.value, {
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

/* =======================
   DROPDOWN ACTION STATE
======================= */
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = ref({
    top: 0,
    left: 0,
});

function toggleDropdown(item, event) {
    const rect = event.currentTarget.getBoundingClientRect();

    // kalau klik item yang sama → tutup
    if (openDropdown.value && selectedItem.value?.id === item.id) {
        openDropdown.value = false;
        selectedItem.value = null;
        return;
    }

    selectedItem.value = item;
    openDropdown.value = true;

    dropdownPosition.value = {
        top: rect.bottom + window.scrollY,
        left: rect.left + window.scrollX,
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
