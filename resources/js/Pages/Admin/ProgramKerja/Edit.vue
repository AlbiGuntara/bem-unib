<template>
    <AppLayout>
        <Head title="Edit Program Kerja" />
        <div>
            <Form />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import Form from "./Form.vue";

defineProps({
    programKerja: Object,
});
</script>
