<template>
    <AppLayout>
        <Head :title="`Tambah Data ${title}`" />

        <div
            class="p-6 lg:p-8 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 transition-all duration-500 backdrop-blur-sm bg-opacity-95"
        >
            <form @submit.prevent="submit" class="space-y-8">
                <!-- Informasi Transaksi Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm space-y-6"
                >
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-6 h-6 bg-gradient-to-r from-green-600 to-green-600 rounded-lg flex items-center justify-center"
                        >
                            <CreditCard class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Informasi Transaksi
                        </h3>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                    >
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Jenis <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label
                                    class="flex items-center gap-2 cursor-pointer"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.jenis"
                                        value="Hutang"
                                        class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span
                                        class="text-gray-900 dark:text-gray-100"
                                        >Hutang</span
                                    >
                                </label>
                                <label
                                    class="flex items-center gap-2 cursor-pointer"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.jenis"
                                        value="Piutang"
                                        class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span
                                        class="text-gray-900 dark:text-gray-100"
                                        >Piutang</span
                                    >
                                </label>
                            </div>
                        </div>

                        <!-- Bukti Transaksi -->
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Bukti Transaksi
                            </label>
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center gap-4"
                            >
                                <div class="relative">
                                    <div
                                        class="w-20 h-20 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden group hover:border-green-600 transition-all duration-300"
                                    >
                                        <img
                                            v-if="previewBukti"
                                            :src="previewBukti"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        />
                                        <FileText
                                            v-else
                                            class="w-6 h-6 text-gray-400"
                                        />
                                    </div>
                                    <div
                                        v-if="previewBukti"
                                        class="absolute -top-2 -right-2 w-5 h-5 bg-green-500 rounded-full border-2 border-white dark:border-gray-900 flex items-center justify-center"
                                    >
                                        <Check class="w-2.5 h-2.5 text-white" />
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div
                                        class="flex flex-col sm:flex-row gap-2"
                                    >
                                        <label
                                            class="flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl cursor-pointer shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 active:scale-95 font-medium text-xs"
                                        >
                                            <Upload class="w-3.5 h-3.5" />
                                            <span>Pilih Bukti</span>
                                            <input
                                                type="file"
                                                @change="
                                                    handleFileChange(
                                                        $event,
                                                        'bukti_transaksi'
                                                    )
                                                "
                                                class="hidden"
                                                accept="image/*,.pdf,.doc,.docx"
                                            />
                                        </label>
                                        <button
                                            v-if="previewBukti"
                                            type="button"
                                            @click="
                                                clearFile('bukti_transaksi')
                                            "
                                            class="flex items-center justify-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95 font-medium text-xs"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                    >
                                        Format: JPG, PNG, PDF.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300 appearance-none cursor-pointer"
                            >
                                <option value="Belum" class="text-orange-500">
                                    Belum Lunas
                                </option>
                                <option value="Lunas" class="text-green-500">
                                    Lunas
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Nominal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-900 dark:text-gray-100"
                                >
                                    Rp
                                </div>
                                <input
                                    v-model="form.nominal"
                                    type="number"
                                    required
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                                />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.tanggal"
                                type="date"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Jatuh Tempo
                            </label>
                            <input
                                v-model="form.jatuh_tempo"
                                type="date"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>
                    </div>
                </div>

                <!-- Kontak Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-gradient-to-r from-green-600 to-green-600 rounded-lg flex items-center justify-center"
                        >
                            <User class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Kontak
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    Nama <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nama"
                                    type="text"
                                    required
                                    :placeholder="
                                        form.jenis === 'Hutang'
                                            ? 'Nama debitur'
                                            : 'Nama kreditur'
                                    "
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                                />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    Kontak
                                </label>
                                <input
                                    v-model="form.kontak"
                                    type="text"
                                    placeholder="Nomor telepon atau email"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                                />
                            </div>
                        </div>

                        <!-- Foto Profil -->
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Foto Profil
                            </label>
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center gap-4"
                            >
                                <div class="relative">
                                    <div
                                        class="w-20 h-20 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden group hover:border-green-600 transition-all duration-300"
                                    >
                                        <img
                                            v-if="previewFoto"
                                            :src="previewFoto"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        />
                                        <User
                                            v-else
                                            class="w-6 h-6 text-gray-400"
                                        />
                                    </div>
                                    <div
                                        v-if="previewFoto"
                                        class="absolute -top-2 -right-2 w-5 h-5 bg-green-500 rounded-full border-2 border-white dark:border-gray-900 flex items-center justify-center"
                                    >
                                        <Check class="w-2.5 h-2.5 text-white" />
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div
                                        class="flex flex-col sm:flex-row gap-2"
                                    >
                                        <label
                                            class="flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl cursor-pointer shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 active:scale-95 font-medium text-xs"
                                        >
                                            <Upload class="w-3.5 h-3.5" />
                                            <span>Pilih Foto</span>
                                            <input
                                                type="file"
                                                @change="
                                                    handleFileChange(
                                                        $event,
                                                        'foto'
                                                    )
                                                "
                                                class="hidden"
                                                accept="image/*"
                                            />
                                        </label>
                                        <button
                                            v-if="previewFoto"
                                            type="button"
                                            @click="clearFile('foto')"
                                            class="flex items-center justify-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95 font-medium text-xs"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                    >
                                        Format: JPG, PNG.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keterangan Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-gradient-to-r from-green-600 to-green-600 rounded-lg flex items-center justify-center"
                        >
                            <FileText class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Keterangan
                        </h3>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Deskripsi Transaksi
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="4"
                            placeholder="Deskripsi lengkap tentang transaksi ini..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300 resize-none"
                        ></textarea>
                        <p
                            class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                        >
                            Jelaskan detail transaksi, syarat, atau informasi
                            penting lainnya.
                        </p>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-gray-200 dark:border-gray-700"
                >
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="text-red-500">*</span> Menandakan field
                        wajib diisi
                    </div>

                    <div
                        class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto"
                    >
                        <Link
                            :href="route('hutang-piutang.index')"
                            class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-900 dark:text-gray-100 transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold order-2 sm:order-1"
                        >
                            <ArrowLeft class="w-4 h-4" />
                            Kembali
                        </Link>

                        <button
                            type="submit"
                            class="flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-green-600 to-green-600 hover:from-green-700 hover:to-green-700 text-white shadow-lg hover:shadow-xl hover:shadow-green-600/30 transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold order-1 sm:order-2"
                            :disabled="form.processing"
                            :class="
                                form.processing
                                    ? 'opacity-70 cursor-not-allowed'
                                    : ''
                            "
                        >
                            <Save class="w-4 h-4" />
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Data {{ title }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Custom select arrow */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

select:focus {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233b82f6' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
}

.dark select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239bb9a5' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
}

.dark select:focus {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2360a5fa' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
}
</style>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { computed, ref } from "vue";
import {
    ArrowLeft,
    Check,
    CreditCard,
    FileText,
    Save,
    Trash2,
    Upload,
    User,
} from "lucide-vue-next";

const previewFoto = ref(null);
const previewBukti = ref(null);

const form = useForm({
    jenis: "Hutang",
    tanggal: new Date().toISOString().split("T")[0],
    nama: "",
    foto: null,
    kontak: "",
    nominal: 0,
    keterangan: "",
    jatuh_tempo: "",
    bukti_transaksi: null,
    status: "Belum",
});

// Compute title based on jenis
const title = computed(() => (form.jenis === "Hutang" ? "Hutang" : "Piutang"));

function handleFileChange(e, field) {
    const file = e.target.files[0];
    if (!file) return;

    form[field] = file;

    if (file.type.startsWith("image/")) {
        const previewField = field === "foto" ? previewFoto : previewBukti;
        previewField.value = URL.createObjectURL(file);
    }
}

function clearFile(field) {
    form[field] = null;
    if (field === "foto") {
        previewFoto.value = null;
    } else if (field === "bukti_transaksi") {
        previewBukti.value = null;
    }
}

function submit() {
    form.post(route("hutang-piutang.store"), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            previewFoto.value = null;
            previewBukti.value = null;
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
}
</script>
