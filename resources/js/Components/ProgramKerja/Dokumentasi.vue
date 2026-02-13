<template>
    <div
        class="mt-4 p-6 bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-all duration-300"
    >
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-gray-100"
                >
                    <Folder class="w-6 h-6 text-yellow-500" />
                    Dokumentasi Kegiatan
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Koleksi foto dan dokumentasi kegiatan organisasi
                </p>
            </div>

            <button
                @click="openForm = true"
                class="group relative flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden"
            >
                <div
                    class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                ></div>
                <Plus
                    class="h-5 w-5 transition-transform group-hover:rotate-90"
                />
                <span class="hidden md:block">Tambah Dokumentasi</span>
            </button>
        </div>

        <!-- GRID FOTO -->
        <div
            v-if="dokumentasis.length"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
        >
            <div
                v-for="item in paginatedDokumentasis"
                :key="item.id"
                @click="openPreview(item)"
                class="group relative cursor-pointer overflow-hidden rounded-3xl border-2 border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-lg hover:shadow-2xl transition-all duration-500 hover:border-green-200 dark:hover:border-green-800/50"
            >
                <!-- Image Container -->
                <div class="relative h-52 overflow-hidden">
                    <!-- ACTION MENU (⋮) -->
                    <div class="absolute top-3 right-3 z-20" @click.stop>
                        <button
                            @click="toggleMenu(item.id)"
                            class="p-2 rounded-full bg-black/40 backdrop-blur-sm hover:bg-black/60 transition"
                        >
                            <MoreVertical class="w-4 h-4 text-white" />
                        </button>

                        <!-- DROPDOWN -->
                        <Transition
                            enter-active-class="transition ease-out duration-150"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-100"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95"
                        >
                            <div
                                v-if="activeMenu === item.id"
                                class="absolute right-0 mt-2 w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden"
                            >
                                <button
                                    @click="editDokumentasi(item)"
                                    class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                                >
                                    Edit
                                </button>

                                <button
                                    @click="hapusDokumentasi(item.id)"
                                    class="w-full px-4 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition"
                                >
                                    Hapus
                                </button>
                            </div>
                        </Transition>
                    </div>

                    <img
                        :src="item.foto"
                        :alt="item.programKerja?.nama_kegiatan"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                    />

                    <!-- Gradient Overlay -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    ></div>

                    <!-- Program Badge -->
                    <div class="absolute top-3 left-3">
                        <span
                            class="px-3 py-1.5 bg-green-600/90 backdrop-blur-sm text-white text-xs font-semibold rounded-full shadow-lg"
                        >
                            {{
                                item.programKerja?.nama_kegiatan?.substring(
                                    0,
                                    20
                                )
                            }}{{
                                item.programKerja?.nama_kegiatan?.length > 20
                                    ? "..."
                                    : ""
                            }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <p
                        class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2"
                    >
                        {{
                            item.deskripsi || "Dokumentasi kegiatan organisasi"
                        }}
                    </p>

                    <!-- CTA -->
                    <div class="flex items-center justify-between mt-4">
                        <div
                            class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400"
                        >
                            <Eye class="w-4 h-4" />
                            <span>Klik untuk melihat</span>
                        </div>

                        <div
                            class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 group-hover:bg-green-100 dark:group-hover:bg-green-900/30 transition-colors"
                        >
                            <ArrowRight
                                class="w-4 h-4 text-gray-500 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="text-center py-16 px-4 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50"
        >
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 rounded-2xl mb-6"
            >
                <Image class="w-10 h-10 text-gray-400" />
            </div>
            <h3
                class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2"
            >
                Belum ada dokumentasi
            </h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">
                Mulai tambahkan dokumentasi kegiatan organisasi untuk
                menginspirasi anggota lainnya
            </p>
            <button
                @click="openForm = true"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors"
            >
                <Plus class="w-5 h-5" />
                Tambah Dokumentasi Pertama
            </button>
        </div>

        <!-- PAGINATION -->
        <div v-if="totalPages > 1" class="flex justify-center mt-8">
            <div class="flex gap-1">
                <!-- PREV -->
                <button
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200 font-medium"
                    :class="[
                        currentPage === 1
                            ? 'opacity-40 cursor-not-allowed'
                            : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 hover:shadow-md',
                    ]"
                >
                    <ChevronLeft class="w-4 h-4" />
                </button>

                <!-- PAGE NUMBERS -->
                <button
                    v-for="page in totalPages"
                    :key="page"
                    @click="currentPage = page"
                    class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200 font-medium"
                    :class="[
                        page === currentPage
                            ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-green-600 shadow-lg scale-105'
                            : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 hover:shadow-md',
                    ]"
                >
                    {{ page }}
                </button>

                <!-- NEXT -->
                <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200 font-medium"
                    :class="[
                        currentPage === totalPages
                            ? 'opacity-40 cursor-not-allowed'
                            : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 hover:shadow-md',
                    ]"
                >
                    <ChevronRight class="w-4 h-4" />
                </button>
            </div>
        </div>

        <!-- PREVIEW MODAL -->
        <teleport to="body">
            <transition name="modal">
                <div
                    v-if="preview"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    @click.self="preview = null"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-black/70 backdrop-blur-md"
                    ></div>

                    <!-- Modal -->
                    <div
                        class="relative bg-white dark:bg-gray-900 rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl animate-[modalIn_.3s_ease-out]"
                    >
                        <!-- Close Button -->
                        <button
                            @click="preview = null"
                            class="absolute top-4 right-4 z-10 p-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-full hover:bg-red-500 hover:text-white transition-all duration-300 hover:rotate-90"
                        >
                            <X class="w-5 h-5" />
                        </button>

                        <!-- Image -->
                        <div class="relative h-96 overflow-hidden">
                            <img
                                :src="preview.foto"
                                :alt="preview.programKerja?.nama_kegiatan"
                                class="w-full h-full object-cover"
                            />
                            <div
                                class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6"
                            >
                                <h3 class="text-2xl font-bold text-white mb-2">
                                    {{ preview.programKerja?.nama_kegiatan }}
                                </h3>
                                <p class="text-gray-200 text-sm">
                                    Dokumentasi kegiatan organisasi
                                </p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 overflow-y-auto max-h-[40vh]">
                            <!-- Description -->
                            <div class="mb-6">
                                <h4
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2"
                                >
                                    DESKRIPSI
                                </h4>
                                <p class="text-gray-700 dark:text-gray-300">
                                    {{
                                        preview.deskripsi ||
                                        "Tidak ada deskripsi untuk dokumentasi ini."
                                    }}
                                </p>
                            </div>

                            <!-- Link -->
                            <div v-if="preview.link">
                                <h4
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2"
                                >
                                    LINK DOKUMENTASI
                                </h4>
                                <a
                                    :href="preview.link"
                                    target="_blank"
                                    class="group inline-flex items-center gap-3 px-4 py-3 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 rounded-xl transition-colors w-full"
                                >
                                    <div class="p-2 bg-green-600/10 rounded-lg">
                                        <ExternalLink
                                            class="w-5 h-5 text-green-600 dark:text-green-400"
                                        />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate"
                                        >
                                            {{ preview.link }}
                                        </p>
                                        <p
                                            class="text-xs text-green-600 dark:text-green-400"
                                        >
                                            Klik untuk membuka dokumentasi
                                            lengkap
                                        </p>
                                    </div>
                                    <ArrowRight
                                        class="w-5 h-5 text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors"
                                    />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </teleport>

        <!-- FORM MODAL -->
        <teleport to="body">
            <transition name="modal">
                <div
                    v-if="openForm"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    @click.self="openForm = false"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                    ></div>

                    <!-- Modal -->
                    <div
                        class="relative bg-white dark:bg-gray-900 rounded-3xl max-w-lg w-full shadow-2xl animate-[modalIn_.3s_ease-out]"
                    >
                        <!-- Header -->
                        <div
                            class="border-b border-gray-100 dark:border-gray-800 p-6"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg"
                                >
                                    <Plus
                                        class="w-5 h-5 text-green-600 dark:text-green-400"
                                    />
                                </div>
                                <div>
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-gray-100"
                                    >
                                        {{
                                            isEdit
                                                ? "Edit Dokumentasi"
                                                : "Tambah Dokumentasi Baru"
                                        }}
                                    </h3>
                                    <p
                                        class="text-sm text-gray-500 dark:text-gray-400 mt-0.5"
                                    >
                                        {{
                                            isEdit
                                                ? "Perbarui informasi dokumentasi kegiatan"
                                                : "Unggah foto dan informasi dokumentasi kegiatan"
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="submit" class="p-6 space-y-5">
                            <!-- File Upload -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Foto Dokumentasi
                                    <span class="text-red-500">*</span>
                                </label>
                                <div
                                    class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl p-6 text-center hover:border-green-500 dark:hover:border-green-500 transition-colors"
                                >
                                    <input
                                        type="file"
                                        @change="handleFile"
                                        accept="image/*"
                                        class="hidden"
                                        id="file-upload"
                                        :required="!isEdit"
                                    />

                                    <label
                                        for="file-upload"
                                        class="cursor-pointer"
                                    >
                                        <div
                                            class="inline-flex items-center justify-center w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-full mb-3"
                                        >
                                            <Image
                                                class="w-6 h-6 text-green-600 dark:text-green-400"
                                            />
                                        </div>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                        >
                                            Klik untuk mengunggah foto
                                        </p>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-500 mt-1"
                                        >
                                            PNG, JPG, JPEG (Maks. 2MB)
                                        </p>
                                    </label>
                                </div>
                                <p
                                    v-if="form.foto"
                                    class="text-sm text-green-600 dark:text-green-400"
                                >
                                    ✓ File terpilih
                                </p>
                            </div>

                            <!-- Program Kerja -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Program Kerja
                                    <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.id_program_kerja"
                                    required
                                    class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                >
                                    <option value="" disabled>
                                        Pilih Program Kerja
                                    </option>
                                    <option
                                        v-for="pk in programKerjas"
                                        :key="pk.id"
                                        :value="pk.id"
                                    >
                                        {{ pk.nama_kegiatan }}
                                    </option>
                                </select>
                            </div>

                            <!-- Link -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Link Google Drive
                                </label>
                                <div class="relative">
                                    <div
                                        class="absolute left-4 top-1/2 -translate-y-1/2"
                                    >
                                        <ExternalLink
                                            class="w-5 h-5 text-gray-400"
                                        />
                                    </div>
                                    <input
                                        v-model="form.link"
                                        type="url"
                                        placeholder="https://drive.google.com/..."
                                        class="w-full pl-12 pr-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                    />
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Deskripsi
                                </label>
                                <textarea
                                    v-model="form.deskripsi"
                                    rows="3"
                                    placeholder="Tulis deskripsi dokumentasi..."
                                    class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition resize-none"
                                />
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-3 pt-4">
                                <button
                                    type="button"
                                    @click="openForm = false"
                                    class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-green-600 to-teal-500 hover:from-green-700 hover:to-teal-600 text-white font-medium shadow-lg shadow-green-500/20 hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="form.processing"
                                        >Menyimpan...</span
                                    >
                                    <span v-else>Simpan Dokumentasi</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </teleport>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed, watch } from "vue";
import {
    Plus,
    X,
    Folder,
    Eye,
    ArrowRight,
    Image,
    MoreVertical,
    ExternalLink,
    ChevronLeft,
    ChevronRight,
} from "lucide-vue-next";
import { router, useForm } from "@inertiajs/vue3";

const { dokumentasis, programKerjas } = defineProps({
    dokumentasis: {
        type: Array,
        default: () => [],
    },
    programKerjas: {
        type: Array,
        default: () => [],
    },
});

// Paginate
const perPage = 4;
const currentPage = ref(1);

const totalPages = computed(() => {
    return Math.max(1, Math.ceil(dokumentasis.length / perPage));
});

const paginatedDokumentasis = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    return dokumentasis.slice(start, start + perPage);
});

function nextPage() {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
}

function prevPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

watch(
    () => dokumentasis,
    () => {
        currentPage.value = 1;
    }
);

// Form & Modal Logic
const openForm = ref(false);
const preview = ref(null);

const form = useForm({
    foto: null,
    id_program_kerja: "",
    link: "",
    deskripsi: "",
});

const activeMenu = ref(null);

function toggleMenu(id) {
    activeMenu.value = activeMenu.value === id ? null : id;
}

function closeMenu(e) {
    if (!e.target.closest(".relative")) {
        activeMenu.value = null;
    }
}

onMounted(() => {
    document.addEventListener("click", closeMenu);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeMenu);
});

const isEdit = ref(false);
const editingId = ref(null);
const previewFoto = ref(null);

function editDokumentasi(item) {
    activeMenu.value = null;

    isEdit.value = true;
    editingId.value = item.id;

    form.id_program_kerja = item.programKerja?.id ?? "";
    form.deskripsi = item.deskripsi;
    form.link = item.link ?? "";

    previewFoto.value = item.foto;
    form.foto = null;

    openForm.value = true;
}

function hapusDokumentasi(id) {
    activeMenu.value = null;

    if (confirm("Yakin ingin menghapus dokumentasi ini?")) {
        router.delete(route("dokumentasi.destroy", id));
    }
}

function handleFile(e) {
    form.foto = e.target.files[0];
}

function openPreview(item) {
    preview.value = item;
}

function submit() {
    if (isEdit.value) {
        form.post(route("dokumentasi.update", editingId.value), {
            forceFormData: true,
            onSuccess: () => {
                resetForm();
            },
        });
    } else {
        form.post(route("dokumentasi.store"), {
            forceFormData: true,
            onSuccess: () => {
                resetForm();
            },
        });
    }
}

function resetForm() {
    openForm.value = false;
    isEdit.value = false;
    editingId.value = null;
    form.reset();
}
</script>

<style>
@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
