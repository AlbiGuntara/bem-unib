<template>
    <div
        class="min-h-screen flex flex-col bg-white dark:bg-gray-950 transition-colors duration-300"
    >
        <Alert />

        <!-- ================= NAVBAR ================= -->
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
                    'flex flex-col flex-1 transition-all duration-500 ease-in-out',
                    collapsed ? 'md:ml-20' : 'md:ml-64',
                ]"
            >
                <!-- Content Wrapper -->
                <div class="flex-1 p-4">
                    <Breadcrumb />

                    <div class="mt-4">
                        <slot />
                    </div>
                </div>

                <!-- ================= FOOTER ================= -->
                <footer
                    class="relative z-10 min-h-[89px] px-6 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 footer-shadow transition-all duration-300 flex items-center"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-start gap-2 text-sm w-full"
                    >
                        <p class="text-gray-600 dark:text-gray-400">
                            © {{ currentYear }} BEM UNIB. Develop by
                        </p>

                        <a
                            href="https://www.instagram.com/albyy_394"
                            class="italic text-blue-600 dark:text-blue-400 hover:opacity-80 transition-opacity"
                        >
                            @el_gntara
                        </a>
                    </div>
                </footer>
            </main>
        </div>

        <!-- ================= SCROLL TO TOP ================= -->
        <Transition name="fade-slide">
            <button
                v-if="showScroll"
                @click="scrollToTop"
                class="fixed bottom-6 right-6 z-50 p-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-lg hover:shadow-xl transition-transform duration-300 transform active:scale-95"
            >
                <ChevronUp class="w-5 h-5" />
            </button>
        </Transition>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted, computed } from "vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Alert from "@/Components/Alert.vue";
import Swal from "sweetalert2";
import { ChevronUp, MessageSquare } from "lucide-vue-next";
import { usePage, router } from "@inertiajs/vue3";

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
        const el = document.querySelector("footer span.text-center a");

        if (!el) return;

        const expectedHref = "https://www.instagram.com/albyy_394";
        const expectedText = "@el_gntara";

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

/* ================= REAL-TIME NOTIFICATIONS ================= */
const page = usePage();

function getSwalToastTheme() {
    const dark = document.documentElement.classList.contains("dark");
    return {
        background: dark ? "#1e293b" : "#ffffff",
        color: dark ? "#f1f5f9" : "#0f172a",
        confirmButtonColor: "#3b82f6",
    };
}

onMounted(() => {
    if (window.Echo) {
        window.Echo.private("admin.messages").listen(".new-message", (e) => {
            const unread = page.props.unreadMessagesCount || 0;

            router.reload({ only: ["unreadMessagesCount"], preserveState: true, preserveScroll: true });

            if (e.message && e.message.name && e.message.subject) {
                const toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: true,
                    confirmButtonText: "Lihat",
                    confirmButtonColor: "#3b82f6",
                    timer: 8000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    ...getSwalToastTheme(),
                });

                toast.fire({
                    icon: "info",
                    title: "Pesan Baru!",
                    text: `${e.message.name} - ${e.message.subject}`,
                    didOpen: (toastEl) => {
                        toastEl.onclick = () => {
                            router.visit(route("messages.index"));
                        };
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        router.visit(route("messages.index"));
                    }
                });
            }
        });
    }
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

/* Footer Style */
.footer-shadow {
    box-shadow:
        0 -4px 12px rgba(0, 0, 0, 0.04),
        0 -1px 3px rgba(0, 0, 0, 0.03);
}

.dark .footer-shadow {
    box-shadow:
        0 -4px 14px rgba(0, 0, 0, 0.3),
        0 -1px 4px rgba(0, 0, 0, 0.2);
}
</style>
