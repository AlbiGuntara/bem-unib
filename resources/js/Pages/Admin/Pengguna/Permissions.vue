<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import {
    Plus,
    Pencil,
    Trash2,
    Save,
} from "lucide-vue-next";

const props = defineProps({
    permissions: Object,
    filters: Object,
});

const tableFilters = computed(() => ({
    search: props.filters?.search || "",
    sort: props.filters?.sort || "",
    order: props.filters?.order || "",
    perPage: props.filters?.perPage || 10,
}));

const columns = [
    {
        key: "name",
        label: "Permission",
    },
];

const filters = ref({
    search: props.filters?.search || "",
    perPage: props.filters?.perPage || 10,
    sort: props.filters?.sort || "",
    order: props.filters?.order || "",
});

/* CRUD */
const form = ref({ name: "" });
const editingId = ref(null);
const editingName = ref("");

function submit() {
    router.post(route("permissions.store"), form.value, {
        onSuccess: () => (form.value.name = ""),
    });
}

function startEdit(permission) {
    editingId.value = permission.id;
    editingName.value = permission.name;
}

function update(permission) {
    router.put(route("permissions.update", permission.id), {
        name: editingName.value,
    });
    editingId.value = null;
}

function destroy(permission) {
    if (confirm("Hapus permission ini?")) {
        router.delete(route("permissions.destroy", permission.id));
    }
}
</script>

<template>
    <AppLayout>

        <Head title="Permission Management" />

        <Table :data="permissions" :columns="columns" routeName="permissions.index" :filters="tableFilters">
            <!-- Header Action -->
            <template #header-action>
                <form @submit.prevent="submit" class="flex w-full md:w-auto">
                    <input v-model="form.name" placeholder="Nama permission"
                        class="flex-1 pl-4 pr-4 py-2 rounded-l-lg bg-white dark:bg-slate-800 border border-blue-700/30 text-blue-800 dark:text-white focus:border-blue-800 focus:outline-none" />

                    <button type="submit" :disabled="!form.name"
                        class="px-4 rounded-r-lg bg-gradient-to-r from-blue-800 to-blue-700 text-white transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:disabled:opacity-50">
                        <Plus class="w-5 h-5" />
                    </button>
                </form>
            </template>

            <!-- Permission Name -->
            <template #name="{ item }">
                <input v-if="editingId === item.id" v-model="editingName"
                    class="px-2 py-1 rounded-lg border border-blue-700/30 bg-white dark:bg-slate-800 w-full text-sm focus:ring-1 focus:ring-blue-700 outline-none" />

                <span v-else>
                    {{ item.name }}
                </span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <div class="flex items-center gap-2">

                    <!-- Save -->
                    <button v-if="editingId === item.id" @click="update(item)"
                        class="p-2 rounded-lg bg-green-600 text-white hover:bg-green-700 active:scale-95 transition"
                        title="Simpan">
                        <Save class="w-4 h-4" />
                    </button>

                    <!-- Edit -->
                    <button v-else @click="startEdit(item)"
                        class="p-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 active:scale-95 transition"
                        title="Edit">
                        <Pencil class="w-4 h-4" />
                    </button>

                    <!-- Delete -->
                    <button @click="destroy(item)"
                        class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700 active:scale-95 transition"
                        title="Hapus">
                        <Trash2 class="w-4 h-4" />
                    </button>

                </div>
            </template>
        </Table>
    </AppLayout>
</template>
