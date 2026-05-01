<template>
    <footer class="bg-blue-950 text-white">
        <!-- KONTAK -->
        <section class="relative pb-28 text-white overflow-hidden bg-blue-900">
            <div
                class="absolute inset-0 bg-gradient-to-b from-blue-900 via-blue-900 to-blue-950"
            ></div>

            <div class="relative max-w-5xl mx-auto px-4 text-center">
                <div
                    class="bg-white/10 backdrop-blur-xl rounded-3xl p-14 border border-white/20 shadow-[0_20px_60px_rgba(0,0,0,0.45)]"
                    data-aos="zoom-in"
                >
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4">
                        Hubungi Kami
                    </h2>

                    <div
                        class="w-24 h-1.5 bg-green-400 mx-auto rounded-full mb-8"
                    ></div>

                    <p class="text-white/70 text-lg mb-12 max-w-2xl mx-auto">
                        Terhubung dengan kami melalui media sosial untuk
                        mendapatkan informasi terbaru mengenai kegiatan, program
                        kerja, dan kolaborasi Badan Eksekutif Mahasiswa
                        Universitas Ibrahimy.
                    </p>

                    <!-- SOCIAL MEDIA -->
                    <div class="flex justify-center flex-wrap gap-6">
                        <!-- Instagram -->
                        <a
                            href="#"
                            class="group w-14 h-14 flex items-center justify-center rounded-full bg-white/10 border border-white/20 backdrop-blur-md hover:bg-pink-500 transition-all duration-300 hover:scale-110 shadow-lg"
                        >
                            <Instagram
                                class="w-6 h-6 text-white group-hover:text-white"
                            />
                        </a>

                        <!-- Facebook -->
                        <a
                            href="#"
                            class="group w-14 h-14 flex items-center justify-center rounded-full bg-white/10 border border-white/20 backdrop-blur-md hover:bg-blue-600 transition-all duration-300 hover:scale-110 shadow-lg"
                        >
                            <Facebook class="w-6 h-6 text-white" />
                        </a>

                        <!-- TikTok -->
                        <a
                            href="#"
                            class="group w-14 h-14 flex items-center justify-center rounded-full bg-white/10 border border-white/20 backdrop-blur-md hover:bg-black transition-all duration-300 hover:scale-110 shadow-lg"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="white"
                                class="w-6 h-6"
                            >
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 0 1-3.77-1.8V14.3a5.18 5.18 0 1 1-4.48-5.13v2.67a2.54 2.54 0 1 0 1.77 2.43V2h2.71a7.6 7.6 0 0 0 3.77 3.18v1.51z"
                                />
                            </svg>
                        </a>

                        <!-- YouTube -->
                        <a
                            href="#"
                            class="group w-14 h-14 flex items-center justify-center rounded-full bg-white/10 border border-white/20 backdrop-blur-md hover:bg-red-600 transition-all duration-300 hover:scale-110 shadow-lg"
                        >
                            <Youtube class="w-6 h-6 text-white" />
                        </a>

                        <!-- Email -->
                        <a
                            href="mailto:bem@ibrahimy.ac.id"
                            class="group w-14 h-14 flex items-center justify-center rounded-full bg-white/10 border border-white/20 backdrop-blur-md hover:bg-yellow-500 transition-all duration-300 hover:scale-110 shadow-lg"
                        >
                            <Mail class="w-6 h-6 text-white" />
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bottom Footer -->
        <div class="border-t border-green-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="md:flex md:items-center md:justify-between">
                    <p class="text-center md:text-left text-green-200">
                        &copy; 2026 Badan Eksekutif Mahasiswa Universitas
                        Ibrahimy.
                    </p>
                    <div
                        class="mt-4 md:mt-0 flex justify-center md:justify-end space-x-2 text-sm text-green-200"
                    >
                        <span>developed by</span>
                        <Link
                            href="https://www.instagram.com/albyy_394"
                            class="hover:text-white transition-colors duration-200"
                        >
                            @el_gntara
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCROLL TO TOP -->
        <Transition name="fade-scroll">
            <button
                v-if="showScrollTop"
                @click="scrollToTop"
                class="fixed bottom-8 right-8 z-50 w-12 h-12 flex items-center justify-center rounded-full bg-green-500/90 backdrop-blur-md text-white shadow-xl hover:bg-green-600 hover:scale-110 transition-all duration-300"
            >
                <ArrowUp class="w-5 h-5" />
            </button>
        </Transition>
    </footer>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import {
    Mail,
    Instagram,
    Facebook,
    Youtube,
    Music,
    ArrowUp,
} from "lucide-vue-next";

// Scroll to Top
const showScrollTop = ref(false);

const handleScroll = () => {
    showScrollTop.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
.fade-scroll-enter-active,
.fade-scroll-leave-active {
    transition: all 0.35s ease;
}

.fade-scroll-enter-from {
    opacity: 0;
    transform: translateY(15px);
}

.fade-scroll-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.fade-scroll-leave-from {
    opacity: 1;
    transform: translateY(0);
}

.fade-scroll-leave-to {
    opacity: 0;
    transform: translateY(15px);
}
</style>
