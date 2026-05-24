<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ArrowLeft, Home, ChevronRight, ExternalLink } from "lucide-vue-next";

const page = usePage();

// Split URL → segments (hapus 'admin')
const segments = computed(() => {
    return page.url
        .split("?")[0]
        .split("#")[0]
        .split("/")
        .filter((s) => s.length > 0 && s !== "admin" && isNaN(Number(s)));
});

// Mapping nama
const nameMap = {
    dashboard: "Dashboard",
    users: "Pengguna",
    create: "Tambah",
    edit: "Edit",
};

// Format label
const formatLabel = (segment) => {
    return segment
        .split("-")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
};

// Buat breadcrumb object
const breadcrumbs = computed(() => {
    return segments.value.map((segment, index) => {
        const label = nameMap[segment] || formatLabel(segment);

        return {
            name: label,
            href: "/admin/" + segments.value.slice(0, index + 1).join("/"),
            current: index === segments.value.length - 1,
        };
    });
});

// Title = breadcrumb terakhir
const pageTitle = computed(() => {
    if (breadcrumbs.value.length === 0) return "Home";
    return breadcrumbs.value[breadcrumbs.value.length - 1].name;
});
</script>

<template>
    <div
        class="flex flex-col md:flex-row md:items-center md:justify-between bg-transparent dark:border-electric-blue/20 backdrop-blur-sm rounded-2xl dark:text-white transition-all duration-300"
    >
        <!-- ================= LEFT ================= -->
        <div>
            <!-- ===== TITLE ===== -->
            <h1
                class="text-2xl font-bold text-deep-blue dark:text-cream mb-1 flex items-center gap-3 transition-all duration-300"
            >
                {{ pageTitle }}
            </h1>

            <!-- ===== BREADCRUMB ===== -->
            <nav
                class="flex text-sm text-deep-blue/70 dark:text-cream/70 transition-all duration-200"
                aria-label="Breadcrumb"
            >
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <!-- HOME -->
                    <li class="mr-0">
                        <Link
                            href="/admin"
                            class="inline-flex items-center py-1 rounded-lg hover:bg-electric-blue/20 dark:hover:bg-electric-blue/30 hover:text-electric-blue dark:hover:text-electric-blue"
                        >
                            <Home class="w-4 h-4 mr-1" />
                            Home
                        </Link>
                    </li>

                    <!-- DYNAMIC -->
                    <li
                        v-for="(bc, index) in breadcrumbs"
                        :key="index"
                        class="flex items-center"
                    >
                        <ChevronRight
                            class="w-4 h-4 mx-2 text-electric-blue/50 dark:text-cream/40 transition-all duration-300"
                        />

                        <!-- LINK -->
                        <Link
                            v-if="!bc.current"
                            :href="bc.href"
                            class="px-2 py-1 rounded-lg hover:bg-electric-blue/20 dark:hover:bg-electric-blue/30 hover:text-electric-blue dark:hover:text-electric-blue transition-all duration-200"
                        >
                            {{ bc.name }}
                        </Link>

                        <!-- ACTIVE -->
                        <span
                            v-else
                            class="px-3 py-1 rounded-lg bg-electric-blue dark:bg-coral text-cream font-medium shadow-sm transition-all duration-300"
                        >
                            {{ bc.name }}
                        </span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- ================= RIGHT ================= -->
        <div class="hidden md:block mt-4 md:mt-0">
            <a
                href="/"
                target="_blank"
                class="inline-flex items-center px-4 py-2.5 bg-electric-blue hover:bg-coral dark:bg-electric-blue dark:hover:bg-coral text-cream text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 active:scale-95"
            >
                <ExternalLink class="w-4 h-4 md:mr-2" />
                <span class="hidden md:block">Lihat Website</span>
            </a>
        </div>
    </div>
</template>
