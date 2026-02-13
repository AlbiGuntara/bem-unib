<script setup>
import { onMounted } from "vue";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    kategoriList: Array,
    periodeList: Array,
});

const form = useForm({
    periode_id: "",
    tanggal: "",
    uraian: "",
    jenis: "Debet",
    kategori_id: "",
    nominal: "",
    bukti_transaksi: null,
});

function handleFile(e) {
    form.bukti_transaksi = e.target.files[0];
}

onMounted(() => {
    if (props.periodeList.length > 0) {
        const latestPeriode = props.periodeList[props.periodeList.length - 1];

        form.periode_id = latestPeriode.id;
    }
});
</script>

<template>
    <AppLayout>
        <Head title="Tambah Kas Umum" />

        <div
            class="p-6 lg:p-8 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 transition-all duration-500 backdrop-blur-sm bg-opacity-95"
        >
            <h1
                class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6"
            >
                Tambah Kas
            </h1>

            <form
                @submit.prevent="form.post(route('kas-umum.store'))"
                class="space-y-8"
            >
                <!-- PERIODE & TANGGAL -->
                <div
                    class="bg-linear-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4"
                    >
                        Informasi Umum
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- PERIODE -->
                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Periode <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.periode_id"
                                class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer outline-none"
                            >
                                <option value="">-- Pilih Periode --</option>
                                <option
                                    v-for="p in props.periodeList"
                                    :key="p.id"
                                    :value="p.id"
                                >
                                    {{ p.periode }} - {{ p.tahun_mulai }}/{{
                                        p.tahun_selesai
                                    }}
                                </option>
                            </select>
                        </div>

                        <!-- TANGGAL -->
                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                v-model="form.tanggal"
                                class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent shadow-sm hover:shadow-md transition-all duration-300 outline-none"
                            />
                        </div>
                    </div>
                </div>

                <!-- RINCIAN TRANSAKSI -->
                <div
                    class="bg-linear-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4"
                    >
                        Rincian Transaksi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- URAIAN -->
                        <div class="space-y-2 md:col-span-2">
                            <label
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Uraian <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                v-model="form.uraian"
                                placeholder="Keterangan transaksi"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent shadow-sm hover:shadow-md transition-all duration-300 outline-none"
                            />
                        </div>

                        <!-- JENIS -->
                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Jenis Transaksi
                            </label>
                            <select
                                v-model="form.jenis"
                                class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer outline-none"
                            >
                                <option value="Debet">Debet</option>
                                <option value="Kredit">Kredit</option>
                            </select>
                        </div>

                        <!-- KATEGORI -->
                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Kategori
                            </label>
                            <select
                                v-model="form.kategori_id"
                                class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer outline-none"
                            >
                                <option value="">-- Pilih Kategori --</option>
                                <option
                                    v-for="k in props.kategoriList"
                                    :key="k.id"
                                    :value="k.id"
                                >
                                    {{ k.kategori }}
                                </option>
                            </select>
                        </div>

                        <!-- NOMINAL -->
                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Nominal <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                v-model="form.nominal"
                                placeholder="100000"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent shadow-sm hover:shadow-md transition-all duration-300 outline-none"
                            />
                        </div>

                        <!-- BUKTI -->
                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Bukti Transaksi
                            </label>
                            <input
                                type="file"
                                @change="handleFile"
                                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent shadow-sm transition-all duration-300 cursor-pointer"
                            />
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="text-right">
                    <button
                        :disabled="form.processing"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 font-semibold"
                    >
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
