<template>
    <AppLayout>
        <Head title="Permission Management" />

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
                        placeholder="Cari permission..."
                        class="pl-10 pr-4 py-2 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-green-600 outline-none transition-all"
                    />
                </div>

                <!-- ADD PERMISSION -->
                <form @submit.prevent="submit" class="flex w-full md:w-auto">
                    <input
                        v-model="form.name"
                        placeholder="Nama permission"
                        class="flex-1 pl-5 pr-4 py-2 rounded-l-xl border border-gray-200 dark:border-gray-700 focus:border-green-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none"
                    />
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
                        <tr class="text-left px-10">
                            <th class="px-4 py-3 text-center">No</th>
                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('name')"
                            >
                                Permission
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
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(permission, index) in permissions.data"
                            :key="permission.id"
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800"
                        >
                            <td class="px-4 py-3 text-center">
                                {{ permissions.from + index }}
                            </td>

                            <td class="px-4 py-3">
                                <input
                                    v-if="editingId === permission.id"
                                    v-model="editingName"
                                    class="px-2 py-1 rounded border w-full"
                                />
                                <span v-else class="font-medium">
                                    {{ permission.name }}
                                </span>
                            </td>

                            <!-- ACTION ICON -->
                            <td class="px-4 py-3 flex gap-3 justify-center">
                                <button
                                    v-if="editingId === permission.id"
                                    @click="update(permission)"
                                    class="text-green-600 hover:scale-110"
                                    title="Simpan"
                                >
                                    <Save class="w-5 h-5" />
                                </button>

                                <button
                                    v-else
                                    @click="startEdit(permission)"
                                    class="text-blue-600 hover:scale-110"
                                    title="Edit"
                                >
                                    <Edit class="w-5 h-5" />
                                </button>

                                <button
                                    @click="destroy(permission)"
                                    class="text-red-600 hover:scale-110"
                                    title="Hapus"
                                >
                                    <Trash2 class="w-5 h-5" />
                                </button>
                            </td>
                        </tr>

                        <tr v-if="permissions.data.length === 0">
                            <td
                                colspan="3"
                                class="py-6 text-center text-gray-500"
                            >
                                Tidak ada data permission
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div
                v-if="permissions.data.length"
                class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
            >
                <!-- SHOW -->
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">
                        Show
                    </label>
                    <select
                        v-model="filters.per_page"
                        @change="applyFilters"
                        class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in permissions.links"
                        :key="index"
                        @click="link.url && router.visit(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all"
                        :class="[
                            link.active
                                ? 'bg-green-600 text-white border-green-700 shadow-lg scale-105'
                                : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 hover:bg-gray-100',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="index === permissions.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    Plus,
    Edit,
    Trash2,
    Save,
    Search,
    ArrowUp,
    ArrowDown,
    ChevronLeft,
    ChevronRight,
} from "lucide-vue-next";

const props = defineProps({
    permissions: Object,
    filters: Object,
});

const page = usePage();
const flash = page.props.flash || {};

const filters = ref({
    search: props.filters?.search || "",
    per_page: props.filters?.per_page || 10,
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
});

function applyFilters() {
    router.get(route("permissions.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

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
