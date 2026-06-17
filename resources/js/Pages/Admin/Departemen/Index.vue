<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";
import { router, Head } from "@inertiajs/vue3";
import { ref } from "vue";
import { Pencil, Trash2, Plus } from "lucide-vue-next";

import Swal from "sweetalert2";

import CreateDepartmentModal from "./Create.vue";
import EditDepartmentModal from "./Edit.vue";

const createModal = ref(false);
const editModal = ref(false);

const selectedDepartment = ref(null);

function editDepartment(item) {
    selectedDepartment.value = item;
    editModal.value = true;
}

const props = defineProps({
    departments: Object,
    filters: Object,
});

const columns = [
    {
        key: "logo",
        label: "Logo",
        sortable: false,
    },
    {
        key: "name",
        label: "Nama Departemen",
        sortable: true,
    },
    {
        key: "code",
        label: "Kode",
        sortable: true,
    },
];

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
            router.delete(route("departments.destroy", id), {
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
</script>

<template>
    <AppLayout>

        <Head title="Departemen" />

        <Table :data="departments" :columns="columns" :filters="filters" route-name="departments.index">
            <template #header-action>
                <button @click="createModal = true"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:scale-95 transition">
                    <Plus class="w-4 h-4" />
                    <span>Tambah Departemen</span>
                </button>
            </template>

            <template #logo="{ item }">
                <img v-if="item.logo_path" :src="`/storage/${item.logo_path}`"
                    class="w-10 h-10 rounded-lg object-cover" />
            </template>

            <template #actions="{ item }">
                <div class="flex items-center gap-2">

                    <!-- Edit -->
                    <button @click="editDepartment(item)"
                        class="p-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
                        <Pencil class="w-4 h-4" />
                    </button>

                    <!-- Delete -->
                    <button @click="deleteData(item)"
                        class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        <Trash2 class="w-4 h-4" />
                    </button>

                </div>
            </template>

        </Table>

        <CreateDepartmentModal :show="createModal" @close="createModal = false" />

        <EditDepartmentModal :show="editModal" :department="selectedDepartment" @close="editModal = false" />

    </AppLayout>

</template>
