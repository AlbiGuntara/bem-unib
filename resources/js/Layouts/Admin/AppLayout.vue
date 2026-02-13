<template>
    <div
        class="min-h-screen flex flex-col bg-cream dark:bg-[#1F1F1F] transition-colors duration-300"
    >
        <Alert />

        <!-- ================= NAVBAR (FIXED) ================= -->
        <Navbar
            class="fixed top-0 left-0 right-0 z-50"
            :isMobileOpen="isMobileOpen"
            @toggleSidebar="toggleSidebar"
            @toggleMobile="toggleMobile"
        />

        <!-- ================= BODY AREA ================= -->
        <div class="flex flex-1 pt-20">
            <!-- Sidebar -->
            <Sidebar
                :collapsed="collapsed"
                :isMobileOpen="isMobileOpen"
                @toggleMobile="toggleMobile"
            />

            <!-- ================= CONTENT ================= -->
            <main
                :class="[
                    'p-4 flex flex-col flex-1 transition-all duration-500 ease-in-out',
                    collapsed ? 'md:ml-20' : 'md:ml-64',
                ]"
            >
                <Breadcrumb />

                <div class="mt-4 flex-1">
                    <slot />
                </div>

                <!-- ================= FOOTER ================= -->
                <footer
                    class="mt-10 pt-6 pb-4 text-center text-sm text-deep-blue/70 dark:text-cream/60 border-t border-electric-blue/20 dark:border-electric-blue/20 transition-all duration-300"
                >
                    © 2026 BEM UNIB —
                    <span id="protected-copyright">
                        <a
                            href="https://www.instagram.com/albyy_394"
                            class="text-italic"
                            ><em>@mas_al</em></a
                        >
                    </span>
                </footer>
            </main>
        </div>

        <!-- ================= SCROLL TO TOP ================= -->
        <Transition name="fade-slide">
            <button
                v-if="showScroll"
                @click="scrollToTop"
                class="fixed bottom-6 right-6 z-50 p-3 rounded-xl bg-electric-blue hover:bg-coral text-cream shadow-lg hover:shadow-xl transition-transform duration-300 transform hover:scale-105 active:scale-95"
            >
                <ChevronUp class="w-5 h-5" />
            </button>
        </Transition>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted } from "vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Alert from "@/Components/Alert.vue";
import Swal from "sweetalert2";
import { ChevronUp } from "lucide-vue-next";

/* ================= STATE ================= */
const collapsed = ref(false);
const isMobileOpen = ref(false);
const showScroll = ref(false);
const currentYear = new Date().getFullYear();

/* ================= SIDEBAR ================= */
const toggleSidebar = () => {
    collapsed.value = !collapsed.value;
};

const toggleMobile = () => {
    isMobileOpen.value = !isMobileOpen.value;
};

/* ================= SCROLL FUNCTION ================= */
const handleScroll = () => {
    showScroll.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
};

/* ================= LIFECYCLE ================= */
onMounted(() => {
    const savedCollapse = localStorage.getItem("sidebarCollapsed");
    collapsed.value = savedCollapse === "true";

    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});

onMounted(() => {
    const checkIntegrity = () => {
        const el = document.querySelector("#protected-copyright a");

        if (!el) return;

        const expectedHref = "https://www.instagram.com/albyy_394";
        const expectedText = "@mas_al";

        const currentHref = el.getAttribute("href");
        const currentText = el.textContent.trim();

        if (currentHref !== expectedHref || currentText !== expectedText) {
            Swal.fire({
                icon: "error",
                title: "Integrity Violation",
                text: "Unauthorized modification of protected system component detected.",
                confirmButtonText: "Understood",
                confirmButtonColor: "#3b82f6",
                background: document.documentElement.classList.contains("dark")
                    ? "#1F1F1F"
                    : "#ffffff",
                color: document.documentElement.classList.contains("dark")
                    ? "#f5f5dc"
                    : "#1e293b",
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then(() => {
                window.location.href = "/integrity-violation";
            });
        }
    };

    setInterval(checkIntegrity, 2000);
});

watch(collapsed, (val) => {
    localStorage.setItem("sidebarCollapsed", val);
});
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(15px);
}

.fade-slide-enter-to,
.fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
