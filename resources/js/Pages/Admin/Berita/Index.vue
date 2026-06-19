<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";

import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";

import { Eye, Pencil, Trash2, Plus, ChevronDown } from "lucide-vue-next";

import Swal from "sweetalert2";

const props = defineProps({
    beritas: Object,
    filters: Object,
});

const columns = [
    {
        key: "thumbnail",
        label: "Thumbnail",
        sortable: false,
    },
    {
        key: "title",
        label: "Judul",
        sortable: true,
    },
    {
        key: "category",
        label: "Kategori",
        sortable: true,
    },
    {
        key: "user",
        label: "Penulis",
        sortable: false,
    },
    {
        key: "views",
        label: "Views",
        sortable: true,
    },
    {
        key: "created_at",
        label: "Tanggal",
        sortable: true,
    },
    {
        key: "status",
        label: "Status",
        sortable: false,
    },
];

const selectedStatuses = ref({});

watch(
    () => props.beritas.data,
    (items) => {
        items.forEach((item) => {
            selectedStatuses.value[item.id] = item.is_published ? "1" : "0";
        });
    },
    { immediate: true },
);

function updateStatus(item) {
    router.patch(
        route("berita.update-status", item.id),
        {
            is_published: selectedStatuses.value[item.id] === "1",
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
}

function deleteData(id) {
    const isDark = document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Hapus berita ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: "warning",

        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",

        confirmButtonColor: "#dc2626",
        cancelButtonColor: isDark ? "#374151" : "#6b7280",

        background: isDark ? "#0f172a" : "#ffffff",
        color: isDark ? "#f1f5f9" : "#111827",

        customClass: {
            popup: "rounded-xl shadow-2xl",
            title: "text-lg font-semibold",
            htmlContainer: "text-sm",
            confirmButton: "px-4 py-2 rounded-lg font-medium",
            cancelButton: "px-4 py-2 rounded-lg font-medium",
        },

        buttonsStyling: true,
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("berita.destroy", id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Terhapus!",
                        text: "Berita berhasil dihapus.",
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
        <Head title="Berita" />

        <Table
            :data="beritas"
            :columns="columns"
            :filters="filters"
            route-name="berita.index"
        >
            <!-- Header Button -->

            <template #header-action>
                <Link
                    href="/admin/berita/create"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:scale-95 transition"
                >
                    <Plus class="w-4 h-4" />
                    <span>Tambah Berita</span>
                </Link>
            </template>

            <!-- Thumbnail -->

            <template #thumbnail="{ item }">
                <img
                    v-if="item.thumbnail"
                    :src="`/storage/${item.thumbnail}`"
                    class="w-16 h-12 rounded-lg object-cover"
                />

                <div v-else class="w-16 h-12 rounded-lg bg-gray-100" />
            </template>

            <!-- Judul -->

            <template #title="{ item }">
                <div class="space-y-1">
                    <div
                        class="font-semibold text-gray-900 dark:text-white line-clamp-2"
                    >
                        {{ item.title }}
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ item.slug }}
                    </div>
                </div>
            </template>

            <!-- Penulis -->

            <template #user="{ item }">
                {{ item.user?.name }}
            </template>

            <!-- Views -->

            <template #views="{ item }">
                <span class="font-medium">
                    {{ item.views }}
                </span>
            </template>

            <!-- Tanggal -->

            <template #created_at="{ item }">
                {{
                    new Date(item.created_at).toLocaleDateString("id-ID", {
                        day: "2-digit",
                        month: "short",
                        year: "numeric",
                    })
                }}
            </template>

            <!-- Status -->

            <template #status="{ item }">
                <div class="relative w-full max-w-[140px]">
                    <select
                        v-model="selectedStatuses[item.id]"
                        @change="updateStatus(item)"
                        class="w-full appearance-none rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 pr-8 text-sm text-gray-700 dark:text-gray-100 shadow-sm hover:border-blue-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                    >
                        <option value="1">Published</option>

                        <option value="0">Draft</option>
                    </select>

                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400"
                    >
                        <ChevronDown class="w-4 h-4" />
                    </div>
                </div>
            </template>

            <!-- Actions -->

            <template #actions="{ item }">
                <div class="flex items-center gap-2">
                    <!-- Preview -->

                    <a
                        :href="route('berita.preview', item.id)"
                        target="_blank"
                        class="p-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600"
                    >
                        <Eye class="w-4 h-4" />
                    </a>

                    <!-- Edit -->

                    <Link
                        :href="route('berita.edit', item.id)"
                        class="p-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600"
                    >
                        <Pencil class="w-4 h-4" />
                    </Link>

                    <!-- Delete -->

                    <button
                        @click="deleteData(item.id)"
                        class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700"
                    >
                        <Trash2 class="w-4 h-4" />
                    </button>
                </div>
            </template>
        </Table>
    </AppLayout>
</template>
