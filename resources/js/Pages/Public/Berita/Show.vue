<script setup>
import { ref, computed, onMounted } from "vue";
import SeoHead from "@/Components/SeoHead.vue";
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import AOS from "aos";
import "aos/dist/aos.css";

import {
    Calendar,
    User,
    Eye,
    ArrowLeft,
    Clock,
    Tag,
    ChevronRight,
    Newspaper,
    Share2,
    Check,
    Link2,
    Sparkles,
} from "lucide-vue-next";

const props = defineProps({
    berita: Object,
    related: Array,
    latest: Array,
    popular: Array,
    categories: Array,
    isPreview: Boolean,
});

const shareCopied = ref(false);

const shareUrl = computed(() => {
    if (typeof window !== "undefined") {
        return window.location.href;
    }
    return "";
});

const shareText = computed(() => {
    return `${props.berita.title} - BEM UNIB`;
});

function shareFacebook() {
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl.value)}`,
        "_blank",
        "width=600,height=400",
    );
}

function shareTwitter() {
    window.open(
        `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText.value)}&url=${encodeURIComponent(shareUrl.value)}`,
        "_blank",
        "width=600,height=400",
    );
}

function shareWhatsApp() {
    window.open(
        `https://wa.me/?text=${encodeURIComponent(shareText.value + " " + shareUrl.value)}`,
        "_blank",
        "width=600,height=400",
    );
}

function shareTelegram() {
    window.open(
        `https://t.me/share/url?url=${encodeURIComponent(shareUrl.value)}&text=${encodeURIComponent(shareText.value)}`,
        "_blank",
        "width=600,height=400",
    );
}

function copyLink() {
    navigator.clipboard.writeText(shareUrl.value).then(() => {
        shareCopied.value = true;
        setTimeout(() => {
            shareCopied.value = false;
        }, 2000);
    });
}

function stripHtml(html) {
    const div = document.createElement("div");
    div.innerHTML = html;
    return div.textContent || div.innerText || "";
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
}

function formatTime(dateStr) {
    return new Date(dateStr).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
}

onMounted(() => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 50,
    });
});
</script>

<template>
    <AppLayout>
        <SeoHead :title="berita.title" :description="stripHtml(berita.content).substring(0, 160)" :image="berita.thumbnail ? `/storage/${berita.thumbnail}` : ''" :url="typeof window !== 'undefined' ? window.location.href : ''" type="article" />

        <!-- Hero -->
        <section class="relative overflow-hidden min-h-[75vh] flex items-end">
            <!-- BACKGROUND IMAGE -->

            <img
                v-if="berita.thumbnail"
                :src="`/storage/${berita.thumbnail}`"
                :alt="berita.title"
                class="absolute inset-0 w-full h-full object-cover scale-105 animate-hero"
            />

            <!-- DARK OVERLAY -->

            <div
                class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-slate-950/30"
            ></div>

            <!-- BLUE OVERLAY -->

            <div
                class="absolute inset-0 bg-gradient-to-r from-blue-950/60 via-transparent to-transparent"
            ></div>

            <!-- DECORATION -->

            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500/20 blur-3xl rounded-full"
            ></div>

            <div
                class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-cyan-500/10 blur-3xl rounded-full"
            ></div>

            <!-- CONTENT -->

            <div
                class="relative z-10 max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 pb-16 pt-32"
                data-aos="fade-up"
                data-aos-duration="1000"
            >
                <!-- PREVIEW -->

                <div
                    v-if="isPreview"
                    class="inline-flex items-center gap-2 mb-5 px-4 py-2 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 backdrop-blur"
                >
                    <Eye class="w-4 h-4" />
                    Mode Preview Admin
                </div>

                <!-- CATEGORY -->

                <div class="flex flex-wrap gap-2 mb-5">
                    <span
                        class="px-4 py-1.5 rounded-full bg-blue-600 text-white text-sm font-semibold shadow-lg"
                    >
                        {{ berita.category }}
                    </span>

                    <span
                        v-for="(label, i) in berita.label"
                        :key="i"
                        class="px-3 py-1 rounded-full bg-white/10 text-white/80 text-xs backdrop-blur"
                    >
                        #{{ label }}
                    </span>
                </div>

                <!-- TITLE -->

                <h1
                    class="max-w-5xl text-4xl md:text-5xl xl:text-6xl font-black text-white leading-tight"
                >
                    {{ berita.title }}
                </h1>

                <!-- META -->

                <div
                    class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-white/80"
                >
                    <div class="flex items-center gap-2">
                        <User class="w-4 h-4 text-blue-300" />
                        {{ berita.user?.name }}
                    </div>

                    <div class="flex items-center gap-2">
                        <Calendar class="w-4 h-4 text-blue-300" />
                        {{ formatDate(berita.created_at) }}
                    </div>

                    <div class="flex items-center gap-2">
                        <Clock class="w-4 h-4 text-blue-300" />
                        {{ formatTime(berita.created_at) }}
                    </div>

                    <div class="flex items-center gap-2">
                        <Eye class="w-4 h-4 text-blue-300" />
                        {{ berita.views }} views
                    </div>
                </div>
            </div>
        </section>

        <!-- Content + Sidebar -->
        <section class="bg-gray-50 py-10 lg:py-14">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-10">
                    <!-- Main Content -->
                    <div class="lg:col-span-2" data-aos="fade-up" data-aos-duration="800">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
                        >
                            <div class="p-6 lg:p-8">
                                <article
                                    class="article-content"
                                    v-html="berita.content"
                                ></article>

                                <div
                                    v-if="berita.label?.length"
                                    class="mt-10 pt-8 border-t border-gray-100"
                                >
                                    <div
                                        class="flex items-center gap-2 flex-wrap"
                                    >
                                        <Tag class="w-4 h-4 text-gray-400" />
                                        <span
                                            v-for="(label, i) in berita.label"
                                            :key="i"
                                            class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full"
                                        >
                                            {{ label }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Share -->
                                <div
                                    class="mt-10 pt-8 border-t border-gray-100"
                                >
                                    <div class="flex items-center gap-2 mb-4">
                                        <Share2 class="w-4 h-4 text-gray-500" />
                                        <span
                                            class="text-sm font-semibold text-gray-700"
                                        >
                                            Bagikan
                                        </span>
                                    </div>
                                    <div
                                        class="flex items-center gap-3 flex-wrap"
                                    >
                                        <button
                                            @click="shareWhatsApp"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-green-500 hover:bg-green-600 transition"
                                            title="WhatsApp"
                                        >
                                            <svg
                                                class="w-5 h-5 text-white"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            @click="shareTelegram"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-500 hover:bg-blue-600 transition"
                                            title="Telegram"
                                        >
                                            <svg
                                                class="w-5 h-5 text-white"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            @click="shareFacebook"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 transition"
                                            title="Facebook"
                                        >
                                            <svg
                                                class="w-5 h-5 text-white"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            @click="shareTwitter"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-black hover:bg-gray-800 transition"
                                            title="X (Twitter)"
                                        >
                                            <svg
                                                class="w-4 h-4 text-white"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            @click="copyLink"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-200 hover:bg-gray-300 transition"
                                            title="Salin Tautan"
                                        >
                                            <Check
                                                v-if="shareCopied"
                                                class="w-4 h-4 text-green-600"
                                            />
                                            <Link2
                                                v-else
                                                class="w-4 h-4 text-gray-600"
                                            />
                                        </button>
                                    </div>
                                </div>

                                <div
                                    class="mt-10 pt-8 border-t border-gray-100 flex flex-wrap gap-3"
                                >
                                    <Link
                                        :href="
                                            isPreview
                                                ? route('berita.index')
                                                : route('berita.public.index')
                                        "
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium"
                                    >
                                        <ArrowLeft class="w-4 h-4" />
                                        {{
                                            isPreview
                                                ? "Kembali ke Admin"
                                                : "Kembali ke Berita"
                                        }}
                                    </Link>

                                    <Link
                                        v-if="isPreview"
                                        :href="route('berita.edit', berita.id)"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition font-medium"
                                    >
                                        Edit Berita
                                        <ChevronRight class="w-4 h-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <aside class="space-y-8 lg:sticky lg:top-28 lg:self-start" data-aos="fade-left" data-aos-duration="800">
                        <!-- Categories -->
                        <div
                            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
                            data-aos="fade-up"
                            data-aos-duration="600"
                        >
                            <h3
                                class="font-bold text-gray-900 mb-4 flex items-center gap-2"
                            >
                                <span
                                    class="w-1 h-5 bg-blue-600 rounded-full"
                                ></span>
                                Kategori
                            </h3>
                            <div class="space-y-1.5">
                                <Link
                                    :href="
                                        route('berita.public.index', {
                                            category: cat,
                                        })
                                    "
                                    v-for="cat in categories"
                                    :key="cat"
                                    class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-blue-50 transition group text-gray-600"
                                >
                                    <span
                                        class="text-sm font-medium group-hover:text-blue-600"
                                        >{{ cat }}</span
                                    >
                                    <ChevronRight
                                        class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition"
                                    />
                                </Link>
                            </div>
                        </div>

                        <!-- Latest News -->
                        <div
                            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
                            data-aos="fade-up"
                            data-aos-delay="100"
                            data-aos-duration="600"
                        >
                            <h3
                                class="font-bold text-gray-900 mb-4 flex items-center gap-2"
                            >
                                <span
                                    class="w-1 h-5 bg-blue-600 rounded-full"
                                ></span>
                                Berita Terbaru
                            </h3>
                            <div class="space-y-4">
                                <Link
                                    v-for="item in latest"
                                    :key="item.id"
                                    :href="
                                        route('berita.public.show', item.slug)
                                    "
                                    class="flex gap-3 group"
                                >
                                    <div
                                        class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0"
                                    >
                                    <img
                                        v-if="item.thumbnail"
                                        :src="`/storage/${item.thumbnail}`"
                                        :alt="item.title"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-full flex items-center justify-center"
                                    >
                                        <Newspaper
                                            class="w-5 h-5 text-gray-300"
                                        />
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2"
                                    >
                                        {{ item.title }}
                                    </h4>
                                    <div
                                        class="flex items-center gap-2 text-xs text-gray-400 mt-1"
                                    >
                                        <span>{{
                                            formatDate(item.created_at)
                                        }}</span>
                                        <span>·</span>
                                        <span
                                            class="flex items-center gap-0.5"
                                            ><Eye class="w-3 h-3" />{{
                                                item.views
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Related News -->
                    <div
                        v-if="related.length"
                        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
                        data-aos="fade-up"
                        data-aos-delay="200"
                        data-aos-duration="600"
                    >
                        <h3
                            class="font-bold text-gray-900 mb-4 flex items-center gap-2"
                        >
                            <span
                                class="w-1 h-5 bg-blue-600 rounded-full"
                            ></span>
                            Artikel Terkait
                        </h3>
                        <div class="space-y-4">
                            <Link
                                v-for="item in related"
                                :key="item.id"
                                :href="
                                    route('berita.public.show', item.slug)
                                "
                                class="flex gap-3 group"
                            >
                                <div
                                    class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0"
                                >
                                    <img
                                        v-if="item.thumbnail"
                                        :src="`/storage/${item.thumbnail}`"
                                        :alt="item.title"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-full flex items-center justify-center"
                                    >
                                        <Newspaper
                                            class="w-5 h-5 text-gray-300"
                                        />
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2"
                                    >
                                        {{ item.title }}
                                    </h4>
                                    <span
                                        class="text-xs text-gray-400 mt-1 block"
                                    >
                                        {{ formatDate(item.created_at) }}
                                    </span>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Popular News -->
                        <div
                            v-if="popular.length"
                            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
                            data-aos="fade-up"
                            data-aos-delay="300"
                            data-aos-duration="600"
                        >
                            <h3
                                class="font-bold text-gray-900 mb-4 flex items-center gap-2"
                            >
                                <span
                                    class="w-1 h-5 bg-blue-600 rounded-full"
                                ></span>
                                Populer
                            </h3>
                            <div class="space-y-4">
                                <Link
                                    v-for="(item, i) in popular"
                                    :key="item.id"
                                    :href="
                                        route('berita.public.show', item.slug)
                                    "
                                    class="flex gap-3 group"
                                >
                                    <div
                                        class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center font-bold text-sm"
                                        :class="
                                            i === 0
                                                ? 'bg-blue-600 text-white'
                                                : i === 1
                                                  ? 'bg-blue-500 text-white'
                                                  : i === 2
                                                    ? 'bg-blue-400 text-white'
                                                    : 'bg-gray-100 text-gray-400'
                                        "
                                    >
                                        {{ i + 1 }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2"
                                        >
                                            {{ item.title }}
                                        </h4>
                                        <span
                                            class="text-xs text-gray-400 mt-0.5 block"
                                        >
                                            {{ item.views }} dilihat
                                        </span>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<style>
@keyframes heroZoom {
    from {
        transform: scale(1.05);
    }
    to {
        transform: scale(1.12);
    }
}

.animate-hero {
    animation: heroZoom 20s ease-in-out infinite alternate;
}

.article-content {
    max-width: none;
    line-height: 1.9;
    font-size: 1.1rem;
    color: rgb(55 65 81);
}

.article-content p {
    margin: 1.5rem 0;
}

.article-content h1 {
    font-size: 2.25rem;
    font-weight: 800;
    line-height: 1.2;
    margin-top: 3rem;
    margin-bottom: 1.5rem;
    color: rgb(17 24 39);
}

.article-content h2 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    color: rgb(17 24 39);
}

.article-content h3 {
    font-size: 1.4rem;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: rgb(17 24 39);
}

.article-content img {
    width: 100%;
    border-radius: 16px;
    margin: 2rem 0;
    box-shadow:
        0 10px 25px rgba(0, 0, 0, 0.08),
        0 4px 10px rgba(0, 0, 0, 0.05);
}

.article-content blockquote {
    margin: 2rem 0;
    padding: 1.25rem 1.5rem;
    border-left: 4px solid #2563eb;
    background: #eff6ff;
    border-radius: 12px;
    font-style: italic;
}

.article-content ul {
    list-style: disc;
    padding-left: 1.8rem;
    margin: 1.5rem 0;
}

.article-content ol {
    list-style: decimal;
    padding-left: 1.8rem;
    margin: 1.5rem 0;
}

.article-content li {
    margin: 0.5rem 0;
}

.article-content a {
    color: #2563eb;
    text-decoration: underline;
    font-weight: 500;
}

.article-content a:hover {
    opacity: 0.8;
}

.article-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    overflow: hidden;
    border-radius: 12px;
}

.article-content table th {
    background: #2563eb;
    color: white;
    padding: 12px;
    text-align: left;
}

.article-content table td {
    padding: 12px;
    border: 1px solid #e5e7eb;
}

.article-content pre {
    background: #0f172a;
    color: #f8fafc;
    padding: 1rem;
    border-radius: 12px;
    overflow-x: auto;
    margin: 2rem 0;
}

.article-content code {
    background: #f3f4f6;
    padding: 2px 6px;
    border-radius: 6px;
}

.article-content hr {
    margin: 3rem 0;
    border: none;
    border-top: 1px solid #e5e7eb;
}
</style>
