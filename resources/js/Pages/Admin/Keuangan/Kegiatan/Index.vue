<template>
    <AppLayout>
        <Head title="Kas Kegiatan" />

        <!-- HEADER -->
        <div
            class="bg-white dark:bg-gray-900 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700 flex items-center justify-between mb-4"
        >
            <div class="flex items-center gap-3">
                <!-- SEARCH -->
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search kegiatan..."
                        v-model="filters.search"
                        @input="applyFilter"
                        class="pl-10 pr-4 py-2 w-64 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:ring-2 focus:ring-green-600 outline-none"
                    />

                    <Search
                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                    />
                </div>

                <!-- Filter -->
                <DateRangeFilter
                    :start="filters.start"
                    :end="filters.end"
                    @update="
                        (val) => {
                            filters.start = val.start;
                            filters.end = val.end;
                            applyFilter();
                        }
                    "
                />
            </div>

            <div class="flex items-center gap-3">
                <!-- BUTTON GRID VIEW -->
                <button
                    @click="viewMode = 'grid'"
                    :class="[
                        'w-10 h-10 flex items-center justify-center rounded-full shadow-sm transition',
                        viewMode === 'grid'
                            ? 'bg-green-600 text-white shadow-md'
                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300',
                    ]"
                >
                    <LayoutGrid class="w-5 h-5" />
                </button>

                <!-- BUTTON LIST VIEW -->
                <button
                    @click="viewMode = 'list'"
                    :class="[
                        'w-10 h-10 flex items-center justify-center rounded-full shadow-sm transition',
                        viewMode === 'list'
                            ? 'bg-green-600 text-white shadow-md'
                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300',
                    ]"
                >
                    <List class="w-5 h-5" />
                </button>

                <!-- TOMBOL TAMBAH -->
                <button
                    @click="openModal = true"
                    class="flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-xl shadow hover:bg-green-700 transition"
                >
                    <PlusCircle class="w-5 h-5" />
                    <span>Tambah</span>
                </button>
            </div>
        </div>

        <!-- DAFTAR KEGIATAN -->
        <div
            :class="[
                viewMode === 'grid'
                    ? 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4'
                    : 'flex flex-col gap-3',
            ]"
        >
            <div
                v-for="item in props.kegiatan"
                :key="item.id"
                class="cursor-pointer relative"
            >
                <div
                    @click="router.visit(`/admin/kas-kegiatan/${item.id}`)"
                    :class="[
                        'bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition relative group',
                        viewMode === 'list' ? 'flex items-center gap-5' : '',
                    ]"
                >
                    <!-- ICON -->
                    <div
                        class="flex items-center justify-center my-4"
                        :class="viewMode === 'list' ? 'my-0' : ''"
                    >
                        <FolderOpen
                            :class="
                                viewMode === 'list'
                                    ? 'w-12 h-12 text-green-500'
                                    : 'md:w-24 md:h-24 text-green-500'
                            "
                        />
                    </div>

                    <!-- CONTENT -->
                    <div :class="viewMode === 'list' ? 'flex-1' : ''">
                        <p
                            :class="[
                                'text-sm font-semibold text-gray-800 dark:text-gray-100 truncate whitespace-nowrap overflow-hidden',
                                viewMode === 'grid' ? 'text-center' : '',
                            ]"
                        >
                            {{ item.nama_kegiatan }}
                        </p>

                        <div
                            class="flex justify-between mt-4 text-[11px] text-gray-500 dark:text-gray-400"
                        >
                            <span>{{
                                tanggal(item.tanggal_pelaksanaan) ?? "-"
                            }}</span>
                            <span
                                class="font-semibold text-red-500 dark:text-red-400"
                            >
                                {{
                                    rupiah(
                                        item.total_pengeluaran ?? 0
                                    ).toLocaleString("id-ID")
                                }}
                            </span>
                        </div>
                    </div>

                    <!-- MENU DOT -->
                    <div
                        class="absolute top-3 right-3 menu-trigger"
                        @click.stop="(e) => toggleDropdown(e, item)"
                    >
                        <MoreVertical
                            class="w-5 h-5 text-gray-400 hover:text-gray-600 cursor-pointer"
                        />
                    </div>
                </div>

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
                            v-if="openDropdown && selectedItem?.id === item.id"
                            ref="dropdownRef"
                            class="dropdown-menu absolute w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-[9999]"
                            :style="{
                                top: dropdownPosition.top + 'px',
                                left: dropdownPosition.left + 'px',
                            }"
                        >
                            <ul
                                class="text-sm text-gray-900 dark:text-gray-100"
                            >
                                <li
                                    @click="
                                        openDropdown = false;
                                        openEdit(item);
                                    "
                                    class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer"
                                >
                                    Edit
                                </li>

                                <li
                                    @click="
                                        openDropdown = false;
                                        router.delete(
                                            `/admin/kas-kegiatan/${item.id}`
                                        );
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
        </div>

        <!-- MODAL TAMBAH KEGIATAN -->
        <Modal
            :open="openModal"
            title="Tambah Data Kegiatan"
            @close="openModal = false"
        >
            <div class="flex flex-col gap-4">
                <div class="space-y-3">
                    <label
                        class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                    >
                        Nama Kegiatan
                    </label>
                    <input
                        v-model="form.nama_kegiatan"
                        type="text"
                        placeholder="Nama Kegiatan"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                    />

                    <label
                        class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                    >
                        Tanggal Pelaksanaan
                    </label>
                    <input
                        v-model="form.tanggal_pelaksanaan"
                        type="date"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                    />

                    <!-- Saldo Awal -->
                    <label
                        class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                    >
                        Saldo Awal (Diambil dari Kas Umum)
                    </label>
                    <input
                        v-model="form.nominal"
                        type="number"
                        placeholder="Masukkan saldo awal"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                    />

                    <!-- Bukti Transaksi Saldo Awal -->
                    <label
                        class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                    >
                        Bukti Transaksi
                    </label>
                    <input
                        type="file"
                        @change="handleFileUpload"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                    />
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button
                    @click="openModal = false"
                    class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                >
                    Batal
                </button>

                <button
                    @click="submit"
                    class="px-4 py-2.5 flex items-center justify-center gap-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-103 font-semibold"
                >
                    <Save class="w-5 h-5" />
                    Simpan
                </button>
            </div>
        </Modal>

        <!-- MODAL EDIT KEGIATAN -->
        <Modal
            :open="openEditModal"
            title="Edit Data Kegiatan"
            @close="openEditModal = false"
        >
            <div class="flex flex-col gap-4">
                <div class="space-y-3">
                    <label
                        class="font-semibold text-sm text-gray-900 dark:text-gray-100"
                    >
                        Nama Kegiatan
                    </label>
                    <input
                        v-model="formEdit.nama_kegiatan"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900"
                    />

                    <label
                        class="font-semibold text-sm text-gray-900 dark:text-gray-100"
                    >
                        Tanggal Pelaksanaan
                    </label>
                    <input
                        v-model="formEdit.tanggal_pelaksanaan"
                        type="date"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900"
                    />
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button
                    @click="openEditModal = false"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg"
                >
                    Batal
                </button>

                <button
                    @click="submitUpdate"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2"
                >
                    <Save class="w-5 h-5" />
                    Update
                </button>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { router, Link, Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Modal from "@/Components/Modal.vue";
import { rupiah, tanggal } from "@/helpers";
import DateRangeFilter from "@/Components/Filters/DateRangeFilter.vue";
import {
    Search,
    Save,
    MoreVertical,
    Pencil,
    Trash2,
    FolderOpen,
    LayoutGrid,
    List,
    PlusCircle,
} from "lucide-vue-next";

const props = defineProps({
    kegiatan: Array,
    filters: Object,
});

// DROPDOWN BARU
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });
const dropdownRef = ref(null);

function toggleDropdown(event, item) {
    const button = event.currentTarget;
    const rect = button.getBoundingClientRect();

    selectedItem.value = item;
    openDropdown.value = !openDropdown.value;

    dropdownPosition.value = {
        top: rect.top + rect.height + 8,
        left: rect.left - 120 + rect.width,
    };
}

// Tutup dropdown saat klik di luar
function handleClickOutside(e) {
    const trigger = document.querySelector(".menu-trigger");
    const dropdown = document.querySelector(".dropdown-menu");

    const clickedTrigger = e.target.closest(".menu-trigger");
    const clickedDropdown = e.target.closest(".dropdown-menu");

    if (!clickedTrigger && !clickedDropdown) {
        openDropdown.value = false;
        selectedItem.value = null;
    }
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});
onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

// FILTER
const viewMode = ref("grid");

const filters = ref({
    search: props.filters?.search ?? "",
    start: props.filters?.start ?? "",
    end: props.filters?.end ?? "",
});

const applyFilter = () => {
    router.get("/admin/kas-kegiatan", filters.value, {
        preserveState: true,
        replace: true,
    });
};

// Date Range Picker
const dateRange = ref({
    startDate: "",
    endDate: "",
});

function updateRange(value) {
    filters.value.start = value?.[0] || null;
    filters.value.end = value?.[1] || null;
    applyFilter();
}

// MODAL TAMBAH KEGIATAN
const openModal = ref(false);

const form = ref({
    nama_kegiatan: "",
    tanggal_pelaksanaan: "",
    nominal: "",
    bukti_transaksi: null,
});

function handleFileUpload(e) {
    form.value.bukti_transaksi = e.target.files[0];
}

function submit() {
    const payload = new FormData();
    payload.append("nama_kegiatan", form.value.nama_kegiatan);
    payload.append("tanggal_pelaksanaan", form.value.tanggal_pelaksanaan);
    payload.append("nominal", form.value.nominal);

    if (form.value.bukti_transaksi) {
        payload.append("bukti_transaksi", form.value.bukti_transaksi);
    }

    router.post(route("kas-kegiatan.store"), payload, {
        forceFormData: true,
        onSuccess: () => {
            openModal.value = false;

            form.value = {
                nama_kegiatan: "",
                tanggal_pelaksanaan: "",
                nominal: "",
                bukti_transaksi: null,
            };
        },
    });
}

// MODAL EDIT KEGIATAN
const openEditModal = ref(false);

const formEdit = ref({
    id: "",
    nama_kegiatan: "",
    tanggal_pelaksanaan: "",
});

// Buka modal edit + isi data
function openEdit(item) {
    formEdit.value.id = item.id;
    formEdit.value.nama_kegiatan = item.nama_kegiatan;
    formEdit.value.tanggal_pelaksanaan = item.tanggal_pelaksanaan;

    openEditModal.value = true;
}

// Submit update kegiatan
function submitUpdate() {
    router.put(
        `/admin/kas-kegiatan/update-kegiatan/${formEdit.value.id}`,
        formEdit.value,
        {
            onSuccess: () => {
                openEditModal.value = false;
            },
        }
    );
}

// FORM TAMBAH DETAIL
const formAdd = ref({
    tanggal: "",
    uraian: "",
    jenis: "Debet",
    kategori_id: "",
    nominal: "",
    bukti_transaksi: null,

    nama_kegiatan: props.kegiatan?.nama_kegiatan,
    tanggal_pelaksanaan: props.kegiatan?.tanggal_pelaksanaan,
});
</script>
