<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Alert from "@/Components/Alert.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import {
    ArrowLeft,
    Mail,
    Instagram,
    MessageCircle,
    Award,
    Calendar,
    MapPin,
    Tag,
    Building,
    TrendingUp,
    Share2,
    Download,
    Plus,
} from "lucide-vue-next";

// Props
const props = defineProps({
    warga: Object,
    prestasi: Array,
});

// Random image generator (PICSUM — selalu muncul)
const topics = [
    "education",
    "school",
    "study",
    "books",
    "library",
    "success",
    "achievement",
    "nature",
    "landscape",
    "city",
];

const randomTopic = topics[Math.floor(Math.random() * topics.length)];
const randomId = Math.floor(Math.random() * 1000);

const bgImage = `https://picsum.photos/seed/${randomTopic}-${randomId}/1200/600`;

// Media Sosial Link
const igUrl = computed(() => {
    if (!props.warga.ig) return null;

    let ig = props.warga.ig.trim();

    // Jika mulai dengan @ → hapus @
    if (ig.startsWith("@")) ig = ig.substring(1);

    // Jika sudah URL → pakai apa adanya
    if (ig.startsWith("http")) return ig;

    // Jika hanya username → arahkan ke profil Instagram
    return `https://instagram.com/${ig}`;
});

const emailUrl = computed(() => {
    if (!props.warga.email) return null;

    let mail = props.warga.email.trim();

    // Jika user memasukkan email tanpa mailto
    if (!mail.startsWith("mailto:")) {
        return `mailto:${mail}`;
    }

    return mail;
});

const waUrl = computed(() => {
    if (!props.warga.wa) return null;

    let wa = props.warga.wa.trim();

    // Hapus karakter non-digit
    wa = wa.replace(/\D/g, "");

    // Jika nomor tidak diawali 62, ganti otomatis
    if (wa.startsWith("0")) {
        wa = "62" + wa.substring(1);
    }

    return `https://wa.me/${wa}`;
});

// state deskripsi
const expandedDesc = ref({});

const toggleDesc = (id) => {
    expandedDesc.value[id] = !expandedDesc.value[id];
};

// Share
const sharePrestasi = async (item) => {
    const shareData = {
        title: `${item.prestasi} - Prestasi ${props.warga.nama}`,
        text: `Prestasi: ${item.prestasi}
Kategori: ${item.kategori}
Tingkat: ${item.tingkat}
Tanggal: ${new Date(item.tanggal).toLocaleDateString("id-ID")}
Tempat: ${item.tempat}`,
        url: window.location.href,
    };

    // Native share (Web Share API)
    if (navigator.share) {
        try {
            await navigator.share(shareData);

            // Reload + kirim ?shared=1
            router.get(
                window.location.pathname,
                { shared: 1 },
                { preserveScroll: true }
            );
        } catch (err) {
            console.log("Share canceled or failed:", err);
        }
        return;
    }

    // Fallback copy ke clipboard
    try {
        await navigator.clipboard.writeText(
            `${shareData.title}\n\n${shareData.text}\n${shareData.url}`
        );

        // Reload + kirim ?copied=1
        router.get(
            window.location.pathname,
            { copied: 1 },
            { preserveScroll: true }
        );
    } catch {
        router.get(window.location.pathname, { error: "Gagal menyalin link." });
    }
};
</script>

<template>
    <AppLayout>
        <Alert />
        <Head :title="`Prestasi - ${props.warga.nama}`" />

        <!-- Banner -->
        <div
            class="relative w-full rounded-lg border dark:border-gray-700 shadow-lg h-52 md:h-64"
        >
            <img :src="bgImage" class="w-full h-full rounded-lg object-cover" />

            <!-- Overlay -->
            <div
                class="absolute inset-0 rounded-lg bg-gradient-to-b from-black/40 to-black/60"
            ></div>

            <!-- Navigation -->
            <div class="absolute top-4 left-4 z-20 md:top-6 md:left-6">
                <Link
                    :href="route('prestasi.index')"
                    class="flex items-center gap-2 px-3 py-2 md:px-4 md:py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl md:hover:bg-white/30 transition-all duration-300 transform md:hover:scale-105 text-sm md:text-base"
                >
                    <ArrowLeft class="w-4 h-4" />
                    <span class="font-medium">Kembali</span>
                </Link>
            </div>

            <!-- Foto Profil -->
            <div
                class="absolute -bottom-16 left-1/2 transform -translate-x-1/2 md:left-10 md:translate-x-0 md:-bottom-20"
            >
                <img
                    :src="`/storage/${props.warga.foto}`"
                    class="w-32 h-32 md:w-40 md:h-40 rounded-full ring-4 ring-white shadow-2xl object-cover bg-white"
                />
            </div>
        </div>

        <!-- USER INFO -->
        <div
            class="relative mt-10 md:-mt-10 md:ml-45 z-20 px-4 md:px-10 flex flex-col md:flex-row md:justify-between items-center gap-4 md:gap-0 text-center md:text-left"
        >
            <div class="mt-10 md:mt-0">
                <h1 class="text-white text-xl md:text-2xl font-bold">
                    {{ props.warga.nama }}
                </h1>

                <p class="mt-3 text-gray-600 dark:text-gray-300 text-sm">
                    {{ props.warga.nis }}
                </p>

                <p class="text-gray-400 text-sm mt-1">
                    Total Prestasi:
                    <span class="font-semibold text-gray-600">{{
                        props.prestasi.length
                    }}</span>
                </p>
            </div>

            <!-- Social Media -->
            <div class="flex items-center gap-3 mt-2 md:mt-10">
                <p
                    class="hidden md:block text-sm font-semibold text-gray-700 dark:text-gray-300 mr-4"
                >
                    Social Media
                </p>

                <a :href="igUrl" target="_blank" v-if="igUrl">
                    <Instagram
                        class="w-6 h-6 text-pink-500 hover:text-pink-300 transition-all duration-200 cursor-pointer"
                    />
                </a>

                <a :href="emailUrl" target="_blank" v-if="emailUrl">
                    <Mail
                        class="w-6 h-6 text-blue-600 hover:text-blue-400 transition-all duration-200 cursor-pointer"
                    />
                </a>

                <a :href="waUrl" target="_blank" v-if="waUrl">
                    <MessageCircle
                        class="w-6 h-6 text-green-600 hover:text-green-400 transition-all duration-200 cursor-pointer"
                    />
                </a>
            </div>
        </div>

        <!-- Title -->
        <div class="mt-20 mb-6 md:px-0 text-left md:text-left">
            <div
                class="flex items-center gap-3 mb-2 justify-center md:justify-start"
            >
                <div
                    class="w-2 h-8 bg-gradient-to-b from-green-500 to-emerald-400 rounded-full"
                ></div>
                <h1
                    class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-gray-100"
                >
                    Daftar Prestasi {{ props.warga.nama }}
                </h1>
            </div>
        </div>

        <!-- Prestasi -->
        <div class="space-y-4">
            <!-- Card Prestasi -->
            <div
                v-for="(item, index) in props.prestasi"
                :key="index"
                class="group relative overflow-hidden rounded-lg shadow-sm transition-all duration-500 transform md:hover:-translate-y-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700/50"
            >
                <!-- Hover BG -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-emerald-50/30 dark:from-green-900/10 dark:to-emerald-900/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                ></div>

                <!-- Tingkat Badge -->
                <div class="absolute top-4 right-4 z-10">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm border backdrop-blur-sm"
                        :class="{
                            'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800':
                                item.tingkat === 'Internasional',
                            'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800':
                                item.tingkat === 'Nasional',
                            'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800':
                                item.tingkat === 'Provinsi',
                            'bg-purple-100 text-purple-800 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800':
                                item.tingkat === 'Kabupaten',
                            'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800':
                                item.tingkat === 'Kecamatan',
                            'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700':
                                !item.tingkat || item.tingkat === 'Sekolah',
                        }"
                    >
                        <Award class="w-3 h-3" />
                        {{ item.tingkat || "Lokal" }}
                    </span>
                </div>

                <div class="flex flex-col md:flex-row items-stretch gap-0">
                    <!-- Dokumentasi -->
                    <div
                        class="relative w-full md:w-2/5 lg:w-1/3 h-56 md:h-auto overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900"
                    >
                        <div
                            class="relative w-full h-full flex items-center justify-center overflow-hidden"
                        >
                            <img
                                v-if="item.dokumentasi"
                                :src="`/storage/${item.dokumentasi}`"
                                class="w-full h-full object-cover transition-transform duration-700 md:group-hover:scale-105"
                                :alt="item.prestasi"
                            />
                            <div
                                v-else
                                class="flex flex-col items-center justify-center text-gray-400 p-6 text-center"
                            >
                                <Award class="w-16 h-16 mb-3 opacity-40" />
                                <span
                                    class="text-sm font-medium tracking-wide uppercase"
                                    >Tidak Ada Dokumentasi</span
                                >
                                <span class="text-xs mt-1 opacity-70"
                                    >Prestasi {{ item.prestasi }}</span
                                >
                            </div>
                        </div>

                        <!-- Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 md:group-hover:opacity-100 transition-opacity duration-300"
                        ></div>

                        <!-- Date -->
                        <div class="absolute bottom-4 left-4">
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 shadow-sm"
                            >
                                <Calendar class="w-3 h-3" />
                                {{
                                    new Date(item.tanggal).toLocaleDateString(
                                        "id-ID",
                                        {
                                            day: "numeric",
                                            month: "short",
                                            year: "numeric",
                                        }
                                    )
                                }}
                            </div>
                        </div>
                    </div>

                    <!-- Detail -->
                    <div class="flex-1 p-6 md:p-8 space-y-4 relative z-10">
                        <h3
                            class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white leading-tight pr-12 md:group-hover:text-green-600 md:dark:group-hover:text-green-400 transition-colors duration-300"
                        >
                            {{ item.prestasi }}
                        </h3>

                        <!-- Metadata Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Kategori -->
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0"
                                >
                                    <Tag
                                        class="w-4 h-4 text-blue-600 dark:text-blue-400"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="text-gray-500 dark:text-gray-400 text-xs font-medium"
                                    >
                                        Kategori
                                    </p>
                                    <p
                                        class="text-gray-800 dark:text-gray-200 font-semibold"
                                    >
                                        {{ item.kategori || "-" }}
                                    </p>
                                </div>
                            </div>

                            <!-- Tempat -->
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0"
                                >
                                    <MapPin
                                        class="w-4 h-4 text-green-600 dark:text-green-400"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="text-gray-500 dark:text-gray-400 text-xs font-medium"
                                    >
                                        Tempat
                                    </p>
                                    <p
                                        class="text-gray-800 dark:text-gray-200 font-semibold"
                                    >
                                        {{ item.tempat || "-" }}
                                    </p>
                                </div>
                            </div>

                            <!-- Penyelenggara -->
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0"
                                >
                                    <Building
                                        class="w-4 h-4 text-purple-600 dark:text-purple-400"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="text-gray-500 dark:text-gray-400 text-xs font-medium"
                                    >
                                        Penyelenggara
                                    </p>
                                    <p
                                        class="text-gray-800 dark:text-gray-200 font-semibold"
                                    >
                                        {{ item.penyelenggara || "-" }}
                                    </p>
                                </div>
                            </div>

                            <!-- Tingkat -->
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0"
                                >
                                    <TrendingUp
                                        class="w-4 h-4 text-orange-600 dark:text-orange-400"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="text-gray-500 dark:text-gray-400 text-xs font-medium"
                                    >
                                        Tingkat
                                    </p>
                                    <p
                                        class="text-gray-800 dark:text-gray-200 font-semibold"
                                    >
                                        {{ item.tingkat || "-" }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="pt-2">
                            <p
                                class="text-gray-600 dark:text-gray-300 leading-relaxed"
                            >
                                {{
                                    expandedDesc[item.id]
                                        ? item.deskripsi
                                        : item.deskripsi
                                        ? item.deskripsi.slice(0, 150) +
                                          (item.deskripsi.length > 150
                                              ? "..."
                                              : "")
                                        : "Tidak ada deskripsi yang tersedia untuk prestasi ini."
                                }}
                            </p>

                            <button
                                v-if="
                                    item.deskripsi &&
                                    item.deskripsi.length > 150
                                "
                                @click="toggleDesc(item.id)"
                                class="mt-2 text-sm font-semibold text-green-600 dark:text-green-400 hover:underline"
                            >
                                {{
                                    expandedDesc[item.id]
                                        ? "Sembunyikan"
                                        : "Lihat selengkapnya"
                                }}
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50"
                        >
                            <button
                                @click="sharePrestasi(item)"
                                class="flex items-center gap-2 w-full justify-center px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition-colors duration-200 font-medium border border-green-200 dark:border-green-800 rounded-lg"
                            >
                                <Share2 class="w-4 h-4" />
                                Bagikan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hover Bottom Line -->
                <div
                    class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-green-500 to-emerald-500 md:group-hover:w-full transition-all duration-500"
                ></div>
            </div>

            <!-- Empty State -->
            <div v-if="props.prestasi.length === 0" class="text-center py-16">
                <div
                    class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center"
                >
                    <Award class="w-10 h-10 text-gray-400" />
                </div>
                <h3
                    class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3"
                >
                    Belum Ada Prestasi
                </h3>
                <p
                    class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto leading-relaxed"
                >
                    {{ props.warga.nama }} belum memiliki prestasi yang
                    tercatat.
                </p>
                <button
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold"
                >
                    <Plus class="w-5 h-5" />
                    <span>Tambah Prestasi Pertama</span>
                </button>
            </div>
        </div>
    </AppLayout>
</template>
