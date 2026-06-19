<script setup>
import { Head } from "@inertiajs/vue3"
import { computed } from "vue"

const props = defineProps({
    title: { type: String, default: "" },
    description: { type: String, default: "" },
    image: { type: String, default: "" },
    url: { type: String, default: "" },
    type: { type: String, default: "website" },
})

const siteName = "BEM Universitas Ibrahimy"

const defaultDescription =
    "Website resmi Badan Eksekutif Mahasiswa Universitas Ibrahimy. Informasi kegiatan, program kerja, berita, dan aspirasi mahasiswa."

const defaultImage = "/images/logo/bem.png"

const pageTitle = computed(() => {
    if (!props.title) return siteName
    return props.title.includes("|") ? props.title : `${props.title} | ${siteName}`
})

const desc = computed(() => props.description || defaultDescription)

const ogImage = computed(() => {
    if (!props.image) return defaultImage
    if (props.image.startsWith("/storage/")) return props.image
    if (props.image.startsWith("/")) return props.image
    return props.image
})

const ogUrl = computed(
    () =>
        props.url ||
        (typeof window !== "undefined" ? window.location.href : "/"),
)
</script>

<template>
    <Head :title="pageTitle">
        <meta name="description" :content="desc" />
        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="desc" />
        <meta property="og:image" :content="ogImage" />
        <meta property="og:url" :content="ogUrl" />
        <meta property="og:type" :content="type" />
        <meta property="og:site_name" :content="siteName" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="pageTitle" />
        <meta name="twitter:description" :content="desc" />
        <meta name="twitter:image" :content="ogImage" />
        <link rel="canonical" :href="ogUrl" />
    </Head>
</template>
