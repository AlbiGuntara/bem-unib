<template>
    <AppLayout>
        <SeoHead title="Profil" description="Profil organisasi Badan Eksekutif Mahasiswa Universitas Ibrahimy. Visi, misi, filosofi logo, dan struktur kepengurusan Kabinet Transformasi." />

        <!-- HERO SECTION -->
        <section
            class="relative min-h-[85vh] overflow-hidden flex items-center justify-center"
        >
            <!-- PARALLAX BACKGROUND -->
            <div
                class="absolute inset-0 z-0"
                style="
                    background-image: url(&quot;/images/bg/bem.jpg&quot;);
                    background-size: cover;
                    background-position: center;
                    background-attachment: fixed;
                "
            ></div>

            <!-- OVERLAY -->
            <div
                class="absolute inset-0 bg-gradient-to-t from-blue-950/60 via-blue-950/60 to-blue-950/30 z-10"
            ></div>

            <!-- HERO CONTENT -->
            <div
                class="relative z-20 max-w-4xl mx-auto px-6 text-center text-white"
            >
                <!-- Badge -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/10 backdrop-blur-md text-xs tracking-wide uppercase mb-6"
                    data-aos="fade-down"
                >
                    <Sparkles class="w-3.5 h-3.5 text-amber-300" />
                    BEM Universitas Ibrahimy
                </div>

                <!-- Heading -->
                <h1
                    class="text-4xl md:text-5xl lg:text-6xl font-semibold leading-tight tracking-tight"
                    data-aos="fade-up"
                >
                    Profil <span class="text-yellow-500">Organisasi</span>
                </h1>

                <!-- Accent Line -->
                <div
                    class="w-16 h-[2px] bg-amber-300 mx-auto my-6 rounded-full"
                    data-aos="zoom-in"
                ></div>

                <!-- Description -->
                <p
                    class="max-w-2xl mx-auto text-base md:text-lg text-white/75 leading-relaxed"
                    data-aos="fade-up"
                    data-aos-delay="100"
                >
                    Wadah aspirasi, kolaborasi, dan pengembangan mahasiswa yang
                    progresif, inspiratif, dan berintegritas.
                </p>
            </div>

            <!-- WAVE SVG -->
            <div
                class="absolute bottom-[-2px] left-0 w-full overflow-hidden rotate-180 leading-[0] z-30"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 283.5 27.8"
                    preserveAspectRatio="none"
                    class="block w-full h-[90px] fill-white"
                >
                    <path
                        class="elementor-shape-fill"
                        d="M283.5,9.7c0,0-7.3,4.3-14,4.6c-6.8,0.3-12.6,0-20.9-1.5c-11.3-2-33.1-10.1-44.7-5.7	s-12.1,4.6-18,7.4c-6.6,3.2-20,9.6-36.6,9.3C131.6,23.5,99.5,7.2,86.3,8c-1.4,0.1-6.6,0.8-10.5,2c-3.8,1.2-9.4,3.8-17,4.7	c-3.2,0.4-8.3,1.1-14.2,0.9c-1.5-0.1-6.3-0.4-12-1.6c-5.7-1.2-11-3.1-15.8-3.7C6.5,9.2,0,10.8,0,10.8V0h283.5V9.7z M260.8,11.3	c-0.7-1-2-0.4-4.3-0.4c-2.3,0-6.1-1.2-5.8-1.1c0.3,0.1,3.1,1.5,6,1.9C259.7,12.2,261.4,12.3,260.8,11.3z M242.4,8.6	c0,0-2.4-0.2-5.6-0.9c-3.2-0.8-10.3-2.8-15.1-3.5c-8.2-1.1-15.8,0-15.1,0.1c0.8,0.1,9.6-0.6,17.6,1.1c3.3,0.7,9.3,2.2,12.4,2.7	C239.9,8.7,242.4,8.6,242.4,8.6z M185.2,8.5c1.7-0.7-13.3,4.7-18.5,6.1c-2.1,0.6-6.2,1.6-10,2c-3.9,0.4-8.9,0.4-8.8,0.5	c0,0.2,5.8,0.8,11.2,0c5.4-0.8,5.2-1.1,7.6-1.6C170.5,14.7,183.5,9.2,185.2,8.5z M199.1,6.9c0.2,0-0.8-0.4-4.8,1.1	c-4,1.5-6.7,3.5-6.9,3.7c-0.2,0.1,3.5-1.8,6.6-3C197,7.5,199,6.9,199.1,6.9z M283,6c-0.1,0.1-1.9,1.1-4.8,2.5s-6.9,2.8-6.7,2.7	c0.2,0,3.5-0.6,7.4-2.5C282.8,6.8,283.1,5.9,283,6z M31.3,11.6c0.1-0.2-1.9-0.2-4.5-1.2s-5.4-1.6-7.8-2C15,7.6,7.3,8.5,7.7,8.6	C8,8.7,15.9,8.3,20.2,9.3c2.2,0.5,2.4,0.5,5.7,1.6S31.2,11.9,31.3,11.6z M73,9.2c0.4-0.1,3.5-1.6,8.4-2.6c4.9-1.1,8.9-0.5,8.9-0.8	c0-0.3-1-0.9-6.2-0.3S72.6,9.3,73,9.2z M71.6,6.7C71.8,6.8,75,5.4,77.3,5c2.3-0.3,1.9-0.5,1.9-0.6c0-0.1-1.1-0.2-2.7,0.2	C74.8,5.1,71.4,6.6,71.6,6.7z M93.6,4.4c0.1,0.2,3.5,0.8,5.6,1.8c2.1,1,1.8,0.6,1.9,0.5c0.1-0.1-0.8-0.8-2.4-1.3	C97.1,4.8,93.5,4.2,93.6,4.4z M65.4,11.1c-0.1,0.3,0.3,0.5,1.9-0.2s2.6-1.3,2.2-1.2s-0.9,0.4-2.5,0.8C65.3,10.9,65.5,10.8,65.4,11.1	z M34.5,12.4c-0.2,0,2.1,0.8,3.3,0.9c1.2,0.1,2,0.1,2-0.2c0-0.3-0.1-0.5-1.6-0.4C36.6,12.8,34.7,12.4,34.5,12.4z M152.2,21.1	c-0.1,0.1-2.4-0.3-7.5-0.3c-5,0-13.6-2.4-17.2-3.5c-3.6-1.1,10,3.9,16.5,4.1C150.5,21.6,152.3,21,152.2,21.1z"
                    ></path>
                    <path
                        class="elementor-shape-fill"
                        d="M269.6,18c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3	C267.7,18.8,269.7,18,269.6,18z"
                    ></path>
                    <path
                        class="elementor-shape-fill"
                        d="M227.4,9.8c-0.2-0.1-4.5-1-9.5-1.2c-5-0.2-12.7,0.6-12.3,0.5c0.3-0.1,5.9-1.8,13.3-1.2	S227.6,9.9,227.4,9.8z"
                    ></path>
                    <path
                        class="elementor-shape-fill"
                        d="M204.5,13.4c-0.1-0.1,2-1,3.2-1.1c1.2-0.1,2,0,2,0.3c0,0.3-0.1,0.5-1.6,0.4	C206.4,12.9,204.6,13.5,204.5,13.4z"
                    ></path>
                    <path
                        class="elementor-shape-fill"
                        d="M201,10.6c0-0.1-4.4,1.2-6.3,2.2c-1.9,0.9-6.2,3.1-6.1,3.1c0.1,0.1,4.2-1.6,6.3-2.6	S201,10.7,201,10.6z"
                    ></path>
                    <path
                        class="elementor-shape-fill"
                        d="M154.5,26.7c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3	C152.6,27.5,154.6,26.8,154.5,26.7z"
                    ></path>
                    <path
                        class="elementor-shape-fill"
                        d="M41.9,19.3c0,0,1.2-0.3,2.9-0.1c1.7,0.2,5.8,0.9,8.2,0.7c4.2-0.4,7.4-2.7,7-2.6	c-0.4,0-4.3,2.2-8.6,1.9c-1.8-0.1-5.1-0.5-6.7-0.4S41.9,19.3,41.9,19.3z"
                    ></path>
                    <path
                        class="elementor-shape-fill"
                        d="M75.5,12.6c0.2,0.1,2-0.8,4.3-1.1c2.3-0.2,2.1-0.3,2.1-0.5c0-0.1-1.8-0.4-3.4,0	C76.9,11.5,75.3,12.5,75.5,12.6z"
                    ></path>
                    <path
                        class="elementor-shape-fill"
                        d="M15.6,13.2c0-0.1,4.3,0,6.7,0.5c2.4,0.5,5,1.9,5,2c0,0.1-2.7-0.8-5.1-1.4	C19.9,13.7,15.7,13.3,15.6,13.2z"
                    ></path>
                </svg>
            </div>
        </section>

        <!-- ABOUT SECTION -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16" data-aos="fade-up">
                    <span
                        class="text-green-600 font-semibold tracking-wider text-sm"
                        >TENTANG KAMI</span
                    >
                    <h2
                        class="text-4xl lg:text-5xl font-bold text-blue-900 mt-2 mb-4"
                    >
                        Profil Kabinet
                    </h2>
                    <div
                        class="w-24 h-1.5 bg-gradient-to-r from-green-500 to-blue-600 mx-auto rounded-full"
                    ></div>
                </div>

                <!-- Tentang Kami -->
                <div data-aos="fade-right">
                    <div class="relative mb-6 max-w-6xl mx-auto">
                        <div
                            class="absolute -top-4 -left-4 w-20 h-20 bg-blue-100 rounded-lg -z-0"
                        ></div>
                        <div
                            class="relative bg-slate-50 rounded-xl shadow-xl p-8 border border-gray-200"
                        >
                            <h3
                                class="text-2xl font-bold text-blue-900 mb-4 flex items-center gap-2"
                            >
                                <Building class="w-7 h-7 text-green-600" />
                                Profil Singkat
                            </h3>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                BEM Universitas Ibrahimy merupakan organisasi
                                mahasiswa tingkat universitas yang menjadi wadah
                                aspirasi, pengembangan potensi, serta penggerak
                                transformasi mahasiswa dalam bidang akademik,
                                sosial, keagamaan, kepemimpinan, dan pengabdian
                                masyarakat.
                            </p>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                Sebagai representasi mahasiswa, BEM Universitas
                                Ibrahimy hadir untuk membangun sinergi antara
                                mahasiswa, civitas akademika, dan masyarakat
                                demi terciptanya lingkungan kampus yang
                                progresif, inovatif, religius, dan
                                berintegritas.
                            </p>
                            <p class="text-gray-700 leading-relaxed">
                                Dengan berlandaskan nilai-nilai keislaman,
                                intelektualitas, dan semangat kolaborasi, BEM
                                Universitas Ibrahimy berkomitmen menjadi
                                organisasi yang adaptif terhadap perkembangan
                                zaman serta mampu melahirkan mahasiswa yang
                                kritis, solutif, dan berdampak bagi umat dan
                                bangsa.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- VISI MISI SECTION -->
                <div class="max-w-6xl mx-auto mt-20">
                    <div
                        class="grid lg:grid-cols-[1fr_1.1fr] gap-10 items-stretch"
                    >
                        <!-- LEFT SIDE -->
                        <div class="space-y-8">
                            <!-- VISI -->
                            <div class="relative mb-6 max-w-6xl mx-auto">
                                <div
                                    class="absolute -top-4 -left-4 w-20 h-20 bg-blue-100 rounded-lg -z-0"
                                ></div>
                                <div
                                    class="relative bg-white rounded-xl shadow-xl p-8 border border-gray-200"
                                >
                                    <h3
                                        class="text-2xl font-bold text-blue-900 mb-4 flex items-center gap-2"
                                    >
                                        <Target
                                            class="w-7 h-7 text-green-600"
                                        />
                                        Visi
                                    </h3>
                                    <p
                                        class="text-gray-700 leading-relaxed mb-4"
                                    >
                                        “Mewujudkan BEM Universitas Ibrahimy
                                        sebagai organisasi mahasiswa yang
                                        progresif, kolaboratif, religius, dan
                                        berintegritas dalam mengembangkan
                                        potensi mahasiswa serta memberikan
                                        kontribusi nyata bagi kampus,
                                        masyarakat, dan peradaban.”
                                    </p>
                                </div>
                            </div>

                            <!-- IMAGE -->
                            <div
                                data-aos="zoom-in"
                                class="hidden md:block relative rounded-xl shadow-xl border border-gray-100 h-[280px] mt-20"
                            >
                                <!-- Image -->
                                <img
                                    :src="'/images/bg/bem.jpg'"
                                    alt="BEM UNIB"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                />

                                <!-- Overlay -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-blue-950/60 via-transparent to-transparent"
                                ></div>

                                <!-- Badge -->
                                <div
                                    class="absolute -top-8 -left-8 z-99 w-24 h-24 rounded-full bg-gradient-to-br from-blue-600 to-green-400 text-white flex flex-col items-center justify-center shadow-2xl border-4 border-white"
                                >
                                    <span
                                        class="text-xs font-medium tracking-wide"
                                    >
                                        BEM UNIB
                                    </span>

                                    <span class="text-lg font-black">
                                        2026
                                    </span>
                                </div>

                                <!-- Decoration -->
                                <div
                                    class="absolute bottom-5 right-5 px-4 py-2 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white text-sm font-medium"
                                >
                                    Kabinet Transformasi
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT SIDE : MISI -->
                        <div data-aos="fade-left">
                            <div class="relative mb-6 max-w-6xl mx-auto">
                                <div
                                    class="absolute -top-4 -left-4 w-20 h-20 bg-blue-100 rounded-lg -z-0"
                                ></div>
                                <div
                                    class="relative bg-white rounded-xl shadow-xl p-8 border border-gray-200"
                                >
                                    <h3
                                        class="text-2xl font-bold text-blue-900 mb-4 flex items-center gap-2"
                                    >
                                        <Flag class="w-7 h-7 text-green-600" />
                                        Misi
                                    </h3>

                                    <ul class="space-y-5 text-gray-700">
                                        <li class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5"
                                            >
                                                <ChevronRight
                                                    class="w-4 h-4 text-green-600"
                                                />
                                            </div>

                                            <span>
                                                Menjadi wadah aspirasi mahasiswa
                                                yang aktif, responsif, dan
                                                solutif terhadap berbagai
                                                persoalan kampus maupun sosial
                                                kemasyarakatan.
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5"
                                            >
                                                <ChevronRight
                                                    class="w-4 h-4 text-green-600"
                                                />
                                            </div>

                                            <span>
                                                Membangun budaya organisasi yang
                                                profesional, transparan, dan
                                                berlandaskan nilai-nilai
                                                keislaman serta kepemimpinan
                                                yang berintegritas.
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5"
                                            >
                                                <ChevronRight
                                                    class="w-4 h-4 text-green-600"
                                                />
                                            </div>

                                            <span>
                                                Mengembangkan potensi mahasiswa
                                                dalam bidang akademik,
                                                intelektual, kreativitas,
                                                teknologi, kewirausahaan, dan
                                                kepemimpinan.
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5"
                                            >
                                                <ChevronRight
                                                    class="w-4 h-4 text-green-600"
                                                />
                                            </div>

                                            <span>
                                                Meningkatkan semangat kolaborasi
                                                dan solidaritas antar mahasiswa,
                                                organisasi, serta civitas
                                                akademika Universitas Ibrahimy.
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5"
                                            >
                                                <ChevronRight
                                                    class="w-4 h-4 text-green-600"
                                                />
                                            </div>

                                            <span>
                                                Menghadirkan program kerja yang
                                                inovatif, berdampak, dan
                                                bermanfaat bagi mahasiswa serta
                                                masyarakat luas.
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5"
                                            >
                                                <ChevronRight
                                                    class="w-4 h-4 text-green-600"
                                                />
                                            </div>

                                            <span>
                                                Menumbuhkan karakter mahasiswa
                                                yang kritis, humanis, adaptif,
                                                dan memiliki kepedulian sosial
                                                tinggi.
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5"
                                            >
                                                <ChevronRight
                                                    class="w-4 h-4 text-green-600"
                                                />
                                            </div>

                                            <span>
                                                Mengoptimalkan peran mahasiswa
                                                sebagai agen perubahan, kontrol
                                                sosial, dan pelopor transformasi
                                                di lingkungan kampus maupun
                                                masyarakat.
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- LOGO PHILOSOPHY SECTION -->
        <section
            class="relative py-28 overflow-hidden inset-0 bg-cover bg-center"
            style="background-image: url(&quot;/images/bg/pattern-b.jpg&quot;)"
        >
            <!-- Blue Overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-slate-100/75 via-blue-50/75 to-slate-200/75"
            ></div>

            <!-- Background Decoration -->
            <div
                class="absolute top-0 left-0 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"
            ></div>

            <div
                class="absolute bottom-0 right-0 w-[30rem] h-[30rem] bg-cyan-300/10 rounded-full blur-3xl"
            ></div>

            <div class="relative max-w-7xl mx-auto px-4">
                <!-- Heading -->
                <div class="text-center mb-24" data-aos="fade-up">
                    <span
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/70 backdrop-blur-md border border-white/40 text-blue-700 font-semibold tracking-widest text-xs uppercase shadow-lg"
                    >
                        Identitas Organisasi
                    </span>

                    <h2
                        class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mt-6 tracking-tight"
                    >
                        Filosofi Logo
                    </h2>
                    <div
                        class="w-28 h-1.5 bg-gradient-to-r from-blue-600 to-cyan-500 mx-auto rounded-full mt-5"
                    ></div>
                </div>

                <!-- MAIN CONTENT -->
                <div class="grid lg:grid-cols-[480px_1fr] gap-20 items-center">
                    <!-- LEFT : LOGO -->
                    <div
                        class="relative flex items-center justify-center"
                        data-aos="fade-right"
                    >
                        <!-- Glass Card -->
                        <div
                            class="relative w-full max-w-[420px] aspect-square flex items-center justify-center overflow-hidden"
                        >
                            <div
                                class="absolute w-72 h-72 bg-blue-400/20 rounded-full blur-3xl"
                            ></div>

                            <!-- PREV BUTTON -->
                            <button
                                @click="changeLogo('prev')"
                                class="absolute left-5 z-30 w-14 h-14 rounded-full bg-white/90 backdrop-blur-md border border-white/60 shadow-lg flex items-center justify-center text-blue-700 hover:scale-110 hover:bg-blue-600 hover:text-white active:scale-95 transition-all duration-300 cursor-pointer"
                            >
                                <ChevronLeft class="w-7 h-7" />
                            </button>

                            <!-- LOGO -->
                            <transition
                                enter-active-class="transition duration-500 ease-out"
                                enter-from-class="opacity-0 scale-75 rotate-[-8deg]"
                                enter-to-class="opacity-100 scale-100 rotate-0"
                                leave-active-class="transition duration-300 ease-in"
                                leave-from-class="opacity-100 scale-100 rotate-0"
                                leave-to-class="opacity-0 scale-75 rotate-[8deg]"
                                mode="out-in"
                            >
                                <div
                                    :key="logos[activeIndex].image"
                                    class="relative z-20 flex items-center justify-center w-full h-full p-10"
                                >
                                    <img
                                        :src="logos[activeIndex].image"
                                        :alt="logos[activeIndex].title"
                                        class="w-full h-full object-contain drop-shadow-[0_20px_30px_rgba(0,0,0,0.25)] select-none"
                                        loading="lazy"
                                    />
                                </div>
                            </transition>

                            <!-- NEXT BUTTON -->
                            <button
                                @click="changeLogo('next')"
                                class="absolute right-5 z-30 w-14 h-14 rounded-full bg-white/90 backdrop-blur-md border border-white/60 shadow-lg flex items-center justify-center text-blue-700 hover:scale-110 hover:bg-blue-600 hover:text-white active:scale-95 transition-all duration-300 cursor-pointer"
                            >
                                <ChevronRight class="w-7 h-7" />
                            </button>
                        </div>

                        <!-- Indicator -->
                        <div
                            class="absolute -bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-3"
                        >
                            <div
                                v-for="(item, index) in logos"
                                :key="index"
                                @click="activeIndex = index"
                                class="cursor-pointer transition-all duration-300"
                                :class="
                                    activeIndex === index
                                        ? 'w-10 h-3 rounded-full bg-blue-600'
                                        : 'w-3 h-3 rounded-full bg-slate-300 hover:bg-slate-400'
                                "
                            ></div>
                        </div>
                    </div>

                    <!-- RIGHT : CONTENT -->
                    <div data-aos="fade-left">
                        <transition
                            enter-active-class="transition duration-500 ease-out"
                            enter-from-class="opacity-0 translate-y-6"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition duration-200 ease-in"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                            mode="out-in"
                        >
                            <div
                                :key="logos[activeIndex].title"
                                class="relative overflow-hidden rounded-xl border border-white/40 bg-white/70 backdrop-blur-xl p-10 lg:p-14 shadow-[0_20px_80px_rgba(15,23,42,0.15)]"
                            >
                                <!-- Title -->
                                <h3
                                    class="text-3xl lg:text-5xl font-black text-slate-900 mb-8 tracking-tight leading-tight"
                                >
                                    {{ logos[activeIndex].title }}
                                </h3>

                                <!-- Divider -->
                                <div
                                    class="w-20 h-1 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-full mb-8"
                                ></div>

                                <!-- Description -->
                                <p
                                    class="text-slate-600 text-lg lg:text-xl leading-relaxed"
                                >
                                    {{ logos[activeIndex].text }}
                                </p>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

        <!-- STRUKTURAL SECTION -->
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Public/AppLayouts.vue";
import SeoHead from "@/Components/SeoHead.vue";
import { onMounted, ref } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";
import {
    Building,
    Target,
    Flag,
    ChevronLeft,
    ChevronRight,
    GraduationCap,
    Heart,
    Megaphone,
    Briefcase,
    Sparkles,
    Users,
    Trophy,
    Lightbulb,
    BookOpen,
    Mic,
    Camera,
    Laptop,
} from "lucide-vue-next";

// Logo dan filosofi
const logos = [
    {
        image: "/images/logo/kabinet.png",
        title: "KABINET TRANSFORMASI",
        text: "Badan Eksekutif Mahasiswa Universitas Ibrahimy",
    },
    {
        image: "/images/logo/kabinet-api.png",
        title: "API ORANYE",
        text: "Api semangat perubahan, energi yang membakar stagnasi. Oranye simbol energi, optimisme, dan semangat baru.",
    },
    {
        image: "/images/logo/kabinet-daun.png",
        title: "DAUN HIJAU",
        text: "Daun tumbuh / tunas baru melambangkan pertumbuhan, perubahan positif, dan harapan baru. Hijau simbol perubahan, kesegaran, dan keberlanjutan.",
    },
    {
        image: "/images/logo/kabinet-gelombang.png",
        title: "AIR BIRU",
        text: "Gelombang / Air Mengalir fleksibilitas, adaptasi, dan transformasi tanpa kehilangan esensi. Biru sering dikaitkan dengan stabilitas, kepercayaan, dan juga inovasi.",
    },
];

const activeIndex = ref(0);
const fade = ref(true);

const changeLogo = (direction) => {
    fade.value = false;

    setTimeout(() => {
        if (direction === "next") {
            activeIndex.value = (activeIndex.value + 1) % logos.length;
        } else {
            activeIndex.value =
                (activeIndex.value - 1 + logos.length) % logos.length;
        }

        fade.value = true;
    }, 200);
};

// Struktur kepengurusan kementerian
const organizationalStructure = [
    {
        name: "Kementerian Dalam Negeri",
        coordinator: "M. Rizki Fadillah",
        members: 8,
        icon: Building,
        bgColor: "bg-blue-600",
    },
    {
        name: "Kementerian Akademik",
        coordinator: "Siti Aisyah",
        members: 7,
        icon: GraduationCap,
        bgColor: "bg-green-600",
    },
    {
        name: "Kementerian Sosial Masyarakat",
        coordinator: "Fajar Setiawan",
        members: 6,
        icon: Heart,
        bgColor: "bg-red-500",
    },
    {
        name: "Kementerian Komunikasi & Informasi",
        coordinator: "Indah Permata",
        members: 9,
        icon: Megaphone,
        bgColor: "bg-purple-600",
    },
    {
        name: "Kementerian Kewirausahaan",
        coordinator: "Budi Santoso",
        members: 5,
        icon: Briefcase,
        bgColor: "bg-yellow-600",
    },
    {
        name: "Kementerian Pengembangan SDM",
        coordinator: "Dewi Kartika",
        members: 7,
        icon: Sparkles,
        bgColor: "bg-pink-500",
    },
    {
        name: "Kementerian Olahraga & Seni",
        coordinator: "Randy Pratama",
        members: 10,
        icon: Trophy,
        bgColor: "bg-orange-500",
    },
    {
        name: "Kementerian Riset & Teknologi",
        coordinator: "Nur Hidayat",
        members: 6,
        icon: Lightbulb,
        bgColor: "bg-indigo-600",
    },
    {
        name: "Kementerian Luar Negeri",
        coordinator: "Laila Hasanah",
        members: 5,
        icon: Users,
        bgColor: "bg-teal-600",
    },
];

// Badan Semi Otonom
const autonomousBodies = [
    { name: "BPM", leader: "Ketua: Hasan Ali", icon: Users },
    { name: "UKM Pers", leader: "Pimpinan: Rina Wati", icon: BookOpen },
    { name: "Radio Kampus", leader: "Ketua: Dodi Saputra", icon: Mic },
    { name: "Fotografi", leader: "Ketua: Aulia Rahman", icon: Camera },
    { name: "LK Teknologi", leader: "Ketua: Fikri Hidayat", icon: Laptop },
];

onMounted(() => {
    AOS.init({
        duration: 800,
        once: true,
    });
});
</script>
