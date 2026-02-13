<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-deep-blue via-[#1F2937] to-black px-6 relative overflow-hidden text-cream"
    >
        <div class="max-w-lg text-center relative">
            <!-- Icon -->
            <div
                class="relative mx-auto mb-8 w-28 h-28 rounded-full bg-gradient-to-r from-electric-blue to-coral flex items-center justify-center shadow-2xl shadow-electric-blue/30 transition-transform duration-300"
            >
                <ShieldAlert class="w-12 h-12 text-white" />

                <div
                    class="absolute top-0 right-0 w-8 h-8 bg-black/70 rounded-full border-2 border-coral flex items-center justify-center"
                >
                    <span class="text-xs font-bold text-coral">!</span>
                </div>
            </div>

            <!-- Code -->
            <div class="mb-4">
                <h1 class="text-6xl font-black mb-2 tracking-tight text-white">
                    499
                </h1>
                <div
                    class="w-24 h-1 bg-gradient-to-r from-electric-blue to-coral mx-auto rounded-full"
                ></div>
            </div>

            <!-- Message -->
            <div class="mb-10">
                <p
                    class="text-xl mb-4 font-semibold tracking-tight text-electric-blue"
                >
                    Application Integrity Violation
                </p>

                <p class="text-cream/70 leading-relaxed max-w-md mx-auto">
                    Sistem mendeteksi adanya modifikasi tidak sah pada komponen
                    yang dilindungi. Akses ke aplikasi telah dibatasi untuk
                    menjaga integritas sistem.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <!-- Back -->
                <button
                    @click="goBack"
                    class="inline-flex items-center justify-center gap-3 px-7 py-3.5 rounded-xl border border-electric-blue/30 bg-white/5 backdrop-blur-sm text-cream font-semibold hover:bg-electric-blue/20 hover:border-electric-blue hover:shadow-lg active:scale-[0.98] transition-all duration-300"
                >
                    <ArrowLeft class="w-4 h-4 text-electric-blue" />
                    <span>Kembali</span>
                </button>

                <!-- Home -->
                <Link
                    href="/"
                    class="relative px-7 py-3.5 bg-electric-blue hover:bg-coral text-white font-semibold rounded-xl flex items-center gap-3 shadow-lg hover:shadow-xl transition-all duration-300 group"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none"
                    ></div>
                    <Home class="w-4 h-4" />
                    <span>Beranda</span>
                </Link>
            </div>

            <!-- Extra note -->
            <p class="mt-10 text-xs text-cream/40 tracking-wide">
                System Security Protocol Activated
            </p>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ArrowLeft, Home, ShieldAlert } from "lucide-vue-next";

const goBack = () => {
    window.history.back();
};
</script>
