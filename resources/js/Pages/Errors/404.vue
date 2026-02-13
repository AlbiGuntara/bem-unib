<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50/80 via-white to-yellow-50/80 px-6 relative overflow-hidden"
    >
        <div class="max-w-lg text-center relative">
            <!-- Icon -->
            <div
                class="relative mx-auto mb-8 w-26 h-26 rounded-full bg-gradient-to-r from-green-500 to-green-400 flex items-center justify-center shadow-inner group-hover:scale-105 transition-transform duration-300"
            >
                <SearchX class="w-12 h-12 text-white" />
                <div
                    class="absolute top-0 right-0 w-8 h-8 bg-yellow-500 rounded-full border-2 border-white flex items-center justify-center"
                >
                    <span class="text-xs font-bold text-white">?</span>
                </div>
            </div>

            <!-- Judul -->
            <div class="mb-4">
                <h1
                    class="text-6xl font-black text-gray-800 mb-2 tracking-tight"
                >
                    404
                </h1>
                <div
                    class="w-24 h-1 bg-gradient-to-r from-green-400 to-yellow-400 mx-auto rounded-full"
                ></div>
            </div>

            <!-- Pesan -->
            <div class="mb-10">
                <p
                    class="text-gray-700 text-xl mb-4 font-medium tracking-tight"
                >
                    Halaman Tidak Ditemukan
                </p>
                <p class="text-gray-500 leading-relaxed max-w-md mx-auto">
                    Halaman yang Anda cari tidak tersedia atau masih dalam tahap
                    pengembangan. Silakan kembali ke halaman sebelumnya atau
                    menuju beranda.
                </p>
            </div>

            <!-- Improved action buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <!-- Back button -->
                <button
                    @click="goBack"
                    class="inline-flex items-center justify-center gap-3 px-7 py-3.5 rounded-xl border border-gray-200 bg-white text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-300 hover:shadow-md active:scale-[0.98] transition-all duration-200 group cursor-pointer"
                >
                    <ArrowLeft class="w-4 h-4 text-gray-600" />
                    <span>Kembali</span>
                </button>

                <!-- Home button -->
                <Link
                    href="/"
                    class="relative px-7 py-3.5 bg-green-600 text-white font-semibold rounded-xl flex items-center gap-3 group"
                >
                    <!-- Hover animation layer -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none"
                    ></div>
                    <Home class="w-4 h-4" />
                    <span>Beranda</span>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ArrowLeft, Home, SearchX } from "lucide-vue-next";

const goBack = () => {
    window.history.back();
};
</script>
