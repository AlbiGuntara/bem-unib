<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { computed, ref, watch, onMounted, onUnmounted, defineAsyncComponent } from "vue";

import { Save, Send, FolderOpen, Tags } from "lucide-vue-next";

const TiptapEditor = defineAsyncComponent(() => import("@/Components/TiptapEditor.vue"));
import TagInput from "@/Components/TagInput.vue";
import ImageUpload from "@/Components/ImageUpload.vue";

import axios from "axios";
import Swal from "sweetalert2";

const props = defineProps({
    categories: Array,
});

const STORAGE_KEY = "berita_create_draft";

const initialValues = {
    title: "",
    content: "",
    category: "",
    label: [],
    is_published: false,
};

const saved = (() => {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
})();

const form = useForm({
    title: saved?.title ?? initialValues.title,
    content: saved?.content ?? initialValues.content,
    thumbnail: null,
    category: saved?.category ?? initialValues.category,
    label: saved?.label ?? initialValues.label,
    is_published: saved?.is_published ?? initialValues.is_published,
});

const isSubmitting = ref(false);
const isAutoSaving = ref(false);
const isNavigating = ref(false);

const isDirty = computed(() => {
    return (
        form.title !== initialValues.title ||
        form.content !== initialValues.content ||
        form.category !== initialValues.category ||
        JSON.stringify(form.label) !== JSON.stringify(initialValues.label) ||
        form.is_published !== initialValues.is_published
    );
});

const shouldWarn = computed(() => isDirty.value && !isSubmitting.value && !isAutoSaving.value && !isNavigating.value);

function persistDraft() {
    localStorage.setItem(
        STORAGE_KEY,
        JSON.stringify({
            title: form.title,
            content: form.content,
            category: form.category,
            label: form.label,
            is_published: form.is_published,
        }),
    );
}

watch(
    () => [form.title, form.content, form.category, form.label, form.is_published],
    persistDraft,
    { deep: true },
);

function clearDraft() {
    localStorage.removeItem(STORAGE_KEY);
}

function fireAlert(options) {
    const isDark = document.documentElement.classList.contains("dark");
    return Swal.fire({
        background: isDark ? "#0f172a" : "#ffffff",
        color: isDark ? "#f1f5f9" : "#111827",
        confirmButtonColor: "#2563eb",
        cancelButtonColor: isDark ? "#475569" : "#6b7280",
        customClass: {
            popup: "rounded-2xl shadow-2xl",
            title: "text-lg font-semibold",
            htmlContainer: "text-sm",
            confirmButton: "px-4 py-2 rounded-lg font-medium",
            cancelButton: "px-4 py-2 rounded-lg font-medium",
        },
        ...options,
    });
}

async function saveDraftAndNavigate(targetUrl) {
    isAutoSaving.value = true;

    Swal.fire({
        title: "Menyimpan draft...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        background: document.documentElement.classList.contains("dark")
            ? "#0f172a" : "#ffffff",
        color: document.documentElement.classList.contains("dark")
            ? "#f1f5f9" : "#111827",
        didOpen: () => Swal.showLoading(),
    });

    try {
        const fd = new FormData();
        fd.append("title", form.title);
        fd.append("content", form.content);
        fd.append("category", form.category);
        fd.append("label", JSON.stringify(form.label));
        fd.append("is_published", "0");
        if (form.thumbnail) {
            fd.append("thumbnail", form.thumbnail);
        }
        await axios.post(route("berita.store"), fd);
        clearDraft();
        Swal.close();
        if (targetUrl) {
            router.visit(targetUrl);
        }
    } catch (error) {
        isAutoSaving.value = false;
        Swal.close();
        const message = error?.response?.data?.message || "Gagal menyimpan draft";
        fireAlert({
            icon: "error",
            title: "Gagal Menyimpan Draft",
            text: message,
            confirmButtonText: "Mengerti",
        });
    }
}

let removeBeforeHook = null;

onMounted(() => {
    removeBeforeHook = router.on("before", (event) => {
        if (isAutoSaving.value || isNavigating.value) return;

        if (shouldWarn.value) {
            const url = event.visit.url.href;

            fireAlert({
                icon: "question",
                title: "Konten Belum Disimpan",
                text: "Anda memiliki konten yang belum disimpan.",
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: "Simpan Draft",
                denyButtonText: "Keluar",
                cancelButtonText: "Tetap di Halaman",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    saveDraftAndNavigate(url);
                } else if (result.isDenied) {
                    clearDraft();
                    isNavigating.value = true;
                    router.visit(url);
                }
            });

            return false;
        }
    });
});

onUnmounted(() => {
    if (removeBeforeHook) removeBeforeHook();
});

const showSuggestions = ref(false);

const filteredCategories = computed(() => {
    if (!form.category) return [];
    return props.categories
        .filter((item) =>
            item.toLowerCase().includes(form.category.toLowerCase()),
        )
        .slice(0, 5);
});

function submit(status) {
    const errors = [];

    if (!form.title?.trim()) {
        errors.push("Judul berita belum diisi");
    }
    if (!form.category?.trim()) {
        errors.push("Kategori belum dipilih");
    }
    if (!form.content || form.content === "<p></p>" || form.content === "") {
        errors.push("Isi berita masih kosong");
    }

    if (errors.length) {
        fireAlert({
            icon: "warning",
            title: "Data Belum Lengkap",
            html: `<div class="text-left space-y-1">${errors.map((item) => `<div>• ${item}</div>`).join("")}</div>`,
            confirmButtonText: "Mengerti",
        });
        return;
    }

    form.is_published = status;
    isSubmitting.value = true;

    form.post(route("berita.store"), {
        preserveScroll: true,
        onStart: () => {
            Swal.fire({
                title: "Menyimpan...",
                text: "Mohon tunggu sebentar",
                allowOutsideClick: false,
                allowEscapeKey: false,
                background: document.documentElement.classList.contains("dark") ? "#0f172a" : "#ffffff",
                color: document.documentElement.classList.contains("dark") ? "#f1f5f9" : "#111827",
                didOpen: () => Swal.showLoading(),
            });
        },
        onSuccess: () => {
            clearDraft();
            Swal.close();
        },
        onError: (errors) => {
            isSubmitting.value = false;
            Swal.close();
            fireAlert({
                icon: "error",
                title: "Gagal Menyimpan Berita",
                html: Object.values(errors).join("<br>"),
            });
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
}
</script>

<template>
    <AppLayout>
        <Head title="Tambah Berita" />

        <div class="space-y-4">
            <div
                class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-8"
            >
                <div
                    class="flex flex-col md:flex-row md:items-start md:justify-between gap-4"
                >
                    <div class="flex-1">
                        <div
                            class="text-xs uppercase tracking-wider text-blue-600 font-semibold mb-3"
                        >
                            Artikel Baru
                        </div>

                        <input
                            v-model="form.title"
                            placeholder="Tulis judul..."
                            class="article-title-input"
                        />

                        <p
                            v-if="form.errors.title"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row gap-3 md:min-w-[320px]"
                    >
                        <button
                            @click="submit(false)"
                            class="flex-1 whitespace-nowrap flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 transition"
                        >
                            <Save class="w-4 h-4" />
                            Simpan Draft
                        </button>

                        <button
                            @click="submit(true)"
                            class="flex-1 whitespace-nowrap flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/20 transition"
                        >
                            <Send class="w-4 h-4" />
                            Publish
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-4 gap-4">
                <div class="lg:col-span-3">
                    <div
                        class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden shadow-sm"
                    >
                        <TiptapEditor v-model="form.content" />

                        <p
                            v-if="form.errors.content"
                            class="px-5 py-3 text-sm text-red-500"
                        >
                            {{ form.errors.content }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="sticky top-6 space-y-4">
                        <div
                            class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-5"
                        >
                            <h3
                                class="font-semibold text-gray-900 dark:text-white mb-4"
                            >
                                Thumbnail Berita
                            </h3>

                            <ImageUpload v-model="form.thumbnail" />
                        </div>

                        <div
                            class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-5"
                        >
                            <div class="flex items-center gap-2 mb-5">
                                <FolderOpen class="w-4 h-4 text-blue-600" />

                                <h3
                                    class="font-semibold text-gray-900 dark:text-white"
                                >
                                    Kategori
                                </h3>
                            </div>

                            <div class="relative">
                                <input
                                    v-model="form.category"
                                    @focus="showSuggestions = true"
                                    @blur="
                                        setTimeout(() => {
                                            showSuggestions = false;
                                        }, 150)
                                    "
                                    placeholder="Tambah kategori..."
                                    class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-slate-700 px-0 pb-3 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:ring-0 focus:outline-none focus:border-blue-500"
                                />

                                <div
                                    v-if="
                                        showSuggestions &&
                                        filteredCategories.length
                                    "
                                    class="absolute z-20 left-0 right-0 mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg overflow-hidden shadow-lg"
                                >
                                    <button
                                        v-for="category in filteredCategories"
                                        :key="category"
                                        type="button"
                                        @mousedown="form.category = category"
                                        class="w-full px-4 py-3 text-left text-sm text-gray-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-700 transition"
                                    >
                                        {{ category }}
                                    </button>
                                </div>
                            </div>

                            <p
                                class="mt-3 text-xs text-gray-500 dark:text-slate-400"
                            >
                                Add a new category or select from existing
                                categories.
                            </p>

                            <p
                                v-if="form.errors.category"
                                class="mt-2 text-sm text-red-500"
                            >
                                {{ form.errors.category }}
                            </p>
                        </div>

                        <div
                            class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-5"
                        >
                            <div class="flex items-center gap-2 mb-4">
                                <Tags class="w-4 h-4 text-blue-600" />

                                <h3
                                    class="font-semibold text-gray-900 dark:text-white"
                                >
                                    Label
                                </h3>
                            </div>

                            <TagInput v-model="form.label" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.article-title-input {
    width: 100%;
    border: none !important;
    box-shadow: none !important;
    background: transparent;
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.1;
    padding: 0;
    border-radius: 0;
}

.article-title-input:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 4px;
    border-radius: 2px;
}

.article-title-input:focus:not(:focus-visible) {
    outline: none;
}

.article-title-input::placeholder {
    color: #7c7d7f;
}
.dark .article-title-input::placeholder {
    color: rgba(124, 125, 127, 0.3);
}

.dark .article-title-input {
    color: white;
}
</style>
