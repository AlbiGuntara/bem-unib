<template>
    <AppLayout>
        <Head title="Role Management" />

        <!-- CONTAINER -->
        <div
            class="p-6 bg-white dark:bg-gray-900 backdrop-blur-sm rounded-2xl shadow-sm border border-electric-blue/20 transition-all duration-300"
        >
            <!-- HEADER -->
            <div
                class="flex flex-col md:flex-row gap-4 justify-between items-stretch md:items-center mb-6"
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
                        class="pl-10 pr-4 py-2 w-full rounded-xl bg-cream dark:bg-[#1F1F1F] border border-electric-blue/30 text-deep-blue dark:text-cream placeholder-deep-blue/40 dark:placeholder-cream/40 focus:ring-2 focus:ring-electric-blue focus:outline-none transition-all duration-300 shadow-sm hover:shadow-md"
                    />
                </div>

                <!-- ADD ROLE -->
                <form @submit.prevent="submit" class="flex w-full md:w-auto">
                    <input
                        v-model="form.name"
                        placeholder="Nama role"
                        class="flex-1 pl-4 pr-4 py-2 rounded-l-xl bg-cream dark:bg-[#1F1F1F] border border-electric-blue/30 text-deep-blue dark:text-cream focus:border-deep-blue focus:outline-none transition-all duration-300 shadow-sm"
                    />
                    <button
                        type="submit"
                        :disabled="!form.name"
                        class="px-4 rounded-r-xl bg-gradient-to-r from-deep-blue to-electric-blue text-white hover:opacity-90 disabled:opacity-40 transition-all duration-300"
                    >
                        <Plus class="w-5 h-5" />
                    </button>
                </form>
            </div>

            <!-- TABLE CONTAINER -->
            <div
                class="bg-white dark:bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl border border-gray-200 dark:border-electric-blue/30 shadow-sm overflow-hidden"
            >
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-deep-blue to-electric-blue text-cream uppercase text-xs tracking-wide"
                            >
                                <th class="px-4 py-3">No</th>

                                <th
                                    class="px-4 py-3 cursor-pointer select-none"
                                    @click="sort('name')"
                                >
                                    <div class="flex items-center gap-1">
                                        <span>Role</span>
                                        <ArrowUp
                                            v-if="
                                                filters.sort_by === 'name' &&
                                                filters.sort_direction === 'asc'
                                            "
                                            class="w-3 h-3 text-cream/70"
                                        />
                                        <ArrowDown
                                            v-else-if="
                                                filters.sort_by === 'name' &&
                                                filters.sort_direction ===
                                                    'desc'
                                            "
                                            class="w-3 h-3 text-cream/70"
                                        />
                                        <ChevronsUpDown
                                            v-else
                                            class="w-3 h-3 opacity-40"
                                        />
                                    </div>
                                </th>

                                <th class="px-4 py-3">Permission</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(role, index) in roles.data"
                                :key="role.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-electric-blue/10 dark:hover:bg-electric-blue/20 transition-all"
                            >
                                <td
                                    class="px-4 py-3 text-gray-900 dark:text-gray-100"
                                >
                                    {{ roles.from + index }}
                                </td>

                                <!-- ROLE NAME -->
                                <td
                                    class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium"
                                >
                                    <input
                                        v-if="editId === role.id"
                                        v-model="editName"
                                        class="px-3 py-2 rounded-lg border border-electric-blue/30 bg-cream dark:bg-[#1F1F1F] w-full focus:ring-1 focus:ring-electric-blue outline-none"
                                    />
                                    <span v-else>
                                        {{ role.name }}
                                    </span>
                                </td>

                                <!-- PERMISSION COUNT -->
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-electric-blue/10 text-electric-blue dark:bg-electric-blue/20"
                                    >
                                        {{ role.permissions?.length ?? 0 }}
                                        hak akses
                                    </span>
                                </td>

                                <!-- ACTIONS -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <button
                                            v-if="editId === role.id"
                                            @click="update(role)"
                                            class="text-green-600 hover:scale-110 transition"
                                        >
                                            <Save class="w-5 h-5" />
                                        </button>

                                        <button
                                            v-else
                                            @click="startEdit(role)"
                                            class="text-blue-600 hover:scale-110 transition"
                                        >
                                            <Edit class="w-5 h-5" />
                                        </button>

                                        <button
                                            @click="openPermission(role)"
                                            class="text-indigo-600 hover:scale-110 transition"
                                        >
                                            <ShieldCheck class="w-5 h-5" />
                                        </button>

                                        <button
                                            v-if="role.name !== 'super-admin'"
                                            @click="destroy(role)"
                                            class="text-red-600 hover:scale-110 transition"
                                        >
                                            <Trash2 class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARD -->
                <div
                    class="md:hidden space-y-4 p-4 bg-cream dark:bg-[#1f1f1f] rounded-b-lg"
                >
                    <div
                        v-for="(role, index) in roles.data"
                        :key="role.id"
                        class="group relative bg-white dark:bg-gradient-to-br dark:from-gray-900 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-xl transition-all duration-300"
                    >
                        <!-- TOP SECTION -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <!-- ICON CIRCLE -->
                                <div
                                    class="w-12 h-12 flex items-center justify-center rounded-xl bg-electric-blue/10 text-electric-blue dark:bg-electric-blue/20"
                                >
                                    <ShieldCheck class="w-6 h-6" />
                                </div>

                                <div>
                                    <div class="w-full">
                                        <input
                                            v-if="editId === role.id"
                                            v-model="editName"
                                            class="w-full px-3 py-2 rounded-xl border border-electric-blue/30 bg-cream dark:bg-[#1F1F1F] text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-electric-blue outline-none"
                                        />
                                        <h3
                                            v-else
                                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                                        >
                                            {{ role.name }}
                                        </h3>
                                    </div>

                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Role ID #{{ roles.from + index }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- INFO SECTION -->
                        <div
                            class="flex items-center justify-between mb-4 p-3 rounded-xl bg-electric-blue/5 dark:bg-electric-blue/10 border border-electric-blue/10"
                        >
                            <div
                                class="text-sm text-gray-600 dark:text-gray-300"
                            >
                                Total Hak Akses
                            </div>

                            <div
                                class="text-sm font-semibold text-electric-blue"
                            >
                                {{ role.permissions?.length ?? 0 }}
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="grid grid-cols-3 gap-2">
                            <!-- EDIT / SAVE -->
                            <button
                                v-if="editId === role.id"
                                @click="update(role)"
                                class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:scale-105 transition-all"
                            >
                                <Save class="w-4 h-4" />
                                <span class="text-[11px]">Save</span>
                            </button>

                            <button
                                v-else
                                @click="startEdit(role)"
                                class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:scale-105 transition-all"
                            >
                                <Edit class="w-4 h-4" />
                                <span class="text-[11px]">Edit</span>
                            </button>

                            <!-- PERMISSION -->
                            <button
                                @click="openPermission(role)"
                                class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:scale-105 transition-all"
                            >
                                <ShieldCheck class="w-4 h-4" />
                                <span class="text-[11px]">Permission</span>
                            </button>

                            <!-- DELETE -->
                            <button
                                v-if="role.name !== 'super-admin'"
                                @click="destroy(role)"
                                class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:scale-105 transition-all"
                            >
                                <Trash2 class="w-4 h-4" />
                                <span class="text-[11px]">Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGINATION -->
            <div
                v-if="roles.data.length"
                class="flex justify-between items-center mt-6"
            >
                <div class="flex items-center gap-4">
                    <span
                        class="hidden md:block text-sm text-gray-900 dark:text-gray-100"
                    >
                        Show
                    </span>

                    <select
                        v-model="filters.per_page"
                        @change="applyFilters"
                        class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-electric-blue focus:outline-none bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 cursor-pointer"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in roles.links"
                        :key="index"
                        @click="link.url && router.visit(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200 font-medium"
                        :class="[
                            link.active
                                ? 'bg-electric-blue text-white border-deep-blue shadow-lg scale-105'
                                : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-electric-blue/10 dark:hover:bg-electric-blue/20 hover:shadow-md',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="index === roles.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL PERMISSION -->
        <div
            v-if="showPermissionModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-60 flex items-center justify-center"
        >
            <div
                class="bg-cream dark:bg-deep-blue w-full max-w-3xl rounded-3xl shadow-2xl border border-electric-blue/30 flex flex-col max-h-[85vh]"
            >
                <!-- HEADER -->
                <div
                    class="px-6 py-4 rounded-t-3xl border-b border-electric-blue/20 flex items-center justify-between sticky top-0 bg-cream dark:bg-deep-blue z-10"
                >
                    <div>
                        <h2
                            class="text-lg font-bold text-deep-blue dark:text-cream"
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
                        class="text-sm px-4 py-2 rounded-xl border border-electric-blue text-electric-blue hover:bg-electric-blue hover:text-white transition"
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
                            class="flex items-center gap-3 px-3 py-2 rounded-xl border border-electric-blue/20 hover:bg-electric-blue/10 cursor-pointer transition"
                        >
                            <input
                                type="checkbox"
                                class="w-4 h-4 text-electric-blue border-electric-blue/40 rounded focus:ring-electric-blue"
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
                    class="px-6 py-4 rounded-b-3xl border-t border-electric-blue/20 flex justify-between items-center sticky bottom-0 bg-cream dark:bg-deep-blue"
                >
                    <span class="text-sm text-gray-500">
                        Dipilih: {{ selectedPermissions.length }} /
                        {{ permissions.length }}
                    </span>

                    <div class="flex gap-3">
                        <button
                            @click="showPermissionModal = false"
                            class="px-5 py-2 rounded-xl border border-electric-blue/40 hover:bg-electric-blue/10 transition"
                        >
                            Batal
                        </button>

                        <button
                            @click="savePermission"
                            class="px-5 py-2 rounded-xl bg-electric-blue text-white hover:bg-deep-blue transition shadow"
                        >
                            Simpan
                            <span class="hidden md:inline">Perubahan</span>
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
    ChevronsUpDown,
    Save,
} from "lucide-vue-next";

/* PROPS */
const props = defineProps({
    roles: Object,
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
