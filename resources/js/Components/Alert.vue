<template></template>

<script setup>
import Swal from "sweetalert2";
import { usePage } from "@inertiajs/vue3";
import { watch } from "vue";

const page = usePage();

watch(
    () => page.props.flash,
    (flash) => {
        if (flash.success) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: flash.success,
                confirmButtonColor: "#16a34a",
            });
        }
        if (flash.error) {
            Swal.fire({
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
