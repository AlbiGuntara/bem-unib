<template>
    <AppLayout>
        <Head title="Detail Data Warga" />

        <div class="mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch">
                <!-- PROFILE (kiri) -->
                <div class="lg:col-span-1 flex flex-col">
                    <div
                        class="flex-1 bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-xs h-full"
                    >
                        <div
                            class="flex flex-col items-center text-center h-full"
                        >
                            <!-- Foto Profil -->
                            <div class="relative mb-4">
                                <div
                                    class="w-32 h-32 rounded-2xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden shadow-lg"
                                >
                                    <img
                                        v-if="warga.foto"
                                        :src="`/storage/${warga.foto}`"
                                        alt="Foto Warga"
                                        class="w-full h-full object-cover"
                                    />
                                    <User
                                        v-else
                                        class="w-12 h-12 text-gray-400"
                                    />
                                </div>
                                <div
                                    class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-600 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center"
                                >
                                    <Check class="w-3 h-3 text-white" />
                                </div>
                            </div>

                            <h2
                                class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-1"
                            >
                                {{ warga.nama }}
                            </h2>
                            <p
                                class="text-sm text-gray-500 dark:text-gray-400 mb-3"
                            >
                                NIS: {{ warga.nis }}
                            </p>

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                :class="{
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300':
                                        warga.status === 'Santri',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300':
                                        warga.status === 'Alumni',
                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300':
                                        warga.status === 'Tidak Jelas',
                                }"
                            >
                                {{ warga.status }}
                            </span>

                            <!-- Media Sosial -->
                            <div
                                class="w-full my-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm"
                            >
                                <div class="flex items-center gap-2 mb-3">
                                    <div
                                        class="w-5 h-5 bg-green-600 rounded-lg flex items-center justify-center"
                                    >
                                        <Globe class="w-3 h-3 text-white" />
                                    </div>
                                    <h4
                                        class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                    >
                                        Media Sosial
                                    </h4>
                                </div>

                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        <Mail
                                            class="w-4 h-4 text-green-600 dark:text-green-400"
                                        />
                                        <span
                                            class="text-gray-900 dark:text-gray-100"
                                        >
                                            {{ warga.email || "-" }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Instagram
                                            class="w-4 h-4 text-green-600 dark:text-green-400"
                                        />
                                        <span
                                            class="text-gray-900 dark:text-gray-100"
                                        >
                                            {{ warga.ig || "-" }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Phone
                                            class="w-4 h-4 text-green-600 dark:text-green-400"
                                        />
                                        <span
                                            class="text-gray-900 dark:text-gray-100"
                                        >
                                            {{ warga.wa || "-" }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="flex gap-3 mt-auto">
                                <Link
                                    :href="route('warga.edit', warga.id)"
                                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold text-sm"
                                >
                                    <Edit class="w-4 h-4" />
                                    Edit
                                    <span class="hidden md:block">Data</span>
                                </Link>
                                <Link
                                    :href="route('warga.index')"
                                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold text-sm"
                                >
                                    <ArrowLeft class="w-4 h-4" />
                                    Kembali
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFORMASI PRIBADI & RIWAYAT -->
                <div class="lg:col-span-2 flex flex-col gap-6">
                    <!-- Informasi Pribadi -->
                    <div
                        class="flex-1 bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-xs"
                    >
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                            >
                                <User class="w-3 h-3 text-white" />
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Informasi Pribadi
                            </h3>
                        </div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                        >
                            <InfoItem label="Asrama" :value="warga.asrama" />
                            <InfoItem label="Jabatan" :value="warga.jabatan" />
                            <InfoItem
                                label="Tahun Mondok"
                                :value="warga.tahun_mondok"
                            />
                            <InfoItem label="Nama Wali" :value="warga.wali" />
                            <InfoItem
                                label="No. Telepon Wali"
                                :value="warga.no_telp_wali"
                            />
                            <InfoItem
                                label="Tahun Lulus"
                                :value="warga.tahun_lulus"
                            />
                        </div>
                    </div>

                    <!-- Riwayat Pendidikan -->
                    <div
                        class="flex-1 bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-xs"
                    >
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                            >
                                <GraduationCap class="w-3 h-3 text-white" />
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Riwayat Pendidikan
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <EducationCard
                                title="Pendidikan Pagi"
                                :institution="warga.pendidikan_pagi"
                                :studentId="warga.no_induk_pendidikan_pagi"
                                :classLevel="warga.kelas_pendidikan_pagi"
                            />
                            <EducationCard
                                title="Pendidikan Sore"
                                :institution="warga.pendidikan_sore"
                                :studentId="warga.no_induk_pendidikan_sore"
                                :classLevel="warga.kelas_pendidikan_sore"
                            />
                            <EducationCard
                                title="Pendidikan Malam"
                                :institution="warga.pendidikan_malam"
                                :studentId="warga.no_induk_pendidikan_malam"
                                :classLevel="warga.kelas_pendidikan_malam"
                            />
                        </div>
                    </div>
                </div>

                <!-- ALAMAT LENGKAP -->
                <div
                    class="lg:col-span-3 bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-xs"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                        >
                            <MapPin class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Alamat Lengkap
                        </h3>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm"
                        >
                            <InfoItem label="Kampung" :value="warga.kampung" />
                            <InfoItem
                                label="RT/RW"
                                :value="
                                    warga.rt && warga.rw
                                        ? `RT ${warga.rt}/RW ${warga.rw}`
                                        : '-'
                                "
                            />
                            <InfoItem label="Desa" :value="warga.desa" />
                            <InfoItem
                                label="Kecamatan"
                                :value="warga.kecamatan"
                            />
                            <InfoItem
                                label="Kabupaten"
                                :value="warga.kabupaten"
                            />
                            <InfoItem
                                label="Provinsi"
                                :value="warga.provinsi"
                            />
                            <InfoItem
                                label="Kode Pos"
                                :value="warga.kode_pos"
                            />
                            <InfoItem
                                v-if="warga.latitude && warga.longitude"
                                label="Koordinat"
                                :value="`${warga.latitude}, ${warga.longitude}`"
                            />
                        </div>
                    </div>

                    <div v-if="warga.latitude && warga.longitude" class="mt-6">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm"
                        >
                            <div
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700"
                            >
                                <div
                                    class="flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    <MapPin
                                        class="w-4 h-4 text-green-600 dark:text-green-400"
                                    />
                                    <span>Lokasi Rumah</span>
                                </div>
                            </div>
                            <iframe
                                class="w-full h-64"
                                :src="`https://maps.google.com/maps?q=${warga.latitude},${warga.longitude}&z=15&output=embed`"
                                loading="lazy"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    User,
    Check,
    Edit,
    ArrowLeft,
    MapPin,
    GraduationCap,
    Globe,
    Mail,
    Instagram,
    Phone,
} from "lucide-vue-next";

defineProps({
    warga: Object,
});

// Komponen Info Item
const InfoItem = {
    props: ["label", "value"],
    template: `
        <div class="space-y-1">
            <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                {{ label }}
            </span>
            <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                {{ value || '-' }}
            </p>
        </div>
    `,
};

// Komponen Education Card
const EducationCard = {
    props: ["title", "institution", "studentId", "classLevel"],
    template: `
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300 h-full flex flex-col justify-between">
            <div>
                <h4 class="text-green-600 dark:text-green-400 font-bold mb-2">{{ title }}</h4>
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Lembaga</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ institution || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">No. Induk</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ studentId || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Kelas</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ classLevel || '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    `,
};
</script>

<style scoped>
iframe {
    border: none;
    min-height: 16rem;
}
</style>
