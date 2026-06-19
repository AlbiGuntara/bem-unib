<script setup>
import {
    User,
    MapPin,
    CalendarDays,
    Clock3,
    Globe,
    Users,
    Wallet,
    Target,
    CheckCircle2,
    TrendingUp,
} from "lucide-vue-next";

const props = defineProps({
    show: Boolean,
    proker: Object,
});

const emit = defineEmits(["close"]);

function formatRupiah(value) {
    if (!value) return "Rp 0";

    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(value);
}

function participantProgress() {
    if (
        !props.proker?.participants ||
        !props.proker?.realization_participants
    )
        return 0;

    return Math.min(
        100,
        Math.round(
            (props.proker.realization_participants /
                props.proker.participants) *
            100
        )
    );
}

function budgetProgress() {
    if (
        !props.proker?.budget ||
        !props.proker?.realization_budget
    )
        return 0;

    return Math.min(
        100,
        Math.round(
            (props.proker.realization_budget /
                props.proker.budget) *
            100
        )
    );
}
</script>

<template>
    <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100"
        leave-to-class="opacity-0">
        <div v-if="show && proker"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div
                class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <!-- HEADER -->
                <div
                    class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-white">
                        Detail Program Kerja
                    </h2>

                    <p class="mt-1 text-sm text-blue-100">
                        Informasi lengkap program kerja BEM.
                    </p>
                </div>

                <!-- BODY -->
                <div class="modal-scroll flex-1 overflow-y-auto">
                    <div class="p-6 space-y-5">

                        <!-- HERO -->
                        <div
                            class="relative overflow-hidden rounded-lg bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 p-6 text-white shadow-lg">
                            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10"></div>

                            <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-white/10"></div>

                            <div class="relative">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-medium backdrop-blur">
                                        {{ proker.department?.name ?? "Tanpa Departemen" }}
                                    </span>

                                    <span class="rounded-full px-3 py-1 text-xs font-medium" :class="proker.status === 'selesai'
                                        ? 'bg-emerald-500/20 text-emerald-100'
                                        : proker.status === 'berjalan'
                                            ? 'bg-amber-500/20 text-amber-100'
                                            : proker.status === 'batal'
                                                ? 'bg-red-500/20 text-red-100'
                                                : 'bg-slate-500/20 text-slate-100'
                                        ">
                                        {{ proker.status }}
                                    </span>
                                </div>

                                <h3 class="mt-4 text-2xl font-bold">
                                    {{ proker.title }}
                                </h3>

                                <p class="mt-3 max-w-3xl text-sm text-blue-100">
                                    {{ proker.description || "Tidak ada deskripsi program kerja." }}
                                </p>
                            </div>
                        </div>

                        <!-- INFO GRID -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Ketua Panitia -->
                            <div
                                class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                <div class="flex items-center gap-4">

                                    <img :src="proker.ketua_panitia?.photo
                                        ? `/storage/${proker.ketua_panitia.photo}`
                                        : '/images/avatar.png'
                                        " class="h-16 w-16 rounded-lg object-cover border-2 border-blue-100" loading="lazy" />

                                    <div>
                                        <p class="text-xs uppercase tracking-wider text-slate-500">
                                            Ketua Panitia
                                        </p>

                                        <h4 class="mt-1 text-lg font-semibold text-slate-800 dark:text-white">
                                            {{ proker.ketua_panitia?.name ?? "-" }}
                                        </h4>

                                        <p class="text-sm text-slate-500">
                                            {{ proker.department?.name }}
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <!-- Lokasi -->
                            <div
                                class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                        <MapPin class="h-5 w-5" />
                                    </div>

                                    <div>
                                        <p class="text-xs uppercase tracking-wider text-slate-500">
                                            Lokasi Kegiatan
                                        </p>

                                        <h4 class="mt-1 text-lg font-semibold text-slate-800 dark:text-white">
                                            {{ proker.location || "-" }}
                                        </h4>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- DETAIL -->
                        <div
                            class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <h4 class="mb-6 text-sm font-semibold uppercase tracking-wider text-slate-500">
                                Informasi Program
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div class="flex gap-3">
                                    <CalendarDays class="w-5 h-5 text-blue-600" />
                                    <div>
                                        <p class="text-xs text-slate-500">
                                            Tanggal Mulai
                                        </p>
                                        <p class="font-medium">
                                            {{ proker.start_date ?? "-" }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <CalendarDays class="w-5 h-5 text-indigo-600" />
                                    <div>
                                        <p class="text-xs text-slate-500">
                                            Tanggal Selesai
                                        </p>
                                        <p class="font-medium">
                                            {{ proker.end_date ?? "-" }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <Clock3 class="w-5 h-5 text-amber-600" />
                                    <div>
                                        <p class="text-xs text-slate-500">
                                            Waktu
                                        </p>
                                        <p class="font-medium">
                                            {{ proker.time ?? "-" }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <Globe class="w-5 h-5 text-emerald-600" />
                                    <div>
                                        <p class="text-xs text-slate-500">
                                            Publikasi
                                        </p>

                                        <span class="inline-flex mt-1 rounded-full px-3 py-1 text-xs font-medium"
                                            :class="proker.is_public
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700'
                                                ">
                                            {{ proker.is_public ? "Publik" : "Privat" }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- STATISTICS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 p-5 text-white">
                                <div class="flex items-center justify-between">
                                    <Users class="w-8 h-8 opacity-80" />
                                    <Target class="w-6 h-6 opacity-80" />
                                </div>

                                <p class="mt-4 text-blue-100">
                                    Target Peserta
                                </p>

                                <h3 class="text-3xl font-bold">
                                    {{ proker.participants ?? 0 }}
                                </h3>
                            </div>

                            <div class="rounded-lg bg-gradient-to-br from-emerald-500 to-green-700 p-5 text-white">
                                <div class="flex items-center justify-between">
                                    <Wallet class="w-8 h-8 opacity-80" />
                                    <TrendingUp class="w-6 h-6 opacity-80" />
                                </div>

                                <p class="mt-4 text-green-100">
                                    Anggaran Program
                                </p>

                                <h3 class="text-2xl font-bold">
                                    {{ formatRupiah(proker.budget) }}
                                </h3>
                            </div>

                        </div>

                        <div
                            class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <h4 class="mb-5 text-sm font-semibold uppercase tracking-wider text-slate-500">
                                Progress Pelaksanaan
                            </h4>

                            <!-- Peserta -->
                            <div class="mb-5">
                                <div class="flex justify-between text-sm">
                                    <span>Realisasi Peserta</span>
                                    <span>{{ participantProgress() }}%</span>
                                </div>

                                <div class="mt-2 h-3 rounded-full bg-slate-200">
                                    <div class="h-3 rounded-full bg-blue-600"
                                        :style="{ width: participantProgress() + '%' }"></div>
                                </div>
                            </div>

                            <!-- Budget -->
                            <div>
                                <div class="flex justify-between text-sm">
                                    <span>Realisasi Anggaran</span>
                                    <span>{{ budgetProgress() }}%</span>
                                </div>

                                <div class="mt-2 h-3 rounded-full bg-slate-200">
                                    <div class="h-3 rounded-full bg-emerald-600"
                                        :style="{ width: budgetProgress() + '%' }"></div>
                                </div>
                            </div>
                        </div>

                        <!-- REALISASI -->
                        <div
                            class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <h4 class="mb-5 text-sm font-semibold uppercase tracking-wider text-slate-500">
                                Realisasi Program
                            </h4>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <div class="rounded-lg bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <p class="text-xs text-slate-500">
                                        Realisasi Peserta
                                    </p>

                                    <h3 class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">
                                        {{ proker.realization_participants ?? "-" }}
                                    </h3>
                                </div>

                                <div class="rounded-lg bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <p class="text-xs text-slate-500">
                                        Realisasi Anggaran
                                    </p>

                                    <h3 class="mt-2 text-xl font-bold text-slate-800 dark:text-white">
                                        {{ formatRupiah(proker.realization_budget) }}
                                    </h3>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div
                        class="sticky bottom-0 flex justify-end border-t border-slate-200 bg-white px-6 py-4 dark:border-slate-700 dark:bg-slate-900">
                        <button type="button" @click="emit('close')"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </Transition>
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