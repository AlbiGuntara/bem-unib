<template>
    <footer class="bg-green-950 text-white">
        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Info -->
                <div
                    class="order-1 lg:order-2 col-span-1 md:col-span-2 flex flex-col justify-center text-left lg:text-center"
                >
                    <div
                        class="flex items-center space-x-3 mb-4 lg:justify-center"
                    >
                        <img
                            src="/public/images/p2s3.png"
                            alt="Pesantren Logo"
                            class="h-10 w-auto"
                        />
                        <img
                            src="/public/images/logo.png"
                            alt="Pesantren Logo"
                            class="h-10 w-auto"
                        />
                    </div>

                    <p class="text-green-100 mb-4 max-w-md mx-0 lg:mx-auto">
                        Organisasi terdepan dalam membina kader dan pengembangan
                        kegiatan pesantren secara berkelanjutan.
                    </p>

                    <div class="flex space-x-4 lg:justify-center">
                        <!-- social icons -->
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="order-2 lg:order-1 text-left lg:text-right">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-400">
                        Tautan Cepat
                    </h3>
                    <ul class="space-y-2">
                        <li v-for="link in quickLinks" :key="link.name">
                            <Link
                                :href="link.href"
                                class="text-green-100 hover:text-white transition-colors duration-200"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="order-3 lg:order-3 text-left">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-400">
                        Kontak
                    </h3>
                    <div class="space-y-2 text-green-100">
                        <p class="flex items-center">
                            <svg
                                class="h-5 w-5 mr-2 text-yellow-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                            Jl. Contoh No. 123, Jakarta
                        </p>
                        <p class="flex items-center">
                            <svg
                                class="h-5 w-5 mr-2 text-yellow-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                />
                            </svg>
                            +62 21 1234 5678
                        </p>
                        <p class="flex items-center">
                            <svg
                                class="h-5 w-5 mr-2 text-yellow-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            info@iksass.com
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-green-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="md:flex md:items-center md:justify-between">
                    <p class="text-center md:text-left text-green-200">
                        &copy; 2025 IKSASS Banten.
                        <a
                            href="https://www.instagram.com/albyy_394"
                            class="text-italic"
                            ><em>@mas_al</em></a
                        >
                    </p>
                    <div
                        class="mt-4 md:mt-0 flex justify-center md:justify-end space-x-6 text-sm text-green-200"
                    >
                        <Link
                            href="/privacy"
                            class="hover:text-white transition-colors duration-200"
                            >Kebijakan Privasi</Link
                        >
                        <Link
                            href="/terms"
                            class="hover:text-white transition-colors duration-200"
                            >Syarat & Ketentuan</Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const quickLinks = [
    { name: "Tentang Kami", href: "/about" },
    { name: "Program Kerja", href: "/program-kerja" },
    { name: "Prestasi", href: "/prestasi" },
    { name: "Media", href: "/media" },
    { name: "Kontak", href: "/kontak" },
];
</script>
