<template>
    <footer class="bg-blue-950 text-white">
        <!-- Bottom Footer -->
        <div class="border-t border-blue-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="md:flex md:items-center md:justify-between">
                    <p class="text-center md:text-left text-blue-200">
                        &copy; 2026 Badan Eksekutif Mahasiswa Universitas
                        Ibrahimy.
                    </p>
                    <div
                        class="mt-4 md:mt-0 flex justify-center md:justify-end space-x-2 text-sm text-blue-200"
                    >
                        <span>developed by</span>
                        <Link
                            href="https://www.instagram.com/albyy_394"
                            class="hover:text-white transition-colors duration-200"
                        >
                            @el_gntara
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCROLL TO TOP -->
        <Transition name="fade-scroll">
            <button
                v-if="showScrollTop"
                @click="scrollToTop"
                class="fixed bottom-8 right-8 z-50 w-12 h-12 flex items-center justify-center rounded-full bg-blue-500/90 backdrop-blur-md text-white shadow-xl hover:bg-blue-600 hover:scale-110 transition-all duration-300"
            >
                <ArrowUp class="w-5 h-5" />
            </button>
        </Transition>
    </footer>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import { ArrowUp } from "lucide-vue-next";

// Scroll to Top
const showScrollTop = ref(false);

const handleScroll = () => {
    showScrollTop.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
.fade-scroll-enter-active,
.fade-scroll-leave-active {
    transition: all 0.35s ease;
}

.fade-scroll-enter-from {
    opacity: 0;
    transform: translateY(15px);
}

.fade-scroll-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.fade-scroll-leave-from {
    opacity: 1;
    transform: translateY(0);
}

.fade-scroll-leave-to {
    opacity: 0;
    transform: translateY(15px);
}
</style>
