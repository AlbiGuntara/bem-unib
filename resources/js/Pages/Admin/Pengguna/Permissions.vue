<template>
    <AppLayout>
        <Head title="Permission Management" />

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
                        placeholder="Cari permission..."
                        class="pl-10 pr-4 py-2 w-full rounded-xl bg-cream dark:bg-[#1F1F1F] border border-electric-blue/30 text-deep-blue dark:text-cream placeholder-deep-blue/40 dark:placeholder-cream/40 focus:ring-2 focus:ring-electric-blue focus:outline-none transition-all duration-300 shadow-sm hover:shadow-md"
                    />
                </div>

                <!-- ADD PERMISSION -->
                <form @submit.prevent="submit" class="flex w-full md:w-auto">
                    <input
                        v-model="form.name"
                        placeholder="Nama permission"
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
                <div class="overflow-x-hidden">
                    <table
                        class="w-full table-fixed text-xs md:text-sm text-left"
                    >
                        <!-- HEAD -->
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-deep-blue to-electric-blue text-cream uppercase tracking-wide"
                            >
                                <th class="w-12 px-2 md:px-4 py-3 text-center">
                                    No
                                </th>

                                <th
                                    class="px-2 md:px-4 py-3 cursor-pointer select-none"
                                    @click="sort('name')"
                                >
                                    <div class="flex items-center gap-1">
                                        <span>Permission</span>
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

                                <th
                                    class="w-20 md:w-28 px-2 md:px-4 py-3 text-center"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <!-- BODY -->
                        <tbody>
                            <tr
                                v-for="(permission, index) in permissions.data"
                                :key="permission.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-electric-blue/10 dark:hover:bg-electric-blue/20 transition-all"
                            >
                                <!-- NO -->
                                <td
                                    class="px-2 md:px-4 py-3 text-center text-gray-900 dark:text-gray-100"
                                >
                                    {{ permissions.from + index }}
                                </td>

                                <!-- NAME -->
                                <td
                                    class="px-2 md:px-4 py-3 text-gray-900 dark:text-gray-100 font-medium break-words"
                                >
                                    <input
                                        v-if="editingId === permission.id"
                                        v-model="editingName"
                                        class="px-2 py-1 rounded-lg border border-electric-blue/30 bg-cream dark:bg-[#1F1F1F] w-full text-xs md:text-sm focus:ring-1 focus:ring-electric-blue outline-none"
                                    />
                                    <span v-else>
                                        {{ permission.name }}
                                    </span>
                                </td>

                                <!-- ACTION -->
                                <td class="px-2 md:px-4 py-3">
                                    <div
                                        class="flex items-center justify-center gap-2 md:gap-3"
                                    >
                                        <button
                                            v-if="editingId === permission.id"
                                            @click="update(permission)"
                                            class="text-green-600 hover:scale-110 transition"
                                        >
                                            <Save
                                                class="w-4 h-4 md:w-5 md:h-5"
                                            />
                                        </button>

                                        <button
                                            v-else
                                            @click="startEdit(permission)"
                                            class="text-blue-600 hover:scale-110 transition"
                                        >
                                            <Edit
                                                class="w-4 h-4 md:w-5 md:h-5"
                                            />
                                        </button>

                                        <button
                                            @click="destroy(permission)"
                                            class="text-red-600 hover:scale-110 transition"
                                        >
                                            <Trash2
                                                class="w-4 h-4 md:w-5 md:h-5"
                                            />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="permissions.data.length === 0">
                                <td
                                    colspan="3"
                                    class="py-8 text-center text-gray-500 dark:text-gray-400"
                                >
                                    Tidak ada data permission
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PAGINATION -->
            <div
                v-if="permissions.data.length"
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
                        v-for="(link, index) in permissions.links"
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
    ChevronsUpDown,
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
