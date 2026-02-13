<template>
    <AppLayout>
        <Head title="Beranda | IKSASS Banten" />

        <!-- HERO SECTION -->
        <section
            class="relative min-h-[88vh] bg-cover bg-center overflow-hidden flex items-center"
            style="background-image: url('/images/white-green-bg.jpg')"
        >
            <!-- Overlay -->
            <div class="absolute inset-0 bg-white/80 backdrop-blur-[2px]"></div>

            <!-- Decorative Shape -->
            <div
                class="absolute -top-32 -right-32 w-96 h-96 bg-green-200 rounded-full blur-3xl opacity-40"
            ></div>
            <div
                class="absolute bottom-0 -left-32 w-96 h-96 bg-yellow-200 rounded-full blur-3xl opacity-30"
            ></div>

            <div
                class="relative max-w-7xl mx-auto my-12 lg:my-0 px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-0 items-center"
            >
                <!-- LOGO / VISUAL -->
                <div
                    class="relative flex justify-center lg:justify-end order-2"
                    data-aos="zoom-in"
                >
                    <div
                        class="relative bg-white rounded-3xl p-8 shadow-2xl group"
                    >
                        <!-- IMAGE -->
                        <img
                            src="/public/images/logo.png"
                            alt="Logo IKSASS Banten"
                            aria-label="Logo IKSASS"
                            class="w-72 sm:w-80 lg:w-96 relative z-10 transform transition duration-500 group-hover:rotate-3"
                        />

                        <!-- TOOLTIP CARD -->
                        <div
                            class="hidden lg:block pointer-events-none absolute -bottom-20 left-1/2 -translate-x-1/2 opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 group-focus-within:opacity-100 group-focus-within:scale-100 transition-all duration-300 delay-300"
                        >
                            <div
                                class="relative bg-white text-gray-800 px-4 py-3 rounded-xl shadow-xl border border-green-100 w-max"
                            >
                                <!-- Arrow -->
                                <div
                                    class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white border-l border-t border-green-100 rotate-45"
                                ></div>

                                <!-- Content -->
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center"
                                    >
                                        <Info class="w-5 h-5 text-green-700" />
                                    </div>

                                    <div class="text-left">
                                        <p class="font-semibold text-sm">
                                            Logo IKSASS
                                        </p>
                                        <p
                                            class="text-xs text-gray-500 leading-snug"
                                        >
                                            Identitas resmi IKSASS
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Glow -->
                    <div
                        class="hidden lg:block absolute -inset-6 bg-gradient-to-r from-green-300 to-yellow-300 rounded-3xl blur-2xl opacity-40"
                    ></div>
                </div>

                <!-- TEXT CONTENT -->
                <div
                    class="order-1 text-center lg:text-left"
                    data-aos="fade-right"
                >
                    <span
                        class="inline-block mb-4 px-4 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold tracking-wide"
                    >
                        IKSASS BANTEN
                    </span>

                    <h1
                        class="text-4xl lg:text-6xl font-extrabold text-gray-900 leading-tight"
                    >
                        Ikatan Santri <br />
                        <span class="text-green-700">
                            Salafiyyah Syafi’iyah
                        </span>
                        <span class="text-yellow-500"> Banten</span>
                    </h1>

                    <p
                        class="mt-6 text-xl text-gray-700 font-medium italic max-w-xl mx-auto lg:mx-0"
                    >
                        “Berjuang Bersama, Mengabdi Tanpa Batas”
                    </p>

                    <div
                        class="mt-10 flex gap-4 flex-wrap justify-center lg:justify-start"
                    >
                        <Link
                            href="/tentang"
                            class="inline-flex items-center gap-2 bg-green-700 text-white px-7 py-3 rounded-xl font-semibold shadow-lg hover:bg-green-800 hover:shadow-xl transition-all duration-300"
                        >
                            <Info class="w-5 h-5" />
                            Tentang IKSASS
                        </Link>

                        <Link
                            href="/kontak"
                            class="inline-flex items-center gap-2 border-2 border-green-700 text-green-700 px-7 py-3 rounded-xl font-semibold hover:bg-green-700 hover:text-white transition-all duration-300"
                        >
                            <Phone class="w-5 h-5" />
                            Kontak Kami
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sekilas Tentang IKSASS -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2
                        class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4"
                    >
                        Sekilas Tentang
                        <span class="text-green-600">IKSASS</span>
                    </h2>
                    <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                </div>

                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center"
                >
                    <div data-aos="fade-right">
                        <div
                            class="bg-gradient-to-br from-green-50 to-yellow-50 rounded-2xl p-8 shadow-lg"
                        >
                            <p
                                class="text-lg text-gray-700 leading-relaxed mb-6"
                            >
                                Ikatan Santri Salafiyyah Syafi'iyah (IKSASS)
                                Banten merupakan organisasi yang berlandaskan
                                nilai-nilai Islam Ahlus Sunnah Wal Jama'ah.
                                Berawal dari jaringan santri Pondok Pesantren
                                Salafiyyah Syafi'iyah Sukorejo, IKSASS
                                berkembang sebagai wadah pengkaderan dan
                                pengabdian masyarakat.
                            </p>
                            <p class="text-lg text-gray-700 leading-relaxed">
                                Organisasi ini lahir dari semangat untuk menjaga
                                tradisi keilmuan salaf sekaligus menjawab
                                tantangan zaman dengan inovasi dan
                                progresivitas.
                            </p>
                        </div>
                    </div>

                    <div data-aos="fade-left">
                        <div class="relative">
                            <div
                                class="bg-green-600 rounded-2xl p-8 text-white shadow-xl"
                            >
                                <h3 class="text-2xl font-bold mb-6">
                                    Keterkaitan dengan Pesantren Sukorejo
                                </h3>
                                <p class="text-lg leading-relaxed mb-6">
                                    IKSASS memiliki hubungan erat dengan Pondok
                                    Pesantren Salafiyyah Syafi'iyah Sukorejo
                                    sebagai basis utama pengkaderan dan
                                    pengembangan SDM santri.
                                </p>
                                <p class="text-lg leading-relaxed">
                                    Nilai-nilai yang diajarkan di pesantren
                                    menjadi fondasi utama dalam setiap program
                                    dan kegiatan IKSASS.
                                </p>
                            </div>
                            <div
                                class="absolute -bottom-4 -right-4 bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg"
                            >
                                <Link
                                    href="/about"
                                    class="font-semibold text-lg flex items-center gap-2"
                                >
                                    Baca Selengkapnya
                                    <ArrowRight class="h-5 w-5" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Visi & Misi -->
        <section class="py-20 bg-gradient-to-br from-green-50 to-yellow-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2
                        class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4"
                    >
                        Visi & <span class="text-green-600">Misi</span>
                    </h2>
                    <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Visi -->
                    <div data-aos="fade-right">
                        <div
                            class="bg-white rounded-2xl p-8 shadow-lg h-full border-l-4 border-green-600"
                        >
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4"
                                >
                                    <Target class="h-6 w-6 text-green-600" />
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">
                                    Visi IKSASS
                                </h3>
                            </div>
                            <p class="text-gray-700 leading-relaxed text-lg">
                                Terbentuknya kader santri yang bertaqwa, berilmu
                                amaliah, inovatif, mandiri, progresif, serta
                                bertanggung jawab terhadap agama, bangsa, dan
                                negara.
                            </p>
                        </div>
                    </div>

                    <!-- Misi -->
                    <div data-aos="fade-left">
                        <div
                            class="bg-white rounded-2xl p-8 shadow-lg h-full border-l-4 border-yellow-500"
                        >
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mr-4"
                                >
                                    <ListChecks
                                        class="h-6 w-6 text-yellow-600"
                                    />
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">
                                    Misi IKSASS
                                </h3>
                            </div>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <CheckCircle
                                        class="h-5 w-5 text-green-600 mt-1 mr-3 flex-shrink-0"
                                    />
                                    <span class="text-gray-700"
                                        >Meningkatkan dan mengembangkan SDM
                                        anggota</span
                                    >
                                </li>
                                <li class="flex items-start">
                                    <CheckCircle
                                        class="h-5 w-5 text-green-600 mt-1 mr-3 flex-shrink-0"
                                    />
                                    <span class="text-gray-700"
                                        >Melakukan pengkaderan secara
                                        sistematis</span
                                    >
                                </li>
                                <li class="flex items-start">
                                    <CheckCircle
                                        class="h-5 w-5 text-green-600 mt-1 mr-3 flex-shrink-0"
                                    />
                                    <span class="text-gray-700"
                                        >Meningkatkan kesejahteraan anggota dan
                                        masyarakat</span
                                    >
                                </li>
                                <li class="flex items-start">
                                    <CheckCircle
                                        class="h-5 w-5 text-green-600 mt-1 mr-3 flex-shrink-0"
                                    />
                                    <span class="text-gray-700"
                                        >Mengaktualisasikan nilai-nilai Islam
                                        Ahlus Sunnah Wal Jamaah dan
                                        kebangsaan</span
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Berita & Informasi Terbaru -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0 mb-12"
                    data-aos="fade-up"
                >
                    <div
                        class="lg:block flex flex-col items-center justify-center"
                    >
                        <h2
                            class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2"
                        >
                            Berita &
                            <span class="text-green-600">Informasi</span>
                        </h2>
                        <p class="text-gray-600">
                            Informasi terbaru seputar kegiatan IKSASS
                        </p>
                    </div>
                    <Link
                        href="/news"
                        class="text-green-600 font-semibold hover:text-green-700 flex items-center gap-2"
                    >
                        Lihat Semua Berita
                        <ArrowRight class="h-5 w-5" />
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        v-for="(news, index) in latestNews"
                        :key="news.id"
                        :data-aos="'fade-up'"
                        :data-aos-delay="index * 100"
                        class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group border border-green-100"
                    >
                        <div
                            class="h-48 bg-gradient-to-r from-green-400 to-yellow-400 relative overflow-hidden"
                        >
                            <div
                                class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-all duration-300"
                            ></div>
                            <div class="absolute top-4 left-4">
                                <span
                                    class="bg-white text-green-700 px-3 py-1 rounded-full text-sm font-semibold"
                                >
                                    {{ news.category }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3
                                class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors duration-300"
                            >
                                {{ news.title }}
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">
                                {{ news.excerpt }}
                            </p>
                            <div
                                class="flex items-center justify-between text-sm text-gray-500"
                            >
                                <span>{{ news.date }}</span>
                                <Link
                                    :href="`/news/${news.id}`"
                                    class="text-green-600 hover:text-green-700 font-semibold flex items-center gap-1"
                                >
                                    Baca Selengkapnya
                                    <ArrowRight class="h-4 w-4" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dokumentasi Visual IKSASS -->
        <section
            class="relative py-20 overflow-hidden bg-gradient-to-b from-white via-green-50/30 to-yellow-50/20"
        >
            <!-- Background decorative elements -->
            <div
                class="absolute top-0 left-0 w-72 h-72 bg-green-200/20 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-200/20 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"
            ></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header with Animation -->
                <div
                    class="text-center mb-20"
                    data-aos="fade-up"
                    data-aos-duration="800"
                >
                    <div
                        class="inline-flex items-center gap-3 bg-gradient-to-r from-green-100 to-yellow-100 px-6 py-2 rounded-full mb-4"
                    >
                        <Grid3x3 class="w-5 h-5 text-green-600" />
                        <span class="text-green-700 font-semibold"
                            >Dokumentasi Visual</span
                        >
                    </div>
                    <h2
                        class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight"
                    >
                        Galeri
                        <span class="relative inline-block">
                            <span class="relative z-10 text-green-600"
                                >Kegiatan</span
                            >
                            <span
                                class="absolute bottom-2 left-0 w-full h-3 bg-yellow-300/40 -rotate-1"
                            ></span>
                        </span>
                        IKSASS
                    </h2>
                    <p
                        class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed"
                    >
                        Jejak perjuangan dan pengabdian dalam setiap momen
                        berharga. Dokumentasi lengkap berbagai program dan
                        aktivitas IKSASS Banten.
                    </p>
                </div>

                <!-- Enhanced Slider Container -->
                <div class="relative" data-aos="fade-up" data-aos-delay="300">
                    <!-- Navigation Buttons -->
                    <button
                        @click="scrollLeft"
                        :disabled="currentIndex === 0"
                        :class="[
                            'absolute top-1/4 left-0 lg:top-1/2 lg:-left-12 transform -translate-y-1/2 z-20 w-14 h-14 rounded-full shadow-xl transition-all duration-300 hover:scale-110 flex items-center justify-center',
                            currentIndex === 0
                                ? 'bg-gray-200 cursor-not-allowed'
                                : 'bg-white hover:bg-green-50',
                        ]"
                    >
                        <ChevronLeft
                            :class="[
                                'w-6 h-6',
                                currentIndex === 0
                                    ? 'text-gray-400'
                                    : 'text-green-600',
                            ]"
                        />
                    </button>

                    <button
                        @click="scrollRight"
                        :disabled="
                            currentIndex >= latestDocs.length - cardsPerView
                        "
                        :class="[
                            'absolute top-1/4 right-0 lg:top-1/2 lg:-right-12 transform -translate-y-1/2 z-20 w-14 h-14 rounded-full shadow-xl transition-all duration-300 hover:scale-110 flex items-center justify-center',
                            currentIndex >= latestDocs.length - cardsPerView
                                ? 'bg-gray-200 cursor-not-allowed'
                                : 'bg-white hover:bg-green-50',
                        ]"
                    >
                        <ChevronRight
                            :class="[
                                'w-6 h-6',
                                currentIndex >= latestDocs.length - cardsPerView
                                    ? 'text-gray-400'
                                    : 'text-green-600',
                            ]"
                        />
                    </button>

                    <!-- Slider with enhanced cards -->
                    <div
                        ref="sliderRef"
                        class="flex overflow-x-auto gap-8 pb-10 snap-x snap-mandatory scrollbar-hide"
                        style="scroll-behavior: smooth"
                    >
                        <div
                            v-for="(doc, index) in latestDocs"
                            :key="doc.id"
                            class="snap-center flex-shrink-0 w-[calc(100%-2rem)] md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)]"
                        >
                            <div
                                class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-500 h-full flex flex-col border border-green-100/50"
                            >
                                <!-- Image Container -->
                                <div class="relative h-72 overflow-hidden">
                                    <!-- Main Media -->
                                    <div class="relative h-full">
                                        <img
                                            v-if="
                                                doc.photo &&
                                                !doc.photo.match(
                                                    /\.(mp4|webm|mov)$/i
                                                )
                                            "
                                            :src="doc.photo"
                                            :alt="doc.title"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                        />
                                        <!-- Overlay Gradient -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-6 flex-1 flex flex-col">
                                    <!-- Date & Location -->
                                    <div
                                        class="flex items-center gap-4 text-sm text-gray-500 mb-4"
                                    >
                                        <div class="flex items-center gap-2">
                                            <Calendar
                                                class="w-4 h-4 text-green-600"
                                            />
                                            <span>{{ doc.date }}</span>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-green-600 transition-colors duration-300"
                                    >
                                        {{ doc.title }}
                                    </h3>

                                    <!-- Description -->
                                    <p
                                        class="text-gray-600 mb-6 flex-1 line-clamp-3"
                                    >
                                        {{ doc.description }}
                                    </p>

                                    <!-- Footer with Actions -->
                                    <div
                                        class="flex items-center justify-end pt-4 border-t border-gray-100"
                                    >
                                        <button
                                            @click="openPreview(doc)"
                                            class="group flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-500 text-white rounded-xl hover:from-green-700 hover:to-green-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105"
                                        >
                                            <span class="font-semibold"
                                                >Detail</span
                                            >
                                            <ArrowRight
                                                class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                            />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scroll Indicator -->
                    <div class="flex justify-center items-center gap-2">
                        <div
                            v-for="n in Math.ceil(
                                latestDocs.length / cardsPerView
                            )"
                            :key="n"
                            @click="currentIndex = (n - 1) * cardsPerView"
                            :class="[
                                'w-2 h-2 rounded-full transition-all duration-300 cursor-pointer',
                                Math.floor(currentIndex / cardsPerView) ===
                                n - 1
                                    ? 'w-8 bg-green-600'
                                    : 'bg-gray-300 hover:bg-gray-400',
                            ]"
                        ></div>
                    </div>
                </div>

                <!-- View All Button -->
                <div
                    class="text-center mt-10"
                    data-aos="fade-up"
                    data-aos-delay="400"
                >
                    <Link
                        href="/documentation"
                        class="relative inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 via-lime-600 to-yellow-500 text-white rounded-2xl overflow-hidden transition-all duration-300 shadow-xl group"
                    >
                        <!-- Hover animation layer -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none"
                        ></div>

                        <!-- Content -->
                        <Grid3x3 class="w-5 h-5 relative z-10" />
                        <span class="text-lg font-bold relative z-10">
                            Lihat Semua Dokumentasi
                        </span>
                        <ArrowRight
                            class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform"
                        />
                    </Link>
                </div>
            </div>
        </section>

        <!-- Preview Modal -->
        <teleport to="body">
            <transition name="modal-fade">
                <div
                    v-if="preview"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    @click.self="preview = null"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-black/80 backdrop-blur-sm"
                    ></div>

                    <!-- Modal Container -->
                    <div
                        class="relative w-full max-w-3xl max-h-[85vh] animate-modalIn"
                        @click.stop
                    >
                        <!-- Close Button -->
                        <button
                            @click="preview = null"
                            class="absolute -top-3 -right-3 z-50 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-all duration-300 hover:rotate-90 shadow border border-white/20"
                        >
                            <X class="w-5 h-5" />
                        </button>

                        <!-- Modal Content -->
                        <div
                            class="bg-white rounded-xl overflow-hidden shadow-lg"
                        >
                            <!-- Top Bar -->
                            <div class="px-5 py-3 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-green-100 rounded-xl">
                                        <ImageIcon
                                            class="w-4 h-4 text-green-600"
                                        />
                                    </div>
                                    <div>
                                        <h3
                                            class="text-md font-bold text-gray-900"
                                        >
                                            {{ preview.title }}
                                        </h3>
                                        <div
                                            class="flex items-center gap-2 text-gray-500 text-xs"
                                        >
                                            <Calendar class="w-3 h-3" />
                                            {{ preview.date }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Media Area -->
                            <div
                                class="relative h-[40vh] bg-gradient-to-br from-green-900 to-gray-900"
                            >
                                <div class="relative h-full overflow-hidden">
                                    <img
                                        v-if="preview.photo && !isVideo"
                                        :src="preview.photo"
                                        :alt="preview.title"
                                        class="w-full h-full object-contain bg-black"
                                    />
                                    <div
                                        v-else
                                        class="h-full flex items-center justify-center text-white"
                                    >
                                        <div class="text-center">
                                            <ImageIcon
                                                class="w-16 h-16 mx-auto mb-2 opacity-50"
                                            />
                                            <p class="text-gray-400 text-sm">
                                                Gambar tidak tersedia
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Area -->
                            <div class="p-6 overflow-y-auto max-h-[35vh]">
                                <!-- Description -->
                                <div class="mb-6">
                                    <h4
                                        class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2"
                                    >
                                        <span
                                            class="w-6 h-px bg-green-500"
                                        ></span>
                                        Deskripsi Kegiatan
                                    </h4>
                                    <p
                                        class="text-gray-700 leading-relaxed text-base"
                                    >
                                        {{
                                            preview.description ||
                                            "Tidak ada deskripsi untuk dokumentasi ini."
                                        }}
                                    </p>
                                </div>

                                <!-- Additional Info -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6"
                                >
                                    <div
                                        v-if="preview.participants"
                                        class="bg-green-50 rounded-2xl p-4"
                                    >
                                        <div
                                            class="flex items-center gap-2 mb-2"
                                        >
                                            <Users
                                                class="w-4 h-4 text-green-600"
                                            />
                                            <span
                                                class="font-semibold text-gray-900"
                                                >Peserta</span
                                            >
                                        </div>
                                        <p
                                            class="text-xl font-bold text-green-600"
                                        >
                                            {{ preview.participants }}
                                        </p>
                                    </div>
                                    <div
                                        v-if="preview.category"
                                        class="bg-yellow-50 rounded-2xl p-4"
                                    >
                                        <div
                                            class="flex items-center gap-2 mb-2"
                                        >
                                            <Grid3x3
                                                class="w-4 h-4 text-yellow-600"
                                            />
                                            <span
                                                class="font-semibold text-gray-900"
                                                >Kategori</span
                                            >
                                        </div>
                                        <span
                                            class="inline-block px-3 py-1 bg-yellow-500 text-white rounded-full text-sm font-semibold"
                                        >
                                            {{ preview.category }}
                                        </span>
                                    </div>
                                    <div
                                        v-if="preview.organizer"
                                        class="bg-green-50 rounded-2xl p-4"
                                    >
                                        <div
                                            class="flex items-center gap-2 mb-2"
                                        >
                                            <Users
                                                class="w-4 h-4 text-green-600"
                                            />
                                            <span
                                                class="font-semibold text-gray-900"
                                                >Penanggung Jawab</span
                                            >
                                        </div>
                                        <p class="text-sm text-gray-700">
                                            {{ preview.organizer }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Link / File -->
                                <div
                                    v-if="preview.link || preview.file"
                                    class="border-t border-gray-100 pt-4"
                                >
                                    <h4
                                        class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-2"
                                    >
                                        <span
                                            class="w-6 h-px bg-green-500"
                                        ></span>
                                        Unduhan & Link
                                    </h4>
                                    <div class="flex flex-wrap gap-3">
                                        <a
                                            v-if="preview.link"
                                            :href="preview.link"
                                            target="_blank"
                                            class="group flex-1 min-w-[250px] flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-xl transition"
                                        >
                                            <div
                                                class="p-2 bg-white rounded-lg shadow-sm"
                                            >
                                                <ExternalLink
                                                    class="w-5 h-5 text-green-600"
                                                />
                                            </div>
                                            <div class="flex-1">
                                                <p
                                                    class="font-semibold text-gray-900 mb-1"
                                                >
                                                    Lihat Dokumentasi Lengkap
                                                </p>
                                                <p
                                                    class="text-sm text-gray-600 truncate"
                                                >
                                                    {{ preview.link }}
                                                </p>
                                            </div>
                                            <ArrowRight
                                                class="w-4 h-4 text-gray-400 group-hover:text-green-600 transition"
                                            />
                                        </a>
                                        <a
                                            v-if="preview.file"
                                            :href="preview.file"
                                            download
                                            class="group flex-1 min-w-[250px] flex items-center gap-3 p-3 bg-yellow-50 hover:bg-yellow-100 rounded-xl transition"
                                        >
                                            <div
                                                class="p-2 bg-white rounded-lg shadow-sm"
                                            >
                                                <Download
                                                    class="w-5 h-5 text-yellow-600"
                                                />
                                            </div>
                                            <div class="flex-1">
                                                <p
                                                    class="font-semibold text-gray-900 mb-1"
                                                >
                                                    Unduh File Lengkap
                                                </p>
                                                <p
                                                    class="text-sm text-gray-600"
                                                >
                                                    Klik untuk mengunduh
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </teleport>

        <!-- Struktur Pengurus -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2
                        class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4"
                    >
                        Struktur <span class="text-green-600">Pengurus</span>
                    </h2>
                    <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12 lg:px-50"
                >
                    <div
                        v-for="(officer, index) in strukturPengurus"
                        :key="index"
                        :data-aos="'fade-up'"
                        :data-aos-delay="index * 100"
                        class="text-center"
                    >
                        <div class="relative inline-block mb-6">
                            <div
                                class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-green-600 shadow-lg"
                            >
                                <img
                                    v-if="officer.photo"
                                    :src="officer.photo"
                                    :alt="'Foto ' + officer.name"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-gradient-to-br from-green-400 to-yellow-400 flex items-center justify-center"
                                >
                                    <User class="h-20 w-20 text-white" />
                                </div>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ officer.name }}
                        </h3>
                        <p class="text-green-600 font-semibold">
                            {{ officer.role }} {{ officer.iksass }} Banten
                        </p>

                        <p class="text-gray-600">Periode {{ periode }}</p>
                    </div>
                </div>

                <div
                    class="text-center mt-10"
                    data-aos="fade-up"
                    data-aos-delay="400"
                >
                    <Link
                        href="/structure"
                        class="relative inline-flex items-center gap-3 px-6 py-3 bg-green-600 text-white rounded-2xl overflow-hidden transition-all duration-300 shadow-xl group"
                    >
                        <!-- Hover animation layer -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none"
                        ></div>

                        <!-- Content -->
                        <Users class="w-5 h-5 relative z-10" />
                        <span class="text-lg font-bold relative z-10">
                            Lihat Struktur Lengkap
                        </span>
                        <ArrowRight
                            class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform"
                        />
                    </Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import { Link, Head } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";
import {
    Users,
    Phone,
    ArrowRight,
    X,
    ExternalLink,
    ChevronLeft,
    ChevronRight,
    Calendar,
    MapPin,
    Download,
    PlayCircle,
    Image as ImageIcon,
    Grid3x3,
    Maximize2,
    Heart,
    Share2,
    Target,
    ListChecks,
    CheckCircle,
    User,
    Crown,
    Info,
    Newspaper,
} from "lucide-vue-next";

// Inisialisasi AOS
onMounted(() => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100,
    });
});

const props = defineProps({
    strukturPengurus: Array,
    periode: String,
    documentation: Array,
});

// Batasi hanya 10 dokumentasi terbaru
const latestDocs = computed(() => props.documentation.slice(0, 10));

// Slider state
const sliderRef = ref(null);
const currentIndex = ref(0);
const cardsPerView = ref(3);

// Modal preview
const preview = ref(null);
const liked = ref(false);
const isVideo = ref(false);

// Responsive cards per view
onMounted(() => {
    updateCardsPerView();
    window.addEventListener("resize", updateCardsPerView);
});

function updateCardsPerView() {
    if (window.innerWidth < 768) {
        cardsPerView.value = 1;
    } else if (window.innerWidth < 1024) {
        cardsPerView.value = 2;
    } else {
        cardsPerView.value = 3;
    }
}

function openPreview(doc) {
    preview.value = doc;
    liked.value = false;
    isVideo.value = doc.photo?.match(/\.(mp4|webm|mov)$/i) || false;
}

function scrollLeft() {
    currentIndex.value = Math.max(0, currentIndex.value - 1);
    if (sliderRef.value) {
        const cardWidth = sliderRef.value.children[0].offsetWidth + 24; // width + gap
        sliderRef.value.scrollTo({
            left: currentIndex.value * cardWidth,
            behavior: "smooth",
        });
    }
}

function scrollRight() {
    const maxIndex = latestDocs.value.length - cardsPerView.value;
    currentIndex.value = Math.min(maxIndex, currentIndex.value + 1);
    if (sliderRef.value) {
        const cardWidth = sliderRef.value.children[0].offsetWidth + 24;
        sliderRef.value.scrollTo({
            left: currentIndex.value * cardWidth,
            behavior: "smooth",
        });
    }
}

function toggleLike() {
    liked.value = !liked.value;
}

function shareDocument() {
    if (navigator.share) {
        navigator.share({
            title: preview.value.title,
            text: preview.value.description,
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert("Link berhasil disalin!");
    }
}

const stats = [
    { value: "500+", label: "Anggota Aktif" },
    { value: "50+", label: "Program Tahunan" },
    { value: "100%", label: "Komitmen Dakwah" },
    { value: "24/7", label: "Pengabdian" },
];

const latestNews = [
    {
        id: 1,
        title: "Pekan Kreativitas Santri 2024",
        excerpt:
            "Kegiatan tahunan yang memfasilitasi bakat dan kreativitas santri dalam berbagai bidang...",
        date: "15 Des 2024",
        category: "Kegiatan",
    },
    {
        id: 2,
        title: "Pelatihan Kepemimpinan Santri",
        excerpt:
            "Program pengembangan kepemimpinan bagi kader IKSASS untuk membentuk pemimpin masa depan...",
        date: "10 Des 2024",
        category: "Pengkaderan",
    },
    {
        id: 3,
        title: "Bakti Sosial di Desa Binaan",
        excerpt:
            "Aksi sosial IKSASS dalam rangka meningkatkan kesejahteraan masyarakat sekitar...",
        date: "5 Des 2024",
        category: "Pengabdian",
    },
];
</script>

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Line Clamp Utilities */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Modal Animations */
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

@keyframes modalIn {
    0% {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-modalIn {
    animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Hover Effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Custom scrollbar for modal */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: #059669;
}
</style>
