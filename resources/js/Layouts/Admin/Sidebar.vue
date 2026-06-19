<script setup>
import SidebarDropdown from "@/Components/SidebarDropdown.vue";
import {
    LayoutDashboard,
    Users,
    FileCog,
    Wallet,
    UserCog,
    Layers2,
    Calendar,
    CalendarDays,
    Phone,
} from "lucide-vue-next";

import { ref, onMounted, onUnmounted, watch, watchEffect, computed } from "vue";

import { usePage, router } from "@inertiajs/vue3";
import { Layer } from "leaflet";

/* ------------------- DATA ------------------- */
const page = usePage();
const user = computed(
    () =>
        page.props.auth?.user ?? {
            roles: [],
            permissions: [],
        },
);

const props = defineProps({
    collapsed: Boolean,
    isMobileOpen: Boolean,
});

const emits = defineEmits(["toggleMobile"]);

const query = ref("");
const results = ref([]);
const showResults = ref(false);

const activeMenu = ref("Dashboard");
const isDesktop = ref(window.innerWidth >= 768);

/* ------------------- PERMISSIONS ------------------- */
const permissions = computed(() => user.value.permissions ?? []);
const roles = computed(() => user.value.roles ?? []);

const isSuperAdmin = computed(() => roles.value.includes("super-admin"));

const can = (permission) => {
    if (isSuperAdmin.value) return true;
    if (!permission) return true;
    return permissions.value.includes(permission);
};

const hasRole = (role) => {
    if (isSuperAdmin.value) return true;
    if (!role) return true;
    return roles.value.includes(role);
};

/* ------------------- MENU ------------------- */
const mainMenuItems = [
    {
        name: "Dashboard",
        icon: LayoutDashboard,
        link: "/admin",
    },
];

const strukturalMenuItems = [
    {
        name: "Data Pengurus",
        link: "/admin/pengurus",
        permission: "view pengurus",
    },
    {
        name: "Departemen",
        link: "/admin/departments",
        permission: "view departemen",
    },
];

const kegiatanMenuItems = [
    {
        name: "Program Kerja",
        link: "/admin/program-kerja",
        permission: "view program-kerja",
    },
    {
        name: "Dokumentasi",
        link: "/admin/dokumentasi",
        permission: "view dokumentasi",
    },
    {
        name: "Berita",
        link: "/admin/berita",
        permission: "view berita",
    },
];

const suratMenuItems = [
    {
        name: "Surat Masuk",
        link: "/admin/surat-masuk",
        permission: "view surat-masuk",
    },
    {
        name: "Surat Keluar",
        link: "/admin/surat-keluar",
        permission: "view surat-keluar",
    },
];

const layananMenuItems = [
    {
        name: "Kontak",
        link: "/admin/contacts",
        permission: "view contacts",
    },
    {
        name: "Kotak Saran",
        link: "/admin/messages",
        permission: "view messages",
    },
    {
        name: "FAQ",
        link: "/admin/faqs",
        permission: "view faqs",
    },
];

const PenggunaMenuItems = [
    {
        name: "Users",
        link: "/admin/users",
        permission: "view users",
    },
    {
        name: "Roles",
        link: "/admin/roles",
        permission: "view role",
    },
    {
        name: "Permissions",
        link: "/admin/permissions",
        permission: "view permission",
    },
];

/* ------------------- FILTERED MENU ------------------- */
const filteredMainMenu = computed(() =>
    mainMenuItems.filter(
        (item) =>
            (!item.permission || can(item.permission)) &&
            (!item.role || hasRole(item.role)),
    ),
);

const filteredStrukturalMenu = computed(() =>
    strukturalMenuItems.filter(
        (item) =>
            (!item.permission || can(item.permission)) &&
            (!item.role || hasRole(item.role)),
    ),
);

const filteredKegiatanMenu = computed(() =>
    kegiatanMenuItems.filter(
        (item) =>
            (!item.permission || can(item.permission)) &&
            (!item.role || hasRole(item.role)),
    ),
);

const filteredSuratMenu = computed(() =>
    suratMenuItems.filter(
        (item) =>
            (!item.permission || can(item.permission)) &&
            (!item.role || hasRole(item.role)),
    ),
);

const filteredLayananMenu = computed(() =>
    layananMenuItems.filter(
        (item) =>
            (!item.permission || can(item.permission)) &&
            (!item.role || hasRole(item.role)),
    ),
);

const filteredPenggunaMenu = computed(() =>
    PenggunaMenuItems.filter(
        (item) =>
            (!item.permission || can(item.permission)) &&
            (!item.role || hasRole(item.role)),
    ),
);

/* ------------------- SEARCH ------------------- */
let searchTimeout = null;

async function handleSearch() {
    clearTimeout(searchTimeout);

    if (!query.value.trim()) {
        results.value = [];
        showResults.value = false;
        return;
    }

    searchTimeout = setTimeout(async () => {
        try {
            const response = await fetch(
                `/admin/search?q=${encodeURIComponent(query.value)}`,
            );
            const data = await response.json();
            results.value = data.results || [];
            showResults.value = results.value.length > 0;
        } catch (error) {
            results.value = [];
            showResults.value = false;
        }
    }, 300);
}

function goToResult(item) {
    showResults.value = false;
    query.value = "";
    router.visit(item.url);
}

/* ------------------- MENU HANDLING ------------------- */
function handleMenuClick(item) {
    activeMenu.value = item.name;

    if (item.link) {
        router.visit(item.link);
    }

    if (!isDesktop.value) {
        emits("toggleMobile");
    }
}

function handleSubMenuClick(item) {
    activeMenu.value = item.name;
    router.visit(item.link);

    if (!isDesktop.value) {
        emits("toggleMobile");
    }
}

function handleSuratSubClick(item) {
    activeMenu.value = item.name;
    router.visit(item.link);

    if (!isDesktop.value) {
        emits("toggleMobile");
    }
}

function handleKeuanganClick(item) {
    activeMenu.value = item.name;
    router.visit(item.link);

    if (!isDesktop.value) {
        emits("toggleMobile");
    }
}

/* --- Update Active Menu berdasarkan URL --- */
watchEffect(() => {
    const currentUrl = page.url.startsWith("/") ? page.url : `/${page.url}`;

    const allItems = [
        ...mainMenuItems,
        ...strukturalMenuItems,
        ...kegiatanMenuItems,
        ...suratMenuItems,
        ...layananMenuItems,
        ...PenggunaMenuItems,
    ];

    const found = allItems
        .filter((i) => i.link && currentUrl.startsWith(i.link))
        .sort((a, b) => b.link.length - a.link.length)[0];

    activeMenu.value = found ? found.name : null;
});

/* ------------------- RESPONSIVE ------------------- */
function handleResize() {
    isDesktop.value = window.innerWidth >= 768;
}

onMounted(() => window.addEventListener("resize", handleResize));
onUnmounted(() => window.removeEventListener("resize", handleResize));

watch(
    () => props.isMobileOpen,
    (val) => {
        document.body.style.overflow = val ? "hidden" : "";
    },
);

/* ------------------- UTILS ------------------- */
function getAvatarUrl(avatar) {
    if (avatar && !avatar.includes("default-avatar.jpg")) {
        return `/storage/${avatar}`;
    }
    return "/images/default-avatar.jpg";
}
</script>

<template>
    <!-- Overlay (Mobile) -->
    <transition
        enter-active-class="transition-opacity duration-300 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isMobileOpen"
            class="fixed inset-0 backdrop-blur-xs bg-black/40 z-40 md:hidden"
            @click="$emit('toggleMobile')"
        />
    </transition>

    <!-- Sidebar -->
    <transition :name="isDesktop ? '' : 'mobile-slide'">
        <aside
            v-show="isMobileOpen || isDesktop"
            :class="[
                'fixed top-0 left-0 h-screen z-40 flex flex-col bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-r border-gray-200 dark:border-gray-800 transition-all duration-500 ease-in-out pt-20',
                isDesktop ? (collapsed ? 'w-20' : 'w-64') : 'w-64',
            ]"
        >
            <!-- ================= MENU AREA ================= -->
            <div class="relative flex-1 overflow-hidden">
                <!-- Top Gradient -->
                <div
                    class="pointer-events-none absolute top-0 left-0 right-0 h-6 bg-gradient-to-b from-white dark:from-gray-900 to-transparent z-10"
                />

                <!-- Scrollable Menu -->
                <nav
                    class="h-full overflow-y-auto overflow-x-hidden px-3 py-4 space-y-1 sidebar-scroll"
                >
                    <!-- ===== MAIN MENU ===== -->
                    <div
                        v-for="item in filteredMainMenu"
                        :key="item.name"
                        @click="handleMenuClick(item)"
                        :class="[
                            'group relative flex items-center gap-4 px-4 py-3 rounded-xl cursor-pointer transition-all duration-300',
                            activeMenu === item.name
                                ? 'bg-blue-600 text-white shadow-lg'
                                : 'hover:bg-blue-50 dark:hover:bg-gray-800',
                        ]"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                'w-5 h-5 transition-transform group-hover:scale-110',
                                activeMenu === item.name
                                    ? 'text-white'
                                    : 'text-gray-500 dark:text-gray-400',
                            ]"
                        />

                        <span
                            v-if="!isDesktop || !collapsed"
                            class="text-sm font-medium"
                        >
                            {{ item.name }}
                        </span>

                        <span
                            v-if="
                                activeMenu === item.name &&
                                (!collapsed || !isDesktop)
                            "
                            class="absolute right-4 w-2 h-2 rounded-full bg-white animate-pulse"
                        />
                    </div>

                    <!-- ===== DROPDOWNS ===== -->
                    <SidebarDropdown
                        v-if="filteredStrukturalMenu.length"
                        label="Struktural"
                        :icon="Users"
                        :items="filteredStrukturalMenu"
                        :activeMenu="activeMenu"
                        :collapsed="collapsed"
                        :isDesktop="isDesktop"
                        @select="handleSubMenuClick"
                    />

                    <SidebarDropdown
                        v-if="filteredKegiatanMenu.length"
                        label="Kegiatan"
                        :icon="CalendarDays"
                        :items="filteredKegiatanMenu"
                        :activeMenu="activeMenu"
                        :collapsed="collapsed"
                        :isDesktop="isDesktop"
                        @select="handleSubMenuClick"
                    />

                    <SidebarDropdown
                        v-if="filteredSuratMenu.length"
                        label="Surat"
                        :icon="FileCog"
                        :items="filteredSuratMenu"
                        :activeMenu="activeMenu"
                        :collapsed="collapsed"
                        :isDesktop="isDesktop"
                        @select="handleSuratSubClick"
                    />

                    <SidebarDropdown
                        v-if="filteredLayananMenu.length"
                        label="Layanan"
                        :icon="Phone"
                        :items="filteredLayananMenu"
                        :activeMenu="activeMenu"
                        :collapsed="collapsed"
                        :isDesktop="isDesktop"
                        @select="handleSubMenuClick"
                    />

                    <SidebarDropdown
                        v-if="filteredPenggunaMenu.length"
                        label="Pengguna"
                        :icon="UserCog"
                        :items="filteredPenggunaMenu"
                        :activeMenu="activeMenu"
                        :collapsed="collapsed"
                        :isDesktop="isDesktop"
                        @select="handleSubMenuClick"
                    />
                </nav>

                <!-- Bottom Gradient -->
                <div
                    class="pointer-events-none absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-white dark:from-gray-900 to-transparent"
                />
            </div>

            <!-- ================= USER SECTION ================= -->
            <div
                class="flex-shrink-0 min-h-[89px] px-4 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 relative z-10 user-section-shadow flex items-center"
            >
                <div
                    :class="[
                        'flex items-center gap-3',
                        collapsed && isDesktop ? 'justify-center' : '',
                    ]"
                >
                    <img
                        :src="getAvatarUrl(user?.avatar)"
                        class="w-11 h-11 rounded-xl object-cover border-2 border-blue-500"
                        loading="lazy"
                    />

                    <div v-if="!collapsed || !isDesktop" class="min-w-0">
                        <p
                            class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate"
                        >
                            {{ user?.name || "Guest" }}
                        </p>
                        <p
                            class="text-xs text-gray-500 dark:text-gray-400 truncate"
                        >
                            {{ user?.email || "Belum login" }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>
    </transition>
</template>

<style scoped>
/* Scrollbar Custom */
.sidebar-scroll::-webkit-scrollbar {
    width: 6px;
}

.sidebar-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-scroll::-webkit-scrollbar-thumb {
    background: #3b82f6;
    border-radius: 10px;
}

.sidebar-scroll::-webkit-scrollbar-thumb:hover {
    background: #2563eb;
}

/* User Section Shadow */
.user-section-shadow {
    box-shadow:
        0 -4px 12px rgba(0, 0, 0, 0.06),
        0 -1px 3px rgba(0, 0, 0, 0.04);
}

/* Dark Mode Shadow */
.dark .user-section-shadow {
    box-shadow:
        0 -4px 14px rgba(0, 0, 0, 0.35),
        0 -1px 4px rgba(0, 0, 0, 0.25);
}

/* Mobile Sidebar Animation */
/* Enter */
.mobile-slide-enter-active {
    transition:
        transform 0.45s cubic-bezier(0.22, 1, 0.36, 1),
        opacity 0.35s ease;
}

.mobile-slide-enter-from {
    transform: translateX(-110%) scale(0.98);
    opacity: 0;
}

.mobile-slide-enter-to {
    transform: translateX(0) scale(1);
    opacity: 1;
}

/* Leave */
.mobile-slide-leave-active {
    transition:
        transform 0.35s cubic-bezier(0.4, 0, 0.2, 1),
        opacity 0.25s ease;
}

.mobile-slide-leave-from {
    transform: translateX(0) scale(1);
    opacity: 1;
}

.mobile-slide-leave-to {
    transform: translateX(-105%) scale(0.98);
    opacity: 0;
}
</style>
