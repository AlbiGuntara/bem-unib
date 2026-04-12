<template>
    <AppLayout>
        <Head title="Beranda | BEM UNIB" />

        <!-- HERO SECTION -->
        <section
            class="relative min-h-[90vh] bg-cover bg-right lg:bg-center overflow-hidden flex items-center"
            style="background-image: url(&quot;/images/bg-hero.png&quot;)"
        >
            <div class="relative z-20 max-w-7xl mx-auto w-full px-4 lg:px-8">
                <div
                    class="flex justify-center lg:justify-start"
                    data-aos="fade-up"
                    data-aos-duration="1000"
                >
                    <div class="text-center lg:text-left">
                        <img
                            src="/public/images/logo-kabinet.png"
                            class="w-56 lg:w-80 mx-auto lg:mx-0 drop-shadow-2xl"
                            alt="Logo Kabinet"
                        />

                        <div class="mt-6 text-center lg:text-left">
                            <p
                                class="text-white lg:text-gray-600 font-bold text-xl lg:text-3xl tracking-widest"
                            >
                                KABINET
                                <span class="text-yellow-600 animate-pulse">
                                    TRANSFORMASI
                                </span>
                            </p>

                            <p
                                class="text-white/80 lg:text-gray-600 text-lg lg:text-xl mt-3 font-light"
                            >
                                Badan Eksekutif Mahasiswa <br />
                                Universitas Ibrahimy
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SAMBUTAN SECTION -->
        <section class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16" data-aos="fade-up">
                    <span
                        class="text-green-600 font-semibold tracking-wider text-sm"
                        >SAPA HANGAT</span
                    >
                    <h2
                        class="text-4xl lg:text-5xl font-bold text-blue-900 mt-2 mb-4"
                    >
                        Sambutan Pimpinan
                    </h2>
                    <div
                        class="w-24 h-1.5 bg-gradient-to-r from-green-500 to-blue-600 mx-auto rounded-full"
                    ></div>
                </div>

                <div
                    class="grid lg:grid-cols-2 gap-16 items-center max-w-6xl mx-auto"
                >
                    <!-- FOTO SECTION (Modern Card) -->
                    <div data-aos="fade-right">
                        <div class="flex justify-center mb-8">
                            <div
                                class="bg-white p-1.5 rounded-full shadow-md flex gap-1 border border-gray-100"
                            >
                                <button
                                    @click="activeLeader = 'presiden'"
                                    :class="[
                                        'px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300',
                                        activeLeader === 'presiden'
                                            ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30'
                                            : 'text-gray-600 hover:bg-gray-100',
                                    ]"
                                >
                                    Presiden
                                </button>

                                <button
                                    @click="activeLeader = 'wapres'"
                                    :class="[
                                        'px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300',
                                        activeLeader === 'wapres'
                                            ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30'
                                            : 'text-gray-600 hover:bg-gray-100',
                                    ]"
                                >
                                    Wapres
                                </button>
                            </div>
                        </div>

                        <div
                            class="relative rounded-xl overflow-hidden shadow-2xl aspect-[3/4] w-full max-w-sm mx-auto"
                        >
                            <img
                                :src="currentLeader.photo"
                                class="w-full h-full object-cover"
                            />

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-blue-900 via-blue-900/60 to-transparent"
                            ></div>

                            <div
                                class="absolute bottom-0 left-0 p-8 text-white w-full"
                            >
                                <h3 class="text-3xl font-bold tracking-tight">
                                    {{ currentLeader.name }}
                                </h3>
                                <p
                                    class="text-green-400 font-medium text-lg mt-1"
                                >
                                    {{ currentLeader.position }}
                                </p>
                                <div
                                    class="w-12 h-1 bg-yellow-500 rounded-full mt-4"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <!-- SAMBUTAN TEXT -->
                    <div data-aos="fade-left">
                        <div class="relative">
                            <!-- Quote Icon -->
                            <svg
                                class="w-12 h-12 text-blue-200 mb-6"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"
                                />
                            </svg>

                            <h3 class="text-2xl font-bold text-blue-800 mb-6">
                                Sambutan {{ currentLeader.position }}
                            </h3>

                            <p
                                class="text-gray-700 leading-relaxed text-lg italic mb-8 border-l-4 border-yellow-500 pl-6"
                            >
                                "{{ currentLeader.message }}"
                            </p>

                            <div class="text-right lg:text-left">
                                <div class="text-gray-800 font-bold text-lg">
                                    {{ currentLeader.name }}
                                </div>
                                <div class="text-gray-500">
                                    {{ currentLeader.position }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TENTANG KAMI -->
        <section
            class="py-24 bg-gradient-to-br from-blue-900 via-blue-800 to-green-700 text-white relative overflow-hidden"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-0 left-0 w-full h-full bg-[url('/images/pattern.png')] bg-repeat"
                ></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4">
                <div class="text-center mb-16" data-aos="fade-up">
                    <span
                        class="text-green-300 font-semibold tracking-wider text-sm"
                        >PROFIL ORGANISASI</span
                    >
                    <h2 class="text-4xl lg:text-5xl font-bold mt-2 mb-4">
                        Tentang Kami
                    </h2>
                    <div
                        class="w-24 h-1.5 bg-yellow-500 mx-auto rounded-full"
                    ></div>
                </div>
                <div class="grid lg:grid-cols-2 gap-12">
                    <div data-aos="fade-right">
                        <h3 class="text-2xl font-bold mb-4 text-yellow-400">
                            BEM UNIB
                        </h3>

                        <p class="text-white/90 leading-relaxed mb-6">
                            Badan Eksekutif Mahasiswa Universitas Ibrahimy (BEM
                            UNIB) adalah organisasi kemahasiswaan yang bertugas
                            menjalankan fungsi eksekutif.
                        </p>

                        <h3 class="text-2xl font-bold mb-4 text-yellow-400">
                            Kabinet Transformasi
                        </h3>

                        <p class="text-white/90 leading-relaxed">
                            Kabinet Transformasi hadir sebagai wujud semangat
                            perubahan menuju organisasi yang adaptif dan
                            inovatif.
                        </p>
                    </div>

                    <div data-aos="fade-left">
                        <h3 class="text-2xl font-bold mb-4 text-yellow-400">
                            Visi & Misi
                        </h3>

                        <div class="space-y-4">
                            <div
                                class="bg-white/10 rounded-xl p-4 backdrop-blur-sm"
                            >
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-2 h-2 bg-yellow-500 rounded-full mt-2"
                                    ></div>

                                    <div>
                                        <h4 class="font-bold text-lg mb-2">
                                            Visi
                                        </h4>

                                        <p class="text-white/90">
                                            Menjadi lembaga eksekutif mahasiswa
                                            yang progresif, inovatif dan
                                            religius.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-white/10 rounded-xl p-4 backdrop-blur-sm"
                            >
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-2 h-2 bg-yellow-500 rounded-full mt-2"
                                    ></div>

                                    <div>
                                        <h4 class="font-bold text-lg mb-2">
                                            Misi
                                        </h4>

                                        <ul
                                            class="list-disc list-inside text-white/90 space-y-1"
                                        >
                                            <li>
                                                Meningkatkan kualitas akademik
                                            </li>
                                            <li>
                                                Memperjuangkan aspirasi
                                                mahasiswa
                                            </li>
                                            <li>Mengembangkan kepemimpinan</li>
                                            <li>
                                                Mewujudkan sinergi mahasiswa
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- KEGIATAN & BERITA -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <span
                        class="text-green-600 font-semibold tracking-wider text-sm"
                        >UPDATE TERKINI</span
                    >
                    <h2
                        class="text-4xl lg:text-5xl font-bold text-blue-900 mt-2 mb-4"
                    >
                        Kegiatan & Berita
                    </h2>
                    <div
                        class="w-24 h-1.5 bg-gradient-to-r from-green-500 to-blue-600 mx-auto rounded-full"
                    ></div>
                </div>

                <!-- Highlight Kegiatan Sebelumnya -->
                <div class="mb-20">
                    <h3
                        class="text-2xl font-bold text-blue-800 mb-8 flex items-center gap-2"
                    >
                        Highlight Kegiatan
                    </h3>

                    <div class="relative event-swiper-wrapper">
                        <!-- Navigation Button -->
                        <div class="swiper-button-prev custom-nav"></div>
                        <div class="swiper-button-next custom-nav"></div>

                        <Swiper
                            :modules="[
                                Navigation,
                                Pagination,
                                Autoplay,
                                EffectCoverflow,
                            ]"
                            effect="coverflow"
                            :grab-cursor="true"
                            :centered-slides="true"
                            :loop="true"
                            :slides-per-view="'auto'"
                            :coverflow-effect="{
                                rotate: 0,
                                stretch: 0,
                                depth: 150,
                                modifier: 2,
                                slideShadows: false,
                            }"
                            :navigation="{
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            }"
                            :pagination="{ clickable: true }"
                            :autoplay="{
                                delay: 3500,
                                disableOnInteraction: false,
                            }"
                            class="pb-14"
                        >
                            <SwiperSlide
                                v-for="(event, index) in pastEvents"
                                :key="index"
                                class="custom-slide"
                            >
                                <!-- MOBILE VERSION -->
                                <div
                                    class="lg:hidden bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 h-full flex flex-col"
                                >
                                    <!-- Image -->
                                    <div class="h-40 overflow-hidden">
                                        <img
                                            :src="event.image"
                                            class="w-full h-full object-cover hover:scale-105 transition duration-500"
                                        />
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6 mb-4 flex flex-col flex-1">
                                        <h4
                                            class="font-bold text-lg text-blue-900 mb-2"
                                        >
                                            {{ event.title }}
                                        </h4>

                                        <p
                                            class="text-gray-500 text-sm mb-2 flex items-center gap-1"
                                        >
                                            <MapPin class="w-4 h-4" />
                                            {{ event.location }}
                                        </p>

                                        <p class="text-gray-600 text-sm mb-3">
                                            {{ event.date }}
                                        </p>

                                        <p
                                            class="text-gray-600 text-sm line-clamp-3 flex-grow"
                                        >
                                            {{ event.description }}
                                        </p>
                                    </div>
                                </div>

                                <!-- DESKTOP VERSION -->
                                <div
                                    class="hidden lg:block relative h-[220px] rounded-[20px] overflow-hidden cursor-pointer group transition-all duration-400 ease-in-out"
                                >
                                    <!-- Image -->
                                    <img
                                        :src="event.image"
                                        class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110"
                                    />

                                    <!-- Overlay -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent text-white p-5 flex flex-col justify-end opacity-0 translate-y-5 transition-all duration-400 ease-in-out group-hover:opacity-100 group-hover:translate-y-0"
                                    >
                                        <h4 class="font-bold text-[16px]">
                                            {{ event.title }}
                                        </h4>

                                        <p class="text-[12px] opacity-80 mb-1">
                                            {{ event.date }}
                                        </p>

                                        <p class="text-[13px] leading-[1.4]">
                                            {{ event.description }}
                                        </p>
                                    </div>
                                </div>
                            </SwiperSlide>
                        </Swiper>
                    </div>
                </div>

                <!-- UPCOMING EVENT CARD -->
                <div
                    class="mb-20 bg-gradient-to-r from-blue-700 to-green-600 text-white rounded-3xl p-8 lg:p-12 shadow-xl relative overflow-hidden"
                >
                    <div class="grid lg:grid-cols-3 gap-8 items-center">
                        <!-- Info Event -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center gap-2 mb-3">
                                <Calendar class="w-5 h-5" />
                                <span
                                    class="uppercase tracking-wider text-sm font-semibold"
                                    >Kegiatan Mendatang</span
                                >
                            </div>

                            <h3 class="text-3xl lg:text-4xl font-bold mb-2">
                                {{ nextEvent.title }}
                            </h3>

                            <p
                                class="flex items-center gap-2 text-blue-100 mb-1"
                            >
                                <MapPin class="w-4 h-4" />
                                {{ nextEvent.location }}
                            </p>

                            <p class="text-blue-100 mb-6">
                                {{ nextEvent.date }}
                            </p>

                            <a
                                href="/program-kerja"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-700 font-semibold rounded-full hover:scale-105 transition"
                            >
                                Lihat Kalender Kegiatan
                                <ChevronRight class="w-4 h-4" />
                            </a>
                        </div>

                        <!-- Countdown -->
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center border border-white/20"
                        >
                            <p
                                class="text-sm uppercase tracking-wider mb-4 text-blue-100"
                                v-if="!isOngoing"
                            >
                                Hitung Mundur
                            </p>

                            <div
                                v-if="isOngoing"
                                class="text-2xl font-bold text-white animate-pulse"
                            >
                                Acara Sedang Berlangsung
                            </div>

                            <div v-else class="flex justify-center gap-6">
                                <div>
                                    <p class="text-4xl font-bold">
                                        {{ countdown.days }}
                                    </p>
                                    <span class="text-sm text-blue-100"
                                        >Hari</span
                                    >
                                </div>

                                <div>
                                    <p class="text-4xl font-bold">
                                        {{ countdown.hours }}
                                    </p>
                                    <span class="text-sm text-blue-100"
                                        >Jam</span
                                    >
                                </div>

                                <div>
                                    <p class="text-4xl font-bold">
                                        {{ countdown.minutes }}
                                    </p>
                                    <span class="text-sm text-blue-100"
                                        >Menit</span
                                    >
                                </div>

                                <div>
                                    <p class="text-4xl font-bold">
                                        {{ countdown.seconds }}
                                    </p>
                                    <span class="text-sm text-blue-100"
                                        >Detik</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- News Grid -->
                <div>
                    <h3 class="text-2xl font-bold text-blue-800 mb-8">
                        Berita Terbaru
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div
                            v-for="(news, index) in newsList"
                            :key="index"
                            class="group bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300"
                        >
                            <div
                                class="h-48 bg-gray-200 relative overflow-hidden"
                            >
                                <img
                                    :src="news.image"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                />
                                <span
                                    class="absolute top-4 left-4 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full"
                                    >{{ news.category }}</span
                                >
                            </div>
                            <div class="p-6">
                                <p class="text-gray-500 text-sm mb-2">
                                    {{ news.date }}
                                </p>
                                <h4
                                    class="font-bold text-xl text-blue-900 mb-2 group-hover:text-blue-600 transition-colors"
                                >
                                    {{ news.title }}
                                </h4>
                                <p class="text-gray-600 line-clamp-3">
                                    {{ news.excerpt }}
                                </p>
                                <button
                                    class="mt-4 text-blue-600 font-semibold flex items-center gap-1 hover:gap-2 transition-all"
                                >
                                    Baca Selengkapnya
                                    <ChevronRight class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-16">
                    <button
                        class="px-10 py-4 bg-gradient-to-r from-blue-700 to-green-600 text-white rounded-full hover:from-blue-800 hover:to-green-700 transition-all duration-300 font-bold text-lg shadow-lg shadow-blue-700/20 hover:scale-105"
                    >
                        Lihat Semua Berita
                    </button>
                </div>
            </div>
        </section>

        <!-- KOLABORASI -->
        <section class="py-28 bg-blue-900 text-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold mb-4">
                    Kolaborasi & Kemitraan
                </h2>

                <div
                    class="w-24 h-1.5 bg-green-400 mx-auto rounded-full mb-6"
                ></div>

                <p class="text-white/70 max-w-2xl mx-auto">
                    BEM Universitas aktif menjalin kolaborasi dengan berbagai
                    organisasi mahasiswa baik internal maupun eksternal untuk
                    menciptakan kegiatan yang berdampak luas.
                </p>
            </div>

            <!-- EXTERNAL -->
            <div class="relative overflow-hidden w-full mb-12 logo-marquee">
                <div class="flex w-max animate-scroll logo-track">
                    <div
                        v-for="(logo, index) in externalPartners"
                        :key="'ext1' + index"
                        class="w-[150px] h-[150px] mx-6 flex items-center justify-center bg-white/10 backdrop-blur-md rounded-xl border border-white/20"
                    >
                        <img
                            :src="logo"
                            class="max-h-[90px] object-contain drop-shadow-lg"
                        />
                    </div>

                    <!-- DUPLICATE -->
                    <div
                        v-for="(logo, index) in externalPartners"
                        :key="'ext2' + index"
                        class="w-[150px] h-[150px] mx-6 flex items-center justify-center bg-white/10 backdrop-blur-md rounded-xl border border-white/20"
                    >
                        <img
                            :src="logo"
                            class="max-h-[90px] object-contain drop-shadow-lg"
                        />
                    </div>
                </div>
            </div>

            <!-- INTERNAL -->
            <div class="relative overflow-hidden w-full logo-marquee reverse">
                <div class="flex w-max animate-scroll-reverse logo-track">
                    <div
                        v-for="(logo, index) in internalPartners"
                        :key="'int1' + index"
                        class="w-[150px] h-[150px] mx-6 flex items-center justify-center bg-white/10 backdrop-blur-md rounded-xl border border-white/20"
                    >
                        <img
                            :src="logo"
                            class="max-h-[90px] object-contain drop-shadow-lg"
                        />
                    </div>

                    <!-- DUPLICATE -->
                    <div
                        v-for="(logo, index) in internalPartners"
                        :key="'int2' + index"
                        class="w-[150px] h-[150px] mx-6 flex items-center justify-center bg-white/10 backdrop-blur-md rounded-xl border border-white/20"
                    >
                        <img
                            :src="logo"
                            class="max-h-[90px] object-contain drop-shadow-lg"
                        />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
    background: #1f6dad;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: #0f3757;
}

/* Swiper Customization */
.event-swiper-wrapper {
    position: relative;
    padding: 0 50px;
}

/* Custom Navigation */
/* Navigation wrapper */
.swiper-button-prev,
.swiper-button-next {
    width: auto !important;
    height: auto !important;
    color: #1f6dad !important;
    transition: all 0.35s ease;
}

/* Hilangkan default icon */
.swiper-button-prev::after,
.swiper-button-next::after {
    font-size: 26px !important;
    font-weight: 300;
}

/* Hover effect */
.swiper-button-prev:hover,
.swiper-button-next:hover {
    color: #0f3757 !important;
    transform: scale(1.15);
}

/* Posisi tombol */
.swiper-button-prev {
    left: -10px !important;
}

.swiper-button-next {
    right: -10px !important;
}

/* posisi tombol */
.swiper-button-prev {
    left: 0 !important;
}

.swiper-button-next {
    right: 0 !important;
}

/* Pagination */
.swiper-pagination-bullet {
    background: #1e3a8a !important;
    opacity: 0.4;
}
.swiper-pagination-bullet-active {
    background: #16a34a !important;
    opacity: 1;
}

/* Slide ukuran auto biar bisa landscape */
.custom-slide {
    width: 260px;
}

@media (min-width: 1024px) {
    .custom-slide {
        width: 420px; /* LANDSCAPE */
    }
}

/* Efek slide samping */
.swiper-slide {
    transition: all 0.4s ease;
    opacity: 0.5;
    transform: scale(0.85);
}

/* Slide aktif (tengah) */
.swiper-slide-active {
    opacity: 1;
    transform: scale(1);
    z-index: 10;
}

/* Sedikit efek ke tetangga */
.swiper-slide-next,
.swiper-slide-prev {
    opacity: 0.7;
    transform: scale(0.9);
}

/* FADE EDGE */
.logo-marquee::before,
.logo-marquee::after {
    content: "";
    position: absolute;
    top: 0;
    width: 120px;
    height: 100%;
    z-index: 2;
}

.logo-marquee::before {
    left: 0;
    background: linear-gradient(to right, #1e3a8a, transparent);
}

.logo-marquee::after {
    right: 0;
    background: linear-gradient(to left, #1e3a8a, transparent);
}

/* ANIMATION */
@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

@keyframes scrollReverse {
    0% {
        transform: translateX(-50%);
    }
    100% {
        transform: translateX(0);
    }
}

.animate-scroll {
    animation: scroll 28s linear infinite;
}

.animate-scroll-reverse {
    animation: scrollReverse 28s linear infinite;
}

/* Line Clamp Utility */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script setup>
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";
import { Calendar, ChevronRight, MapPin } from "lucide-vue-next";

// SwiperJS
import { Swiper, SwiperSlide } from "swiper/vue";
import {
    Navigation,
    Pagination,
    Autoplay,
    EffectCoverflow,
} from "swiper/modules";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/effect-coverflow";

// Sambutan
const activeLeader = ref("presiden");

const leaders = {
    presiden: {
        name: "Baitul Anam",
        position: "Presiden BEM UNIB",
        photo: "/images/profile33.png",
        message:
            "Assalamu'alaikum warahmatullahi wabarakatuh. Mari kita bersama-sama membangun organisasi yang bermanfaat bagi seluruh mahasiswa Universitas Ibrahimy. Transformasi bukan hanya slogan, tapi komitmen kita bersama untuk terus berkembang.",
    },

    wapres: {
        name: "Aisyah Putri",
        position: "Wakil Presiden BEM UNIB",
        photo: "/public/images/default-avatar.jpg",
        message:
            "Wahai jiwa yang tenang, kembalilah kepada Tuhanmu dengan hati yang ridha dan diridhai. Mari jadikan BEM sebagai rumah kedua yang penuh manfaat dan keberkahan bagi kita semua.",
    },
};

const currentLeader = computed(() => leaders[activeLeader.value]);

// Data upcoming events
const nextEvent = ref({
    title: "Seminar Kebangsaan",
    location: "Auditorium PP. Salafiyah Syafi'iyah",
    date: "15 April 2026",
    targetDate: "2026-04-15 08:00:00",
    endDate: "2026-04-15 23:59:59",
});

const countdown = ref({
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
});

const isOngoing = ref(false);

const updateCountdown = () => {
    const now = new Date().getTime();
    const start = new Date(nextEvent.value.targetDate).getTime();
    const end = new Date(nextEvent.value.endDate).getTime();

    // Acara sedang berlangsung
    if (now >= start && now <= end) {
        isOngoing.value = true;
        return;
    }

    isOngoing.value = false;

    // Jika acara sudah lewat
    if (now > end) {
        countdown.value.days = 0;
        countdown.value.hours = 0;
        countdown.value.minutes = 0;
        countdown.value.seconds = 0;
        return;
    }

    // Countdown sebelum acara
    const diff = start - now;

    countdown.value.days = Math.floor(diff / (1000 * 60 * 60 * 24));
    countdown.value.hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
    countdown.value.minutes = Math.floor((diff / 1000 / 60) % 60);
    countdown.value.seconds = Math.floor((diff / 1000) % 60);
};

onMounted(() => {
    updateCountdown();
    setInterval(updateCountdown, 1000);
});

// Data past events
const pastEvents = ref([
    {
        title: "Orientasi Mahasiswa Baru 2024",
        date: "20-25 Feb 2024",
        description:
            "Kegiatan pengenalan kehidupan kampus bagi mahasiswa baru UNIB.",
        participants: "500+",
        location: "Kampus UNIB",
        image: "/images/bg-mesjid.jpg",
    },
    {
        title: "Pelatihan Kepemimpinan",
        date: "10-12 Mar 2024",
        description:
            "Program pengembangan soft skill dan leadership bagi pengurus BEM.",
        participants: "100+",
        location: "Gedung Serbaguna",
        image: "/images/bg-login.jpg",
    },
    {
        title: "Diskusi Publik: Peran Mahasiswa",
        date: "5 Mar 2024",
        description:
            "Diskusi interaktif mengenai peran strategis mahasiswa di era digital.",
        participants: "200+",
        location: "Auditorium",
        image: "/images/kas-umum-green.jpeg",
    },
    {
        title: "Orientasi Mahasiswa Baru 2024",
        date: "20-25 Feb 2024",
        description:
            "Kegiatan pengenalan kehidupan kampus bagi mahasiswa baru UNIB.",
        participants: "500+",
        location: "Kampus UNIB",
        image: "/images/bg-mesjid.jpg",
    },
    {
        title: "Pelatihan Kepemimpinan",
        date: "10-12 Mar 2024",
        description:
            "Program pengembangan soft skill dan leadership bagi pengurus BEM.",
        participants: "100+",
        location: "Gedung Serbaguna",
        image: "/images/bg-login.jpg",
    },
    {
        title: "Diskusi Publik: Peran Mahasiswa",
        date: "5 Mar 2024",
        description:
            "Diskusi interaktif mengenai peran strategis mahasiswa di era digital.",
        participants: "200+",
        location: "Auditorium",
        image: "/images/kas-umum-green.jpeg",
    },
]);

// Data news
const newsList = ref([
    {
        title: "BEM UNIB Gelar Pengabdian Masyarakat",
        date: "25 Maret 2024",
        category: "Kegiatan",
        excerpt:
            "BEM UNIB bersama mahasiswa mengadakan kegiatan bakti sosial di Desa sekitar kampus...",
        image: "/images/bg-mesjid.jpg",
    },
    {
        title: "Rapat Kerja Kabinet Transformasi",
        date: "20 Maret 2024",
        category: "Organisasi",
        excerpt:
            "Seluruh pengurus BEM UNIB mengadakan rapat kerja untuk menyusun program kerja...",
        image: "/images/bg-login.jpg",
    },
    {
        title: "Prestasi Mahasiswa di Tingkat Nasional",
        date: "15 Maret 2024",
        category: "Prestasi",
        excerpt:
            "Mahasiswa UNIB berhasil meraih juara 1 dalam lomba karya tulis ilmiah nasional...",
        image: "/images/kas-umum-green.jpeg",
    },
]);

// Data partners
const externalPartners = ref([
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
]);

const internalPartners = ref([
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
    "/images/logo-bem.png",
]);

onMounted(() => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100,
    });
});
</script>
