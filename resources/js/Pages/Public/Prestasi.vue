<template>
    <AppLayout>
        <Head title="Prestasi | IKSASS Banten" />

        <!-- HERO -->
        <section
            class="relative py-24 bg-gradient-to-br from-green-700 via-green-600 to-yellow-500 text-white"
        >
            <div class="max-w-6xl mx-auto px-6 text-center" data-aos="fade-up">
                <Trophy class="w-14 h-14 mx-auto mb-6 text-yellow-200" />
                <h1 class="text-4xl lg:text-5xl font-extrabold mb-4">
                    Prestasi Warga IKSASS Banten
                </h1>
                <p class="max-w-2xl mx-auto text-green-100">
                    Kumpulan prestasi membanggakan warga rayon IKSASS Banten
                </p>
            </div>
        </section>

        <section class="-mt-12 relative z-10">
            <div
                class="max-w-5xl mx-auto bg-white rounded-t-2xl md:rounded-2xl shadow-xl p-6 flex flex-wrap gap-4 items-center justify-center"
                data-aos="zoom-in"
            >
                <!-- SEARCH -->
                <div class="relative w-full sm:w-1/2">
                    <Search
                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-600"
                    />
                    <input
                        v-model="filters.search"
                        type="text"
                        placeholder="Cari nama, prestasi, kategori..."
                        class="pl-10 pr-4 py-2 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-600 outline-none"
                    />
                </div>

                <!-- DATE -->
                <DateRangeFilter
                    :start="filters.start"
                    :end="filters.end"
                    @update="onDateUpdate"
                />
            </div>
        </section>

        <!-- CONTENT -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                <!-- EMPTY STATE -->
                <div
                    v-if="prestasis.data.length === 0"
                    class="text-center py-24 text-gray-500"
                    data-aos="fade-up"
                >
                    <Award class="w-16 h-16 mx-auto mb-4 opacity-40" />
                    <p class="text-lg">
                        Belum ada data prestasi yang ditampilkan
                    </p>
                </div>

                <!-- GRID PRESTASI -->
                <div
                    v-if="prestasis.data.length"
                    class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 auto-rows-fr"
                >
                    <div
                        v-for="(item, index) in prestasis.data.slice(
                            0,
                            prestasis.current_page === 1 ? 8 : 9
                        )"
                        :key="item.id"
                        data-aos="fade-up"
                        :data-aos-delay="index * 100"
                        :class="[
                            'group bg-white relative rounded-2xl overflow-hidden transition-all duration-300',
                            'shadow-md hover:shadow-2xl hover:-translate-y-1',
                            index === 0 && prestasis.current_page === 1
                                ? 'lg:col-span-2'
                                : '',
                        ]"
                    >
                        <!-- IMAGE -->
                        <div
                            :class="[
                                'relative overflow-hidden',
                                index === 0 && prestasis.current_page === 1
                                    ? 'h-48 lg:h-[420px]'
                                    : 'h-48',
                            ]"
                        >
                            <img
                                v-if="item.dokumentasi"
                                :src="item.dokumentasi"
                                :class="[
                                    'w-full h-full object-cover transition-transform duration-500 group-hover:scale-110',
                                    index === 0 && prestasis.current_page === 1
                                        ? 'rounded-xl'
                                        : '',
                                ]"
                            />

                            <div
                                v-else
                                class="h-full bg-gradient-to-br from-green-400 to-yellow-400 flex items-center justify-center"
                            >
                                <Trophy class="w-12 h-12 text-white" />
                            </div>
                            <div
                                v-if="
                                    index === 0 && prestasis.current_page === 1
                                "
                                class="hidden lg:flex absolute top-4 left-4 z-20"
                            >
                                <div
                                    class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-yellow-600 to-yellow-500 text-white text-xs font-extrabold tracking-wide shadow-xl ring-2 ring-yellow-400/40 animate-pulse"
                                >
                                    <Sparkles class="w-4 h-4" />
                                    <span>Prestasi Terbaru</span>
                                </div>
                            </div>

                            <!-- OVERLAY DETAIL (HANYA PRESTASI TERBARU) -->
                            <div
                                v-if="
                                    index === 0 && prestasis.current_page === 1
                                "
                                class="hidden lg:flex absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 items-end"
                            >
                                <div class="p-8 text-white space-y-3">
                                    <h3
                                        class="text-3xl font-extrabold leading-tight"
                                    >
                                        {{ item.prestasi }}
                                    </h3>

                                    <p
                                        class="text-sm text-gray-200 line-clamp-2 max-w-xl"
                                    >
                                        {{ item.deskripsi }}
                                    </p>

                                    <div
                                        class="flex flex-wrap gap-4 text-sm text-gray-200 pt-2"
                                    >
                                        <div class="flex items-center gap-2">
                                            <User
                                                class="w-4 h-4 text-green-300"
                                            />
                                            <span>{{ item.nama }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <MapPin
                                                class="w-4 h-4 text-green-300"
                                            />
                                            <span
                                                >{{ item.tempat }} –
                                                {{ item.tingkat }}</span
                                            >
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <Calendar
                                                class="w-4 h-4 text-green-300"
                                            />
                                            <span>{{ item.tanggal }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONTENT NORMAL -->
                        <div
                            :class="[
                                'p-6',
                                index === 0 && prestasis.current_page === 1
                                    ? 'lg:hidden'
                                    : '',
                            ]"
                        >
                            <h3 class="font-bold text-lg text-gray-800 mb-2">
                                {{ item.prestasi }}
                            </h3>

                            <p class="text-sm text-gray-500 mb-3 line-clamp-3">
                                {{ item.deskripsi }}
                            </p>

                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <User class="w-4 h-4 text-green-600" />
                                    <span class="font-medium">{{
                                        item.nama
                                    }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <MapPin class="w-4 h-4 text-green-600" />
                                    <span
                                        >{{ item.tempat }} –
                                        {{ item.tingkat }}</span
                                    >
                                </div>

                                <div class="flex items-center gap-2">
                                    <Calendar class="w-4 h-4 text-green-600" />
                                    <span>{{ item.tanggal }}</span>
                                </div>
                            </div>

                            <!-- BADGE -->
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span
                                    v-if="item.kategori"
                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700"
                                >
                                    {{ item.kategori }}
                                </span>
                                <span
                                    v-if="item.penyelenggara"
                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700"
                                >
                                    {{ item.penyelenggara }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="prestasis.data.length"
                    class="flex justify-center items-center mt-16 gap-2 flex-wrap"
                >
                    <button
                        v-for="(link, index) in prestasis.links"
                        :key="index"
                        @click="goToPrestasi(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all font-medium"
                        :class="[
                            link.active
                                ? 'bg-green-600 text-white border-green-700 shadow-lg scale-105'
                                : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100 hover:shadow',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <!-- Previous -->
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />

                        <!-- Next -->
                        <ChevronRight
                            v-else-if="index === prestasis.links.length - 1"
                            class="w-4 h-4"
                        />

                        <!-- Number -->
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import { Head, router } from "@inertiajs/vue3";
import { reactive, watch, onMounted } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";
import {
    Trophy,
    Calendar,
    MapPin,
    User,
    Award,
    Search,
    ChevronLeft,
    ChevronRight,
    Sparkles,
} from "lucide-vue-next";
import DateRangeFilter from "@/Components/Filters/DateRangeFilter.vue";

const props = defineProps({
    prestasis: Object,
    filters: Object,
});

// reactive filter state
const filters = reactive({
    search: props.filters.search || "",
    start: props.filters.start || "",
    end: props.filters.end || "",
});

// AUTO SEARCH (debounced ringan)
let searchTimeout = null;
watch(
    () => filters.search,
    () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilter, 400);
    }
);

// Date filter handler
const onDateUpdate = (val) => {
    filters.start = val.start;
    filters.end = val.end;
    applyFilter();
};

// Apply filter (tanpa reload kasar)
const applyFilter = () => {
    router.get(route("prestasi"), filters, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Pagination handler
const goToPrestasi = (url) => {
    if (!url) return;

    router.get(
        url,
        {},
        {
            preserveState: true,
            replace: true,
        }
    );
};

onMounted(() => {
    AOS.init({ duration: 900, once: true });
});
</script>
