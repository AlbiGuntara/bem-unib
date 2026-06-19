<script setup>
import { ref, computed, watch, onMounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import AOS from "aos";
import "aos/dist/aos.css";

import {
    Calendar,
    User,
    Eye,
    Search,
    ArrowRight,
    Clock,
    Tag,
    ChevronRight,
    TrendingUp,
    Newspaper,
    AlertCircle,
    X,
    Sparkles,
} from "lucide-vue-next";

const props = defineProps({
    beritas: Object,
    categories: Array,
    featured: Array,
    latest: Array,
    popular: Array,
    filters: Object,
    stats: Object,
});

const search = ref(props.filters?.search ?? "");
const selectedCategory = ref(props.filters?.category ?? "");
const isFiltering = computed(() => selectedCategory.value || search.value);

let searchTimeout = null;

watch(search, (val) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route("berita.public.index"),
            {
                search: val || undefined,
                category: selectedCategory.value || undefined,
            },
            { preserveState: true, replace: true, preserveScroll: true },
        );
    }, 400);
});

function clearSearch() {
    search.value = "";
}

function filterByCategory(category) {
    selectedCategory.value = category;
    router.get(
        route("berita.public.index"),
        { category, search: search.value || undefined },
        { preserveState: true, replace: true, preserveScroll: true },
    );
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
}

function timeAgo(dateStr) {
    const diff = Date.now() - new Date(dateStr).getTime();
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    if (days === 0) return "Hari ini";
    if (days === 1) return "Kemarin";
    return `${days} hari lalu`;
}

function stripHtml(html) {
    const div = document.createElement("div");
    div.innerHTML = html;
    return div.textContent || div.innerText || "";
}
</script>

<template>
    <AppLayout>
        <Head title="Berita" />

        <!-- Hero -->
        <section
            class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900"
        >
            <!-- GRID PATTERN -->

            <div
                class="absolute inset-0 opacity-[0.04]"
                style="
                    background-image:
                        linear-gradient(white 1px, transparent 1px),
                        linear-gradient(90deg, white 1px, transparent 1px);
                    background-size: 40px 40px;
                "
            />

            <!-- BLUR ORBS -->

            <div
                class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl animate-pulse"
            ></div>

            <div
                class="absolute top-40 right-0 w-[500px] h-[500px] bg-cyan-500/20 rounded-full blur-3xl"
            ></div>

            <div
                class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[700px] h-[300px] bg-blue-600/20 blur-3xl rounded-full"
            ></div>

            <!-- CONTENT -->

            <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- LEFT -->

                    <div>
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-blue-200 backdrop-blur"
                        >
                            <Newspaper class="w-4 h-4" />
                            Portal Berita Resmi
                        </div>

                        <h1
                            class="mt-6 text-5xl lg:text-7xl font-black text-white leading-tight"
                        >
                            Berita dan Informasi
                            <span
                                class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent"
                            >
                                BEM UNIB
                            </span>
                        </h1>

                        <p
                            class="mt-6 text-xl text-slate-300 max-w-2xl leading-relaxed"
                        >
                            Publikasi resmi kegiatan, prestasi mahasiswa,
                            program kerja, serta informasi terkini Badan
                            Eksekutif Mahasiswa Universitas Ibrahimy.
                        </p>

                        <!-- STATS -->

                        <div class="mt-10 flex flex-wrap gap-8">
                            <div>
                                <div class="text-3xl font-black text-white">
                                    {{ stats.totalNews }}+
                                </div>

                                <div class="text-slate-400 text-sm">
                                    Artikel
                                </div>
                            </div>

                            <div>
                                <div class="text-3xl font-black text-white">
                                    {{ stats.totalCategories }}
                                </div>

                                <div class="text-slate-400 text-sm">
                                    Kategori
                                </div>
                            </div>

                            <div>
                                <div class="text-3xl font-black text-white">
                                    {{ stats.totalViews }}
                                </div>

                                <div class="text-slate-400 text-sm">Views</div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT -->

                    <div class="hidden lg:flex justify-end">
                        <div class="relative w-[500px]">
                            <!-- CARD 1 -->

                            <div
                                class="floating absolute top-0 left-0 w-72 p-5 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/10 shadow-2xl rotate-[-8deg]"
                            >
                                <div class="text-xs text-blue-300 mb-2">
                                    Berita Terbaru
                                </div>

                                <div class="font-bold text-white">
                                    Prestasi Mahasiswa Tingkat Nasional
                                </div>
                            </div>

                            <!-- CARD 2 -->

                            <div
                                class="floating absolute top-32 right-0 w-80 p-5 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/10 shadow-2xl rotate-[6deg]"
                            >
                                <div class="text-xs text-cyan-300 mb-2">
                                    Program Kerja
                                </div>

                                <div class="font-bold text-white">
                                    Pengabdian Masyarakat Bersama Mahasiswa
                                </div>
                            </div>

                            <!-- CARD 3 -->

                            <div
                                class="floating absolute top-64 left-10 w-72 p-5 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/10 shadow-2xl rotate-[-4deg]"
                            >
                                <div class="text-xs text-yellow-300 mb-2">
                                    Organisasi
                                </div>

                                <div class="font-bold text-white">
                                    Rapat Kerja Kabinet Transformasi
                                </div>
                            </div>

                            <div class="h-[420px]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Articles -->
        <section class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-6">
                <Link
                    :href="route('berita.public.show', featured[0].slug)"
                    class="group block"
                >
                    <div class="grid lg:grid-cols-2 gap-10 items-center">
                        <div class="aspect-[16/10] overflow-hidden rounded-3xl">
                            <img
                                :src="`/storage/${featured[0].thumbnail}`"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                            />
                        </div>

                        <div>
                            <span
                                class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold"
                            >
                                {{ featured[0].category }}
                            </span>

                            <h2
                                class="mt-4 text-4xl font-black text-slate-900 leading-tight"
                            >
                                {{ featured[0].title }}
                            </h2>

                            <p class="mt-5 text-lg text-slate-600 line-clamp-4">
                                {{
                                    stripHtml(featured[0].content).substring(
                                        0,
                                        250,
                                    )
                                }}
                            </p>

                            <div
                                class="mt-6 flex items-center gap-6 text-sm text-slate-500"
                            >
                                <span>{{ featured[0].user?.name }}</span>

                                <span>
                                    {{ formatDate(featured[0].created_at) }}
                                </span>

                                <span> {{ featured[0].views }} views </span>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </section>

        <!-- Search & Filter -->
        <section
            class="bg-white border-b border-gray-100 sticky top-20 lg:top-24 z-40 shadow-sm"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div
                    class="flex flex-col md:flex-row gap-4 items-center justify-between"
                >
                    <div class="flex gap-2 flex-wrap w-full md:w-auto">
                        <button
                            @click="filterByCategory('')"
                            class="px-4 py-2 rounded-xl text-sm font-medium transition-all"
                            :class="
                                selectedCategory === ''
                                    ? 'bg-blue-600 text-white shadow-md'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                            "
                        >
                            Semua
                        </button>
                        <button
                            v-for="cat in categories"
                            :key="cat"
                            @click="filterByCategory(cat)"
                            class="px-4 py-2 rounded-xl text-sm font-medium transition-all"
                            :class="
                                selectedCategory === cat
                                    ? 'bg-blue-600 text-white shadow-md'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                            "
                        >
                            {{ cat }}
                        </button>
                    </div>
                    <div class="relative w-full md:w-72">
                        <Search
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                        />
                        <input
                            v-model="search"
                            placeholder="Cari berita..."
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none text-sm transition-all"
                        />
                        <button
                            v-if="search"
                            @click="clearSearch"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                        >
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Berita Grid (Full Width, No Sidebar) -->
        <section class="bg-gray-50 py-10 lg:py-14 min-h-[50vh]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section title when filtering -->
                <div v-if="isFiltering" class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        Hasil
                        {{
                            search
                                ? `pencarian "${search}"`
                                : `kategori "${selectedCategory}"`
                        }}
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ beritas.total }} berita ditemukan
                    </p>
                </div>

                <!-- Empty State -->
                <div v-if="beritas.data.length === 0" class="text-center py-20">
                    <div
                        class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center"
                    >
                        <AlertCircle class="w-10 h-10 text-gray-300" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">
                        Belum ada berita
                    </h3>
                    <p class="text-gray-400">
                        Belum ada berita yang dipublikasikan saat ini.
                    </p>
                </div>

                <!-- Grid -->
                <template v-else>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link
                            v-for="berita in beritas.data"
                            :key="berita.id"
                            :href="route('berita.public.show', berita.slug)"
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5 border border-gray-100"
                        >
                            <div
                                class="aspect-[16/10] overflow-hidden bg-gray-50 relative"
                            >
                                <img
                                    v-if="berita.thumbnail"
                                    :src="`/storage/${berita.thumbnail}`"
                                    :alt="berita.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center"
                                >
                                    <Newspaper
                                        class="w-12 h-12 text-gray-200"
                                    />
                                </div>
                                <div
                                    class="absolute top-3 left-3 flex gap-1.5 flex-wrap"
                                >
                                    <span
                                        class="px-2.5 py-0.5 bg-blue-600/90 text-white text-[10px] font-semibold rounded-full backdrop-blur-sm"
                                    >
                                        {{ berita.category }}
                                    </span>
                                    <span
                                        v-if="berita.label?.length"
                                        v-for="(
                                            label, li
                                        ) in berita.label.slice(0, 2)"
                                        :key="li"
                                        class="px-2 py-0.5 bg-white/90 text-gray-700 text-[10px] rounded-full backdrop-blur-sm"
                                    >
                                        {{ label }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3
                                    class="text-base font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 mb-2.5"
                                >
                                    {{ berita.title }}
                                </h3>
                                <p
                                    class="text-gray-500 text-sm line-clamp-2 mb-4 leading-relaxed"
                                >
                                    {{
                                        stripHtml(berita.content).substring(
                                            0,
                                            120,
                                        )
                                    }}...
                                </p>
                                <div
                                    class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-gray-100"
                                >
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center gap-1"
                                            ><User class="w-3 h-3" />{{
                                                berita.user?.name
                                            }}</span
                                        >
                                        <span
                                            class="flex items-center gap-1 hidden sm:flex"
                                            ><Calendar class="w-3 h-3" />{{
                                                formatDate(berita.created_at)
                                            }}</span
                                        >
                                    </div>
                                    <span class="flex items-center gap-1"
                                        ><Eye class="w-3 h-3" />{{
                                            berita.views
                                        }}</span
                                    >
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="beritas.last_page > 1"
                        class="mt-12 flex justify-center"
                    >
                        <nav class="flex items-center gap-1.5">
                            <Link
                                v-if="beritas.prev_page_url"
                                :href="beritas.prev_page_url"
                                class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-sm font-medium"
                            >
                                Sebelumnya
                            </Link>
                            <template
                                v-for="(link, i) in beritas.links"
                                :key="i"
                            >
                                <span
                                    v-if="!link.url && !link.active"
                                    class="px-3 py-2 text-gray-300"
                                    >...</span
                                >
                                <Link
                                    v-else-if="link.url"
                                    :href="link.url"
                                    class="px-4 py-2 rounded-xl text-sm font-medium transition"
                                    :class="
                                        link.active
                                            ? 'bg-blue-600 text-white shadow-md'
                                            : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'
                                    "
                                    v-html="link.label"
                                />
                            </template>
                            <Link
                                v-if="beritas.next_page_url"
                                :href="beritas.next_page_url"
                                class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-sm font-medium"
                            >
                                Selanjutnya
                            </Link>
                        </nav>
                    </div>
                </template>
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
@keyframes float {
    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-12px);
    }
}

.floating {
    animation: float 6s ease-in-out infinite;
}
</style>
