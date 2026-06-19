<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import {
    computed,
    ref,
    watch,
    onMounted,
} from "vue";

import {
    Trash2,
    Pencil,
    Link2,
    X,
    Plus,
    LayoutGrid,
    Columns3,
    Search,
} from "lucide-vue-next";

import Swal from "sweetalert2";

import CreateDokumentasi from "./Create.vue";
import EditDokumentasi from "./Edit.vue";

const props = defineProps({
    dokumentasis: Array,
    programKerjas: Array,
    departments: Array,
});

const createModal = ref(false);
const editModal = ref(false);

const selectedDok = ref(null);
const selectedImage = ref(null);

const search = ref("");
const department = ref("");
const layout = ref(
    localStorage.getItem("dokumentasi-layout") ||
    "all"
);

watch(layout, (value) => {
    localStorage.setItem(
        "dokumentasi-layout",
        value
    );
});

function openImage(img) {
    selectedImage.value = img;
}

function closeImage() {
    selectedImage.value = null;
}

function editDok(item) {
    selectedDok.value = item;
    editModal.value = true;
}

const galleryItems = computed(() => {
    let items = [];

    props.dokumentasis.forEach((doc) => {
        if (doc.thumbnail_portrait) {
            items.push({
                ...doc,
                image: doc.thumbnail_portrait,
                orientation: "portrait",
                thumbnailType: "portrait",
            });
        }

        if (doc.thumbnail_landscape) {
            items.push({
                ...doc,
                image: doc.thumbnail_landscape,
                orientation: "landscape",
                thumbnailType: "landscape",
            });
        }
    });

    return items.filter((item) => {
        const matchSearch =
            item.program_kerja?.title
                ?.toLowerCase()
                .includes(search.value.toLowerCase());

        const matchDept =
            !department.value ||
            item.program_kerja?.departemen_id ==
            department.value;

        const matchLayout =
            layout.value === "all"
                ? true
                : item.orientation === layout.value;

        return (
            matchSearch &&
            matchDept &&
            matchLayout
        );
    });
});

function deleteDok(item) {
    const isDark =
        document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Hapus thumbnail ini?",
        text:
            "Thumbnail yang dipilih akan dihapus. Jika ini thumbnail terakhir, dokumentasi juga akan dihapus.",
        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",

        confirmButtonColor: "#dc2626",

        background: isDark
            ? "#0f172a"
            : "#ffffff",

        color: isDark
            ? "#ffffff"
            : "#111827",
    }).then((result) => {

        if (!result.isConfirmed) return;

        router.delete(
            route(
                "dokumentasi.thumbnail.destroy",
                [
                    item.id,
                    item.thumbnailType,
                ]
            )
        );
    });
}
function formatDate(date) {
    if (!date) return "-";

    return new Date(date)
        .toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
}
</script>

<template>
    <AppLayout>

        <Head title="Dokumentasi Program Kerja" />

        <!-- TOOLBAR -->
        <div
            class="mb-6 rounded-xl border border-slate-200/70 bg-white/80 p-4 shadow-sm backdrop-blur-xl dark:border-slate-700 dark:bg-slate-900/80">
            <div class="space-y-3">

                <div class="grid grid-cols-1 gap-3 lg:grid-cols-[1fr_260px]">
                    <!-- SEARCH -->
                    <div class="relative">
                        <Search class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                        <input v-model="search" type="text" placeholder="Cari program kerja..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm text-slate-700 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:bg-slate-800" />
                    </div>

                    <!-- FILTER -->
                    <select v-model="department"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:bg-slate-800">
                        <option value="">
                            Semua Departemen
                        </option>

                        <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                            {{ dept.name }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center justify-between gap-3">

                    <!-- LAYOUT SWITCHER -->
                    <div class="flex items-center rounded-lg bg-slate-100 p-1 dark:bg-slate-800">
                        <button @click="layout = 'all'" :class="layout === 'all'
                            ? 'bg-white text-blue-600 shadow dark:bg-slate-700'
                            : 'text-slate-500'
                            " class="rounded-lg px-3 py-2 text-xs font-medium transition sm:px-4 sm:text-sm">
                            Semua
                        </button>

                        <button @click="layout = 'portrait'" :class="layout === 'portrait'
                            ? 'bg-white text-blue-600 shadow dark:bg-slate-700'
                            : 'text-slate-500'
                            " class="rounded-lg p-2 transition">
                            <Columns3 class="h-4 w-4 sm:h-5 sm:w-5" />
                        </button>

                        <button @click="layout = 'landscape'" :class="layout === 'landscape'
                            ? 'bg-white text-blue-600 shadow dark:bg-slate-700'
                            : 'text-slate-500'
                            " class="rounded-lg p-2 transition">
                            <LayoutGrid class="h-4 w-4 sm:h-5 sm:w-5" />
                        </button>
                    </div>

                    <!-- ADD BUTTON -->
                    <button @click="createModal = true"
                        class="group relative flex shrink-0 items-center gap-2 overflow-hidden rounded-lg bg-gradient-to-r from-blue-600 to-sky-500 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-500/25">
                        <div
                            class="absolute inset-0 -translate-x-full -skew-x-12 bg-gradient-to-r from-white/20 to-transparent transition-transform duration-700 group-hover:translate-x-full" />

                        <Plus class="relative z-10 h-4 w-4 transition-transform duration-300 group-hover:rotate-90" />

                        <span class="relative z-10 hidden sm:inline">
                            Tambah Dokumentasi
                        </span>

                        <span class="relative z-10 sm:hidden">
                            Tambah
                        </span>
                    </button>
                </div>

            </div>
        </div>

        <!-- GALLERY -->
        <div class="columns-2 md:columns-3 xl:columns-4 gap-5 space-y-5">

            <div v-for="item in galleryItems" :key="`${item.id}-${item.orientation}`"
                class="group relative mb-5 break-inside-avoid overflow-hidden rounded-lg bg-white shadow-lg">
                <img :src="`/storage/${item.image}`" @click="openImage(item.image)"
                    class="w-full object-cover transition duration-500 group-hover:scale-110" :class="item.orientation === 'portrait'
                        ? 'aspect-[3/4]'
                        : 'aspect-[16/9]'
                        " loading="lazy" />

                <!-- OVERLAY -->
                <div
                    class="absolute inset-0 flex flex-col justify-between bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 transition group-hover:opacity-100">
                    <!-- ACTION -->
                    <div class="flex justify-end gap-2 p-4">
                        <a v-if="item.link_drive" :href="item.link_drive" target="_blank"
                            class="rounded-lg bg-blue-600 p-2 text-white">
                            <Link2 class="h-4 w-4" />
                        </a>

                        <button @click="editDok(item)" class="rounded-lg bg-yellow-500 p-2 text-white">
                            <Pencil class="h-4 w-4" />
                        </button>

                        <button @click="deleteDok(item)" class="rounded-lg bg-red-600 p-2 text-white">
                            <Trash2 class="h-4 w-4" />
                        </button>
                    </div>

                    <!-- INFO -->
                    <div class="p-4 text-white">
                        <h3 class="font-semibold">
                            {{ item.program_kerja?.title }}
                        </h3>

                        <p class="mt-1 text-sm text-white/80">
                            {{
                                formatDate(
                                    item.program_kerja?.start_date
                                )
                            }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- LIGHTBOX -->
        <div v-if="selectedImage" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-6">
            <button @click="closeImage" class="absolute right-5 top-5 text-white">
                <X class="h-8 w-8" />
            </button>

            <img :src="`/storage/${selectedImage}`" class="max-h-[90vh] rounded-lg" loading="lazy" />
        </div>

        <CreateDokumentasi :show="createModal" :programKerjas="programKerjas" @close="createModal = false" />

        <EditDokumentasi :show="editModal" :dokumentasi="selectedDok" :programKerjas="programKerjas"
            @close="editModal = false" />

    </AppLayout>
</template>