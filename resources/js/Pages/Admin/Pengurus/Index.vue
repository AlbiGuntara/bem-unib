<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";
import CreateModal from "./Create.vue";
import EditModal from "./Edit.vue";

import Swal from "sweetalert2";

import { router, usePage, Head } from "@inertiajs/vue3";
import { ref } from "vue";

import {
    Plus,
    Pencil,
    Trash2,
} from "lucide-vue-next";

const props = defineProps({
    pengurus: Object,
    departments: Array,
    filters: Object,
});

const createModal = ref(false);
const editModal = ref(false);

const selected = ref(null);

const editData = (item) => {
    selected.value = item;
    editModal.value = true;
};

// Delete
function deleteData(id) {
    const isDark =
        document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Hapus departemen ini?",
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
            router.delete(route("pengurus.destroy", id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Terhapus!",
                        text: "Departemen berhasil dihapus.",
                        icon: "success",
                        background: isDark ? "#0f172a" : "#ffffff",
                        color: isDark ? "#f1f5f9" : "#111827",
                        confirmButtonColor: "#2563eb",
                    }),
            });
        }
    });
}

const positionTypeLabels = {
    top_leader: 'Pimpinan',
    core: 'Pengurus Inti',
    ministry: 'Kementerian',
};

const columns = [
    { key: "name", label: "Pengurus", sortable: true },
    { key: "department", label: "Departemen" },
    { key: "position", label: "Jabatan" },
    { key: "position_type", label: "Tipe" },
    { key: "email", label: "Email" },
];
</script>

<template>
    <AppLayout>

        <Head title="Pengurus" />

        <Table :data="pengurus" :columns="columns" :filters="filters" route-name="pengurus.index">
            <template #header-action>
                <button @click="createModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white">
                    <Plus class="w-4 h-4" />
                    Tambah Pengurus
                </button>
            </template>

            <template #name="{ item }">
                <div class="flex items-center gap-3">
                    <img :src="item.photo
                        ? `/storage/${item.photo}`
                        : '/images/default-avatar.png'
                        " class="w-12 h-12 rounded-full object-cover border" loading="lazy" />

                    <div>
                        <h4 class="font-semibold text-slate-900 dark:text-slate-100">
                            {{ item.name }}
                        </h4>

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ item.npm }}
                        </p>
                    </div>
                </div>
            </template>

            <template #department="{ item }">
                {{ item.department?.name ?? "-" }}
            </template>

            <template #position_type="{ item }">
                <span v-if="item.position_type === 'top_leader'"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                    Pimpinan
                </span>

                <span v-else-if="item.position_type === 'core'"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    Pengurus Inti
                </span>

                <span v-else
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                    Kementerian
                </span>
            </template>

            <template #photo="{ item }">
                <img v-if="item.photo" :src="`/storage/${item.photo}`" class="w-12 h-12 rounded-full object-cover" loading="lazy" />
            </template>

            <template #actions="{ item }">
                <div class="flex gap-2">

                    <button @click="editData(item)" class="p-2 bg-yellow-500 rounded-lg text-white">
                        <Pencil class="w-4 h-4" />
                    </button>

                    <button @click="deleteData(item.id)" class="p-2 bg-red-600 rounded-lg text-white">
                        <Trash2 class="w-4 h-4" />
                    </button>

                </div>
            </template>
        </Table>

        <CreateModal :show="createModal" :departments="departments" @close="createModal = false" />

        <EditModal :show="editModal" :departments="departments" :pengurus="selected" @close="editModal = false" />

    </AppLayout>
</template>