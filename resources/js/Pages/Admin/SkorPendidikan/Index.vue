<template>
    <AppLayout>
        <Head title="Skor Pendidikan" />
        <!-- Card -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div class="flex flex-col gap-4">
                <!-- CARD WARNING -->
                <div
                    class="p-6 rounded-xl text-white shadow-xs bg-gradient-to-br from-red-600 via-red-700 to-red-800 dark:from-red-700 dark:via-red-800 dark:to-red-900 relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"
                    ></div>
                    <div
                        class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -translate-x-12 translate-y-12"
                    ></div>

                    <div
                        class="relative z-10 flex flex-col justify-between h-full"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <AlertTriangle
                                    class="w-8 h-8 text-yellow-300 drop-shadow-sm"
                                />
                                <div
                                    class="text-lg font-extrabold tracking-wide"
                                >
                                    PERINGATAN!
                                </div>
                            </div>

                            <button
                                @click="
                                    isWarningFiltered
                                        ? resetWarningFilter()
                                        : filterWarning()
                                "
                                class="p-2 rounded-full bg-white/20 hover:bg-white/30 text-whitexs transition-all duration-300 backdrop-blur-sm transform hover:scale-110 active:scale-95"
                                :title="
                                    isWarningFiltered
                                        ? 'Reset filter'
                                        : 'Tampilkan skor ≥ 10'
                                "
                            >
                                <ArrowUpRight
                                    v-if="!isWarningFiltered"
                                    class="w-5 h-5"
                                />
                                <X v-else class="w-5 h-5" />
                            </button>
                        </div>

                        <div class="text-5xl font-black mt-4 drop-shadow-sm">
                            {{ warningCount }}
                        </div>

                        <p
                            class="mt-4 font-medium leading-relaxed text-white/90"
                        >
                            <span v-if="warningCount > 0">
                                <span class="font-bold"
                                    >{{ warningCount }} warga</span
                                >
                                Rayon IKSASS Banten memiliki skor di atas 10
                            </span>
                            <span v-else>
                                Tidak ada warga dengan skor di atas 10 — kondisi
                                aman 👍
                            </span>
                        </p>
                    </div>
                </div>

                <!-- CARD RATA-RATA PER BULAN -->
                <div
                    class="p-6 rounded-xl bg-white dark:bg-gradient-to-br dark:from-gray-900 dark:to-gray-800 border border-gray-200 dark:border-gray-700 shadow- relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full -translate-y-16 translate-x-16"
                    ></div>

                    <div
                        class="relative z-10 flex flex-col justify-between h-full"
                    >
                        <div class="flex items-center gap-3">
                            <TrendingUp
                                class="w-8 h-8 text-green-600 dark:text-green-400 drop-shadow-sm"
                            />
                            <h3
                                class="text-lg font-bold text-gray-800 dark:text-gray-100"
                            >
                                Rata-rata Skor Bulan {{ bulanLabel }}
                            </h3>
                        </div>

                        <div
                            class="text-5xl font-black mt-4 text-green-600 dark:text-green-400 drop-shadow-sm"
                        >
                            {{ averageScore }}
                        </div>

                        <p
                            class="mt-4 text-gray-600 dark:text-gray-300 font-medium leading-relaxed"
                        >
                            Rata-rata dihitung dari seluruh warga pada bulan
                            <span
                                class="font-semibold text-green-600 dark:text-green-400"
                                >{{ bulanLabel }}</span
                            >.
                        </p>
                    </div>
                </div>
            </div>

            <!-- GRAFIK TREND SKOR -->
            <div
                class="lg:col-span-2 hidden md:block p-8 rounded-2xl shadow-xs border border-gray-200/70 bg-white dark:bg-gray-900 dark:border-gray-700 dark:shadow-none relative overflow-hidden transition-all"
            >
                <!-- Decorative gradient bubble (Light mode) -->
                <div
                    class="absolute top-0 right-0 w-40 h-40 bg-green-200/30 dark:bg-green-500/10 rounded-full blur-2xl -translate-y-20 translate-x-10"
                ></div>

                <div
                    class="flex items-center justify-between mb-6 relative z-10"
                >
                    <h2
                        class="text-xl font-bold text-gray-800 dark:text-gray-100 tracking-tight"
                    >
                        Grafik Tren Rata-rata Skor per Bulan
                    </h2>

                    <div
                        class="text-sm px-3 py-1 rounded-full font-semibold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 shadow-sm"
                    >
                        12 Bulan Terakhir
                    </div>
                </div>

                <div class="relative z-10">
                    <LineChart :monthlyData="props.monthly_average" />
                </div>
            </div>
        </div>

        <!-- main tabel -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 transition-all duration-500 ease-in-out"
        >
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row flex-wrap gap-4 justify-between items-center mb-6"
            >
                <div class="flex gap-3">
                    <input
                        type="text"
                        v-model="filters.search"
                        @input="applyFilters"
                        placeholder="Cari..."
                        class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl w-full max-w-xs md:max-w-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none placeholder-gray-400 transition-all duration-300 shadow-sm hover:shadow-md"
                    />
                </div>

                <div class="flex items-center gap-3">
                    <!-- FILTER LEMBAGA -->
                    <div class="relative" ref="lembagaRef">
                        <button
                            @click.stop="toggleFilter('lembaga')"
                            class="filter-btn flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 dark:hover:from-gray-700 dark:hover:to-gray-800 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95"
                        >
                            <Filter
                                class="h-5 text-green-600 dark:text-green-400"
                            />
                            <span class="hidden md:block">
                                {{ filters.lembaga || "Lembaga" }}
                            </span>
                            <ChevronDown
                                class="w-4 h-4 transition-transform duration-300"
                                :class="{ 'rotate-180': showLembaga }"
                            />
                        </button>

                        <!-- Dropdown -->
                        <Transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-2"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 translate-y-2"
                        >
                            <div
                                v-if="showLembaga"
                                class="dropdown-filter absolute left-0 md:right-0 md:left-auto mt-2 w-48 h-48 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-auto z-50 custom-scrollbar"
                            >
                                <ul
                                    class="py-1 text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <li
                                        v-for="l in lembagaOptions"
                                        :key="l"
                                        @click="
                                            filters.lembaga = l;
                                            closeAllFilters();
                                            applyFilters();
                                        "
                                        class="flex items-center px-4 py-2 cursor-pointer transition-all hover:bg-green-50 dark:hover:bg-green-900/20"
                                        :class="
                                            filters.lembaga === l
                                                ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200'
                                                : ''
                                        "
                                    >
                                        {{ l }}
                                    </li>

                                    <!-- RESET -->
                                    <li
                                        @click="
                                            filters.lembaga = '';
                                            closeAllFilters();
                                            applyFilters();
                                        "
                                        class="flex items-center px-4 py-2 text-red-500 cursor-pointer hover:bg-red-50 dark:hover:bg-red-900/20 transition-all"
                                    >
                                        Reset
                                    </li>
                                </ul>
                            </div>
                        </Transition>
                    </div>

                    <!-- FILTER BULAN -->
                    <div class="relative" ref="bulanRef">
                        <button
                            @click.stop="toggleFilter('bulan')"
                            class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 dark:hover:from-gray-700 dark:hover:to-gray-800 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95"
                        >
                            <Filter
                                class="h-5 text-green-600 dark:text-green-400"
                            />
                            <span class="hidden md:block">
                                {{ filters.bulan || "Bulan" }}
                            </span>
                            <ChevronDown
                                class="w-4 h-4 transition-transform duration-300"
                                :class="{ 'rotate-180': showBulan }"
                            />
                        </button>

                        <!-- Dropdown -->
                        <Transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-2"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 translate-y-2"
                        >
                            <div
                                v-if="showBulan"
                                class="absolute right-0 mt-2 w-48 h-48 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-auto z-50 custom-scrollbar"
                            >
                                <ul
                                    class="py-1 text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <li
                                        v-for="b in bulanOptions"
                                        :key="b"
                                        @click="
                                            filters.bulan = b;
                                            closeAllFilters();
                                            applyFilters();
                                        "
                                        class="flex items-center px-4 py-2 cursor-pointer transition-all hover:bg-green-50 dark:hover:bg-green-900/20"
                                        :class="
                                            filters.bulan === b
                                                ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200'
                                                : ''
                                        "
                                    >
                                        {{ b }}
                                    </li>

                                    <!-- RESET -->
                                    <li
                                        @click="
                                            filters.bulan = '';
                                            closeAllFilters();
                                            applyFilters();
                                        "
                                        class="flex items-center px-4 py-2 text-red-500 cursor-pointer hover:bg-red-50 dark:hover:bg-red-900/20 transition-all"
                                    >
                                        Reset
                                    </li>
                                </ul>
                            </div>
                        </Transition>
                    </div>

                    <button
                        @click="openAddModal()"
                        class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 active:scale-95"
                    >
                        <Plus class="h-6.5 md:h-5" />
                        <span class="hidden md:block">Tambah Skor</span>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div
                class="md:bg-white md:dark:bg-gray-900 rounded-2xl md:border md:border-gray-200 md:dark:border-gray-700 md:shadow-lg overflow-hidden transition-all duration-300"
            >
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-green-600 to-green-700 text-white uppercase text-xs tracking-wide"
                            >
                                <th class="px-4 py-3">No</th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('nama')"
                                >
                                    Santri
                                    <SortIcon field="nama" :filters="filters" />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('lembaga')"
                                >
                                    Lembaga Pendidikan
                                    <SortIcon
                                        field="lembaga"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('kelas')"
                                >
                                    Kelas
                                    <SortIcon
                                        field="kelas"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('jumlah')"
                                >
                                    Jumlah
                                    <SortIcon
                                        field="jumlah"
                                        :filters="filters"
                                    />
                                </th>
                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('bulan')"
                                >
                                    Bulan
                                    <SortIcon
                                        field="bulan"
                                        :filters="filters"
                                    />
                                </th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(item, index) in skor.data"
                                :key="item.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                            >
                                <td class="px-4 py-3">
                                    {{ skor.from + index }}
                                </td>
                                <td class="px-4 py-3 flex items-center gap-2">
                                    <img
                                        v-if="item.warga.foto"
                                        :src="`/storage/${item.warga.foto}`"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />
                                    <div>
                                        <p class="font-semibold">
                                            {{ item.warga.nama }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ item.warga.nis }}
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-800 dark:text-gray-200"
                                >
                                    {{ getLembaga(item.warga, item.jenis) }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"
                                    >
                                        {{ getKelas(item.warga, item.jenis) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold"
                                        :class="
                                            item.jumlah >= 10
                                                ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                                                : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                        "
                                    >
                                        {{ item.jumlah }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ item.bulan }}</td>
                                <td class="px-4 py-3">
                                    <div
                                        class="relative inline-block text-left"
                                    >
                                        <button
                                            @click.stop="
                                                toggleDropdown(item, $event)
                                            "
                                            class="aksi-button flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 text-gray-900 dark:text-gray-100 dark:hover:text-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all duration-300 group/btn"
                                        >
                                            <span class="font-medium"
                                                >Aksi</span
                                            >
                                            <ChevronDown
                                                class="w-4 h-4 transition-transform duration-300 group-hover/btn:rotate-180"
                                            />
                                        </button>

                                        <teleport to="body">
                                            <Transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-from-class="opacity-0 translate-y-2"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-from-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-2"
                                            >
                                                <div
                                                    v-if="
                                                        openDropdown &&
                                                        selectedItem?.id ===
                                                            item.id
                                                    "
                                                    ref="dropdownRef"
                                                    class="absolute z-50 w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden dropdown-menu-active"
                                                    :style="{
                                                        top:
                                                            dropdownPosition.top +
                                                            'px',
                                                        left:
                                                            dropdownPosition.left +
                                                            'px',
                                                    }"
                                                >
                                                    <ul
                                                        class="text-sm text-gray-900 dark:text-gray-100"
                                                    >
                                                        <li
                                                            @click="
                                                                openEditModal(
                                                                    item
                                                                )
                                                            "
                                                            class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer transition-all"
                                                        >
                                                            Edit
                                                        </li>
                                                        <li
                                                            @click="
                                                                destroy(item.id)
                                                            "
                                                            class="px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 cursor-pointer transition-all"
                                                        >
                                                            Hapus
                                                        </li>
                                                    </ul>
                                                </div>
                                            </Transition>
                                        </teleport>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE LIST VIEW (Responsive for <lg) -->
                <div class="lg:hidden space-y-4">
                    <div
                        v-for="(item, index) in skor.data"
                        :key="item.id"
                        class="p-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300"
                    >
                        <!-- Header -->
                        <div class="flex items-center gap-4">
                            <img
                                v-if="item.warga.foto"
                                :src="`/storage/${item.warga.foto}`"
                                class="w-14 h-14 rounded-full object-cover shadow"
                            />
                            <div class="flex-1">
                                <p
                                    class="font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    {{ item.warga.nama }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    NIS: {{ item.warga.nis }}
                                </p>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-semibold"
                                :class="
                                    item.jumlah >= 10
                                        ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                                        : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                "
                            >
                                {{ item.jumlah }}
                            </span>
                        </div>

                        <!-- Body -->
                        <div class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300"
                                    >Lembaga:</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ getLembaga(item.warga, item.jenis) }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300"
                                    >Kelas:</span
                                >
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"
                                >
                                    {{ getKelas(item.warga, item.jenis) }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300"
                                    >Bulan:</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ item.bulan }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex justify-end">
                            <div class="relative">
                                <button
                                    @click.stop="toggleDropdown(item, $event)"
                                    class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-all duration-300"
                                >
                                    <span class="font-medium">Aksi</span>
                                    <ChevronDown class="w-4 h-4" />
                                </button>

                                <teleport to="body">
                                    <Transition
                                        enter-active-class="transition ease-out duration-200"
                                        enter-from-class="opacity-0 translate-y-2"
                                        enter-to-class="opacity-100 translate-y-0"
                                        leave-active-class="transition ease-in duration-150"
                                        leave-from-class="opacity-100 translate-y-0"
                                        leave-to-class="opacity-0 translate-y-2"
                                    >
                                        <div
                                            v-if="
                                                openDropdown &&
                                                selectedItem?.id === item.id
                                            "
                                            ref="dropdownRef"
                                            class="absolute z-50 w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg overflow-hidden"
                                            :style="{
                                                top:
                                                    dropdownPosition.top + 'px',
                                                left:
                                                    dropdownPosition.left +
                                                    'px',
                                            }"
                                        >
                                            <ul
                                                class="text-sm text-gray-900 dark:text-gray-100"
                                            >
                                                <li
                                                    @click="openEditModal(item)"
                                                    class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer"
                                                >
                                                    Edit
                                                </li>
                                                <li
                                                    @click="destroy(item.id)"
                                                    class="px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 cursor-pointer"
                                                >
                                                    Hapus
                                                </li>
                                            </ul>
                                        </div>
                                    </Transition>
                                </teleport>
                            </div>
                        </div>
                    </div>

                    <!-- If empty -->
                    <div v-if="!skor.data.length" class="text-center py-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2"
                        >
                            Tidak ada data ditemukan
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Coba ubah pencarian atau tambah data baru
                        </p>
                    </div>
                </div>

                <div v-if="!skor.data.length" class="text-center py-6">
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2"
                    >
                        Tidak ada data ditemukan
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        Coba ubah pencarian atau tambah data baru
                    </p>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="skor.data.length"
                class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
            >
                <div class="flex items-center gap-2">
                    <label
                        for="per_page"
                        class="text-sm text-gray-600 dark:text-gray-300"
                    >
                        Show
                    </label>
                    <select
                        id="per_page"
                        v-model="filters.per_page"
                        @change="applyFilters"
                        class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 outline-none transition-all duration-200"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in skor.links"
                        :key="index"
                        @click="goTo(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200 font-medium"
                        :class="[
                            link.active
                                ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-green-600 shadow-lg scale-105'
                                : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 hover:shadow-md',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="index === skor.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>

            <FormModal
                v-if="showModal"
                :show="showModal"
                :warga="warga"
                :editData="editData"
                @close="closeModal"
            />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import FormModal from "./FormModal.vue";
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import {
    Plus,
    ChevronLeft,
    ChevronRight,
    ArrowUp,
    ArrowDown,
    ChevronUp,
    ChevronDown,
    Filter,
    AlertTriangle,
    TrendingUp,
    ArrowUpRight,
    X,
} from "lucide-vue-next";
import LineChart from "@/Components/Charts/LineChart.vue";

// Card Warning dan Rata-rata Skor
const warningCount = computed(() => props.warning_total);

function filterWarning() {
    filters.value = {
        search: "",
        sort_by: "",
        sort_direction: "",
        per_page: filters.value.per_page,
        lembaga: "",
        bulan: "",
        min_score: 10,
    };
    applyFilters();
}

function resetWarningFilter() {
    filters.value = {
        search: "",
        sort_by: "",
        sort_direction: "",
        per_page: filters.value.per_page,
        lembaga: "",
        bulan: "",
        min_score: null,
    };
    applyFilters();
}

const isWarningFiltered = computed(() => filters.value.min_score === 10);

// Rata-rata skor bulanan
const averageScore = computed(() => props.average_month);
const bulanLabel = computed(() => props.bulan_dipilih);

// ==== FILTERS =====
const props = defineProps({
    skor: Object,
    warga: Array,
    filters: Object,
    warning_total: Number,
    average_month: Number,
    bulan_dipilih: String,
    monthly_average: Object,
});

const filters = ref({
    search: props.filters?.search || "",
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
    per_page: props.filters?.per_page || 10,
    lembaga: props.filters?.lembaga || "",
    bulan: props.filters?.bulan || "",
    min_score: props.filters?.min_score || null,
});

const lembagaDropdownStyle = ref({});
const bulanDropdownStyle = ref({});

function toggleFilter(type, event) {
    openDropdown.value = false;
    selectedItem.value = null;

    if (type === "lembaga") {
        showBulan.value = false;
        showLembaga.value = !showLembaga.value;
    }

    if (type === "bulan") {
        showLembaga.value = false;
        showBulan.value = !showBulan.value;
    }
}

function closeAllFilters() {
    showLembaga.value = false;
    showBulan.value = false;
}

const showLembaga = ref(false);
const showBulan = ref(false);

const lembagaOptions = [
    "MI",
    "MTs",
    "MA",
    "MTI",
    "MDI",
    "Maly",
    "SD",
    "SMP",
    "SMA",
    "SMK",
    "PT",
    "Qiraati",
    "Amtsilati",
    "Pengajian",
];

const bulanOptions = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
];

function applyFilters() {
    router.get(route("skor-pendidikan.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

// ===== SORT BY COLUMN =====
function sort(column) {
    if (filters.value.sort_by === column) {
        if (filters.value.sort_direction === "asc") {
            filters.value.sort_direction = "desc";
        } else if (filters.value.sort_direction === "desc") {
            filters.value.sort_by = "";
            filters.value.sort_direction = "";
        } else {
            filters.value.sort_direction = "asc";
        }
    } else {
        filters.value.sort_by = column;
        filters.value.sort_direction = "asc";
    }
    applyFilters();
}

const SortIcon = {
    props: ["field", "filters"],
    components: { ArrowUp, ArrowDown },
    template: `
        <span class="inline-flex ml-1">
            <ArrowUp v-if="filters.sort_by === field && filters.sort_direction === 'asc'" class="w-3 h-3 inline-block" />
            <ArrowDown v-else-if="filters.sort_by === field && filters.sort_direction === 'desc'" class="w-3 h-3 inline-block" />
            <span v-else class="text-gray-300 dark:text-gray-500 ml-0.5">↕</span>
        </span>
    `,
};

// === DROPDOWN AKSI ===
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });
const dropdownRef = ref(null); // referensi dropdown aktif

function toggleDropdown(item, event) {
    const rect = event.target.getBoundingClientRect();
    const isSame = selectedItem.value?.id === item.id && openDropdown.value;

    // Jika klik dropdown yang sama → tutup
    if (isSame) {
        openDropdown.value = false;
        selectedItem.value = null;
        return;
    }

    // Tampilkan dropdown baru
    selectedItem.value = item;
    openDropdown.value = true;
    dropdownPosition.value = {
        top: rect.bottom + window.scrollY,
        left: rect.left,
    };
}

// Klik luar menutup dropdown
function handleGlobalClick(event) {
    const target = event.target;

    // === 1. Dropdown Aksi ===
    const aksiDropdown = document.querySelector(".dropdown-menu-active");

    if (
        openDropdown.value &&
        aksiDropdown &&
        !aksiDropdown.contains(target) &&
        !target.closest(".aksi-button")
    ) {
        openDropdown.value = false;
        selectedItem.value = null;
    }

    // === 2. Dropdown Filters (Lembaga & Bulan) ===
    const isFilterButton = target.closest(".filter-btn");
    const isFilterDropdown = target.closest(".dropdown-filter");

    if (!isFilterButton && !isFilterDropdown) {
        showLembaga.value = false;
        showBulan.value = false;
        openDropdown.value = false;
        selectedItem.value = null;
    }

    if (
        !target.closest(".aksi-button") &&
        !target.closest(".dropdown-menu-active")
    ) {
        openDropdown.value = false;
        selectedItem.value = null;
    }
}

onMounted(() => {
    document.addEventListener("click", handleGlobalClick);
});
onBeforeUnmount(() => {
    document.removeEventListener("click", handleGlobalClick);
});

// === PAGINATION ===
function goTo(url) {
    if (url) router.visit(url, { preserveScroll: true });
}

// === MODAL ===
const showModal = ref(false);
const editData = ref(null);

const openAddModal = () => {
    editData.value = null;
    showModal.value = true;
};
const openEditModal = (data) => {
    editData.value = data;
    showModal.value = true;
};
const closeModal = () => (showModal.value = false);

// === DELETE ===
const destroy = (id) => {
    if (confirm("Yakin ingin menghapus data ini?")) {
        router.delete(route("skor-pendidikan.destroy", id));
    }
};

// === GETTER ===
function getLembaga(warga, jenis) {
    if (jenis === "pagi") return warga.pendidikan_pagi;
    if (jenis === "sore") return warga.pendidikan_sore;
    if (jenis === "malam") return warga.pendidikan_malam;
}

function getKelas(warga, jenis) {
    if (jenis === "pagi") return warga.kelas_pendidikan_pagi;
    if (jenis === "sore") return warga.kelas_pendidikan_sore;
    if (jenis === "malam") return warga.kelas_pendidikan_malam;
}
</script>

<style scoped>
.fade-scale-enter-active,
.fade-scale-leave-active {
    transition: all 0.18s ease;
}

.fade-scale-enter-from {
    opacity: 0;
    transform: scale(0.95);
}

.fade-scale-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.dark .custom-scrollbar::-webkit-scrollbar-track {
    background: #1f2937;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #16a34a;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #15803d;
}
</style>
