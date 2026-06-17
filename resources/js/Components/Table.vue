<script setup>
import { router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import {
    ArrowUp,
    ArrowDown,
    ChevronsUpDown,
    ChevronLeft,
    ChevronRight,
    Search,
    Inbox
} from "lucide-vue-next";

const props = defineProps({
    data: Object,
    columns: Array,
    routeName: String,

    filters: {
        type: Object,
        default: () => ({
            search: "",
            sort: null,
            order: null,
            perPage: 10,
        }),
    },
});

const search = ref(props.filters.search || "");
const perPage = ref(props.filters.perPage || 10);

function sortBy(column) {
    let sort = props.filters.sort;
    let order = props.filters.order;

    if (sort !== column) {
        sort = column;
        order = "asc";
    } else if (order === "asc") {
        order = "desc";
    } else {
        sort = null;
        order = null;
    }

    router.get(
        route(props.routeName),
        {
            search: search.value,
            sort,
            order,
            perPage: perPage.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}

function updateSearch() {
    router.get(
        route(props.routeName),
        {
            search: search.value,
            perPage: perPage.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function updatePerPage() {
    updateSearch();
}

function goTo(url) {
    if (!url) return;

    router.visit(url, {
        preserveState: true,
        replace: true,
    });
}
</script>

<template>
    <div
        class="space-y-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 transition-all duration-500">
        <!-- Header -->
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="relative w-full md:w-80">
                <!-- Icon -->
                <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-500" />

                <!-- Input -->
                <input v-model="search" @input="updateSearch" type="text" placeholder="Cari data..."
                    class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-2.5 text-sm text-slate-700 transition focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-500 dark:focus:ring-blue-900/30" />
            </div>

            <slot name="header-action" />
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700">
            <!-- Desktop -->
            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full">
                    <thead>
                        <tr
                            class="bg-gradient-to-r from-blue-800 to-blue-700 text-left text-xs uppercase tracking-wider text-white">
                            <th class="px-4 py-3 font-semibold whitespace-nowrap">
                                No
                            </th>

                            <th v-for="col in columns" :key="col.key" @click="
                                col.sortable !== false &&
                                sortBy(col.key)
                                " :class="[
                                    'px-4 py-3 font-semibold whitespace-nowrap transition',
                                    col.sortable !== false
                                        ? 'cursor-pointer'
                                        : '',
                                ]">
                                <div class="flex items-center gap-1">
                                    <span>{{ col.label }}</span>

                                    <template v-if="col.sortable !== false">
                                        <ArrowUp v-if="
                                            filters.sort === col.key &&
                                            filters.order ===
                                            'asc'
                                        " class="h-3 w-3" />

                                        <ArrowDown v-else-if="
                                            filters.sort === col.key &&
                                            filters.order ===
                                            'desc'
                                        " class="h-3 w-3" />

                                        <ChevronsUpDown v-else class="h-3 w-3 opacity-60" />
                                    </template>
                                </div>
                            </th>

                            <th class="px-4 py-3 font-semibold whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="transition divide-y divide-slate-200 dark:divide-slate-700">
                        <tr v-for="(item, index) in data.data" :key="item.id"
                            class="transition hover:bg-blue-50 dark:hover:bg-slate-800">
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                                {{ (data.from || 0) + index }}
                            </td>

                            <td v-for="col in columns" :key="col.key"
                                class="px-4 py-3 text-sm text-slate-700 dark:text-slate-200">
                                <slot :name="col.key" :item="item">
                                    {{ item[col.key] }}
                                </slot>
                            </td>

                            <td class="px-4 py-3">
                                <slot name="actions" :item="item" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile -->
            <div class="space-y-3 bg-slate-50 p-4 dark:bg-slate-900 md:hidden">
                <div v-for="item in data.data" :key="item.id"
                    class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div v-for="col in columns" :key="col.key"
                        class="flex items-center justify-between border-b border-slate-100 py-2 last:border-none dark:border-slate-700">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            {{ col.label }}
                        </span>

                        <div class="text-sm text-slate-700 dark:text-slate-200">
                            <slot :name="col.key" :item="item">
                                {{ item[col.key] }}
                            </slot>
                        </div>
                    </div>

                    <div class="pt-3">
                        <slot name="actions" :item="item" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="data.data.length" class="flex flex-col gap-3 md:flex-row items-center md:justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500 dark:text-slate-400">
                    Show
                </span>

                <select v-model="perPage" @change="updatePerPage"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-600 focus:outline-none dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <div class="flex flex-wrap gap-1">
                <button v-for="(link, index) in data.links" :key="index" @click="goTo(link.url)" :disabled="!link.url"
                    class="flex h-9 w-9 items-center justify-center rounded-lg border text-sm transition" :class="[
                        link.active
                            ? 'border-blue-700 bg-blue-700 text-white'
                            : 'border-slate-300 bg-white text-slate-700 hover:bg-blue-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700',
                        !link.url
                            ? 'cursor-not-allowed opacity-40'
                            : '',
                    ]">
                    <ChevronLeft v-if="index === 0" class="h-4 w-4" />

                    <ChevronRight v-else-if="
                        index ===
                        data.links.length - 1
                    " class="h-4 w-4" />

                    <span v-else v-html="link.label" />
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="!data.data.length" class="py-12 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                <Inbox class="h-8 w-8 text-blue-600 dark:text-blue-400" />
            </div>

            <h3 class="font-semibold text-slate-800 dark:text-slate-100">
                Tidak ada data
            </h3>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Belum ada data yang dapat ditampilkan.
            </p>
        </div>
    </div>
</template>