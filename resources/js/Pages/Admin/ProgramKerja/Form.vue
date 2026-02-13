<template>
    <Head :title="isEdit ? 'Edit Program Kerja' : 'Tambah Program Kerja'" />

    <form @submit.prevent="submit" class="space-y-4">
        <!-- IDENTITAS PROGRAM -->
        <div
            class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
        >
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                >
                    <ClipboardList class="w-3 h-3 text-white" />
                </div>
                <h3 class="text-lg font-semibold">Identitas Program Kerja</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Nama Kegiatan
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.nama_kegiatan"
                        required
                        placeholder="Contoh: Bakti Sosial"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none transition-all"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Tujuan
                    </label>
                    <textarea
                        v-model="form.tujuan"
                        rows="3"
                        placeholder="Tujuan kegiatan"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none transition-all"
                    />
                </div>
            </div>
        </div>

        <!-- WAKTU -->
        <div
            class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
        >
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                >
                    <Calendar class="w-3 h-3 text-white" />
                </div>
                <h3 class="text-lg font-semibold">Waktu & Peserta</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Tanggal Mulai
                    </label>
                    <input
                        type="date"
                        v-model="form.tanggal_mulai"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Tanggal Selesai
                    </label>
                    <input
                        type="date"
                        v-model="form.tanggal_selesai"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Penanggung Jawab
                    </label>

                    <select
                        v-model="form.jabatan_id"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:outline-none transition"
                    >
                        <option value="">— Pilih Jabatan —</option>

                        <option
                            v-for="jabatan in jabatans"
                            :key="jabatan.id"
                            :value="jabatan.id"
                        >
                            {{ jabatan.departemen
                            }}{{
                                jabatan.IKSASS
                                    ? " (" + jabatan.IKSASS + ")"
                                    : ""
                            }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Peserta
                    </label>
                    <input
                        v-model="form.peserta"
                        placeholder="Contoh: Seluruh Santri"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none"
                    />
                </div>
            </div>
        </div>

        <!-- ANGGARAN -->
        <div
            class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
        >
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                >
                    <Wallet class="w-3 h-3 text-white" />
                </div>
                <h3 class="text-lg font-semibold">Anggaran</h3>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">
                    Total Anggaran
                </label>
                <input
                    type="number"
                    v-model="form.anggaran"
                    placeholder="Contoh: 5000000"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none"
                />
                <p class="text-xs text-gray-500 mt-1">
                    Isi tanpa titik atau koma
                </p>
            </div>
        </div>

        <!-- ACTION -->
        <div
            class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700"
        >
            <Link
                :href="route('program-kerja.index')"
                class="flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all"
            >
                <ArrowLeft class="w-4 h-4" />
                Kembali
            </Link>

            <button
                type="submit"
                :disabled="form.processing"
                class="flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-semibold shadow-lg transition-all disabled:opacity-70"
            >
                <Save class="w-4 h-4" />
                <span>
                    {{
                        form.processing
                            ? "Menyimpan..."
                            : "Simpan Program Kerja"
                    }}
                </span>
            </button>
        </div>
    </form>
</template>

<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import {
    ClipboardList,
    Calendar,
    Wallet,
    FileText,
    ArrowLeft,
    Save,
} from "lucide-vue-next";

const page = usePage();

const jabatans = page.props.jabatans ?? [];
const programKerja = page.props.programKerja ?? null;

const isEdit = !!programKerja;

const form = useForm({
    nama_kegiatan: programKerja?.nama_kegiatan ?? "",
    tujuan: programKerja?.tujuan ?? "",
    tanggal_mulai: programKerja?.tanggal_mulai ?? "",
    tanggal_selesai: programKerja?.tanggal_selesai ?? "",
    jabatan_id: programKerja?.jabatan_id ? Number(programKerja.jabatan_id) : "",
    peserta: programKerja?.peserta ?? "",
    anggaran: programKerja?.anggaran ?? "",
    deskripsi: programKerja?.deskripsi ?? "",
});

const submit = () => {
    isEdit
        ? form.put(route("program-kerja.update", programKerja.id))
        : form.post(route("program-kerja.store"));
};
</script>
