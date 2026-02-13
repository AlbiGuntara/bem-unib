<template>
    <AppLayout>
        <Head title="Manajemen Kas" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
            <!-- LEFT SIDE -->
            <div
                class="p-6 bg-white dark:bg-gradient-to-bl dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700"
            >
                <!-- Premium Chart Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Pemasukan Card -->
                    <div class="relative group">
                        <div
                            class="absolute bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"
                        ></div>
                        <ChartCard
                            title="TOTAL PEMASUKAN"
                            :amount="props.totalGlobal.debet"
                            :monthlyData="props.monthlyTransactions.debet"
                            color="#10B981"
                            type="line"
                            class="relative"
                        />

                        <!-- Animated border effect -->
                        <div
                            class="absolute inset-0 rounded-2xl border-2 border-transparent transition-all duration-500 pointer-events-none"
                        ></div>
                    </div>

                    <!-- Pengeluaran Card -->
                    <div class="relative group">
                        <div
                            class="absolute bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"
                        ></div>
                        <ChartCard
                            title="TOTAL PENGELUARAN"
                            :amount="props.totalGlobal.kredit"
                            :monthlyData="props.monthlyTransactions.kredit"
                            color="#EF4444"
                            type="line"
                            class="relative"
                        />

                        <!-- Animated border effect -->
                        <div
                            class="absolute inset-0 rounded-2xl border-2 border-transparent transition-all duration-500 pointer-events-none"
                        ></div>
                    </div>
                </div>

                <div class="md:text-right">
                    <p
                        class="text-sm font-semibold text-gray-600 dark:text-gray-400 tracking-wider"
                    >
                        SISA SALDO
                    </p>

                    <div
                        class="flex items-baseline gap-2 mt-1 justify-center md:justify-end"
                    >
                        <span
                            :class="[
                                'text-3xl font-black transition-all duration-500',
                                animatedSaldo >= 0
                                    ? 'bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent'
                                    : 'bg-gradient-to-r from-red-600 to-pink-600 dark:from-red-400 dark:to-pink-400 bg-clip-text text-transparent',
                            ]"
                        >
                            {{ animatedSaldo >= 0 ? "+" : "-" }}
                            Rp
                            {{
                                Math.abs(animatedSaldo).toLocaleString("id-ID")
                            }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE (Tabel Kategori) -->
            <div>
                <!-- Header -->
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-6"
                >
                    <h2
                        class="text-lg font-bold text-gray-900 dark:text-gray-100"
                    >
                        TABEL KATEGORI
                    </h2>

                    <button
                        @click="openAddKategori = true"
                        class="group relative flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform overflow-hidden"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                        ></div>
                        <Plus
                            class="h-5 w-5 transition-transform group-hover:rotate-90"
                        />
                        <span class="hidden md:block">Tambah Kategori</span>
                    </button>
                </div>

                <!-- Table -->
                <div
                    class="rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
                >
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-green-600 to-green-700 text-white uppercase text-xs tracking-wide"
                            >
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3"></th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(item, index) in kategoriPaginated.data"
                                :key="item.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-gray-800 transition-all"
                            >
                                <td class="px-4 py-3">
                                    {{ kategoriPaginated.from + index }}
                                </td>

                                <td
                                    class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100 hover:text-green-500"
                                >
                                    {{ item.kategori }}
                                </td>

                                <td
                                    class="px-4 py-3 text-gray-500 dark:text-gray-400"
                                >
                                    <span class="text-green-600"
                                        >{{ item.kasumum_count }}
                                    </span>
                                    transaksi
                                </td>

                                <td
                                    class="px-4 py-3 flex items-end justify-end gap-2 text-right"
                                >
                                    <button
                                        @click="openEditModal(item)"
                                        class="p-2 bg-gray-50 dark:bg-gray-800 hover:bg-green-100 dark:hover:bg-green-900/20 text-gray-900 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm transition-all hover:scale-110"
                                    >
                                        <Pencil class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="kategoriPaginated.data.length === 0">
                                <td
                                    colspan="3"
                                    class="text-center py-6 text-gray-500 dark:text-gray-400"
                                >
                                    Tidak ada kategori
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    v-if="kategoriPaginated.data.length"
                    class="flex justify-center items-center mt-6 gap-2"
                >
                    <button
                        v-for="(link, index) in kategoriPaginated.links"
                        :key="index"
                        @click="goToKategori(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all font-medium"
                        :class="[
                            link.active
                                ? 'bg-green-600 text-white border-green-700 shadow-lg scale-105'
                                : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 hover:text-gray-900 hover:shadow',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="
                                index === kategoriPaginated.links.length - 1
                            "
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Container Tabel -->
        <div class="md:shadow-sm rounded-xl">
            <div
                :style="{
                    backgroundImage:
                        filters.jenis === 'Kredit'
                            ? 'url(/images/kas-umum-red.png)'
                            : 'url(/images/kas-umum-green.jpeg)',
                }"
                class="relative flex p-6 rounded-t-xl gap-4 z-10 border-b border-gray-300 dark:border-none justify-center items-center bg-cover bg-center"
            >
                <!-- Pemasukan (Debet) -->
                <button
                    @click="setJenis('Debet')"
                    :class="
                        filters.jenis === 'Debet'
                            ? 'bg-green-600 text-white shadow-lg transform scale-105'
                            : 'bg-white dark:bg-gray-800 text-green-700 dark:text-green-400'
                    "
                    class="flex items-center gap-2 px-4 py-2 rounded-xl font-semibold transition-all duration-300 shadow"
                >
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    Pemasukan
                </button>

                <!-- Pengeluaran (Kredit) -->
                <button
                    @click="setJenis('Kredit')"
                    :class="
                        filters.jenis === 'Kredit'
                            ? 'bg-red-600 text-white shadow-lg transform scale-105'
                            : 'bg-white dark:bg-gray-800 text-red-700 dark:text-red-400'
                    "
                    class="flex items-center gap-2 px-4 py-2 rounded-xl font-semibold transition-all duration-300 shadow"
                >
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    Pengeluaran
                </button>

                <!-- Reset -->
                <button
                    @click="setJenis('')"
                    :class="
                        !filters.jenis
                            ? 'bg-gray-700 text-white shadow-lg transform scale-105'
                            : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                    "
                    class="flex items-center gap-2 px-4 py-2 rounded-xl font-semibold transition-all duration-300 shadow"
                >
                    <div class="w-3 h-3 rounded-full bg-gray-500"></div>
                    Semua Transaksi
                </button>
            </div>

            <!-- Main Tabel -->
            <div
                class="p-6 bg-white dark:bg-gray-900 rounded-b-xl shadow-xs transition-all duration-500 border border-gray-200 dark:border-gray-700"
            >
                <!-- Header -->
                <div
                    class="flex flex-col md:flex-row flex-wrap gap-4 justify-between items-center mb-6"
                >
                    <div class="flex gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:flex-none">
                            <Search
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                            />
                            <input
                                type="text"
                                v-model="filters.search"
                                @input="applyFilters"
                                placeholder="Cari uraian, kategori..."
                                class="pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl w-full md:w-80 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 placeholder-gray-400 transition-all shadow-sm hover:shadow-md focus:outline-none"
                            />
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Tombol Export -->
                        <div class="relative" ref="exportRef">
                            <button
                                @click.stop="toggleExportDropdown"
                                class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 dark:hover:from-gray-700 dark:hover:to-gray-800 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95"
                            >
                                <Download class="h-5 w-5" />
                                <span class="hidden md:block">Export</span>
                                <ChevronDown
                                    class="w-4 h-4 transition-transform duration-300"
                                    :class="{ 'rotate-180': showExport }"
                                />
                            </button>

                            <!-- Dropdown -->
                            <Transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 translate-y-2"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0 translate-y-2"
                            >
                                <div
                                    v-if="showExport"
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden z-50"
                                >
                                    <ul class="py-1 text-sm">
                                        <li
                                            @click.stop="exportToExcel"
                                            class="flex items-center gap-2 px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer"
                                        >
                                            <FileSpreadsheet
                                                class="w-4 h-4 text-green-600 dark:text-green-400"
                                            />
                                            Export ke Excel
                                        </li>
                                    </ul>
                                </div>
                            </Transition>
                        </div>

                        <!-- BUTTON TAMBAH -->
                        <Link
                            :href="route('kas-umum.create')"
                            class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105"
                        >
                            <Plus class="h-6.5 md:h-5" />
                            <span class="hidden md:block">Tambah Data Kas</span>
                        </Link>
                    </div>
                </div>

                <!-- Table -->
                <div
                    class="md:bg-white md:dark:bg-gray-900 rounded-xl md:border md:border-gray-200 md:dark:border-gray-700 md:shadow-lg overflow-hidden"
                >
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr
                                    :class="[
                                        filters.jenis === 'Kredit'
                                            ? 'bg-gradient-to-r from-red-600 to-red-700'
                                            : 'bg-gradient-to-r from-green-600 to-green-700',
                                        'text-white uppercase text-xs tracking-wide',
                                    ]"
                                >
                                    <th class="px-4 py-3">No</th>

                                    <th
                                        class="px-4 py-3 cursor-pointer"
                                        @click="sort('tanggal')"
                                    >
                                        Tanggal
                                        <SortIcon
                                            field="tanggal"
                                            :filters="filters"
                                        />
                                    </th>

                                    <th
                                        class="px-4 py-3 cursor-pointer"
                                        @click="sort('uraian')"
                                    >
                                        Uraian
                                        <SortIcon
                                            field="uraian"
                                            :filters="filters"
                                        />
                                    </th>

                                    <th
                                        class="px-4 py-3 cursor-pointer"
                                        @click="sort('jenis')"
                                    >
                                        Jenis
                                        <SortIcon
                                            field="jenis"
                                            :filters="filters"
                                        />
                                    </th>

                                    <th
                                        class="px-4 py-3 cursor-pointer"
                                        @click="sort('kategori_id')"
                                    >
                                        Kategori
                                        <SortIcon
                                            field="kategori_id"
                                            :filters="filters"
                                        />
                                    </th>

                                    <th
                                        class="px-4 py-3 cursor-pointer"
                                        @click="sort('nominal')"
                                    >
                                        Nominal
                                        <SortIcon
                                            field="nominal"
                                            :filters="filters"
                                        />
                                    </th>

                                    <th class="px-4 py-3">Bukti</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="(item, index) in kas.data"
                                    :key="item.id"
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                                >
                                    <td class="px-4 py-3">
                                        {{ kas.from + index }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-medium text-gray-900 dark:text-white"
                                            >
                                                {{
                                                    new Date(
                                                        item.tanggal
                                                    ).toLocaleDateString(
                                                        "id-ID"
                                                    )
                                                }}
                                            </span>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{
                                                    new Date(
                                                        item.tanggal
                                                    ).toLocaleDateString(
                                                        "id-ID",
                                                        { weekday: "long" }
                                                    )
                                                }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ item.uraian }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold"
                                            :class="
                                                item.jenis === 'Debet'
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                                    : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                                            "
                                        >
                                            {{ item.jenis }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ item.kategori?.kategori }}
                                    </td>

                                    <td
                                        class="px-4 py-3 font-semibold"
                                        :class="
                                            item.jenis === 'Debet'
                                                ? 'text-green-600 dark:text-green-400'
                                                : 'text-red-600 dark:text-red-400'
                                        "
                                    >
                                        {{ item.jenis === "Debet" ? "+" : "-" }}
                                        Rp
                                        {{
                                            new Intl.NumberFormat().format(
                                                item.nominal
                                            )
                                        }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <a
                                            v-if="item.bukti_transaksi"
                                            :href="`/storage/${item.bukti_transaksi}`"
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                        >
                                            <Eye class="w-4 h-4" />
                                            Lihat
                                        </a>
                                        <span
                                            v-else
                                            class="text-gray-400 dark:text-gray-500"
                                            >-</span
                                        >
                                    </td>

                                    <td class="px-4 py-3">
                                        <div
                                            class="relative inline-block text-left"
                                        >
                                            <button
                                                @click.stop="
                                                    toggleDropdown(item, $event)
                                                "
                                                class="flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-gray-800 hover:bg-green-100 dark:hover:bg-green-900/20 text-gray-900 dark:text-gray-100 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all"
                                            >
                                                Aksi
                                                <ChevronDown class="w-4 h-4" />
                                            </button>

                                            <!-- Dropdown -->
                                            <teleport to="body">
                                                <Transition
                                                    enter-active-class="transition ease-out duration-200"
                                                    enter-from-class="opacity-0 translate-y-2"
                                                    enter-to-class="opacity-100 translate-y-0"
                                                    leave-active-class="transition ease-in duration-150"
                                                    leave-from-class="opacity-100"
                                                    leave-to-class="opacity-0 translate-y-2"
                                                >
                                                    <div
                                                        v-if="
                                                            openDropdown &&
                                                            selectedItem?.id ===
                                                                item.id
                                                        "
                                                        ref="dropdownRef"
                                                        class="absolute w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden shadow-md z-50"
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
                                                                    goToEdit(
                                                                        item.id
                                                                    )
                                                                "
                                                                class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer"
                                                            >
                                                                Edit
                                                            </li>
                                                            <li
                                                                @click="
                                                                    destroy(
                                                                        item.id
                                                                    )
                                                                "
                                                                class="px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 cursor-pointer"
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

                                <tr v-if="props.kas.data.length === 0">
                                    <td
                                        colspan="7"
                                        class="text-center py-6 text-gray-500 dark:text-gray-400"
                                    >
                                        Tidak ada data kas
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="kas.data.length"
                    class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
                >
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 dark:text-gray-300"
                            >Show</label
                        >
                        <select
                            v-model="filters.per_page"
                            @change="applyFilters"
                            class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div class="flex gap-1">
                        <button
                            v-for="(link, index) in props.kas.links"
                            :key="index"
                            @click="goToKas(link.url)"
                            :disabled="!link.url"
                            class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all font-medium"
                            :class="[
                                link.active
                                    ? 'bg-green-600 text-white border-green-700 shadow-lg scale-105'
                                    : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 hover:text-gray-900 hover:shadow',
                                !link.url
                                    ? 'opacity-40 cursor-not-allowed'
                                    : '',
                            ]"
                        >
                            <ChevronLeft v-if="index === 0" class="w-4 h-4" />
                            <ChevronRight
                                v-else-if="index === props.kas.links.length - 1"
                                class="w-4 h-4"
                            />
                            <span v-else v-html="link.label"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH KATEGORI -->
        <div
            v-if="openAddKategori"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition"
        >
            <div
                class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 animate-[fade-in_0.25s_ease-out]"
            >
                <!-- Header -->
                <h2
                    class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2"
                >
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Tambah Kategori
                </h2>

                <!-- Input -->
                <div class="mb-5">
                    <label
                        class="text-sm text-gray-600 dark:text-gray-300 font-medium"
                    >
                        Nama Kategori
                    </label>
                    <input
                        type="text"
                        v-model="kategoriForm.kategori"
                        placeholder="contoh: Perlengkapan, Donasi, Acara..."
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:border-green-500 focus:ring-2 focus:ring-green-400/40 outline-none transition"
                    />
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2">
                    <button
                        @click="openAddKategori = false"
                        class="px-4 py-2.5 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition font-medium"
                    >
                        Batal
                    </button>

                    <button
                        @click="submitKategori"
                        class="px-4 py-2.5 rounded-xl bg-green-600 text-white hover:bg-green-700 transition font-semibold shadow-md"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT KATEGORI -->
        <div
            v-if="openEditKategori"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition"
        >
            <div
                class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 animate-[fade-in_0.25s_ease-out]"
            >
                <!-- Header -->
                <h2
                    class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2"
                >
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    Edit Kategori
                </h2>

                <!-- Input -->
                <div class="mb-5">
                    <label
                        class="text-sm text-gray-600 dark:text-gray-300 font-medium"
                    >
                        Nama Kategori
                    </label>
                    <input
                        type="text"
                        v-model="kategoriForm.kategori"
                        placeholder="Nama kategori"
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-400/40 outline-none transition"
                    />
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2">
                    <button
                        @click="openEditKategori = false"
                        class="px-4 py-2.5 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition font-medium"
                    >
                        Batal
                    </button>

                    <button
                        @click="updateKategori"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition font-semibold shadow-md"
                    >
                        Update
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";
import ChartCard from "@/Components/Charts/ChartCard.vue";
import {
    ChevronLeft,
    ChevronRight,
    ChevronDown,
    ArrowUp,
    ArrowDown,
    Plus,
    Search,
    Eye,
    Pencil,
    Trash,
    Download,
    FileSpreadsheet,
} from "lucide-vue-next";

const props = defineProps({
    kas: Object,
    kategoriPaginated: Object,
    totalGlobal: Object,
    monthlyTransactions: Object,
    filters: Object,
});

// Animasi Saldo
const animatedSaldo = ref(0);

watch(
    () => props.totalGlobal.saldo,
    (newVal, oldVal) => {
        const duration = 800; // ms
        const start = oldVal ?? 0;
        const end = newVal;
        const startTime = performance.now();

        function animate(time) {
            const progress = Math.min((time - startTime) / duration, 1);
            animatedSaldo.value = Math.floor(start + (end - start) * progress);

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    },
    { immediate: true }
);

// FILTERS
const filters = ref({
    search: props.filters?.search || "",
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
    per_page: props.filters?.per_page || 10,
    jenis: props.filters?.jenis || "",
});

// Filter jenis (Pemasukan/Pengeluaran)
function setJenis(value) {
    filters.value.jenis = value;
    applyFilters();
}

function applyFilters() {
    router.get(route("kas-umum.index"), filters.value, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

// Export Excel
const showExport = ref(false);
const exportRef = ref(null);

function toggleExportDropdown() {
    showExport.value = !showExport.value;
}

function closeExport(e) {
    if (exportRef.value && !exportRef.value.contains(e.target)) {
        showExport.value = false;
    }
}

function exportToExcel() {
    showExport.value = false;

    const params = new URLSearchParams({
        search: filters.search ?? "",
        jenis: filters.jenis ?? "",
        sort: filters.sort ?? "",
        direction: filters.direction ?? "",
    });

    window.location.href = route("kas-umum.export") + "?" + params.toString();
}

// SORTING
function sort(column) {
    if (filters.value.sort_by !== column) {
        filters.value.sort_by = column;
        filters.value.sort_direction = "asc";
    } else {
        filters.value.sort_direction =
            filters.value.sort_direction === "asc"
                ? "desc"
                : filters.value.sort_direction === "desc"
                ? ""
                : "asc";

        if (filters.value.sort_direction === "") {
            filters.value.sort_by = "";
        }
    }

    applyFilters();
}

const SortIcon = {
    props: ["field", "filters"],
    components: { ArrowUp, ArrowDown },
    template: `
        <span class="inline-flex ml-1">
            <ArrowUp v-if="filters.sort_by === field && filters.sort_direction === 'asc'" class="w-3 h-3" />
            <ArrowDown v-else-if="filters.sort_by === field && filters.sort_direction === 'desc'" class="w-3 h-3" />
            <span v-else class="text-gray-300 dark:text-gray-500">↕</span>
        </span>
    `,
};

// DROPDOWN
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });

function toggleDropdown(item, event) {
    const rect = event.currentTarget.getBoundingClientRect();

    if (selectedItem.value?.id === item.id && openDropdown.value) {
        openDropdown.value = false;
        selectedItem.value = null;
        return;
    }

    selectedItem.value = item;
    openDropdown.value = true;

    dropdownPosition.value = {
        top: rect.bottom + window.scrollY,
        left: rect.left,
    };
}

// klik di luar → tutup dropdown
function handleGlobalClick(e) {
    if (
        openDropdown.value &&
        !e.target.closest(".dropdown-container") &&
        !e.target.closest(".aksi-button")
    ) {
        openDropdown.value = false;
        selectedItem.value = null;
    }
}

function handleClickOutside(e) {
    closeExport(e);
    handleGlobalClick(e);
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

// AKSI
function goToEdit(id) {
    router.visit(route("kas-umum.edit", id));
}

// PAGINATION
function goToKategori(url) {
    if (url)
        router.visit(url, {
            preserveScroll: true,
            preserveState: true,
            only: ["kategoriPaginated"],
        });
}

function goToKas(url) {
    if (url)
        router.visit(url, {
            preserveScroll: true,
            preserveState: true,
            only: ["kas", "filters"],
        });
}

// DELETE
function destroy(id) {
    if (confirm("Yakin ingin menghapus data ini?")) {
        router.delete(`/admin/kas-umum/${id}`);
    }
}

// Kategori
const openAddKategori = ref(false);
const openEditKategori = ref(false);

const kategoriForm = ref({ kategori: "" });
const editKategoriId = ref(null);

function openEditModal(item) {
    editKategoriId.value = item.id;
    kategoriForm.value.kategori = item.kategori;
    openEditKategori.value = true;
}

function submitKategori() {
    router.post(route("kategori.store"), kategoriForm.value, {
        onSuccess: () => {
            kategoriForm.value.kategori = "";
            openAddKategori.value = false;
        },
    });
}

function updateKategori() {
    router.put(
        route("kategori.update", editKategoriId.value),
        kategoriForm.value,
        {
            onSuccess: () => {
                openEditKategori.value = false;
                kategoriForm.value.kategori = "";
            },
        }
    );
}

function confirmDelete(item) {
    if (!confirm(`Hapus kategori "${item.kategori}"?`)) return;

    router.delete(route("kategori.destroy", item.id), {
        preserveScroll: true,
    });
}
</script>

<!-- ANIMATION -->
<style scoped></style>
