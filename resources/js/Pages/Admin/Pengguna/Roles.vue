<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Table from "@/Components/Table.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

import {
    Plus,
    Pencil,
    Trash2,
    ShieldCheck,
    Save,
} from "lucide-vue-next";

/* PROPS */
const props = defineProps({
    roles: Object,
    permissions: Array,
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
        label: "Role",
    },
    {
        key: "permissions_count",
        label: "Permission",
        sortable: false,
    },
];

/* CREATE */
const form = ref({ name: "" });
function submit() {
    router.post(route("roles.store"), form.value, {
        onSuccess: () => (form.value.name = ""),
    });
}

/* EDIT */
const editId = ref(null);
const editName = ref("");

function startEdit(role) {
    editId.value = role.id;
    editName.value = role.name;
}

function update(role) {
    router.put(route("roles.update", role.id), {
        name: editName.value,
    });
    editId.value = null;
}

/* DELETE */
function destroy(role) {
    if (confirm("Hapus role ini?")) {
        router.delete(route("roles.destroy", role.id));
    }
}

/* PERMISSION MODAL */
const showPermissionModal = ref(false);
const activeRole = ref(null);
const selectedPermissions = ref([]);

function openPermission(role) {
    activeRole.value = role;
    selectedPermissions.value = role.permissions.map((p) => p.name);
    showPermissionModal.value = true;
}

function savePermission() {
    router.post(
        route("roles.sync-permissions", activeRole.value.id),
        { permissions: selectedPermissions.value },
        { onSuccess: () => (showPermissionModal.value = false) },
    );
}

/* SELECT ALL */
const isAllSelected = computed(() => {
    return (
        selectedPermissions.value.length === props.permissions.length &&
        props.permissions.length > 0
    );
});

function toggleSelectAll() {
    if (isAllSelected.value) {
        selectedPermissions.value = [];
    } else {
        selectedPermissions.value = props.permissions.map((p) => p.name);
    }
}
</script>

<template>
    <AppLayout>

        <Head title="Role Management" />

        <Table :data="roles" :columns="columns" routeName="roles.index" :filters="tableFilters">
            <!-- HEADER ACTION -->
            <template #header-action>
                <form @submit.prevent="submit" class="flex w-full md:w-auto">
                    <input v-model="form.name" placeholder="Nama role"
                        class="flex-1 pl-4 pr-4 py-2 rounded-l-lg bg-white dark:bg-slate-800 border border-blue-700/30 text-blue-800 dark:text-white focus:border-blue-800 focus:outline-none" />

                    <button type="submit" :disabled="!form.name"
                        class="px-4 rounded-r-lg bg-gradient-to-r from-blue-800 to-blue-700 text-white transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <Plus class="w-5 h-5" />
                    </button>
                </form>
            </template>

            <!-- ROLE -->
            <template #name="{ item }">
                <input v-if="editId === item.id" v-model="editName"
                    class="px-2 py-1 rounded-lg border border-blue-700/30 bg-white dark:bg-slate-800 w-full text-sm focus:ring-1 focus:ring-blue-700 outline-none" />

                <span v-else>
                    {{ item.name }}
                </span>
            </template>

            <!-- PERMISSION COUNT -->
            <template #permissions_count="{ item }">
                <span
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                    {{ item.permissions_count }}
                    hak akses
                </span>
            </template>

            <!-- ACTIONS -->
            <template #actions="{ item }">
                <div class="flex items-center gap-2">

                    <!-- SAVE -->
                    <button v-if="editId === item.id" @click="update(item)"
                        class="p-2 rounded-lg bg-green-600 text-white hover:bg-green-700 active:scale-95 transition"
                        title="Simpan">
                        <Save class="w-4 h-4" />
                    </button>

                    <!-- EDIT -->
                    <button v-else @click="startEdit(item)"
                        class="p-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 active:scale-95 transition"
                        title="Edit">
                        <Pencil class="w-4 h-4" />
                    </button>

                    <!-- PERMISSION -->
                    <button @click="openPermission(item)"
                        class="p-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 active:scale-95 transition"
                        title="Permission">
                        <ShieldCheck class="w-4 h-4" />
                    </button>

                    <!-- DELETE -->
                    <button v-if="item.name !== 'super-admin'" @click="destroy(item)"
                        class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700 active:scale-95 transition"
                        title="Hapus">
                        <Trash2 class="w-4 h-4" />
                    </button>

                </div>
            </template>
        </Table>

        <!-- MODAL PERMISSION -->
        <!-- MODAL PERMISSION -->
        <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
            enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="showPermissionModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                <div
                    class="flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                    <!-- HEADER -->
                    <div
                        class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-white">
                                    Kelola Permission
                                </h2>

                                <p class="mt-1 text-sm text-blue-100">
                                    Atur hak akses untuk role
                                    <span class="font-semibold">
                                        {{ activeRole?.name }}
                                    </span>
                                </p>
                            </div>

                            <button @click="toggleSelectAll"
                                class="rounded-lg border border-white/30 bg-white/10 px-4 py-2 text-sm font-medium text-white transition hover:bg-white hover:text-blue-700">
                                {{
                                    isAllSelected
                                        ? "Lepas Semua"
                                        : "Pilih Semua"
                                }}
                            </button>
                        </div>
                    </div>

                    <!-- BODY -->
                    <div class="modal-scroll flex-1 overflow-y-auto">
                        <div class="p-6">
                            <!-- COUNTER -->
                            <div
                                class="mb-5 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 dark:border-blue-900 dark:bg-blue-950/30">
                                <p class="text-sm font-medium text-blue-700 dark:text-blue-300">
                                    {{ selectedPermissions.length }}
                                    dari
                                    {{ permissions.length }}
                                    permission dipilih
                                </p>
                            </div>

                            <!-- LIST PERMISSION -->
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                <label v-for="permission in permissions" :key="permission.id"
                                    class="group flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 transition hover:border-blue-600 hover:bg-blue-50 dark:border-slate-700 dark:bg-slate-800 dark:hover:border-blue-500 dark:hover:bg-slate-700">
                                    <input v-model="selectedPermissions" :value="permission.name" type="checkbox"
                                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600" />

                                    <div class="flex-1 min-w-0">
                                        <p class="truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                                            {{ permission.name }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div
                        class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-between dark:border-slate-700 dark:bg-slate-900">
                        <div class="flex items-center text-sm text-slate-500 dark:text-slate-400">
                            Dipilih:
                            <span class="ml-1 font-semibold">
                                {{ selectedPermissions.length }}
                            </span>
                        </div>

                        <div class="flex gap-3">
                            <button @click="showPermissionModal = false"
                                class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                                Batal
                            </button>

                            <button @click="savePermission"
                                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<style scoped>
.modal-scroll {
    scrollbar-width: thin;
    scrollbar-color: rgb(37 99 235 / 0.6) transparent;
}

/* Chrome */
.modal-scroll::-webkit-scrollbar {
    width: 6px;
}

.modal-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.modal-scroll::-webkit-scrollbar-thumb {
    background: rgb(37 99 235 / 0.5);
    border-radius: 9999px;
    border: 2px solid transparent;
    background-clip: padding-box;
}

.modal-scroll::-webkit-scrollbar-thumb:hover {
    background: rgb(29 78 216 / 0.8);
}

/* Dark Mode */
:global(.dark) .modal-scroll {
    scrollbar-color: rgb(59 130 246 / 0.7) transparent;
}

:global(.dark) .modal-scroll::-webkit-scrollbar-thumb {
    background: rgb(59 130 246 / 0.6);
}

:global(.dark) .modal-scroll::-webkit-scrollbar-thumb:hover {
    background: rgb(96 165 250 / 0.8);
}
</style>