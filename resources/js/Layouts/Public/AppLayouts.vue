<template>
    <div class="min-h-screen bg-white flex flex-col">
        <Navbar />
        <main class="flex-grow">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Navbar from "./Navbar.vue";
import Footer from "./Footer.vue";
</script>
