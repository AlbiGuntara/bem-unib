<template>
    <Head title="Login | IKSASS Banten" />

    <div class="flex flex-col md:flex-row h-screen">
        <img
            src="/public/images/login.jpg"
            class="absolute inset-0 w-full h-full object-cover"
            alt="Login Background"
        />

        <!-- <div class="absolute inset-0 bg-black/80"></div> -->

        <!-- LEFT SIDE (IMAGE HERO) -->
        <div class="relative md:w-1/2 hidden md:block">
            <!-- Overlay -->

            <div
                class="relative z-10 p-10 h-full flex flex-col justify-between text-cream"
            >
                <div>
                    <button
                        @click="router.get('/')"
                        class="text-sm text-cream/80 hover:text-coral transition"
                    >
                        <ChevronLeft class="w-4 h-4 inline" />
                        Back to Home
                    </button>
                </div>

                <div>
                    <h1 class="text-4xl font-bold leading-tight mb-4">
                        Akses Sistem Internal<br />
                        BEM Universitas Ibrahimy
                    </h1>

                    <p class="text-cream/80 max-w-md">
                        Silakan login untuk mengelola data organisasi, anggota,
                        dan sistem administrasi secara terintegrasi.
                    </p>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE (FORM) -->
        <div
            class="m-4 z-10 rounded-2xl md:w-2xl h-screen md:h-auto flex items-center justify-center bg-cream p-8"
        >
            <div class="w-full max-w-md">
                <img
                    src="/public/images/logo.png"
                    alt="Logo BEM Unib"
                    class="w-15 md:w-20 mx-auto mb-4 md:mb-6"
                />
                <h2 class="text-3xl font-bold text-deep-blue mb-2 text-center">
                    Welcome Back!
                </h2>
                <p class="text-sm text-deep-blue/60 mb-8 text-center">
                    Login untuk melanjutkan ke dashboard sistem.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm mb-1 text-deep-blue">
                            Username / Email
                        </label>
                        <input
                            v-model="form.username"
                            type="text"
                            class="w-full border border-deep-blue/20 rounded-lg px-4 py-3 focus:ring-2 focus:ring-electric-blue focus:outline-none"
                            placeholder="Masukkan username atau email"
                            required
                        />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm mb-1 text-deep-blue">
                            Password
                        </label>

                        <div class="relative">
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="w-full border border-deep-blue/20 rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-electric-blue focus:outline-none"
                                placeholder="Masukkan password"
                                required
                            />

                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-deep-blue/50 hover:text-coral"
                            >
                                <Eye v-if="!showPassword" class="w-5 h-5" />
                                <EyeOff v-else class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between text-sm">
                        <label
                            class="flex items-center gap-2 text-deep-blue/70"
                        >
                            <input
                                type="checkbox"
                                class="accent-electric-blue"
                            />
                            Remember me
                        </label>

                        <a
                            href="#"
                            class="text-electric-blue hover:text-coral transition"
                        >
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="w-full bg-electric-blue hover:bg-deep-blue text-cream py-3 rounded-full font-semibold transition"
                    >
                        Login
                    </button>

                    <!-- Divider -->
                    <div
                        class="flex items-center gap-4 text-xs text-deep-blue/40"
                    >
                        <div class="flex-1 h-px bg-deep-blue/20"></div>
                        Or continue with
                        <div class="flex-1 h-px bg-deep-blue/20"></div>
                    </div>

                    <!-- Google Button -->
                    <button
                        type="button"
                        class="w-full border border-deep-blue/20 py-3 rounded-full hover:bg-white transition text-sm"
                    >
                        Continue with Google
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router } from "@inertiajs/vue3";
import { reactive, ref } from "vue";
import { Eye, EyeOff, ChevronLeft } from "lucide-vue-next";

const form = reactive({
    username: "",
    password: "",
});

const showPassword = ref(false);

function submit() {
    router.post(route("login.store"), form);
}
</script>
