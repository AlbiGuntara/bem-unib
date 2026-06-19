<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";
import { router, Head } from "@inertiajs/vue3";
import { ref } from "vue";
import { Pencil, Trash2, Plus, Download } from "lucide-vue-next";

import Swal from "sweetalert2";

import CreateModal from "./Create.vue";
import EditModal from "./Edit.vue";

const createModal = ref(false);
const editModal = ref(false);

const selectedItem = ref(null);

function editItem(item) {
    selectedItem.value = item;
    editModal.value = true;
}

const props = defineProps({
    letters: Object,
    filters: Object,
});

const columns = [
    {
        key: "letter_number",
        label: "No Surat",
        sortable: true,
    },
    {
        key: "subject",
        label: "Perihal",
        sortable: true,
    },
    {
        key: "receiver",
        label: "Penerima",
        sortable: true,
    },
    {
        key: "date",
        label: "Tanggal",
        sortable: true,
    },
    {
        key: "file",
        label: "File",
        sortable: false,
    },
];

function deleteData(item) {
    const isDark =
        document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Hapus surat ini?",
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
            confirmButton:
                "px-4 py-2 rounded-lg font-medium",
            cancelButton:
                "px-4 py-2 rounded-lg font-medium",
        },

        buttonsStyling: true,
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("surat-keluar.destroy", item.id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Terhapus!",
                        text: "Surat berhasil dihapus.",
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

        <Head title="Surat Keluar" />

        <Table :data="letters" :columns="columns" :filters="filters" route-name="surat-keluar.index">
            <template #header-action>
                <button @click="createModal = true"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:scale-95 transition">
                    <Plus class="w-4 h-4" />
                    <span>Tambah Surat Keluar</span>
                </button>
            </template>

            <template #letter_number="{ item }">
                <span class="font-medium text-slate-800 dark:text-slate-100">
                    {{ item.letter_number }}
                </span>
            </template>

            <template #file="{ item }">
                <a v-if="item.file_path" :href="`/storage/${item.file_path}`" target="_blank"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50">
                    <Download class="h-3.5 w-3.5" />
                    Unduh
                </a>
            </template>

            <template #actions="{ item }">
                <div class="flex items-center gap-2">

                    <button @click="editItem(item)"
                        class="p-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
                        <Pencil class="w-4 h-4" />
                    </button>

                    <button @click="deleteData(item)"
                        class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        <Trash2 class="w-4 h-4" />
                    </button>

                </div>
            </template>

        </Table>

        <CreateModal :show="createModal" @close="createModal = false" />

        <EditModal :show="editModal" :item="selectedItem" @close="editModal = false" />

    </AppLayout>

</template>
