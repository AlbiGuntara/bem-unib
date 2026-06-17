<template></template>

<script setup>
import Swal from "sweetalert2";
import { usePage } from "@inertiajs/vue3";
import { watch } from "vue";

const page = usePage();

function getSwalTheme() {
    const dark =
        document.documentElement.classList.contains("dark");

    return {
        background: dark
            ? "#0f172a"
            : "#ffffff",

        color: dark
            ? "#f8fafc"
            : "#0f172a",

        customClass: {
            popup: dark
                ? "border border-slate-700"
                : "border border-slate-200",

            title: dark
                ? "text-slate-100"
                : "text-slate-900",

            htmlContainer: dark
                ? "text-slate-300"
                : "text-slate-600",
        },
    };
}

watch(
    () => page.props.flash,
    (flash) => {
        if (flash.success) {
            Swal.fire({
                ...getSwalTheme(),
                icon: "success",
                title: "Berhasil!",
                text: flash.success,
                confirmButtonColor: "#16a34a",
            });
        }

        if (flash.error) {
            Swal.fire({
                ...getSwalTheme(),
                icon: "error",
                title: "Gagal!",
                text: flash.error,
                confirmButtonColor: "#dc2626",
            });
        }
    },
    { deep: true, immediate: true }
);
</script>