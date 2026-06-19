<script setup>
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";
import {
    Phone,
    Mail,
    MapPin,
    Send,
    ChevronDown,
    ChevronUp,
    MessageSquare,
    AlertTriangle,
    Lightbulb,
    ThumbsUp,
    Instagram,
    Music2,
    Youtube,
    Facebook,
    HelpCircle,
    Inbox,
    Sparkles,
} from "lucide-vue-next";

const props = defineProps({
    contacts: Object,
    faqs: Array,
});

const page = usePage();
const recaptchaSiteKey = computed(
    () =>
        page.props.recaptchaSiteKey ||
        "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI",
);

const form = useForm({
    name: "",
    nim: "",
    email: "",
    category: "",
    subject: "",
    content: "",
    attachment: null,
    "g-recaptcha-response": "",
});

const openFaq = ref(null);

const categoryOptions = [
    { value: "saran", label: "Saran", icon: Lightbulb, color: "blue" },
    { value: "kritik", label: "Kritik", icon: AlertTriangle, color: "yellow" },
    { value: "aspirasi", label: "Aspirasi", icon: ThumbsUp, color: "green" },
    { value: "aduan", label: "Aduan", icon: MessageSquare, color: "red" },
];

const contactInfo = computed(() => [
    {
        icon: Mail,
        label: "Email",
        value: props.contacts?.email,
        href: `mailto:${props.contacts?.email}`,
    },
    {
        icon: Phone,
        label: "Telepon / WhatsApp",
        value: props.contacts?.phone,
        href: `https://wa.me/${props.contacts?.phone?.replace(/[^0-9]/g, "")}`,
    },
    { icon: MapPin, label: "Alamat", value: props.contacts?.address },
]);

const socialLinks = computed(() => {
    const links = [];
    if (props.contacts?.instagram_url)
        links.push({
            icon: Instagram,
            url: props.contacts.instagram_url,
            label: "Instagram",
            color: "hover:text-pink-500",
        });
    if (props.contacts?.tiktok_url)
        links.push({
            icon: Music2,
            url: props.contacts.tiktok_url,
            label: "TikTok",
            color: "hover:text-gray-900",
        });
    if (props.contacts?.youtube_url)
        links.push({
            icon: Youtube,
            url: props.contacts.youtube_url,
            label: "YouTube",
            color: "hover:text-red-600",
        });
    if (props.contacts?.facebook_url)
        links.push({
            icon: Facebook,
            url: props.contacts.facebook_url,
            label: "Facebook",
            color: "hover:text-blue-600",
        });
    return links;
});

function toggleFaq(id) {
    openFaq.value = openFaq.value === id ? null : id;
}

function getCategoryColor(cat) {
    const map = {
        saran: "ring-blue-500 text-blue-600 bg-blue-50 border-blue-200",
        kritik: "ring-yellow-500 text-yellow-600 bg-yellow-50 border-yellow-200",
        aspirasi: "ring-green-500 text-green-600 bg-green-50 border-green-200",
        aduan: "ring-red-500 text-red-600 bg-red-50 border-red-200",
    };
    return map[cat] || "";
}

function handleFileChange(e) {
    form.attachment = e.target.files[0];
}

const recaptchaWidgetId = ref(null);

onMounted(() => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 50,
    });

    if (window.grecaptcha) {
        recaptchaWidgetId.value = window.grecaptcha.render(
            "recaptcha-container",
            {
                sitekey: recaptchaSiteKey.value,
                callback: (token) => {
                    form["g-recaptcha-response"] = token;
                },
                "expired-callback": () => {
                    form["g-recaptcha-response"] = "";
                },
            },
        );
    }
});

function submitForm() {
    form.post(route("kontak.store"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            openFaq.value = null;
        },
    });
}

const errors = computed(() => page.props.errors || {});
</script>

<template>
    <AppLayout>
        <Head title="Kontak - BEM UNIB" />

        <div
            class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-white"
        >
            <!-- Hero Section -->
            <section class="relative overflow-hidden py-24 lg:py-32">
                <!-- Animated Gradient Background -->
                <div class="absolute inset-0 hero-gradient"></div>

                <!-- Decorative Orbs -->
                <div class="absolute inset-0 overflow-hidden">
                    <div
                        class="absolute -top-40 -right-40 h-96 w-96 rounded-full bg-yellow-400/10 blur-3xl animate-pulse"
                    ></div>
                    <div
                        class="absolute -bottom-40 -left-40 h-96 w-96 rounded-full bg-green-400/10 blur-3xl animate-pulse"
                        style="animation-delay: 1s"
                    ></div>
                    <div
                        class="absolute top-1/2 left-1/3 h-64 w-64 rounded-full bg-blue-400/10 blur-3xl animate-pulse"
                        style="animation-delay: 2s"
                    ></div>
                </div>

                <!-- Grid Pattern Overlay -->
                <div
                    class="absolute inset-0 opacity-[0.03]"
                    style="
                        background-image:
                            linear-gradient(
                                rgba(255, 255, 255, 0.1) 1px,
                                transparent 1px
                            ),
                            linear-gradient(
                                90deg,
                                rgba(255, 255, 255, 0.1) 1px,
                                transparent 1px
                            );
                        background-size: 60px 60px;
                    "
                ></div>

                <!-- Decorative Shapes -->
                <div
                    class="absolute top-20 right-10 w-20 h-20 border-4 border-yellow-400/20 rounded-lg rotate-12 hidden lg:block"
                ></div>
                <div
                    class="absolute bottom-20 left-10 w-16 h-16 border-4 border-green-400/20 rounded-full hidden lg:block"
                ></div>
                <div
                    class="absolute top-40 left-1/4 w-12 h-12 bg-yellow-400/10 rounded-lg -rotate-12 hidden lg:block"
                ></div>

                <div
                    class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center"
                >
                    <div
                        data-aos="fade-down"
                        data-aos-duration="800"
                        class="inline-flex items-center gap-2 rounded-full bg-white/20 backdrop-blur-md border border-white/30 px-5 py-2 text-sm text-white shadow-lg mb-8"
                    >
                        <MessageSquare class="h-4 w-4 text-yellow-300" />
                        Hubungi Kami
                    </div>

                    <h1
                        data-aos="fade-up"
                        data-aos-duration="1000"
                        class="text-5xl font-bold tracking-tight text-white sm:text-6xl lg:text-7xl"
                    >
                        Kami Siap <span class="text-yellow-300">Mendengar</span>
                    </h1>

                    <p
                        data-aos="fade-up"
                        data-aos-delay="150"
                        data-aos-duration="1000"
                        class="mx-auto mt-6 max-w-2xl text-lg text-white/80"
                    >
                        Punya saran, kritik, aspirasi, atau aduan? Sampaikan
                        kepada kami. Setiap suara mahasiswa berarti bagi
                        kemajuan BEM UNIB.
                    </p>

                    <div
                        data-aos="fade-up"
                        data-aos-delay="300"
                        data-aos-duration="1000"
                        class="mt-10 flex flex-wrap items-center justify-center gap-4"
                    >
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-sm px-4 py-2 text-sm text-white/70 border border-white/10"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-green-400 animate-pulse"
                            ></span>
                            Aktif merespon pesan
                        </span>
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-sm px-4 py-2 text-sm text-white/70 border border-white/10"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-yellow-400"
                            ></span>
                            Respon dalam 1x24 jam
                        </span>
                    </div>
                </div>
            </section>

            <!-- Contact Info & Form Section -->
            <section class="relative -mt-10 pb-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <!-- Contact Info Cards -->
                        <div
                            class="space-y-6"
                            data-aos="fade-right"
                            data-aos-duration="800"
                        >
                            <div
                                v-if="!contacts"
                                class="rounded-2xl border-2 border-dashed border-slate-300 bg-white/50 p-8 text-center"
                            >
                                <div
                                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-slate-100"
                                >
                                    <Inbox class="h-8 w-8 text-slate-400" />
                                </div>
                                <h3 class="font-semibold text-slate-700">
                                    Informasi Kontak Belum Tersedia
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Mohon maaf, informasi kontak belum tersedia
                                    saat ini.
                                </p>
                            </div>

                            <template v-if="contacts">
                                <div
                                    v-for="(info, index) in contactInfo"
                                    :key="info.label"
                                    data-aos="fade-up"
                                    :data-aos-delay="index * 100"
                                    class="group rounded-2xl bg-white p-6 shadow-lg shadow-slate-200/50 transition-all duration-300 hover:shadow-xl border border-slate-100"
                                >
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md"
                                        >
                                            <component
                                                :is="info.icon"
                                                class="h-5 w-5"
                                            />
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-sm font-medium text-slate-500"
                                            >
                                                {{ info.label }}
                                            </p>
                                            <p
                                                class="mt-1 text-base font-semibold text-slate-800"
                                            >
                                                <template v-if="info.href">
                                                    <a
                                                        :href="info.href"
                                                        class="hover:text-blue-600 transition-colors"
                                                        >{{ info.value }}</a
                                                    >
                                                </template>
                                                <template v-else>{{
                                                    info.value
                                                }}</template>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Social Media -->
                                <div
                                    v-if="socialLinks.length"
                                    data-aos="fade-up"
                                    data-aos-delay="300"
                                    class="rounded-2xl bg-white p-6 shadow-lg shadow-slate-200/50 border border-slate-100"
                                >
                                    <p
                                        class="text-sm font-medium text-slate-500 mb-4 flex items-center gap-2"
                                    >
                                        <span
                                            class="h-2 w-2 rounded-full bg-green-500"
                                        ></span>
                                        Ikuti Kami
                                    </p>
                                    <div class="flex gap-3">
                                        <a
                                            v-for="social in socialLinks"
                                            :key="social.label"
                                            :href="social.url"
                                            target="_blank"
                                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-all duration-300 hover:scale-110 hover:shadow-lg"
                                            :class="social.color"
                                        >
                                            <component
                                                :is="social.icon"
                                                class="h-5 w-5"
                                            />
                                        </a>
                                    </div>
                                </div>

                                <!-- Maps -->
                                <div
                                    v-if="contacts.maps_embed"
                                    data-aos="fade-up"
                                    data-aos-delay="400"
                                    class="rounded-2xl overflow-hidden shadow-lg shadow-slate-200/50 border border-slate-100"
                                >
                                    <div
                                        v-html="contacts.maps_embed"
                                        class="w-full h-64"
                                    ></div>
                                </div>
                            </template>
                        </div>

                        <!-- Form -->
                        <div class="lg:col-span-2">
                            <div
                                data-aos="fade-left"
                                data-aos-duration="800"
                                class="rounded-2xl bg-white p-8 shadow-lg shadow-slate-200/50 border border-slate-100"
                            >
                                <div class="mb-8">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div
                                            class="h-8 w-1 rounded-full bg-gradient-to-b from-yellow-500 to-green-500"
                                        ></div>
                                        <h2
                                            class="text-2xl font-bold text-slate-800"
                                        >
                                            Kirim Pesan
                                        </h2>
                                    </div>
                                    <p class="text-slate-500 ml-11">
                                        Isi form di bawah untuk menyampaikan
                                        saran, kritik, aspirasi, atau aduan.
                                    </p>
                                </div>

                                <form
                                    @submit.prevent="submitForm"
                                    class="space-y-6"
                                >
                                    <div
                                        class="grid grid-cols-1 gap-6 md:grid-cols-2"
                                    >
                                        <div>
                                            <label
                                                class="mb-2 block text-sm font-medium text-slate-700"
                                            >
                                                Nama Lengkap
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <input
                                                v-model="form.name"
                                                type="text"
                                                required
                                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-500/10"
                                            />
                                            <p
                                                v-if="form.errors.name"
                                                class="mt-1 text-sm text-red-500"
                                            >
                                                {{ form.errors.name }}
                                            </p>
                                        </div>
                                        <div>
                                            <label
                                                class="mb-2 block text-sm font-medium text-slate-700"
                                            >
                                                NIM
                                                <span class="text-slate-400"
                                                    >(opsional)</span
                                                >
                                            </label>
                                            <input
                                                v-model="form.nim"
                                                type="text"
                                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-500/10"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="mb-2 block text-sm font-medium text-slate-700"
                                            >
                                                Email
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <input
                                                v-model="form.email"
                                                type="email"
                                                required
                                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-500/10"
                                            />
                                            <p
                                                v-if="form.errors.email"
                                                class="mt-1 text-sm text-red-500"
                                            >
                                                {{ form.errors.email }}
                                            </p>
                                        </div>
                                        <div>
                                            <label
                                                class="mb-2 block text-sm font-medium text-slate-700"
                                            >
                                                Kategori
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <select
                                                v-model="form.category"
                                                required
                                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-500/10"
                                            >
                                                <option value="" disabled>
                                                    Pilih kategori
                                                </option>
                                                <option
                                                    v-for="cat in categoryOptions"
                                                    :key="cat.value"
                                                    :value="cat.value"
                                                >
                                                    {{ cat.label }}
                                                </option>
                                            </select>
                                            <p
                                                v-if="form.errors.category"
                                                class="mt-1 text-sm text-red-500"
                                            >
                                                {{ form.errors.category }}
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="mb-2 block text-sm font-medium text-slate-700"
                                        >
                                            Subjek
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.subject"
                                            type="text"
                                            required
                                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-500/10"
                                        />
                                        <p
                                            v-if="form.errors.subject"
                                            class="mt-1 text-sm text-red-500"
                                        >
                                            {{ form.errors.subject }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="mb-2 block text-sm font-medium text-slate-700"
                                        >
                                            Isi Pesan
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            v-model="form.content"
                                            rows="5"
                                            required
                                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-500/10"
                                        ></textarea>
                                        <p
                                            v-if="form.errors.content"
                                            class="mt-1 text-sm text-red-500"
                                        >
                                            {{ form.errors.content }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="mb-2 block text-sm font-medium text-slate-700"
                                        >
                                            Lampiran
                                            <span class="text-slate-400"
                                                >(opsional, PDF/JPEG/PNG, maks
                                                5MB)</span
                                            >
                                        </label>
                                        <input
                                            type="file"
                                            accept=".pdf,.jpeg,.jpg,.png"
                                            @change="handleFileChange"
                                            class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white file:rounded-lg hover:file:bg-blue-700"
                                        />
                                        <p
                                            v-if="form.errors.attachment"
                                            class="mt-1 text-sm text-red-500"
                                        >
                                            {{ form.errors.attachment }}
                                        </p>
                                    </div>

                                    <!-- reCAPTCHA -->
                                    <div>
                                        <div id="recaptcha-container"></div>
                                        <p
                                            v-if="
                                                form.errors[
                                                    'g-recaptcha-response'
                                                ]
                                            "
                                            class="mt-1 text-sm text-red-500"
                                        >
                                            {{
                                                form.errors[
                                                    "g-recaptcha-response"
                                                ]
                                            }}
                                        </p>
                                    </div>

                                    <!-- Category Tags Preview -->
                                    <div
                                        v-if="form.category"
                                        class="flex flex-wrap gap-2"
                                    >
                                        <span
                                            v-for="cat in categoryOptions.filter(
                                                (c) =>
                                                    c.value === form.category,
                                            )"
                                            :key="cat.value"
                                            class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-xs font-medium ring-1 shadow-sm"
                                            :class="getCategoryColor(cat.value)"
                                        >
                                            <component
                                                :is="cat.icon"
                                                class="h-3.5 w-3.5"
                                            />
                                            {{ cat.label }}
                                        </span>
                                    </div>

                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-8 py-4 text-base font-semibold text-white shadow-lg shadow-yellow-500/25 transition-all duration-300 hover:bg-blue-700 hover:shadow-xl hover:shadow-yellow-500/30 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <Send class="h-5 w-5" />
                                        <span v-if="form.processing"
                                            >Mengirim...</span
                                        >
                                        <span v-else>Kirim Pesan</span>
                                    </button>
                                </form>

                                <!-- Info -->
                                <div
                                    class="mt-6 rounded-xl bg-gradient-to-r from-yellow-50 to-green-50 border border-yellow-200 p-4 text-sm text-slate-700"
                                >
                                    <p class="flex items-center gap-2">
                                        <span
                                            class="h-2 w-2 rounded-full bg-green-500"
                                        ></span>
                                        Setiap pesan akan dibaca dan
                                        ditindaklanjuti oleh tim BEM UNIB.
                                        Balasan akan dikirim melalui email yang
                                        kamu daftarkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="pb-24">
                <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                    <div data-aos="fade-up" class="text-center mb-12">
                        <div
                            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-yellow-100 to-green-100 px-5 py-2 text-sm font-medium text-slate-700 border border-yellow-200/50 shadow-sm mb-4"
                        >
                            <HelpCircle class="h-4 w-4 text-green-600" />
                            FAQ
                        </div>
                        <h2 class="text-3xl font-bold text-slate-800">
                            Pertanyaan yang Sering Diajukan
                        </h2>
                        <p class="mt-2 text-slate-500">
                            Temukan jawaban untuk pertanyaan yang paling sering
                            ditanyakan.
                        </p>
                        <div
                            class="w-20 h-1 bg-gradient-to-r from-yellow-500 to-green-500 mx-auto rounded-full mt-4"
                        ></div>
                    </div>

                    <!-- Empty State FAQ -->
                    <div
                        v-if="!faqs || !faqs.length"
                        class="rounded-2xl border-2 border-dashed border-slate-300 bg-white/50 p-12 text-center"
                    >
                        <div
                            class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100"
                        >
                            <HelpCircle class="h-10 w-10 text-slate-400" />
                        </div>
                        <h3 class="text-xl font-semibold text-slate-700">
                            FAQ Belum Tersedia
                        </h3>
                        <p class="mt-2 text-slate-500">
                            Belum ada pertanyaan yang sering diajukan saat ini.
                        </p>
                    </div>

                    <!-- FAQ List -->
                    <div v-if="faqs && faqs.length" class="space-y-4">
                        <div
                            v-for="(faq, index) in faqs"
                            :key="faq.id"
                            class="group rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:shadow-md"
                            :class="{
                                'ring-2 ring-yellow-400/20 border-yellow-200':
                                    openFaq === faq.id,
                            }"
                        >
                            <button
                                @click="toggleFaq(faq.id)"
                                class="flex w-full items-center justify-between px-6 py-5 text-left"
                            >
                                <span
                                    class="text-base font-semibold text-slate-800 pr-4"
                                    :class="{
                                        'text-yellow-700': openFaq === faq.id,
                                    }"
                                    >{{ faq.question }}</span
                                >
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg transition-all duration-300"
                                    :class="
                                        openFaq === faq.id
                                            ? 'bg-gradient-to-br from-yellow-500 to-green-500 text-white rotate-180'
                                            : 'bg-slate-100 text-slate-500'
                                    "
                                >
                                    <ChevronDown
                                        class="h-4 w-4 transition-transform duration-300"
                                    />
                                </div>
                            </button>
                            <transition
                                enter-active-class="transition-all duration-300 ease-out"
                                enter-from-class="max-h-0 opacity-0"
                                enter-to-class="max-h-96 opacity-100"
                                leave-active-class="transition-all duration-200 ease-in"
                                leave-from-class="max-h-96 opacity-100"
                                leave-to-class="max-h-0 opacity-0"
                            >
                                <div
                                    v-if="openFaq === faq.id"
                                    class="overflow-hidden"
                                >
                                    <div
                                        class="border-t border-slate-100 px-6 py-5"
                                    >
                                        <p
                                            class="text-slate-600 leading-relaxed"
                                        >
                                            {{ faq.answer }}
                                        </p>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.hero-gradient {
    background: linear-gradient(
        135deg,
        #1e3a5f,
        #2563eb,
        #059669,
        #ca8a04,
        #1e3a5f
    );
    background-size: 400% 400%;
    animation: gradientShift 12s ease infinite;
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    25% {
        background-position: 100% 0%;
    }
    50% {
        background-position: 100% 100%;
    }
    75% {
        background-position: 0% 100%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>
