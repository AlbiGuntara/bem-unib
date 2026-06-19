<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";
import { router, Head } from "@inertiajs/vue3";
import { ref } from "vue";
import { Pencil, Trash2, Plus } from "lucide-vue-next";
import Swal from "sweetalert2";
import CreateFaqModal from "./Create.vue";
import EditFaqModal from "./Edit.vue";

const createModal = ref(false);
const editModal = ref(false);
const selectedFaq = ref(null);

function editFaq(item) {
    selectedFaq.value = item;
    editModal.value = true;
}

const props = defineProps({
    faqs: Object,
    filters: Object,
});

const columns = [
    { key: "question", label: "Pertanyaan", sortable: true },
    { key: "answer", label: "Jawaban", sortable: false },
];

function deleteData(item) {
    const isDark = document.documentElement.classList.contains("dark");
    Swal.fire({
        title: "Hapus FAQ ini?",
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
            router.delete(route("faqs.destroy", item.id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Terhapus!",
                        text: "FAQ berhasil dihapus.",
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
        <Head title="FAQ" />

        <Table :data="faqs" :columns="columns" :filters="filters" route-name="faqs.index">
            <template #header-action>
                <button @click="createModal = true"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:scale-95 transition">
                    <Plus class="w-4 h-4" />
                    <span>Tambah FAQ</span>
                </button>
            </template>

            <template #answer="{ item }">
                <span class="text-sm text-slate-500 line-clamp-2">{{ item.answer }}</span>
            </template>

            <template #actions="{ item }">
                <div class="flex items-center gap-2">
                    <button @click="editFaq(item)"
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

        <CreateFaqModal :show="createModal" @close="createModal = false" />
        <EditFaqModal :show="editModal" :faq="selectedFaq" @close="editModal = false" />
    </AppLayout>
</template>
