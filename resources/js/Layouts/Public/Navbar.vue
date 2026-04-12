<template>
    <nav
        class="fixed top-0 left-0 right-0 z-[100] bg-white/90 backdrop-blur-md border-b border-green-100 shadow-lg shadow-green-500/5 transition-all duration-300"
        :class="{ 'shadow-2xl shadow-green-500/10': scrolled }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 lg:h-24">
                <!-- Logo with Animation -->
                <div class="flex-shrink-0 flex items-center group">
                    <Link href="/" class="flex items-center space-x-4">
                        <img
                            src="/public/images/logo-bem.png"
                            alt="IKSASS Logo"
                            class="relative h-12 w-12 lg:h-14 lg:w-14"
                        />
                        <div>
                            <div
                                class="font-logo text-xl font-bold text-gray-900"
                            >
                                IKSASS
                            </div>
                            <div
                                class="mt-1 text-sm text-green-600 font-semibold"
                            >
                                BANTEN
                            </div>
                        </div>
                    </Link>
                </div>

                <div
                    class="hidden lg:flex items-center space-x-2 bg-gradient-to-r from-green-50 to-yellow-50 rounded-2xl p-2 border border-green-100"
                >
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        class="relative px-4 py-2 rounded-xl font-medium transition-all duration-300 group"
                        :class="[
                            route().current(item.route)
                                ? 'bg-green-600 text-white shadow-lg shadow-green-500/30'
                                : 'text-gray-700 hover:text-green-600 hover:bg-white hover:shadow-md',
                        ]"
                    >
                        <span class="relative z-10">
                            {{ item.name }}
                        </span>
                        <span
                            v-if="item.href !== $page.url"
                            class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-green-500 to-yellow-400 group-hover:w-8 transition-all duration-300"
                        ></span>
                    </Link>
                </div>

                <!-- CTA Button -->
                <div class="hidden lg:block relative">
                    <Link
                        :href="route('login')"
                        class="relative px-6 py-3 bg-green-600 text-white font-semibold rounded-2xl flex items-center gap-2 group"
                    >
                        <!-- Hover animation layer -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none"
                        ></div>
                        <LogIn
                            class="w-4 h-4 group-hover:rotate-12 transition-transform"
                        />
                        <span>Login</span>
                        <ChevronRight
                            class="w-4 h-4 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"
                        />
                    </Link>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button
                        @click="toggleSidebar"
                        class="relative w-12 h-12 flex flex-col items-center justify-center gap-1.5 group"
                        aria-label="Toggle menu"
                    >
                        <span
                            class="w-6 h-0.5 bg-green-600 rounded-full transition-all duration-300"
                            :class="{ 'rotate-45 translate-y-2': sidebarOpen }"
                        ></span>
                        <span
                            class="w-6 h-0.5 bg-green-600 rounded-full transition-all duration-300"
                            :class="{ 'opacity-0': sidebarOpen }"
                        ></span>
                        <span
                            class="w-6 h-0.5 bg-green-600 rounded-full transition-all duration-300"
                            :class="{
                                '-rotate-45 -translate-y-2': sidebarOpen,
                            }"
                        ></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <transition name="sidebar">
            <div
                v-show="sidebarOpen"
                class="fixed h-screen inset-0 z-[99] lg:hidden"
                @click="sidebarOpen = false"
            >
                <!-- Backdrop -->
                <div
                    class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                ></div>

                <!-- Sidebar Panel -->
                <div
                    class="absolute top-0 right-0 h-full w-80 max-w-full bg-gradient-to-b from-white via-white to-green-50 shadow-2xl shadow-green-900/20 border-l border-green-100 overflow-y-auto"
                    @click.stop
                >
                    <!-- Sidebar Header -->
                    <div class="p-6 flex items-center justify-between">
                        <Link
                            href="/"
                            class="flex items-center space-x-3 group"
                            @click="sidebarOpen = false"
                        >
                            <div class="relative">
                                <img
                                    src="/public/images/logo-bem.png"
                                    alt="IKSASS Logo"
                                    class="relative h-12 w-12"
                                />
                            </div>
                            <div>
                                <div
                                    class="font-logo text-xl font-bold text-gray-900"
                                >
                                    IKSASS
                                </div>
                                <div
                                    class="mt-1 text-sm text-green-600 font-semibold"
                                >
                                    BANTEN
                                </div>
                            </div>
                        </Link>
                        <button
                            @click="sidebarOpen = false"
                            class="p-2 hover:bg-red-50 rounded-xl transition-colors"
                        >
                            <X class="w-5 h-5 text-gray-500" />
                        </button>
                    </div>

                    <!-- Navigation Links -->
                    <div class="p-4">
                        <div class="space-y-1">
                            <Link
                                v-for="(item, index) in navigation"
                                :key="item.name"
                                :href="item.href"
                                @click="sidebarOpen = false"
                                class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 group"
                                :class="[
                                    item.href === $page.url
                                        ? 'bg-gradient-to-r from-green-600 to-green-500 text-white shadow-lg'
                                        : 'text-gray-700 hover:bg-green-50 hover:text-green-600',
                                ]"
                            >
                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center transition-all"
                                    :class="[
                                        item.href === $page.url
                                            ? 'bg-white/20'
                                            : 'bg-green-100 group-hover:bg-green-200 text-green-600',
                                    ]"
                                >
                                    <component
                                        :is="item.icon"
                                        class="w-5 h-5"
                                    />
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">
                                        {{ item.name }}
                                    </div>
                                    <div
                                        v-if="item.description"
                                        class="text-xs opacity-75 mt-1"
                                    >
                                        {{ item.description }}
                                    </div>
                                </div>
                                <ChevronRight
                                    class="w-4 h-4 transition-transform"
                                    :class="{
                                        'rotate-90': item.href === $page.url,
                                    }"
                                />
                            </Link>
                        </div>

                        <!-- Divider -->
                        <div class="my-6 px-4">
                            <div
                                class="h-px bg-gradient-to-r from-transparent via-green-200 to-transparent"
                            ></div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="px-4">
                            <Link
                                :href="route('login')"
                                @click="sidebarOpen = false"
                                class="flex items-center justify-center gap-3 p-4 border-2 border-green-600 text-green-600 rounded-xl"
                            >
                                <LogIn class="w-5 h-5" />
                                <span class="font-semibold">Login Anggota</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Progress Indicator -->
        <div
            class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-green-500 to-yellow-400 transition-all duration-300"
            :style="{ width: `${scrollProgress}%` }"
        ></div>
    </nav>

    <!-- Spacer untuk konten -->
    <div class="h-20 lg:h-24"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    Home,
    Users,
    Target,
    Trophy,
    LogIn,
    ChevronRight,
    X,
    Phone,
    Mail,
    Image as ImageIcon,
    BookOpen,
} from "lucide-vue-next";
import { route } from "ziggy-js";

const sidebarOpen = ref(false);
const scrolled = ref(false);
const scrollProgress = ref(0);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
    if (sidebarOpen.value) {
        document.body.style.overflow = "hidden";
    } else {
        document.body.style.overflow = "";
    }
};

const navigation = [
    {
        name: "Beranda",
        route: "home",
        href: "/",
        icon: Home,
        description: "Halaman utama",
    },
    {
        name: "Tentang Kami",
        route: "tentang-kami",
        href: "/tentang-kami",
        icon: Users,
        description: "Profil & sejarah",
    },
    {
        name: "Program Kerja",
        route: "program-kerja",
        href: "/program-kerja",
        icon: Target,
        description: "Agenda & kegiatan",
    },
    {
        name: "Prestasi",
        route: "prestasi",
        href: "/prestasi",
        icon: Trophy,
        description: "Penghargaan & capaian",
    },
    {
        name: "Media",
        route: "media",
        href: "/media",
        icon: BookOpen,
        description: "Berita & karya",
    },
    {
        name: "Kontak",
        route: "kontak",
        href: "/kontak",
        icon: Phone,
        description: "Hubungi kami",
    },
];

// Handle scroll effects
const handleScroll = () => {
    scrolled.value = window.scrollY > 20;

    // Calculate scroll progress
    const winScroll =
        document.body.scrollTop || document.documentElement.scrollTop;
    const height =
        document.documentElement.scrollHeight -
        document.documentElement.clientHeight;
    scrollProgress.value = (winScroll / height) * 100;
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
    document.body.style.overflow = "";
});
</script>

<style scoped>
/* Sidebar Animations */
.sidebar-enter-active,
.sidebar-leave-active {
    transition: opacity 0.3s ease;
}

.sidebar-enter-from,
.sidebar-leave-to {
    opacity: 0;
}

.sidebar-enter-active .sidebar-panel,
.sidebar-leave-active .sidebar-panel {
    transition: transform 0.3s ease;
}

.sidebar-enter-from .sidebar-panel,
.sidebar-leave-to .sidebar-panel {
    transform: translateX(100%);
}

/* Custom scrollbar for sidebar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #10b981, #fbbf24);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #059669, #f59e0b);
}

/* Active link indicator */
.router-link-active {
    position: relative;
}

.router-link-active::before {
    content: "";
    position: absolute;
    left: -8px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 60%;
    background: linear-gradient(to bottom, #10b981, #fbbf24);
    border-radius: 0 2px 2px 0;
}

/* Progress bar animation */
.progress-bar {
    background: linear-gradient(90deg, #10b981, #fbbf24, #10b981);
    background-size: 200% 100%;
    animation: gradient 2s linear infinite;
}

@keyframes gradient {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Hover effects */
.hover-lift {
    transition: transform 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
}

/* Glow effect */
.glow {
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
}

.glow:hover {
    box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
}
</style>
