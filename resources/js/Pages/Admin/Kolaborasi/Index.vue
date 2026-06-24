<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";
import CreateModal from "./Create.vue";
import EditModal from "./Edit.vue";

import Swal from "sweetalert2";

import { router, Head } from "@inertiajs/vue3";
import { ref } from "vue";

import {
    Plus,
    Pencil,
    Trash2,
    ExternalLink,
} from "lucide-vue-next";

const props = defineProps({
    kolaborasis: Object,
    filters: Object,
});

const createModal = ref(false);
const editModal = ref(false);
const selected = ref(null);

const editData = (item) => {
    selected.value = item;
    editModal.value = true;
};

function deleteData(id) {
    const isDark = document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Hapus kolaborasi ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
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
            router.delete(route("kolaborasi.destroy", id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Terhapus!",
                        text: "Kolaborasi berhasil dihapus.",
                        icon: "success",
                        background: isDark ? "#0f172a" : "#ffffff",
                        color: isDark ? "#f1f5f9" : "#111827",
                        confirmButtonColor: "#2563eb",
                    }),
            });
        }
    });
}

const typeLabels = {
    external: "External",
    internal: "Internal",
};

const columns = [
    { key: "logo", label: "Logo" },
    { key: "name", label: "Nama", sortable: true },
    { key: "type", label: "Tipe", sortable: true },
    { key: "url", label: "URL" },
    { key: "order", label: "Urutan", sortable: true },
];
</script>

<template>
    <AppLayout>
        <Head title="Kolaborasi" />

        <Table :data="kolaborasis" :columns="columns" :filters="filters" route-name="kolaborasi.index">
            <template #header-action>
                <button @click="createModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white">
                    <Plus class="w-4 h-4" />
                    Tambah Kolaborasi
                </button>
            </template>

            <template #logo="{ item }">
                <img :src="`/storage/${item.logo}`"
                    class="w-14 h-14 rounded-lg object-contain border bg-white p-1" loading="lazy" />
            </template>

            <template #type="{ item }">
                <span v-if="item.type === 'external'"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                    External
                </span>
                <span v-else
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    Internal
                </span>
            </template>

            <template #url="{ item }">
                <a v-if="item.url" :href="item.url" target="_blank"
                    class="inline-flex items-center gap-1 text-blue-600 hover:underline text-sm">
                    {{ item.url.replace(/^https?:\/\//, '').replace(/\/.*$/, '') }}
                    <ExternalLink class="w-3 h-3" />
                </a>
                <span v-else class="text-slate-400 text-sm">-</span>
            </template>

            <template #actions="{ item }">
                <div class="flex gap-2">
                    <button @click="editData(item)"
                        class="p-2 bg-yellow-500 rounded-lg text-white">
                        <Pencil class="w-4 h-4" />
                    </button>

                    <button @click="deleteData(item.id)"
                        class="p-2 bg-red-600 rounded-lg text-white">
                        <Trash2 class="w-4 h-4" />
                    </button>
                </div>
            </template>
        </Table>

        <CreateModal :show="createModal" @close="createModal = false" />
        <EditModal :show="editModal" :kolaborasi="selected" @close="editModal = false" />
    </AppLayout>
</template>
