<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import {
    Mail,
    MailOpen,
    ReplyAll,
    Trash2,
    Eye,
    Search,
    Inbox,
    MessageSquare,
    AlertTriangle,
    Lightbulb,
    ThumbsUp,
    ChevronLeft,
    ChevronRight,
} from "lucide-vue-next";
import Swal from "sweetalert2";

const props = defineProps({
    messages: Object,
    filters: Object,
    stats: Object,
    categoryStats: Object,
});

const search = ref(props.filters.search || "");
const selectedIds = ref([]);
const selectAll = ref(false);
const perPage = ref(props.filters.perPage || 10);
const category = ref(props.filters.category || "");
const status = ref(props.filters.status || "");
const dateFrom = ref(props.filters.date_from || "");
const dateTo = ref(props.filters.date_to || "");

const isDark = computed(() =>
    document.documentElement.classList.contains("dark"),
);

function getSwalTheme() {
    return {
        background: isDark.value ? "#0f172a" : "#ffffff",
        color: isDark.value ? "#f1f5f9" : "#111827",
        confirmButtonColor: "#2563eb",
    };
}

const categoryOptions = [
    {
        value: "saran",
        label: "Saran",
        icon: Lightbulb,
        color: "text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900/30",
    },
    {
        value: "kritik",
        label: "Kritik",
        icon: AlertTriangle,
        color: "text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900/30",
    },
    {
        value: "aspirasi",
        label: "Aspirasi",
        icon: ThumbsUp,
        color: "text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900/30",
    },
    {
        value: "aduan",
        label: "Aduan",
        icon: MessageSquare,
        color: "text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900/30",
    },
];

const statusBadge = (s) => {
    const map = {
        unread: "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
        read: "bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400",
        replied:
            "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
    };
    return map[s] || "";
};

function applyFilters() {
    router.get(
        route("messages.index"),
        {
            search: search.value,
            category: category.value,
            status: status.value,
            date_from: dateFrom.value,
            date_to: dateTo.value,
            perPage: perPage.value,
        },
        { preserveState: true, replace: true },
    );
}

function toggleSelectAll() {
    if (selectAll.value) {
        selectedIds.value = props.messages.data.map((m) => m.id);
    } else {
        selectedIds.value = [];
    }
}

function toggleSelect(id) {
    const idx = selectedIds.value.indexOf(id);
    if (idx === -1) {
        selectedIds.value.push(id);
    } else {
        selectedIds.value.splice(idx, 1);
    }
}

function bulkAction(action) {
    if (!selectedIds.value.length) {
        Swal.fire({
            ...getSwalTheme(),
            icon: "warning",
            title: "Pilih pesan",
            text: "Pilih minimal satu pesan terlebih dahulu.",
        });
        return;
    }

    const labels = {
        read: "tandai dibaca",
        deleted: "hapus",
    };
    Swal.fire({
        ...getSwalTheme(),
        title: `Yakin ingin ${labels[action]} ${selectedIds.value.length} pesan?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, lanjutkan!",
        cancelButtonText: "Batal",
        confirmButtonColor: action === "deleted" ? "#dc2626" : "#2563eb",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(
                route("messages.bulk-action"),
                {
                    ids: selectedIds.value,
                    action: action,
                },
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        selectedIds.value = [];
                        selectAll.value = false;
                    },
                },
            );
        }
    });
}

function deleteMessage(item) {
    Swal.fire({
        ...getSwalTheme(),
        title: "Hapus pesan ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc2626",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("messages.destroy", item.id), {
                onSuccess: () =>
                    Swal.fire({
                        ...getSwalTheme(),
                        title: "Terhapus!",
                        text: "Pesan berhasil dihapus.",
                        icon: "success",
                    }),
            });
        }
    });
}

function updateStatus(item, newStatus) {
    router.patch(
        route("messages.update-status", item.id),
        { status: newStatus },
        { preserveScroll: true },
    );
}

const statCards = computed(() => [
    {
        label: "Total",
        value: props.stats.total,
        icon: Mail,
        color: "text-blue-600 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400",
    },
    {
        label: "Belum Dibaca",
        value: props.stats.unread,
        icon: MailOpen,
        color: "text-indigo-600 bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400",
    },
    {
        label: "Dibaca",
        value: props.stats.read,
        icon: Eye,
        color: "text-gray-600 bg-gray-100 dark:bg-gray-800 dark:text-gray-400",
    },
    {
        label: "Dibalas",
        value: props.stats.replied,
        icon: ReplyAll,
        color: "text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-400",
    },
]);

function formatDate(date) {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function goTo(url) {
    if (!url) return;
    router.visit(url, { preserveState: true, replace: true });
}
</script>

<template>
    <AppLayout>
        <Head title="Kotak Saran & Aspirasi" />

        <div class="space-y-4">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                <div
                    v-for="card in statCards"
                    :key="card.label"
                    class="group relative overflow-hidden rounded-lg border border-slate-200/70 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-700 dark:bg-slate-900"
                >
                    <!-- Decorative -->
                    <div
                        class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-slate-100 opacity-50 blur-2xl dark:bg-slate-700"
                    />

                    <div class="relative flex items-center justify-between">
                        <div>
                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                {{ card.label }}
                            </p>

                            <h3
                                class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-white"
                            >
                                {{ card.value }}
                            </h3>
                        </div>

                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-lg shadow-sm transition-transform duration-300 group-hover:scale-110"
                            :class="card.color"
                        >
                            <component :is="card.icon" class="h-7 w-7" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                <div
                    v-for="cat in categoryOptions"
                    :key="cat.value"
                    class="group relative overflow-hidden rounded-lg border border-slate-200/70 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-700 dark:bg-slate-900"
                >
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg transition-transform duration-300 group-hover:scale-110"
                            :class="cat.color"
                        >
                            <component :is="cat.icon" class="h-7 w-7" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <p
                                class="truncate text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                {{ cat.label }}
                            </p>

                            <h3
                                class="mt-1 text-3xl font-bold text-slate-900 dark:text-white"
                            >
                                {{ props.categoryStats[cat.value] || 0 }}
                            </h3>
                        </div>
                    </div>

                    <!-- Bottom Accent -->
                    <div
                        class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                    />
                </div>
            </div>

            <!-- Messages Table -->
            <div
                class="space-y-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 transition-all duration-500"
            >
                <!-- Header / Search -->
                <div
                    class="flex flex-col gap-3 md:flex-row md:items-center md:flex-wrap"
                >
                    <div class="relative w-full md:w-80">
                        <Search
                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-500"
                        />
                        <input
                            v-model="search"
                            @input="applyFilters"
                            type="text"
                            placeholder="Cari pesan..."
                            class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-2.5 text-sm text-slate-700 transition focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-500 dark:focus:ring-blue-900/30"
                        />
                    </div>

                    <select
                        v-model="category"
                        @change="applyFilters"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                    >
                        <option value="">Semua Kategori</option>
                        <option
                            v-for="cat in categoryOptions"
                            :key="cat.value"
                            :value="cat.value"
                        >
                            {{ cat.label }}
                        </option>
                    </select>

                    <select
                        v-model="status"
                        @change="applyFilters"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                    >
                        <option value="">Semua Status</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="read">Dibaca</option>
                        <option value="replied">Dibalas</option>
                    </select>

                    <input
                        v-model="dateFrom"
                        @change="applyFilters"
                        type="date"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                    />

                    <input
                        v-model="dateTo"
                        @change="applyFilters"
                        type="date"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                    />
                </div>

                <!-- Bulk Actions -->
                <div v-if="selectedIds.length" class="flex items-center gap-3">
                    <span class="text-sm text-slate-600 dark:text-slate-400"
                        >{{ selectedIds.length }} dipilih</span
                    >
                    <button
                        @click="bulkAction('read')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400"
                    >
                        <Eye class="w-4 h-4" /> Tandai Dibaca
                    </button>
                    <button
                        @click="bulkAction('deleted')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400"
                    >
                        <Trash2 class="w-4 h-4" /> Hapus
                    </button>
                </div>

                <!-- Table -->
                <div
                    class="overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700"
                >
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr
                                    class="bg-gradient-to-r from-blue-800 to-blue-700 text-left text-xs uppercase tracking-wider text-white"
                                >
                                    <th class="px-4 py-3 w-10">
                                        <input
                                            type="checkbox"
                                            v-model="selectAll"
                                            @change="toggleSelectAll"
                                            class="rounded border-white/30 bg-white/20"
                                        />
                                    </th>
                                    <th class="px-4 py-3 font-semibold">No</th>
                                    <th class="px-4 py-3 font-semibold">
                                        Pengirim
                                    </th>
                                    <th class="px-4 py-3 font-semibold">
                                        Kategori
                                    </th>
                                    <th class="px-4 py-3 font-semibold">
                                        Subjek
                                    </th>
                                    <th class="px-4 py-3 font-semibold">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 font-semibold">
                                        Tanggal
                                    </th>
                                    <th class="px-4 py-3 font-semibold">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-slate-200 dark:divide-slate-700"
                            >
                                <tr
                                    v-for="(item, index) in messages.data"
                                    :key="item.id"
                                    class="transition hover:bg-blue-50 dark:hover:bg-slate-800"
                                    :class="{
                                        'bg-blue-50/50 dark:bg-blue-900/10':
                                            item.status === 'unread',
                                    }"
                                >
                                    <td class="px-4 py-3">
                                        <input
                                            type="checkbox"
                                            :checked="
                                                selectedIds.includes(item.id)
                                            "
                                            @change="toggleSelect(item.id)"
                                            class="rounded border-slate-300 dark:border-slate-600"
                                        />
                                    </td>
                                    <td
                                        class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300"
                                    >
                                        {{ (messages.from || 0) + index }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <p
                                                class="text-sm font-medium text-slate-800 dark:text-white"
                                            >
                                                {{ item.name }}
                                            </p>
                                            <p
                                                class="text-xs text-slate-500 dark:text-slate-400"
                                            >
                                                {{ item.email }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium"
                                            :class="
                                                categoryOptions.find(
                                                    (c) =>
                                                        c.value ===
                                                        item.category,
                                                )?.color
                                            "
                                        >
                                            {{ item.category }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <Link
                                            :href="
                                                route('messages.show', item.id)
                                            "
                                            class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                        >
                                            {{ item.subject }}
                                        </Link>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                            :class="statusBadge(item.status)"
                                        >
                                            {{ item.status }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300"
                                    >
                                        {{ formatDate(item.created_at) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <Link
                                                :href="
                                                    route(
                                                        'messages.show',
                                                        item.id,
                                                    )
                                                "
                                                class="p-1.5 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400"
                                            >
                                                <Eye class="w-4 h-4" />
                                            </Link>
                                            <button
                                                @click="deleteMessage(item)"
                                                class="p-1.5 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400"
                                            >
                                                <Trash2 class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="messages.data.length"
                    class="flex flex-col gap-3 md:flex-row items-center md:justify-between"
                >
                    <div class="flex items-center gap-2">
                        <span
                            class="text-sm text-slate-500 dark:text-slate-400"
                        >
                            Show
                        </span>
                        <select
                            v-model="perPage"
                            @change="applyFilters"
                            class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-600 focus:outline-none dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div class="flex flex-wrap gap-1">
                        <button
                            v-for="(link, index) in messages.links"
                            :key="index"
                            @click="goTo(link.url)"
                            :disabled="!link.url"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border text-sm transition"
                            :class="[
                                link.active
                                    ? 'border-blue-700 bg-blue-700 text-white'
                                    : 'border-slate-300 bg-white text-slate-700 hover:bg-blue-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700',
                                !link.url
                                    ? 'cursor-not-allowed opacity-40'
                                    : '',
                            ]"
                        >
                            <ChevronLeft v-if="index === 0" class="h-4 w-4" />
                            <ChevronRight
                                v-else-if="index === messages.links.length - 1"
                                class="h-4 w-4"
                            />
                            <span v-else v-html="link.label" />
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!messages.data.length" class="py-12 text-center">
                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30"
                    >
                        <Inbox
                            class="h-8 w-8 text-blue-600 dark:text-blue-400"
                        />
                    </div>
                    <h3
                        class="font-semibold text-slate-800 dark:text-slate-100"
                    >
                        Tidak ada data
                    </h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Belum ada data yang dapat ditampilkan.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
