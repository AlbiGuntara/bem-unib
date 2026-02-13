<template>
    <Head title="Login | IKSASS Banten" />

    <div
        class="h-screen flex flex-col md:flex-row bg-gradient-to-br from-green-900 via-green-800 to-green-900 md:bg-none"
    >
        <!-- LEFT / HERO -->
        <div
            class="relative md:w-1/2 md:min-h-screen flex flex-col justify-center items-center text-white px-6 py-12 md:py-0 overflow-hidden bg-gradient-to-b from-green-800 to-green-900"
        >
            <!-- MOBILE DECORATION -->
            <div
                class="md:hidden absolute -top-20 -left-20 w-72 h-72 bg-green-500/30 rounded-full blur-3xl"
            ></div>
            <div
                class="md:hidden absolute bottom-0 -right-20 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl"
            ></div>

            <img
                src="/public/images/logo.png"
                class="h-20 mb-6 relative z-10"
                alt="Logo"
            />

            <div class="text-center relative z-10">
                <h1
                    class="text-xl font-semibold leading-snug md:text-xl md:font-bold"
                >
                    Selamat Datang di Website Resmi
                </h1>

                <p class="text-3xl font-bold mt-2 md:text-5xl md:font-bold">
                    Rayon IKSASS Banten
                </p>

                <!-- DESKTOP TEXT -->
                <p
                    class="hidden md:block mt-4 text-white/80 max-w-md mx-auto text-sm"
                >
                    Silakan login untuk mengakses sistem dan fitur internal
                    organisasi.
                </p>
            </div>

            <!-- DESKTOP WAVE -->
            <div
                class="hidden md:block absolute top-0 right-0 h-full w-24 bg-white"
                style="
                    clip-path: polygon(100% 0, 0 0, 100% 100%);
                    filter: drop-shadow(-4px 0 6px rgba(0, 0, 0, 0.15));
                "
            ></div>
        </div>

        <!-- FORM SECTION -->
        <div
            class="w-full md:w-1/2 flex items-center justify-center px-4 pb-10 md:pb-0 bg-transparent md:bg-white"
        >
            <!-- CARD -->
            <div
                class="w-full max-w-md bg-white/90 md:bg-white backdrop-blur-xl md:backdrop-blur-0 rounded-2xl md:rounded-none shadow-2xl md:shadow-none p-6 md:p-8"
            >
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                    Login ke Akun Anda
                </h2>

                <form @submit.prevent="submit">
                    <!-- Username -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1 text-sm">
                            Username / Email
                        </label>
                        <div class="relative">
                            <User
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"
                            />
                            <input
                                v-model="form.username"
                                type="text"
                                class="w-full border border-gray-300 rounded-xl md:rounded-lg pl-10 pr-3 py-2.5 md:py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                                placeholder="username atau email"
                                required
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-1 text-sm">
                            Password
                        </label>
                        <div class="relative">
                            <Lock
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"
                            />
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="w-full border border-gray-300 rounded-xl md:rounded-lg pl-10 pr-10 py-2.5 md:py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                                placeholder="password"
                                required
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-green-700 transition"
                            >
                                <Eye v-if="!showPassword" class="w-5 h-5" />
                                <EyeOff v-else class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col md:flex-row-reverse gap-3">
                        <button
                            type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 md:py-2 rounded-xl md:rounded-lg font-semibold transition shadow-md md:shadow-none"
                        >
                            Login
                        </button>

                        <button
                            type="button"
                            @click="router.get('/')"
                            class="w-full border border-gray-300 text-gray-700 py-2.5 md:py-2 rounded-xl md:rounded-lg hover:bg-green-600 hover:text-white transition"
                        >
                            Kembali
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router } from "@inertiajs/vue3";
import { reactive, ref } from "vue";
import { User, Lock, Eye, EyeOff } from "lucide-vue-next";

const form = reactive({
    username: "",
    password: "",
});

const showPassword = ref(false);

function submit() {
    router.post(route("login.store"), form);
}
</script>
