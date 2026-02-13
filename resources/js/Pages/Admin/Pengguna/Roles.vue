<template>
    <AppLayout>
        <Head title="Role Management" />

        <!-- CONTAINER -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 space-y-6"
        >
            <!-- HEADER -->
            <div
                class="flex flex-col md:flex-row gap-4 justify-between items-center"
            >
                <!-- SEARCH -->
                <div class="relative w-full md:w-80">
                    <Search
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                    />
                    <input
                        v-model="filters.search"
                        @input="applyFilters"
                        placeholder="Cari role..."
                        class="pl-10 pr-4 py-2 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-green-600 outline-none transition-all"
                    />
                </div>

                <!-- Add Role -->
                <form @submit.prevent="submit" class="flex w-full md:w-auto">
                    <!-- INPUT -->
                    <input
                        v-model="form.name"
                        placeholder="Nama role"
                        class="flex-1 pl-5 pr-4 py-2 rounded-l-xl border border-gray-200 dark:border-gray-700 focus:border-green-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none placeholder-gray-400"
                    />

                    <!-- BUTTON + -->
                    <button
                        type="submit"
                        :disabled="!form.name"
                        class="px-4 rounded-r-xl bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center"
                    >
                        <Plus class="w-5 h-5 text-white" />
                    </button>
                </form>
            </div>

            <!-- FLASH -->
            <div
                v-if="flash.success"
                class="bg-green-100 text-green-700 px-4 py-2 rounded-lg"
            >
                {{ flash.success }}
            </div>

            <!-- TABLE -->
            <div
                class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg"
            >
                <table class="min-w-full text-sm">
                    <thead
                        class="bg-gradient-to-r from-green-600 to-green-700 text-white uppercase text-xs"
                    >
                        <tr class="text-center">
                            <th class="px-4 py-3">No</th>
                            <th
                                class="px-4 py-3 text-left cursor-pointer"
                                @click="sort('name')"
                            >
                                Role
                                <ArrowUp
                                    v-if="
                                        filters.sort_by === 'name' &&
                                        filters.sort_direction === 'asc'
                                    "
                                    class="inline w-3 h-3"
                                />
                                <ArrowDown
                                    v-else-if="
                                        filters.sort_by === 'name' &&
                                        filters.sort_direction === 'desc'
                                    "
                                    class="inline w-3 h-3"
                                />
                            </th>
                            <th class="px-4 py-3 text-left">Permission</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(role, index) in roles.data"
                            :key="role.id"
                            class="text-center border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800"
                        >
                            <td class="px-4 py-3">
                                {{ roles.from + index }}
                            </td>

                            <td class="px-4 py-3 text-left">
                                <input
                                    v-if="editId === role.id"
                                    v-model="editName"
                                    class="px-2 py-1 rounded border w-full"
                                />
                                <span v-else class="font-medium">
                                    {{ role.name }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-left text-gray-500">
                                {{ role.permissions?.length ?? 0 }} permission
                            </td>

                            <!-- ACTION ICON -->
                            <td class="px-4 py-3 flex justify-start gap-3">
                                <button
                                    v-if="editId === role.id"
                                    @click="update(role)"
                                    class="text-green-600 hover:scale-110"
                                    title="Simpan"
                                >
                                    <ShieldCheck class="w-5 h-5" />
                                </button>

                                <button
                                    v-else
                                    @click="startEdit(role)"
                                    class="text-blue-600 hover:scale-110"
                                    title="Edit"
                                >
                                    <Edit class="w-5 h-5" />
                                </button>

                                <button
                                    @click="openPermission(role)"
                                    class="text-indigo-600 hover:scale-110"
                                    title="Permission"
                                >
                                    <ShieldCheck class="w-5 h-5" />
                                </button>

                                <button
                                    v-if="role.name !== 'super-admin'"
                                    @click="destroy(role)"
                                    class="text-red-600 hover:scale-110"
                                    title="Hapus"
                                >
                                    <Trash2 class="w-5 h-5" />
                                </button>
                            </td>
                        </tr>

                        <tr v-if="roles.data.length === 0">
                            <td
                                colspan="3"
                                class="py-6 text-center text-gray-500"
                            >
                                Tidak ada data role
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div
                v-if="roles.data.length"
                class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
            >
                <!-- SHOW PER PAGE -->
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">
                        Show
                    </label>

                    <select
                        v-model="filters.per_page"
                        @change="applyFilters"
                        class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <!-- PAGINATION BUTTON -->
                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in roles.links"
                        :key="index"
                        @click="link.url && router.visit(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all font-medium"
                        :class="[
                            link.active
                                ? 'bg-green-600 text-white border-green-700 shadow-lg scale-105'
                                : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 hover:shadow',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <!-- PREV -->
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />

                        <!-- NEXT -->
                        <ChevronRight
                            v-else-if="index === roles.links.length - 1"
                            class="w-4 h-4"
                        />

                        <!-- NUMBER -->
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL PERMISSION -->
        <div
            v-if="showPermissionModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center"
        >
            <div
                class="bg-white dark:bg-gray-900 w-full max-w-3xl rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh]"
            >
                <!-- HEADER -->
                <div
                    class="px-6 py-4 rounded-t-2xl border-b border-gray-200 dark:border-gray-700 flex items-center justify-between sticky top-0 bg-white dark:bg-gray-900 z-10"
                >
                    <div>
                        <h2
                            class="text-lg font-bold text-gray-800 dark:text-gray-100"
                        >
                            Kelola Permission
                        </h2>
                        <p class="text-sm text-gray-500">
                            Role:
                            <span class="font-semibold">{{
                                activeRole.name
                            }}</span>
                        </p>
                    </div>

                    <button
                        @click="toggleSelectAll"
                        class="text-sm px-4 py-2 rounded-xl border border-green-600 text-green-600 hover:bg-green-600 hover:text-white transition"
                    >
                        {{ isAllSelected ? "Lepas Semua" : "Pilih Semua" }}
                    </button>
                </div>

                <!-- BODY -->
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <label
                            v-for="permission in permissions"
                            :key="permission.id"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-gray-800 cursor-pointer transition"
                        >
                            <input
                                type="checkbox"
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                :value="permission.name"
                                v-model="selectedPermissions"
                            />
                            <span
                                class="text-sm text-gray-700 dark:text-gray-200"
                            >
                                {{ permission.name }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- FOOTER -->
                <div
                    class="px-6 py-4 rounded-b-2xl border-t border-gray-200 dark:border-gray-700 flex justify-between items-center sticky bottom-0 bg-white dark:bg-gray-900"
                >
                    <span class="text-sm text-gray-500">
                        Dipilih: {{ selectedPermissions.length }} /
                        {{ permissions.length }}
                    </span>

                    <div class="flex gap-3">
                        <button
                            @click="showPermissionModal = false"
                            class="px-5 py-2 rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                        >
                            Batal
                        </button>

                        <button
                            @click="savePermission"
                            class="px-5 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700 transition shadow"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import {
    Plus,
    Edit,
    Trash2,
    ShieldCheck,
    Search,
    ArrowUp,
    ArrowDown,
    ChevronLeft,
    ChevronRight,
} from "lucide-vue-next";

/* PROPS */
const props = defineProps({
    roles: Object, // PAGINATED
    permissions: Array,
    filters: Object,
});

/* FLASH */
const page = usePage();
const flash = page.props.flash || {};

/* FILTER STATE */
const filters = ref({
    search: props.filters?.search || "",
    per_page: props.filters?.per_page || 10,
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
});

function applyFilters() {
    router.get(route("roles.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

/* SORT */
function sort(column) {
    if (filters.value.sort_by !== column) {
        filters.value.sort_by = column;
        filters.value.sort_direction = "asc";
    } else {
        filters.value.sort_direction =
            filters.value.sort_direction === "asc"
                ? "desc"
                : filters.value.sort_direction === "desc"
                ? ""
                : "asc";

        if (!filters.value.sort_direction) {
            filters.value.sort_by = "";
        }
    }
    applyFilters();
}

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
        { onSuccess: () => (showPermissionModal.value = false) }
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
