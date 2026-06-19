<script setup>
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, onUnmounted } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";
import {
    Calendar,
    ChevronRight,
    MapPin,
    Mail,
    Instagram,
    Facebook,
    Youtube,
    CalendarDays,
    User,
} from "lucide-vue-next";

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

let countdownInterval = null;

// Sambutan
const activeLeader = ref("presiden");

const leaders = {
    presiden: {
        name: "Baitul Anam",
        position: "Presiden BEM UNIB",
        photo: "/images/profile33.png",
        message: `
            <p><strong>Assalamu’alaikum warahmatullahi wabarakatuh,</strong></p>

            <p>Salam sejahtera bagi kita semua,</p>

            <p>
                Dengan penuh rasa syukur, kami hadir membawa semangat
                <strong>pengabdian dan transformasi</strong> untuk mewujudkan
                BEM Universitas Ibrahimy sebagai ruang perjuangan mahasiswa
                yang aktif, kolaboratif, dan berdampak.
            </p>

            <p>
                Bersama semangat <strong>Kampus Pahlawan Santri</strong>,
                mari kita bangun generasi yang tidak hanya unggul dalam intelektual,
                tetapi juga kuat dalam moral dan pengabdian.
            </p>

            <p>
                Selamat datang di
                <strong>Website Resmi BEM Universitas Ibrahimy</strong>.
                Melalui platform ini, kami menghadirkan berbagai informasi
                seputar kegiatan, program kerja, aspirasi mahasiswa,
                serta ruang kolaborasi untuk seluruh civitas akademika.
            </p>

            <p>
                Semoga website ini menjadi media komunikasi yang bermanfaat
                dan mempererat semangat kebersamaan kita.
            </p>

            <blockquote class=" italic text-gray-600">
                <strong>Mahasiswa Santri Transformatif!!!</strong><br>
                Sebuah semangat yang mengajak kita untuk menjadi mahasiswa
                yang unggul dalam intelektual, kuat dalam nilai kepesantrenan,
                serta mampu membawa perubahan positif bagi kampus dan masyarakat.
            </blockquote>

            <p><strong>Wassalamu’alaikum warahmatullahi wabarakatuh.</strong></p>
        `,
    },

    wapres: {
        name: "AINUR RAFIQI",
        position: "Wakil Presiden BEM UNIB",
        photo: "/images/default-avatar.jpg",
        message: `
            <p><strong>Assalamu’alaikum warahmatullahi wabarakatuh.</strong></p>

            <p>Salam sejahtera bagi kita semua.</p>

            <p>
                Dengan rasa syukur dan tanggung jawab besar, saya hadir mendampingi ruang perjuangan ini sebagai wadah aspirasi, kolaborasi, dan penggerak perubahan yang positif. Berlandaskan semangat transparansi dan solidaritas, mari kita bersama-sama membangun organisasi kemahasiswaan yang inovatif, inklusif, dan bermanfaat bagi seluruh mahasiswa.
            </p>

            <p>
                Selamat datang di Website Resmi BEM Universitas Ibrahimy. Melalui platform digital ini, mari kita perkuat sinergi dengan aktif berpartisipasi, menyampaikan ide, serta kritik membangun demi kampus yang progresif. Semoga wadah ini menjadi awal komitmen kita untuk bergerak bersama dan berkarya nyata dalam setiap langkah kepengurusan.
            </p>

            <blockquote class="border-l-4 border-blue-500 pl-4 italic text-gray-600">
                <p><em>Magnus successus ex magno labore et processu nascitur</em></p>

                <p>
                    “Kesuksesan besar lahir dari proses dan perjuangan yang besar.”
                </p>
            </blockquote>

            <p><strong>Wassalamu’alaikum warahmatullahi wabarakatuh.</strong></p>
        `,
    },
};

const currentLeader = computed(() => leaders[activeLeader.value]);

// Data upcoming events
const props = defineProps({
    pastEvents: {
        type: Array,
        default: () => [],
    },

    ongoingEvent: {
        type: Object,
        default: null,
    },

    nextEvent: {
        type: Object,
        default: null,
    },

    latestNews: Array,
});

const countdown = ref({
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
});

const isOngoing = ref(false);

const updateCountdown = () => {
    if (!props.nextEvent) return;

    const now = new Date().getTime();
    const start = new Date(props.nextEvent.targetDate).getTime();
    const end = new Date(props.nextEvent.endDate).getTime();

    if (now >= start && now <= end) {
        isOngoing.value = true;
        return;
    }

    isOngoing.value = false;

    if (now > end) {
        countdown.value = {
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
        };
        return;
    }

    const diff = start - now;

    countdown.value.days = Math.floor(diff / (1000 * 60 * 60 * 24));

    countdown.value.hours = Math.floor((diff / (1000 * 60 * 60)) % 24);

    countdown.value.minutes = Math.floor((diff / 1000 / 60) % 60);

    countdown.value.seconds = Math.floor((diff / 1000) % 60);
};

onMounted(() => {
    updateCountdown();

    countdownInterval = setInterval(() => {
        updateCountdown();
    }, 1000);
});

onUnmounted(() => {
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
});

// Data partners
const externalPartners = ref([
    "/images/logo/bem.png",
    "/images/logo/iai.png",
    "/images/logo/dema-iai.png",
    "/images/logo/kabinet.png",
    "/images/logo/bem-ti.png",
    "/images/logo/mti.png",
]);

const internalPartners = ref([
    "/images/logo/bem.png",
    "/images/logo/iai.png",
    "/images/logo/dema-iai.png",
    "/images/logo/kabinet.png",
    "/images/logo/bem-ti.png",
    "/images/logo/mti.png",
]);

onMounted(() => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100,
    });
});
</script>

<template>
    <AppLayout>
        <Head title="Beranda | BEM UNIB" />

        <!-- HERO SECTION -->
        <section
            class="relative min-h-[90vh] bg-cover bg-right lg:bg-center overflow-hidden flex items-center"
            style="background-image: url(&quot;/images/bg/hero.png&quot;)"
        >
            <div class="relative z-20 max-w-7xl mx-auto w-full px-4 lg:px-8">
                <div
                    class="flex justify-center lg:justify-start"
                    data-aos="fade-up"
                    data-aos-duration="1000"
                >
                    <div class="text-center lg:text-left">
                        <img
                            src="/public/images/logo/kabinet.png"
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
                            class="relative rounded-lg overflow-hidden shadow-2xl aspect-[3/4] w-full max-w-sm mx-auto"
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

                            <div
                                class="text-gray-700 leading-relaxed text-lg mb-8 border-l-4 border-yellow-500 pl-6 space-y-4"
                                v-html="currentLeader.message"
                            ></div>

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
        <section class="py-24 text-white relative overflow-hidden">
            <!-- Background Image -->
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="
                    background-image: url(&quot;/images/bg/pattern-a.jpg&quot;);
                "
            ></div>

            <!-- Blue Overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-r from-blue-800 via-blue-700/80 to-blue-800"
            ></div>

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
                                class="bg-white/10 rounded-lg p-4 backdrop-blur-sm"
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
                                class="bg-white/10 rounded-lg p-4 backdrop-blur-sm"
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
                                v-for="event in props.pastEvents"
                                :key="event.id"
                                class="custom-slide"
                            >
                                <!-- MOBILE VERSION -->
                                <div
                                    class="lg:hidden bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 h-full flex flex-col"
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
                                    class="hidden lg:block relative h-[220px] rounded-lg overflow-hidden cursor-pointer group transition-all duration-400 ease-in-out"
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
                    v-if="props.ongoingEvent"
                    class="mb-20 bg-gradient-to-r from-green-700 to-emerald-600 text-white rounded-lg p-8 lg:p-12 shadow-xl relative overflow-hidden"
                >
                    <div class="grid lg:grid-cols-3 gap-8 items-center">
                        <div class="lg:col-span-2">
                            <div
                                class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full mb-4"
                            >
                                <span
                                    class="w-3 h-3 bg-green-300 rounded-full animate-pulse"
                                ></span>

                                <span class="font-semibold">
                                    Kegiatan Sedang Berlangsung
                                </span>
                            </div>

                            <h3 class="text-3xl lg:text-4xl font-bold mb-3">
                                {{ ongoingEvent.title }}
                            </h3>

                            <p class="mb-2 flex items-center gap-2">
                                <MapPin class="w-4 h-4" />
                                {{ ongoingEvent.location }}
                            </p>

                            <p class="text-green-100">
                                {{ ongoingEvent.description }}
                            </p>
                        </div>

                        <div
                            class="bg-white/10 backdrop-blur-md rounded-lg p-6 text-center border border-white/20"
                        >
                            <p class="text-xl font-bold animate-pulse">
                                Sedang Berlangsung
                            </p>

                            <p class="text-green-100 mt-2">
                                Jangan lewatkan kegiatan ini!
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    v-else-if="props.nextEvent"
                    class="mb-20 bg-gradient-to-r from-blue-700 to-green-600 text-white rounded-lg p-8 lg:p-12 shadow-xl relative overflow-hidden"
                >
                    <div class="grid lg:grid-cols-3 gap-8 items-center">
                        <div class="lg:col-span-2">
                            <div class="flex items-center gap-2 mb-3">
                                <Calendar class="w-5 h-5" />
                                <span
                                    class="uppercase tracking-wider text-sm font-semibold"
                                >
                                    Kegiatan Mendatang
                                </span>
                            </div>

                            <h3 class="text-3xl lg:text-4xl font-bold mb-2">
                                {{ props.nextEvent.title }}
                            </h3>

                            <p
                                class="flex items-center gap-2 text-blue-100 mb-1"
                            >
                                <MapPin class="w-4 h-4" />
                                {{ props.nextEvent.location }}
                            </p>

                            <p class="text-blue-100 mb-6">
                                {{ props.nextEvent.date }}
                            </p>

                            <a
                                href="/program-kerja#kalender"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-700 font-semibold rounded-full hover:scale-105 transition"
                            >
                                Lihat Kalender Kegiatan
                            </a>
                        </div>

                        <div
                            class="bg-white/10 backdrop-blur-md rounded-lg p-6 text-center border border-white/20"
                        >
                            <p
                                class="text-sm uppercase tracking-wider mb-4 text-blue-100"
                            >
                                Hitung Mundur
                            </p>

                            <div class="flex justify-center gap-6">
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
                        <Link
                            v-for="news in latestNews"
                            :key="news.id"
                            :href="route('berita.show', news.slug)"
                            class="group bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300"
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
                                <div
                                    class="flex items-center gap-4 text-xs text-gray-500 mb-3"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <CalendarDays class="w-3.5 h-3.5" />
                                        <span>{{ news.date }}</span>
                                    </div>

                                    <div class="flex items-center gap-1.5">
                                        <User class="w-3.5 h-3.5" />
                                        <span>{{ news.author }}</span>
                                    </div>
                                </div>
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
                        </Link>
                    </div>
                </div>

                <div class="text-center mt-16">
                    <Link
                        :href="route('berita.public.index')"
                        class="inline-flex items-center gap-2 px-10 py-4 bg-gradient-to-r from-blue-700 to-green-600 text-white rounded-full hover:from-blue-800 hover:to-green-700 transition-all duration-300 font-bold text-lg shadow-lg shadow-blue-700/20 hover:scale-105"
                    >
                        Lihat Semua Berita

                        <ChevronRight class="w-5 h-5" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- KOLABORASI -->
        <section
            class="relative overflow-hidden py-20 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 text-white"
        >
            <!-- White Light Decoration -->
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full blur-2xl opacity-40 md:opacity-10 transform translate-x-1/3 -translate-y-1/2"
            ></div>

            <div
                class="absolute bottom-0 left-0 w-40 h-40 bg-white rounded-full blur-3xl opacity-20 md:opacity-10 transform -translate-x-1/3 translate-y-1/3"
            ></div>

            <div class="relative mx-auto max-w-7xl px-4">
                <!-- Heading -->
                <div class="mb-20 text-center">
                    <span
                        class="inline-block rounded-full border border-white/20 bg-white/10 px-4 py-1 text-sm uppercase tracking-widest text-cyan-300 backdrop-blur-md"
                    >
                        Partnership & Networking
                    </span>

                    <h2 class="mt-6 text-4xl font-bold lg:text-6xl">
                        Our Collaboration
                    </h2>

                    <p class="mx-auto mt-4 max-w-2xl text-white/70">
                        Bersama membangun sinergi dan kolaborasi strategis untuk
                        menciptakan organisasi mahasiswa yang progresif,
                        inovatif, dan berdampak.
                    </p>
                </div>

                <!-- EXTERNAL -->
                <div
                    class="group relative mb-14 w-full overflow-hidden [mask-image:linear-gradient(to_right,transparent,white_10%,white_90%,transparent)]"
                >
                    <div
                        class="flex w-max min-w-max items-center gap-10 animate-marquee group-hover:[animation-play-state:paused]"
                    >
                        <!-- ORIGINAL -->
                        <div
                            v-for="(logo, index) in externalPartners"
                            :key="'ext1' + index"
                            class="group/card relative flex h-[150px] w-[150px] flex-shrink-0 items-center justify-center transition-all duration-500 hover:scale-110"
                        >
                            <!-- Light Decoration -->
                            <div
                                class="absolute top-0 right-0 w-20 h-20 rounded-full bg-white blur-2xl opacity-20 transition-all duration-500 group-hover/card:opacity-40"
                            ></div>

                            <img
                                :src="logo"
                                class="relative max-h-[105px] max-w-[105px] object-contain drop-shadow-[0_8px_25px_rgba(255,255,255,0.35)] transition-transform duration-500 group-hover/card:scale-110"
                            />
                        </div>

                        <!-- DUPLICATE -->
                        <div
                            v-for="(logo, index) in externalPartners"
                            :key="'ext2' + index"
                            class="group/card relative flex h-[150px] w-[150px] flex-shrink-0 items-center justify-center transition-all duration-500 hover:scale-110"
                        >
                            <!-- Light Decoration -->
                            <div
                                class="absolute top-0 right-0 w-20 h-20 rounded-full bg-white blur-2xl opacity-20 transition-all duration-500 group-hover/card:opacity-40"
                            ></div>

                            <img
                                :src="logo"
                                class="relative max-h-[105px] max-w-[105px] object-contain drop-shadow-[0_8px_25px_rgba(255,255,255,0.35)] transition-transform duration-500 group-hover/card:scale-110"
                            />
                        </div>
                    </div>
                </div>

                <!-- INTERNAL -->
                <div
                    class="group relative w-full overflow-hidden [mask-image:linear-gradient(to_right,transparent,white_10%,white_90%,transparent)]"
                >
                    <div
                        class="flex w-max min-w-max items-center gap-10 animate-marquee-reverse group-hover:[animation-play-state:paused]"
                    >
                        <!-- ORIGINAL -->
                        <div
                            v-for="(logo, index) in internalPartners"
                            :key="'int1' + index"
                            class="group/card relative flex h-[150px] w-[150px] flex-shrink-0 items-center justify-center transition-all duration-500 hover:scale-110"
                        >
                            <!-- Light Decoration -->
                            <div
                                class="absolute top-0 right-0 w-20 h-20 rounded-full bg-white blur-2xl opacity-20 transition-all duration-500 group-hover/card:opacity-40"
                            ></div>

                            <img
                                :src="logo"
                                class="relative max-h-[105px] max-w-[105px] object-contain drop-shadow-[0_8px_25px_rgba(255,255,255,0.35)] transition-transform duration-500 group-hover/card:scale-110"
                            />
                        </div>

                        <!-- DUPLICATE -->
                        <div
                            v-for="(logo, index) in internalPartners"
                            :key="'int2' + index"
                            class="group/card relative flex h-[150px] w-[150px] flex-shrink-0 items-center justify-center transition-all duration-500 hover:scale-110"
                        >
                            <!-- Light Decoration -->
                            <div
                                class="absolute top-0 right-0 w-20 h-20 rounded-full bg-white blur-2xl opacity-20 transition-all duration-500 group-hover/card:opacity-40"
                            ></div>

                            <img
                                :src="logo"
                                class="relative max-h-[105px] max-w-[105px] object-contain drop-shadow-[0_8px_25px_rgba(255,255,255,0.35)] transition-transform duration-500 group-hover/card:scale-110"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- SOCIAL MEDIA -->
            <div class="relative mt-24 flex flex-col items-center">
                <!-- Divider -->
                <div
                    class="mb-10 h-px w-40 bg-gradient-to-r from-transparent via-white/40 to-transparent"
                ></div>

                <!-- Label -->
                <div
                    class="mb-8 inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/5 px-5 py-2 backdrop-blur-xl"
                >
                    <div
                        class="h-2 w-2 rounded-full bg-cyan-400 animate-pulse"
                    ></div>

                    <span
                        class="text-xs font-medium uppercase tracking-[0.3em] text-white/70"
                    >
                        Connect With Us
                    </span>
                </div>

                <!-- Social Icons -->
                <div class="flex flex-wrap items-center justify-center gap-5">
                    <!-- Instagram -->
                    <a
                        href="#"
                        class="group relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-lg border border-white/10 bg-white/5 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-pink-400/40 hover:bg-pink-500/20 hover:shadow-[0_10px_40px_rgba(236,72,153,0.35)]"
                    >
                        <!-- Glow -->
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-pink-400/20 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        ></div>

                        <Instagram
                            class="relative h-7 w-7 text-white/90 transition-all duration-500 group-hover:scale-110 group-hover:text-white"
                        />
                    </a>

                    <!-- Facebook -->
                    <a
                        href="#"
                        class="group relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-lg border border-white/10 bg-white/5 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-blue-400/40 hover:bg-blue-500/20 hover:shadow-[0_10px_40px_rgba(59,130,246,0.35)]"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        ></div>

                        <Facebook
                            class="relative h-7 w-7 text-white/90 transition-all duration-500 group-hover:scale-110 group-hover:text-white"
                        />
                    </a>

                    <!-- TikTok -->
                    <a
                        href="#"
                        class="group relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-lg border border-white/10 bg-white/5 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-white/30 hover:bg-white/10 hover:shadow-[0_10px_40px_rgba(255,255,255,0.2)]"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        ></div>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="relative h-7 w-7 text-white/90 transition-all duration-500 group-hover:scale-110 group-hover:text-white"
                        >
                            <path
                                d="M19.59 6.69a4.83 4.83 0 0 1-3.77-1.8V14.3a5.18 5.18 0 1 1-4.48-5.13v2.67a2.54 2.54 0 1 0 1.77 2.43V2h2.71a7.6 7.6 0 0 0 3.77 3.18v1.51z"
                            />
                        </svg>
                    </a>

                    <!-- YouTube -->
                    <a
                        href="#"
                        class="group relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-lg border border-white/10 bg-white/5 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-red-400/40 hover:bg-red-500/20 hover:shadow-[0_10px_40px_rgba(239,68,68,0.35)]"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-red-400/20 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        ></div>

                        <Youtube
                            class="relative h-7 w-7 text-white/90 transition-all duration-500 group-hover:scale-110 group-hover:text-white"
                        />
                    </a>

                    <!-- Email -->
                    <a
                        href="mailto:bem@ibrahimy.ac.id"
                        class="group relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-lg border border-white/10 bg-white/5 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-yellow-400/40 hover:bg-yellow-500/20 hover:shadow-[0_10px_40px_rgba(234,179,8,0.35)]"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-yellow-300/20 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        ></div>

                        <Mail
                            class="relative h-7 w-7 text-white/90 transition-all duration-500 group-hover:scale-110 group-hover:text-white"
                        />
                    </a>
                </div>

                <!-- Bottom Text -->
                <p class="mt-8 text-sm tracking-wide text-white/50">
                    Follow our journey and stay connected with BEM UNIB
                </p>
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
        width: 420px;
        /* LANDSCAPE */
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

@keyframes marquee {
    from {
        transform: translateX(0);
    }

    to {
        transform: translateX(calc(-50% - 16px));
    }
}

@keyframes marqueeReverse {
    from {
        transform: translateX(calc(-50% - 16px));
    }

    to {
        transform: translateX(0);
    }
}

.animate-marquee {
    animation: marquee 30s linear infinite;
}

.animate-marquee-reverse {
    animation: marqueeReverse 30s linear infinite;
}

/* Line Clamp Utility */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
