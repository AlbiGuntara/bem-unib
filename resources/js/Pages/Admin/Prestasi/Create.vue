<template>
    <AppLayout>
        <Head title="Tambah Prestasi" />

        <!-- FORM -->
        <form @submit.prevent="submit" class="space-y-6">
            <!-- CARD 1: DATA warga -->
            <div
                class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow"
            >
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-2 h-8 bg-gradient-to-b from-green-500 to-emerald-400 rounded-full"
                        ></div>
                        <h1
                            class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-gray-100"
                        >
                            Tambah Prestasi Baru
                        </h1>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 ml-5">
                        Isi formulir berikut untuk menambahkan data prestasi
                        warga
                    </p>
                </div>

                <!-- warga -->
                <div class="space-y-1 mb-4">
                    <label
                        class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
                    >
                        <Users class="w-4 h-4 text-green-600" /> warga
                    </label>

                    <select
                        v-model="form.id_warga"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition-all duration-300 shadow-sm"
                    >
                        <option value="">Pilih warga</option>

                        <option v-for="w in warga" :key="w.id" :value="w.id">
                            {{ w.nama }} ({{ w.nis }})
                        </option>
                    </select>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- PRESTASI -->
                    <div>
                        <label
                            class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
                        >
                            <Trophy class="w-4 h-4 text-green-600" />
                            Prestasi
                        </label>
                        <input
                            v-model="form.prestasi"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition-all duration-300 shadow-sm"
                        />
                    </div>

                    <!-- KATEGORI -->
                    <div>
                        <label
                            class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
                        >
                            <Tag class="w-4 h-4 text-green-600" /> Kategori
                        </label>
                        <input
                            v-model="form.kategori"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition-all duration-300 shadow-sm"
                        />
                    </div>

                    <!-- TINGKAT -->
                    <div>
                        <label
                            class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
                        >
                            <BarChart class="w-4 h-4 text-green-600" />
                            Tingkat
                        </label>
                        <input
                            v-model="form.tingkat"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition-all duration-300 shadow-sm"
                        />
                    </div>

                    <!-- PENYELENGGARA -->
                    <div>
                        <label
                            class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
                        >
                            <Building class="w-4 h-4 text-green-600" />
                            Penyelenggara
                        </label>
                        <input
                            v-model="form.penyelenggara"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition-all duration-300 shadow-sm"
                        />
                    </div>

                    <!-- TEMPAT -->
                    <div>
                        <label
                            class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
                        >
                            <MapPin class="w-4 h-4 text-green-600" /> Tempat
                        </label>
                        <input
                            v-model="form.tempat"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition-all duration-300 shadow-sm"
                        />
                    </div>

                    <!-- TANGGAL -->
                    <div>
                        <label
                            class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
                        >
                            <Calendar class="w-4 h-4 text-green-600" />
                            Tanggal
                        </label>
                        <input
                            type="date"
                            v-model="form.tanggal"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition-all duration-300 shadow-sm"
                        />
                    </div>
                </div>

                <!-- DESKRIPSI -->
                <div class="mt-4">
                    <label
                        class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
                    >
                        <FileText class="w-4 h-4 text-green-600" />
                        Deskripsi
                    </label>
                    <textarea
                        v-model="form.deskripsi"
                        rows="4"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition-all duration-300 shadow-sm"
                    ></textarea>
                </div>

                <div class="space-y-2 my-4">
                    <label
                        class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                    >
                        <Image class="w-4 h-4 text-green-600" />
                        Dokumentasi
                    </label>
                    <div
                        class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 hover:border-green-400 transition-all duration-300 group"
                    >
                        <div
                            class="flex flex-col items-center justify-center text-center"
                        >
                            <Upload
                                class="w-8 h-8 text-gray-400 mb-2 group-hover:text-green-500 transition-colors"
                            />
                            <p
                                class="text-sm text-gray-500 dark:text-gray-400 mb-2"
                            >
                                <span class="font-medium text-green-600"
                                    >Klik untuk upload</span
                                >
                                atau drag & drop
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                PNG, JPG, JPEG (Maks. 2MB)
                            </p>
                            <input
                                type="file"
                                @change="handleFile"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full cursor-pointer"
                            />
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Upload foto sertifikat atau dokumentasi prestasi
                    </p>
                    <div
                        v-if="form.dokumentasi"
                        class="flex items-center gap-2 mt-2 p-2 bg-green-50 dark:bg-green-900/20 rounded-lg"
                    >
                        <Check class="w-4 h-4 text-green-600" />
                        <span
                            class="text-sm text-green-700 dark:text-green-300"
                        >
                            File terpilih
                        </span>
                    </div>
                    <!-- PREVIEW GAMBAR -->
                    <div v-if="previewImage" class="mt-3 flex justify-center">
                        <img
                            :src="previewImage"
                            alt="Preview"
                            class="w-40 h-40 object-cover rounded-lg shadow-md border"
                        />
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="mt-6 flex justify-between md:justify-end gap-2">
                    <!-- Batal -->
                    <Link
                        :href="route('prestasi.index')"
                        class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl border border-gray-300 dark:border-gray-600 transition-all duration-300 transform font-semibold"
                    >
                        <ArrowLeft class="w-4 h-4" />
                        Kembali
                    </Link>

                    <!-- Simpan -->
                    <button
                        class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all font-semibold tracking-wide flex items-center justify-center gap-2"
                    >
                        <Save class="w-5 h-5" />
                        Simpan <span class="hidden md:inline">Prestasi</span>
                    </button>
                </div>
            </div>
        </form>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    Trophy,
    Medal,
    Users,
    Tag,
    BarChart,
    Building,
    MapPin,
    Calendar,
    FileText,
    Image,
    Save,
    Upload,
    ArrowLeft,
    Check,
} from "lucide-vue-next";

const props = defineProps({
    warga: Array,
});

const form = useForm({
    id_warga: "",
    prestasi: "",
    kategori: "",
    tingkat: "",
    penyelenggara: "",
    tempat: "",
    tanggal: "",
    deskripsi: "",
    dokumentasi: null,
});

const previewImage = ref(null);

function handleFile(e) {
    const file = e.target.files[0];
    if (!file) return;

    form.dokumentasi = file;
    previewImage.value = URL.createObjectURL(file);
}

const submit = () => form.post(route("prestasi.store"));
</script>
