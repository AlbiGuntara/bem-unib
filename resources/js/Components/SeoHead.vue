<script setup>
import { Head } from "@inertiajs/vue3"
import { computed, watch, onUnmounted } from "vue"

const props = defineProps({
    title: { type: String, default: "" },
    description: { type: String, default: "" },
    image: { type: String, default: "" },
    url: { type: String, default: "" },
    type: { type: String, default: "website" },
    keywords: { type: String, default: "" },
    robots: { type: String, default: "index, follow" },
    jsonLd: { type: Object, default: null },
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

const defaultKeywords =
    "bem unib, bem universitas ibrahimy, bem ibrahimy, kabinet transformasi, bem situbondo, bem jawa timur, organisasi mahasiswa, universitas ibrahimy"

const pageKeywords = computed(() => props.keywords || defaultKeywords)

const JSONLD_ID = "seo-jsonld"

function injectJsonLd(data) {
    const existing = document.getElementById(JSONLD_ID)
    if (existing) existing.remove()

    if (!data) return

    const script = document.createElement("script")
    script.id = JSONLD_ID
    script.type = "application/ld+json"
    script.textContent = JSON.stringify(data)
    document.head.appendChild(script)
}

watch(() => props.jsonLd, injectJsonLd, { immediate: true, deep: true })

onUnmounted(() => {
    const el = document.getElementById(JSONLD_ID)
    if (el) el.remove()
})
</script>

<template>
    <Head :title="pageTitle">
        <meta name="description" :content="desc" />
        <meta name="keywords" :content="pageKeywords" />
        <meta name="robots" :content="robots" />
        <meta name="googlebot" :content="robots" />

        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="desc" />
        <meta property="og:image" :content="ogImage" />
        <meta property="og:url" :content="ogUrl" />
        <meta property="og:type" :content="type" />
        <meta property="og:site_name" :content="siteName" />
        <meta property="og:locale" content="id_ID" />

        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="pageTitle" />
        <meta name="twitter:description" :content="desc" />
        <meta name="twitter:image" :content="ogImage" />

        <link rel="canonical" :href="ogUrl" />
    </Head>
</template>
