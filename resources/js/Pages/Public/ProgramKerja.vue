<template>
    <AppLayout>
        <Head title="Program Kerja | IKSASS Banten" />

        <!-- HERO -->
        <section
            class="relative py-24 bg-gradient-to-br from-green-700 via-green-600 to-yellow-500 text-white overflow-hidden"
        >
            <div class="absolute inset-0 bg-black/20"></div>

            <div
                class="relative max-w-7xl mx-auto px-6 text-center"
                data-aos="fade-up"
            >
                <ClipboardList class="w-14 h-14 mx-auto mb-6 text-yellow-200" />
                <h1 class="text-4xl lg:text-5xl font-extrabold mb-4">
                    Program Kerja IKSASS Banten
                </h1>
                <p class="max-w-3xl mx-auto text-green-100">
                    Rangkaian program kerja strategis IKSASS Banten sebagai
                    wujud pengabdian, pengkaderan, dan kontribusi nyata untuk
                    umat dan masyarakat.
                </p>
            </div>
        </section>

        <!-- Filter & Search Section -->
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <div class="relative">
                            <Search
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5"
                            />
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Cari program kerja..."
                                class="w-full pl-12 pr-4 py-3 border-2 border-green-200 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-300"
                            />
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="flex gap-4">
                        <select
                            v-model="filterStatus"
                            class="flex-1 px-4 py-3 border-2 border-green-200 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-300"
                        >
                            <option value="">Semua Status</option>
                            <option value="planning">Perencanaan</option>
                            <option value="ongoing">Berlangsung</option>
                            <option value="completed">Selesai</option>
                        </select>
                        <select
                            v-model="filterMonth"
                            class="flex-1 px-4 py-3 border-2 border-green-200 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-300"
                        >
                            <option value="">Semua Bulan</option>
                            <option
                                v-for="month in months"
                                :key="month.value"
                                :value="month.value"
                            >
                                {{ month.label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <!-- Program Kerja Grid -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Tabs -->
                <div class="flex flex-wrap gap-2 mb-12" data-aos="fade-up">
                    <button
                        v-for="category in categories"
                        :key="category.id"
                        @click="activeCategory = category.id"
                        :class="[
                            'px-6 py-3 rounded-lg font-semibold transition-all duration-300',
                            activeCategory === category.id
                                ? 'bg-green-600 text-white shadow-lg'
                                : 'bg-white text-gray-700 hover:bg-green-50 border border-green-200',
                        ]"
                    >
                        {{ category.name }}
                    </button>
                </div>

                <!-- Program Grid -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="program in filteredPrograms"
                        :key="program.id"
                        data-aos="fade-up"
                        :data-aos-delay="program.delay"
                        class="group"
                    >
                        <div
                            class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-full flex flex-col"
                        >
                            <!-- Program Header -->
                            <div class="relative h-48 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-green-600 to-yellow-500"
                                >
                                    <div
                                        class="absolute inset-0 bg-black/20"
                                    ></div>
                                    <div class="absolute top-4 right-4">
                                        <span
                                            :class="[
                                                'px-3 py-1 rounded-full text-xs font-bold',
                                                program.status === 'ongoing'
                                                    ? 'bg-yellow-500 text-white'
                                                    : program.status ===
                                                      'completed'
                                                    ? 'bg-green-500 text-white'
                                                    : 'bg-blue-500 text-white',
                                            ]"
                                        >
                                            {{ getStatusLabel(program.status) }}
                                        </span>
                                    </div>
                                    <div class="absolute bottom-4 left-4">
                                        <Calendar
                                            class="h-5 w-5 text-white inline-block mr-2"
                                        />
                                        <span class="text-white text-sm">
                                            {{
                                                formatDate(
                                                    program.tanggal_mulai
                                                )
                                            }}
                                            -
                                            {{
                                                formatDate(
                                                    program.tanggal_selesai
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <div
                                    class="absolute inset-0 flex items-center justify-center"
                                >
                                    <component
                                        :is="getCategoryIcon(program.kategori)"
                                        class="h-16 w-16 text-white opacity-30"
                                    />
                                </div>
                            </div>

                            <!-- Program Content -->
                            <div class="p-6 flex-grow">
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors duration-300 line-clamp-2"
                                >
                                    {{ program.nama_kegiatan }}
                                </h3>

                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ program.deskripsi }}
                                </p>

                                <!-- Progress Bar -->
                                <div class="mb-6">
                                    <div
                                        class="flex justify-between text-sm text-gray-500 mb-1"
                                    >
                                        <span>Progress</span>
                                        <span>{{ program.progress }}%</span>
                                    </div>
                                    <div
                                        class="h-2 bg-gray-200 rounded-full overflow-hidden"
                                    >
                                        <div
                                            class="h-full bg-gradient-to-r from-green-500 to-yellow-500 rounded-full transition-all duration-500"
                                            :style="{
                                                width: program.progress + '%',
                                            }"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Program Details -->
                                <div class="space-y-3">
                                    <div
                                        class="flex items-center text-sm text-gray-600"
                                    >
                                        <Users
                                            class="h-4 w-4 mr-2 text-green-600"
                                        />
                                        <span
                                            >{{ program.peserta }} Peserta</span
                                        >
                                    </div>
                                    <div
                                        class="flex items-center text-sm text-gray-600"
                                    >
                                        <Target
                                            class="h-4 w-4 mr-2 text-yellow-600"
                                        />
                                        <span class="line-clamp-1">{{
                                            program.tujuan
                                        }}</span>
                                    </div>
                                    <div
                                        class="flex items-center text-sm text-gray-600"
                                    >
                                        <Banknote
                                            class="h-4 w-4 mr-2 text-blue-600"
                                        />
                                        <span
                                            >Anggaran: Rp
                                            {{
                                                formatCurrency(program.anggaran)
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        v-if="program.penanggung_jawab"
                                        class="flex items-center text-sm text-gray-600"
                                    >
                                        <User
                                            class="h-4 w-4 mr-2 text-purple-600"
                                        />
                                        <span
                                            >PJ:
                                            {{ program.penanggung_jawab }}</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Program Footer -->
                            <div
                                class="px-6 py-4 bg-gray-50 border-t border-gray-200"
                            >
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="flex -space-x-2">
                                            <div
                                                v-for="i in 3"
                                                :key="i"
                                                class="w-8 h-8 rounded-full border-2 border-white bg-gradient-to-r from-green-400 to-yellow-400 flex items-center justify-center text-xs text-white font-bold"
                                            >
                                                {{ i }}
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500 ml-2"
                                            >+{{
                                                program.peserta - 3
                                            }}
                                            lainnya</span
                                        >
                                    </div>
                                    <Link
                                        :href="`/program-kerja/${program.id}`"
                                        class="text-green-600 hover:text-green-700 font-semibold flex items-center gap-1"
                                    >
                                        Detail
                                        <ArrowRight class="h-4 w-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="filteredPrograms.length === 0"
                    class="text-center py-16"
                    data-aos="fade-up"
                >
                    <div
                        class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-green-100 to-yellow-100 rounded-full flex items-center justify-center"
                    >
                        <Search class="h-12 w-12 text-gray-400" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Program kerja tidak ditemukan
                    </h3>
                    <p class="text-gray-600">
                        Coba gunakan kata kunci lain atau filter yang berbeda
                    </p>
                </div>

                <!-- Timeline View Toggle -->
                <div class="mt-16 text-center">
                    <button
                        @click="showTimeline = !showTimeline"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-green-600 text-green-600 rounded-lg font-semibold hover:bg-green-50 transition-all duration-300"
                    >
                        <LayoutGrid class="h-5 w-5" v-if="showTimeline" />
                        <Calendar class="h-5 w-5" v-else />
                        {{
                            showTimeline ? "Tampilan Grid" : "Tampilan Timeline"
                        }}
                    </button>
                </div>
            </div>
        </section>

        <!-- Timeline View -->
        <section v-if="showTimeline" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2
                    class="text-3xl font-bold text-gray-900 mb-12 text-center"
                    data-aos="fade-up"
                >
                    Timeline <span class="text-green-600">Program Kerja</span>
                </h2>

                <div class="relative" data-aos="fade-up">
                    <!-- Timeline Line -->
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-green-500 to-yellow-500"
                    ></div>

                    <!-- Timeline Items -->
                    <div class="space-y-12">
                        <div
                            v-for="(program, index) in timelinePrograms"
                            :key="program.id"
                            :class="[
                                'relative',
                                index % 2 === 0
                                    ? 'pr-12 lg:pr-0 lg:pl-12'
                                    : 'pl-12 lg:pl-0 lg:pr-12',
                            ]"
                            data-aos="fade-up"
                            :data-aos-delay="index * 100"
                        >
                            <!-- Timeline Dot -->
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-6 h-6 bg-white border-4 border-green-500 rounded-full z-10"
                            ></div>

                            <!-- Timeline Content -->
                            <div
                                :class="[
                                    'relative bg-white rounded-xl shadow-lg p-6 border-l-4',
                                    program.status === 'ongoing'
                                        ? 'border-yellow-500'
                                        : program.status === 'completed'
                                        ? 'border-green-500'
                                        : 'border-blue-500',
                                ]"
                            >
                                <div
                                    class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4"
                                >
                                    <div>
                                        <h3
                                            class="text-xl font-bold text-gray-900"
                                        >
                                            {{ program.nama_kegiatan }}
                                        </h3>
                                        <div class="flex items-center mt-2">
                                            <Calendar
                                                class="h-4 w-4 text-gray-400 mr-2"
                                            />
                                            <span class="text-sm text-gray-600">
                                                {{
                                                    formatDate(
                                                        program.tanggal_mulai
                                                    )
                                                }}
                                                -
                                                {{
                                                    formatDate(
                                                        program.tanggal_selesai
                                                    )
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                    <span
                                        :class="[
                                            'px-3 py-1 rounded-full text-xs font-bold',
                                            program.status === 'ongoing'
                                                ? 'bg-yellow-500 text-white'
                                                : program.status === 'completed'
                                                ? 'bg-green-500 text-white'
                                                : 'bg-blue-500 text-white',
                                        ]"
                                    >
                                        {{ getStatusLabel(program.status) }}
                                    </span>
                                </div>

                                <p class="text-gray-600 mb-4 line-clamp-2">
                                    {{ program.deskripsi }}
                                </p>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div
                                        class="flex items-center text-gray-600"
                                    >
                                        <Users
                                            class="h-4 w-4 mr-2 text-green-600"
                                        />
                                        <span
                                            >{{ program.peserta }} Peserta</span
                                        >
                                    </div>
                                    <div
                                        class="flex items-center text-gray-600"
                                    >
                                        <Target
                                            class="h-4 w-4 mr-2 text-yellow-600"
                                        />
                                        <span class="truncate">{{
                                            program.tujuan
                                        }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gradient-to-r from-green-400 to-yellow-400 flex items-center justify-center text-xs text-white font-bold mr-2"
                                            >
                                                PJ
                                            </div>
                                            <span
                                                class="text-sm text-gray-600"
                                                >{{
                                                    program.penanggung_jawab
                                                }}</span
                                            >
                                        </div>
                                        <Link
                                            :href="`/program-kerja/${program.id}`"
                                            class="text-green-600 hover:text-green-700 font-semibold flex items-center gap-1"
                                        >
                                            Detail
                                            <ArrowRight class="h-4 w-4" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="py-16 bg-gradient-to-br from-green-50 to-yellow-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2
                    class="text-3xl font-bold text-gray-900 mb-12 text-center"
                    data-aos="fade-up"
                >
                    Statistik <span class="text-green-600">Program Kerja</span>
                </h2>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8"
                >
                    <div
                        v-for="stat in statistics"
                        :key="stat.label"
                        class="bg-white rounded-2xl p-6 shadow-lg text-center"
                        data-aos="fade-up"
                        :data-aos-delay="stat.delay"
                    >
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                            :class="stat.bgColor"
                        >
                            <component
                                :is="stat.icon"
                                class="h-8 w-8"
                                :class="stat.iconColor"
                            />
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-2">
                            {{ stat.value }}
                        </div>
                        <div class="text-gray-600">{{ stat.label }}</div>
                    </div>
                </div>

                <!-- Chart -->
                <div
                    class="mt-16 bg-white rounded-2xl shadow-lg p-6"
                    data-aos="fade-up"
                >
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        Distribusi Program per Kategori
                    </h3>
                    <div class="h-64 flex items-end justify-between gap-4">
                        <div
                            v-for="category in categoryStats"
                            :key="category.name"
                            class="flex flex-col items-center flex-1"
                        >
                            <div class="relative w-full flex justify-center">
                                <div
                                    class="w-3/4 rounded-t-lg"
                                    :style="{
                                        height:
                                            (category.count /
                                                maxCategoryCount) *
                                                200 +
                                            'px',
                                        background: `linear-gradient(to top, ${category.color}66, ${category.color})`,
                                    }"
                                ></div>
                                <div
                                    class="absolute -top-8 text-sm font-semibold text-gray-700"
                                >
                                    {{ category.count }}
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <div class="font-semibold text-gray-900">
                                    {{ category.name }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ category.percentage }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-gradient-to-r from-green-600 to-yellow-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div data-aos="fade-up">
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                        Ingin Berpartisipasi dalam Program?
                    </h2>
                    <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
                        Bergabunglah dengan program kerja IKSASS untuk
                        mengembangkan potensi diri dan berkontribusi untuk umat
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            href="/contact"
                            class="bg-white text-green-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 inline-flex items-center justify-center gap-2"
                        >
                            <MessageSquare class="h-5 w-5" />
                            Hubungi Koordinator
                        </Link>
                        <Link
                            href="/program-kerja/daftar"
                            class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-green-600 transition-all duration-300 inline-flex items-center justify-center gap-2"
                        >
                            <ClipboardCheck class="h-5 w-5" />
                            Daftar Program
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import { Head, Link } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";
import {
    ClipboardList,
    Search,
    Calendar,
    Users,
    Target,
    Banknote,
    User,
    ArrowRight,
    LayoutGrid,
    BookOpen,
    GraduationCap,
    Users2,
    HeartHandshake,
    MessageSquare,
    ClipboardCheck,
    TrendingUp,
    Clock,
    CheckCircle2,
} from "lucide-vue-next";
import { ref, computed, onMounted } from "vue";

// Inisialisasi AOS
onMounted(() => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100,
    });
});

// Data statistik
const activePrograms = ref(8);
const completedPrograms = ref(24);
const totalParticipants = ref(1250);

// Data kategori
const categories = ref([
    { id: "all", name: "Semua Program" },
    { id: "pendidikan", name: "Pendidikan & Ilmu" },
    { id: "dakwah", name: "Dakwah & Syiar" },
    { id: "sosial", name: "Sosial & Kemanusiaan" },
    { id: "kaderisasi", name: "Kaderisasi" },
    { id: "olahraga", name: "Olahraga & Kesenian" },
]);

const activeCategory = ref("all");

// Data filter
const searchQuery = ref("");
const filterStatus = ref("");
const filterMonth = ref("");

const months = [
    { value: "01", label: "Januari" },
    { value: "02", label: "Februari" },
    { value: "03", label: "Maret" },
    { value: "04", label: "April" },
    { value: "05", label: "Mei" },
    { value: "06", label: "Juni" },
    { value: "07", label: "Juli" },
    { value: "08", label: "Agustus" },
    { value: "09", label: "September" },
    { value: "10", label: "Oktober" },
    { value: "11", label: "November" },
    { value: "12", label: "Desember" },
];

// Data contoh program kerja
const programs = ref([
    {
        id: 1,
        nama_kegiatan: "Pelatihan Kepemimpinan Santri Nasional",
        tujuan: "Membentuk kader pemimpin yang berkarakter dan berkualitas",
        tanggal_mulai: "2024-01-15",
        tanggal_selesai: "2024-01-20",
        kategori: "kaderisasi",
        peserta: 150,
        anggaran: 50000000,
        deskripsi:
            "Program intensif selama 5 hari untuk mengembangkan kemampuan kepemimpinan, manajemen organisasi, dan public speaking santri.",
        status: "completed",
        progress: 100,
        penanggung_jawab: "H. Ahmad Fauzi, M.Pd.",
        delay: 0,
    },
    {
        id: 2,
        nama_kegiatan: "Bimbingan Tahfidz Al-Qur'an Metode Sukorejo",
        tujuan: "Mencetak penghafal Al-Qur'an yang berkualitas",
        tanggal_mulai: "2024-02-01",
        tanggal_selesai: "2024-12-31",
        kategori: "pendidikan",
        peserta: 300,
        anggaran: 75000000,
        deskripsi:
            "Program tahfidz intensif dengan metode khas Pesantren Sukorejo, dibimbing oleh ustadz-ustadzah berpengalaman.",
        status: "ongoing",
        progress: 75,
        penanggung_jawab: "Ust. Dr. Siti Aminah, M.Ag.",
        delay: 100,
    },
    {
        id: 3,
        nama_kegiatan: "Bakti Sosial di Daerah Terpencil",
        tujuan: "Meningkatkan kesejahteraan masyarakat marginal",
        tanggal_mulai: "2024-03-10",
        tanggal_selesai: "2024-03-12",
        kategori: "sosial",
        peserta: 80,
        anggaran: 25000000,
        deskripsi:
            "Aksi sosial berupa pembagian sembako, pengobatan gratis, dan renovasi rumah warga tidak mampu.",
        status: "completed",
        progress: 100,
        penanggung_jawab: "M. Rizki Abdullah, S.E.",
        delay: 200,
    },
    {
        id: 4,
        nama_kegiatan: "Seminar Nasional Aswaja dan Kebangsaan",
        tujuan: "Memperkuat pemahaman Aswaja dalam konteks kebangsaan",
        tanggal_mulai: "2024-04-20",
        tanggal_selesai: "2024-04-21",
        kategori: "dakwah",
        peserta: 500,
        anggaran: 100000000,
        deskripsi:
            "Seminar dengan menghadirkan tokoh-tokoh nasional untuk membahas relevansi Ahlus Sunnah wal Jamaah dalam menjaga NKRI.",
        status: "completed",
        progress: 100,
        penanggung_jawab: "Prof. Dr. Abdul Malik, M.A.",
        delay: 300,
    },
    {
        id: 5,
        nama_kegiatan: "Pekan Olahraga dan Seni Santri",
        tujuan: "Mengembangkan bakat non-akademik santri",
        tanggal_mulai: "2024-05-15",
        tanggal_selesai: "2024-05-22",
        kategori: "olahraga",
        peserta: 200,
        anggaran: 35000000,
        deskripsi:
            "Kompetisi olahraga dan seni antar pondok pesantren se-Banten untuk menjalin ukhuwah islamiyah.",
        status: "ongoing",
        progress: 40,
        penanggung_jawab: "Ust. Bambang Sutrisno, S.Pd.",
        delay: 400,
    },
    {
        id: 6,
        nama_kegiatan: "Pelatihan Kewirausahaan Santri",
        tujuan: "Menciptakan santri mandiri dan berdaya saing",
        tanggal_mulai: "2024-06-01",
        tanggal_selesai: "2024-08-31",
        kategori: "pendidikan",
        peserta: 120,
        anggaran: 60000000,
        deskripsi:
            "Program kewirausahaan dengan pendampingan intensif hingga produk siap dipasarkan.",
        status: "ongoing",
        progress: 60,
        penanggung_jawab: "Dr. Fatimah Azzahra, M.M.",
        delay: 500,
    },
]);

// Toggle timeline view
const showTimeline = ref(false);

// Filtered programs
const filteredPrograms = computed(() => {
    let filtered = programs.value;

    // Filter by category
    if (activeCategory.value !== "all") {
        filtered = filtered.filter(
            (program) => program.kategori === activeCategory.value
        );
    }

    // Filter by search query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (program) =>
                program.nama_kegiatan.toLowerCase().includes(query) ||
                program.deskripsi.toLowerCase().includes(query) ||
                program.tujuan.toLowerCase().includes(query)
        );
    }

    // Filter by status
    if (filterStatus.value) {
        filtered = filtered.filter(
            (program) => program.status === filterStatus.value
        );
    }

    // Filter by month
    if (filterMonth.value) {
        filtered = filtered.filter((program) => {
            const startMonth = program.tanggal_mulai.split("-")[1];
            return startMonth === filterMonth.value;
        });
    }

    return filtered;
});

// Timeline programs (take 4 for demo)
const timelinePrograms = computed(() => {
    return filteredPrograms.value.slice(0, 4);
});

// Statistics data
const statistics = ref([
    {
        icon: TrendingUp,
        value: "32",
        label: "Total Program",
        bgColor: "bg-green-100",
        iconColor: "text-green-600",
        delay: 0,
    },
    {
        icon: Clock,
        value: "8",
        label: "Program Aktif",
        bgColor: "bg-yellow-100",
        iconColor: "text-yellow-600",
        delay: 100,
    },
    {
        icon: CheckCircle2,
        value: "24",
        label: "Program Selesai",
        bgColor: "bg-green-100",
        iconColor: "text-green-600",
        delay: 200,
    },
    {
        icon: Users,
        value: "1.250+",
        label: "Total Peserta",
        bgColor: "bg-blue-100",
        iconColor: "text-blue-600",
        delay: 300,
    },
]);

// Category statistics
const categoryStats = computed(() => {
    const stats = categories.value.slice(1).map((cat) => {
        const count = programs.value.filter(
            (p) => p.kategori === cat.id
        ).length;
        return {
            name: cat.name,
            count: count,
            percentage: Math.round((count / programs.value.length) * 100),
            color: getCategoryColor(cat.id),
        };
    });

    return stats.sort((a, b) => b.count - a.count);
});

const maxCategoryCount = computed(() => {
    return Math.max(...categoryStats.value.map((stat) => stat.count));
});

// Helper functions
const getStatusLabel = (status) => {
    switch (status) {
        case "ongoing":
            return "Berlangsung";
        case "completed":
            return "Selesai";
        case "planning":
            return "Perencanaan";
        default:
            return "Tidak Diketahui";
    }
};

const getCategoryIcon = (category) => {
    switch (category) {
        case "pendidikan":
            return BookOpen;
        case "dakwah":
            return GraduationCap;
        case "sosial":
            return HeartHandshake;
        case "kaderisasi":
            return Users2;
        case "olahraga":
            return Users;
        default:
            return Target;
    }
};

const getCategoryColor = (category) => {
    switch (category) {
        case "pendidikan":
            return "#16a34a"; // green-600
        case "dakwah":
            return "#eab308"; // yellow-500
        case "sosial":
            return "#3b82f6"; // blue-500
        case "kaderisasi":
            return "#8b5cf6"; // purple-500
        case "olahraga":
            return "#ef4444"; // red-500
        default:
            return "#6b7280"; // gray-500
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

const formatCurrency = (amount) => {
    return amount.toLocaleString("id-ID");
};
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
