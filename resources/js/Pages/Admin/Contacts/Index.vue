<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { router, Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import {
    Phone,
    Mail,
    MapPin,
    Trash2,
    Plus,
    Send,
    Instagram,
    Music2,
    Youtube,
    Facebook,
    Pencil,
    Save,
    X,
} from "lucide-vue-next";
import Swal from "sweetalert2";
import CreateContactModal from "./Create.vue";

const createModal = ref(false);

const props = defineProps({
    contacts: Array,
});

const contactFields = [
    {
        key: "email",
        label: "Email",
        icon: Mail,
        color: "bg-blue-100 text-blue-600",
    },
    {
        key: "phone",
        label: "Telepon / WhatsApp",
        icon: Phone,
        color: "bg-green-100 text-green-600",
    },
    {
        key: "address",
        label: "Alamat",
        icon: MapPin,
        color: "bg-purple-100 text-purple-600",
    },
    {
        key: "instagram_url",
        label: "Instagram",
        icon: Instagram,
        color: "bg-pink-100 text-pink-600",
    },
    {
        key: "tiktok_url",
        label: "TikTok",
        icon: Music2,
        color: "bg-slate-100 text-slate-700",
    },
    {
        key: "youtube_url",
        label: "YouTube",
        icon: Youtube,
        color: "bg-red-100 text-red-600",
    },
    {
        key: "facebook_url",
        label: "Facebook",
        icon: Facebook,
        color: "bg-blue-100 text-blue-700",
    },
];
const groupedFields = computed(() => {
    return contactFields
        .map((field) => ({
            ...field,
            items: props.contacts
                .filter((c) => c[field.key])
                .map((c) => ({ id: c.id, value: c[field.key] })),
        }))
        .filter((g) => g.items.length > 0);
});

const editKey = ref(null);
const editValue = ref("");

function startEdit(id, fieldKey, value) {
    editKey.value = `${id}-${fieldKey}`;
    editValue.value = value;
}

function cancelEdit() {
    editKey.value = null;
    editValue.value = "";
}

function updateContact(id, fieldKey) {
    router.put(
        route("contacts.update", id),
        { [fieldKey]: editValue.value },
        { onSuccess: () => cancelEdit() },
    );
}

function getSwalTheme() {
    const isDark = document.documentElement.classList.contains("dark");
    return {
        background: isDark ? "#0f172a" : "#ffffff",
        color: isDark ? "#f1f5f9" : "#111827",
        confirmButtonColor: "#2563eb",
        cancelButtonColor: isDark ? "#374151" : "#6b7280",
    };
}

function deleteContact(id) {
    const contact = props.contacts.find((c) => c.id === id);
    const label = contact?.email || contact?.phone || `Kontak #${id}`;

    Swal.fire({
        ...getSwalTheme(),
        title: "Hapus kontak ini?",
        text: `${label} akan dihapus permanen.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc2626",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("contacts.destroy", id), {
                onSuccess: () =>
                    Swal.fire({
                        ...getSwalTheme(),
                        title: "Terhapus!",
                        text: "Kontak berhasil dihapus.",
                        icon: "success",
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
            <div
                class="relative overflow-hidden rounded-lg bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8 text-white shadow-xl"
            >
                <div
                    class="absolute top-0 right-0 h-48 w-48 rounded-full bg-white/10 blur-3xl"
                ></div>

                <div
                    class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h1 class="text-3xl font-bold">
                            Informasi Kontak BEM UNIB
                        </h1>
                        <p class="mt-2 text-blue-100">
                            Kelola seluruh informasi kontak dan media sosial
                            organisasi dalam satu tempat.
                        </p>
                    </div>

                    <button
                        @click="createModal = true"
                        class="flex items-center gap-2 rounded-lg bg-white px-5 py-3 font-medium text-blue-700 transition hover:scale-105"
                    >
                        <Plus class="h-4 w-4" />
                        Tambah Kontak
                    </button>
                </div>
            </div>

            <!-- CONTENT -->
            <div v-if="contacts.length" class="grid gap-5 lg:grid-cols-2">
                <div
                    v-for="group in groupedFields"
                    :key="group.key"
                    class="group rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-700 dark:bg-slate-900"
                >
                    <!-- HEADER -->
                    <div class="mb-5 flex items-center gap-3">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-lg"
                            :class="group.color"
                        >
                            <component :is="group.icon" class="h-6 w-6" />
                        </div>

                        <div>
                            <h3
                                class="font-semibold text-slate-800 dark:text-white"
                            >
                                {{ group.label }}
                            </h3>

                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ group.items.length }} data tersedia
                            </p>
                        </div>
                    </div>

                    <!-- ITEMS -->
                    <div class="space-y-3">
                        <div
                            v-for="item in group.items"
                            :key="item.id"
                            class="rounded-lg border border-slate-100 bg-slate-50 p-3 transition hover:border-blue-200 hover:bg-blue-50/50 dark:border-slate-700 dark:bg-slate-800"
                        >
                            <!-- EDIT MODE -->
                            <template
                                v-if="editKey === `${item.id}-${group.key}`"
                            >
                                <div class="flex items-center gap-2">
                                    <input
                                        v-model="editValue"
                                        class="flex-1 rounded-lg border border-blue-300 bg-white px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-200 dark:border-blue-700 dark:bg-slate-900 dark:text-white"
                                        @keyup.enter="
                                            updateContact(item.id, group.key)
                                        "
                                        @keyup.escape="cancelEdit"
                                    />

                                    <button
                                        @click="
                                            updateContact(item.id, group.key)
                                        "
                                        class="rounded-lg bg-green-100 p-2 text-green-600 hover:bg-green-200 dark:bg-green-900/30"
                                    >
                                        <Save class="h-4 w-4" />
                                    </button>

                                    <button
                                        @click="cancelEdit"
                                        class="rounded-lg bg-slate-200 p-2 text-slate-600 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300"
                                    >
                                        <X class="h-4 w-4" />
                                    </button>
                                </div>
                            </template>

                            <!-- VIEW MODE -->
                            <template v-else>
                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <span
                                        class="flex-1 break-all text-sm text-slate-700 dark:text-slate-200"
                                    >
                                        {{ item.value }}
                                    </span>

                                    <div class="flex items-center gap-2">
                                        <button
                                            @click="
                                                startEdit(
                                                    item.id,
                                                    group.key,
                                                    item.value,
                                                )
                                            "
                                            class="rounded-lg bg-amber-100 p-2 text-amber-600 transition hover:bg-amber-200 dark:bg-amber-900/30"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </button>

                                        <button
                                            @click="deleteContact(item.id)"
                                            class="rounded-lg bg-red-100 p-2 text-red-600 transition hover:bg-red-200 dark:bg-red-900/30"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EMPTY STATE -->
            <div
                v-else
                class="rounded-lg border border-dashed border-slate-300 bg-white p-16 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900"
            >
                <div
                    class="mx-auto flex h-24 w-24 items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30"
                >
                    <Send class="h-10 w-10 text-blue-600 dark:text-blue-400" />
                </div>

                <h3
                    class="mt-6 text-xl font-semibold text-slate-800 dark:text-white"
                >
                    Belum Ada Informasi Kontak
                </h3>

                <p class="mt-2 text-slate-500 dark:text-slate-400">
                    Tambahkan email, nomor telepon, alamat, dan media sosial
                    organisasi.
                </p>

                <button
                    @click="createModal = true"
                    class="mt-6 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-3 font-medium text-white transition hover:bg-blue-700"
                >
                    <Plus class="h-4 w-4" />
                    Tambah Kontak
                </button>
            </div>
        </div>

        <CreateContactModal :show="createModal" @close="createModal = false" />
    </AppLayout>
</template>
