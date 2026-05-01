<template>
    <header
        class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-4 bg-deep-blue text-cream shadow-md"
    >
        <!-- ================= LEFT ================= -->
        <div class="flex items-center gap-4">
            <!-- Desktop Toggle -->
            <button
                @click="$emit('toggleSidebar')"
                class="hidden md:flex items-center justify-center p-2.5 rounded-xl bg-electric-blue hover:bg-coral transition-all duration-300 transform hover:scale-105 active:scale-95"
            >
                <Menu class="w-5 h-5" />
            </button>

            <!-- Mobile Toggle -->
            <button
                @click="$emit('toggleMobile')"
                class="md:hidden flex items-center justify-center p-2.5 rounded-xl bg-electric-blue hover:bg-coral transition-all duration-300 transform hover:scale-105 active:scale-95"
            >
                <component :is="isMobileOpen ? X : Menu" class="w-5 h-5" />
            </button>

            <!-- Logo & Title -->
            <div class="flex items-center gap-3">
                <img
                    src="/public/images/logo-bem.png"
                    class="w-10 h-10 transition-transform duration-300"
                />
                <div>
                    <h1 class="text-lg font-bold tracking-wide">BEM UNIB</h1>
                    <p class="text-xs opacity-80">Management System</p>
                </div>
            </div>
        </div>

        <!-- ================= RIGHT ================= -->
        <div class="flex items-center gap-3">
            <!-- Theme Toggle -->
            <div class="relative group">
                <button
                    @click="toggleTheme"
                    class="p-2.5 rounded-xl bg-electric-blue hover:bg-coral transition-all duration-300 transform hover:scale-105 active:scale-95"
                >
                    <component
                        :is="themeIcon"
                        class="w-5 h-5 transition-transform duration-500"
                        :class="{ 'rotate-180': isDark }"
                    />
                </button>

                <Tooltip
                    :text="isDark ? 'Switch to Light' : 'Switch to Dark'"
                />
            </div>

            <!-- Logout -->
            <div class="relative group">
                <button
                    @click="logout"
                    class="p-2.5 rounded-xl bg-electric-blue hover:bg-coral transition-all duration-300 transform hover:scale-105 active:scale-95"
                >
                    <LogOut
                        class="w-5 h-5 transition-transform duration-300 group-hover:rotate-12"
                    />
                </button>

                <Tooltip text="Logout" />
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { Menu, X, Sun, Moon, LogOut } from "lucide-vue-next";

const props = defineProps({
    isMobileOpen: Boolean,
});

const emit = defineEmits(["toggleSidebar", "changeTheme", "toggleMobile"]);

/* ================= THEME ================= */
const currentTheme = ref("light");
const isDark = computed(() => currentTheme.value === "dark");
const themeIcon = computed(() => (isDark.value ? Sun : Moon));

const toggleTheme = () => {
    currentTheme.value = isDark.value ? "light" : "dark";
    document.documentElement.classList.toggle("dark", isDark.value);
    localStorage.setItem("theme", currentTheme.value);
    emit("changeTheme", currentTheme.value);
};

/* ================= LOGOUT ================= */
function logout() {
    Swal.fire({
        title: "Apakah kamu yakin ingin keluar?",
        text: "Sesi kamu akan berakhir setelah logout.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ff8040",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Logout",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route("logout"));
        }
    });
}

/* ================= INIT ================= */
onMounted(() => {
    const savedTheme = localStorage.getItem("theme") || "light";
    currentTheme.value = savedTheme;
    document.documentElement.classList.toggle("dark", savedTheme === "dark");
});
</script>

<script>
export default {
    components: {
        Tooltip: {
            props: ["text"],
            template: `
                <div
                    class="absolute -bottom-9 left-1/2 transform -translate-x-1/2 bg-electric-blue text-cream text-xs px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none whitespace-nowrap"
                >
                    {{ text }}
                    <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 border-4 border-transparent border-b-electric-blue"></div>
                </div>
            `,
        },
    },
};
</script>
