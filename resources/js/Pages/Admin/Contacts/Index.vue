<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { router, Head } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    Phone,
    Mail,
    MapPin,
    Trash2,
    Plus,
    Instagram,
    Music2,
    Youtube,
    Facebook,
    Map,
    Pencil,
} from "lucide-vue-next";
import Swal from "sweetalert2";
import CreateContactModal from "./Create.vue";
import EditContactModal from "./Edit.vue";

const createModal = ref(false);
const editModal = ref(false);
const selected = ref(null);

const props = defineProps({
    contacts: Array,
});

const typeMeta = {
    email: { icon: Mail, label: "Email", color: "bg-blue-100 text-blue-600" },
    phone: { icon: Phone, label: "Telepon / WhatsApp", color: "bg-green-100 text-green-600" },
    address: { icon: MapPin, label: "Alamat", color: "bg-purple-100 text-purple-600" },
    instagram: { icon: Instagram, label: "Instagram", color: "bg-pink-100 text-pink-600" },
    tiktok: { icon: Music2, label: "TikTok", color: "bg-slate-100 text-slate-700" },
    youtube: { icon: Youtube, label: "YouTube", color: "bg-red-100 text-red-600" },
    facebook: { icon: Facebook, label: "Facebook", color: "bg-blue-100 text-blue-700" },
    maps: { icon: Map, label: "Google Maps", color: "bg-yellow-100 text-yellow-600" },
};

function icon(type) {
    return typeMeta[type]?.icon || Mail;
}

function typeLabel(type) {
    return typeMeta[type]?.label || type;
}

function typeColor(type) {
    return typeMeta[type]?.color || "bg-slate-100 text-slate-600";
}

const editData = (item) => {
    selected.value = item;
    editModal.value = true;
};

function deleteData(id) {
    const isDark = document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Hapus kontak ini?",
        text: "Kontak akan dihapus permanen.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc2626",
        cancelButtonColor: isDark ? "#374151" : "#6b7280",
        background: isDark ? "#0f172a" : "#ffffff",
        color: isDark ? "#f1f5f9" : "#111827",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("contacts.destroy", id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Terhapus!",
                        text: "Kontak berhasil dihapus.",
                        icon: "success",
                        background: isDark ? "#0f172a" : "#ffffff",
                        color: isDark ? "#f1f5f9" : "#111827",
                        confirmButtonColor: "#2563eb",
                    }),
            });
        }
    });
}
</script>

<template>
    <AppLayout>
        <Head title="Kontak" />

        <div class="space-y-6">
            <!-- HERO HEADER -->
            <div class="relative overflow-hidden rounded-lg bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8 text-white shadow-xl">
                <div class="absolute top-0 right-0 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>
                <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Informasi Kontak BEM UNIB</h1>
                        <p class="mt-2 text-blue-100">Kelola kontak dan media sosial organisasi. Setiap kontak bersifat individual.</p>
                    </div>
                    <button @click="createModal = true"
                        class="flex items-center gap-2 rounded-lg bg-white px-5 py-3 font-medium text-blue-700 transition hover:scale-105">
                        <Plus class="h-4 w-4" />
                        Tambah Kontak
                    </button>
                </div>
            </div>

            <!-- CONTENT -->
            <div v-if="contacts.length" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="contact in contacts" :key="contact.id"
                    class="group rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg"
                            :class="typeColor(contact.type)">
                            <component :is="icon(contact.type)" class="h-5 w-5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ typeLabel(contact.type) }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-white break-all">{{ contact.value }}</p>
                        </div>
                        <div class="flex shrink-0 gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="editData(contact)"
                                class="rounded-lg bg-amber-100 p-1.5 text-amber-600 hover:bg-amber-200 dark:bg-amber-900/30">
                                <Pencil class="h-4 w-4" />
                            </button>
                            <button @click="deleteData(contact.id)"
                                class="rounded-lg bg-red-100 p-1.5 text-red-600 hover:bg-red-200 dark:bg-red-900/30">
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EMPTY STATE -->
            <div v-else
                class="rounded-lg border border-dashed border-slate-300 bg-white p-16 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30">
                    <Mail class="h-10 w-10 text-blue-600 dark:text-blue-400" />
                </div>
                <h3 class="mt-6 text-xl font-semibold text-slate-800 dark:text-white">Belum Ada Kontak</h3>
                <p class="mt-2 text-slate-500 dark:text-slate-400">Tambahkan kontak satu per satu sesuai kebutuhan.</p>
                <button @click="createModal = true"
                    class="mt-6 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-3 font-medium text-white transition hover:bg-blue-700">
                    <Plus class="h-4 w-4" />
                    Tambah Kontak
                </button>
            </div>
        </div>

        <CreateContactModal :show="createModal" @close="createModal = false" />
        <EditContactModal :show="editModal" :contact="selected" @close="editModal = false; selected = null" />
    </AppLayout>
</template>
