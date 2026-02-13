<template>
    <AppLayout>
        <Head title="Hutang Piutang" />

        <!-- DASHBOARD -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-4">
            <!-- SUMMARY (KIRI) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-4">
                <SummaryCard
                    title="Total Hutang"
                    :value="summary.total_hutang"
                    color="red"
                    icon="Clock"
                />

                <SummaryCard
                    title="Total Piutang"
                    :value="summary.total_piutang"
                    color="green"
                    icon="DollarSign"
                />

                <SummaryCard
                    title="Jatuh Tempo"
                    :value="summary.jatuh_tempo"
                    color="yellow"
                    suffix="Data"
                    icon="Calendar"
                />

                <SummaryCard
                    title="Saldo Bersih Kas"
                    :value="summary.saldo_kas"
                    color="emerald"
                    icon="Wallet"
                />
            </div>

            <!-- TREND (KANAN) -->
            <div class="xl:col-span-2">
                <TrendHutangPiutang :data="trend" />
            </div>
        </div>

        <!-- Main Container -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs transition-all border border-gray-200 dark:border-gray-700"
        >
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row gap-4 justify-between items-center mb-6"
            >
                <!-- Search -->
                <div class="relative w-full md:w-80">
                    <Search
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                    />
                    <input
                        v-model="filters.search"
                        @input="applyFilters"
                        placeholder="Cari kontak / keterangan..."
                        class="pl-10 pr-4 py-2 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 transition-all outline-none shadow-sm hover:shadow-md"
                    />
                </div>

                <!-- Filter Jenis -->
                <div
                    class="flex items-center gap-1 p-1 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700"
                >
                    <button
                        v-for="type in ['', 'Hutang', 'Piutang']"
                        :key="type"
                        @click="setJenis(type)"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-sm font-medium transition-all',
                            filters.jenis === type
                                ? 'bg-green-600 text-white shadow'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700',
                        ]"
                    >
                        {{ type || "Semua" }}
                    </button>
                </div>

                <!-- Button Tambah -->
                <Link
                    :href="route('hutang-piutang.create')"
                    class="group relative flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                    ></div>
                    <Plus
                        class="h-5 w-5 transition-transform group-hover:rotate-90"
                    />
                    <span class="hidden md:block">Tambah Data</span>
                </Link>
            </div>

            <!-- Table -->
            <div
                class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700"
            >
                <table class="min-w-full text-sm text-left">
                    <thead
                        class="bg-gradient-to-r from-green-600 to-green-700 text-white uppercase text-xs tracking-wide"
                    >
                        <tr>
                            <th class="px-4 py-3">No</th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('tanggal')"
                            >
                                Tanggal
                                <SortIcon field="tanggal" :filters="filters" />
                            </th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('nama')"
                            >
                                Kontak
                                <SortIcon field="nama" :filters="filters" />
                            </th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('keterangan')"
                            >
                                Keterangan
                                <SortIcon
                                    field="keterangan"
                                    :filters="filters"
                                />
                            </th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('jenis')"
                            >
                                Jenis
                                <SortIcon field="jenis" :filters="filters" />
                            </th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('nominal')"
                            >
                                Jumlah
                                <SortIcon field="nominal" :filters="filters" />
                            </th>

                            <th
                                class="px-4 py-3 cursor-pointer"
                                @click="sort('status')"
                            >
                                Status
                                <SortIcon field="status" :filters="filters" />
                            </th>

                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(item, index) in data.data"
                            :key="item.id"
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                        >
                            <td class="px-4 py-3">
                                {{ data.from + index }}
                            </td>

                            <td class="px-4 py-3 max-w-24">
                                {{ tanggal(item.tanggal) }}
                                <p class="text-xs text-gray-500">
                                    {{
                                        item.jatuh_tempo
                                            ? `Jatuh tempo: ${tanggal(
                                                  item.jatuh_tempo
                                              )}`
                                            : ""
                                    }}
                                </p>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img
                                        v-if="item.foto"
                                        :src="`/storage/${item.foto}`"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />
                                    <div>
                                        <p class="font-semibold">
                                            {{ item.nama }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ item.kontak }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 max-w-48">
                                {{ item.keterangan }}
                            </td>

                            <td class="px-4 py-3">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold"
                                    :class="
                                        item.jenis === 'Hutang'
                                            ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                            : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                    "
                                >
                                    {{ item.jenis }}
                                </span>
                            </td>

                            <td
                                class="px-4 py-3 font-semibold"
                                :class="
                                    item.jenis === 'Piutang'
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-red-600 dark:text-red-400'
                                "
                            >
                                Rp
                                {{
                                    new Intl.NumberFormat("id-ID").format(
                                        item.nominal
                                    )
                                }}
                            </td>

                            <td class="px-4 py-3">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold"
                                    :class="
                                        item.status === 'Lunas'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-yellow-100 text-yellow-700'
                                    "
                                >
                                    {{ item.status }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="relative inline-block text-left">
                                    <!-- BUTTON -->
                                    <button
                                        @click.stop="
                                            toggleDropdown(item, $event)
                                        "
                                        class="flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-gray-800 hover:bg-green-100 dark:hover:bg-green-900/20 text-gray-900 dark:text-gray-100 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all"
                                    >
                                        Aksi
                                        <ChevronDown class="w-4 h-4" />
                                    </button>

                                    <!-- DROPDOWN -->
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
                                                    selectedItem?.id === item.id
                                                "
                                                class="absolute w-56 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md z-50"
                                                :style="{
                                                    top:
                                                        dropdownPosition.top +
                                                        'px',
                                                    left:
                                                        dropdownPosition.left +
                                                        'px',
                                                }"
                                            >
                                                <!-- GRID 2 KOLOM -->
                                                <ul
                                                    class="grid grid-cols-2 text-sm text-gray-900 dark:text-gray-100"
                                                >
                                                    <!-- KOLOM KIRI -->
                                                    <li class="flex flex-col">
                                                        <button
                                                            v-if="
                                                                item.bukti_transaksi
                                                            "
                                                            @click="
                                                                lihatBukti(item)
                                                            "
                                                            class="px-4 py-2 text-left hover:text-green-600 dark:hover:text-green-400 transition-all duration-150"
                                                        >
                                                            Lihat Bukti
                                                        </button>

                                                        <button
                                                            v-if="
                                                                item.status ===
                                                                'Belum'
                                                            "
                                                            @click="
                                                                openBayarModal(
                                                                    item
                                                                )
                                                            "
                                                            class="px-4 py-2 text-left hover:text-green-600 dark:hover:text-green-400 transition-all duration-150"
                                                        >
                                                            Bayar
                                                        </button>

                                                        <button
                                                            @click.stop="
                                                                openRiwayat(
                                                                    item
                                                                )
                                                            "
                                                            class="px-4 py-2 text-left hover:text-green-600 dark:hover:text-green-400 transition-all duration-150"
                                                        >
                                                            Riwayat
                                                        </button>
                                                    </li>

                                                    <!-- KOLOM KANAN -->
                                                    <li class="flex flex-col">
                                                        <button
                                                            @click="
                                                                goToEdit(
                                                                    item.id
                                                                )
                                                            "
                                                            class="px-4 py-2 text-left hover:text-green-600 dark:hover:text-green-400 transition-all duration-150"
                                                        >
                                                            Edit
                                                        </button>

                                                        <button
                                                            @click="
                                                                destroy(item.id)
                                                            "
                                                            class="px-4 py-2 text-red-600 dark:text-red-400 text-left hover:font-semibold transition-all duration-150"
                                                        >
                                                            Hapus
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </Transition>
                                    </teleport>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="data.data.length === 0">
                            <td
                                colspan="7"
                                class="py-6 text-center text-gray-500"
                            >
                                Tidak ada data hutang/piutang
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                v-if="data.data.length"
                class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4"
            >
                <!-- SHOW PER PAGE -->
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

                <!-- PAGINATION BUTTON -->
                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in data.links"
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
                            v-else-if="index === data.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Pembayaran -->
        <Transition name="modal-fade">
            <div
                v-if="showBayar"
                class="fixed inset-0 z-[100] bg-black/60 dark:bg-black/70 backdrop-blur-lg flex items-center justify-center p-4"
                @click.self="showBayar = false"
            >
                <div
                    class="bg-gradient-to-br from-white to-green-50 dark:from-gray-900 dark:to-gray-950 w-full max-w-md rounded-2xl shadow-2xl border border-green-100 dark:border-gray-800 overflow-hidden transform transition-all duration-500"
                >
                    <!-- Header -->
                    <div
                        class="relative bg-gradient-to-r from-green-600 to-green-500 p-6"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm"
                                >
                                    <DollarSign class="w-5 h-5 text-white" />
                                </div>
                                <h3
                                    class="text-xl font-bold text-white tracking-tight"
                                >
                                    Bayar {{ bayarItem?.nama }}
                                </h3>
                            </div>
                            <button
                                @click="showBayar = false"
                                class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all duration-300 group"
                            >
                                <X
                                    class="w-4 h-4 text-white group-hover:scale-110 transition-transform"
                                />
                            </button>
                        </div>

                        <!-- Sisa Hutang/Piutang -->
                        <div
                            class="mt-6 bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/20"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-green-100 font-medium"
                                    >Sisa
                                    {{
                                        bayarItem?.jenis === "Hutang"
                                            ? "Hutang"
                                            : "Piutang"
                                    }}</span
                                >
                                <span class="text-2xl font-bold text-white">
                                    {{
                                        rupiah(
                                            bayarItem?.nominal?.toLocaleString(
                                                "id-ID"
                                            ) || 0
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pembayaran -->
                    <form @submit.prevent="submitBayar" class="p-6 space-y-6">
                        <!-- Tanggal -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 mb-2">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center"
                                >
                                    <Calendar
                                        class="w-4 h-4 text-green-600 dark:text-green-400"
                                    />
                                </div>
                                <label
                                    class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    Tanggal Pembayaran
                                    <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <div class="relative">
                                <input
                                    type="date"
                                    v-model="bayarForm.tanggal"
                                    required
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                                />
                                <div
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2"
                                >
                                    <Calendar
                                        class="w-5 h-5 text-green-600 dark:text-green-400"
                                    />
                                </div>
                            </div>
                            <p
                                class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            >
                                Tanggal saat pembayaran dilakukan
                            </p>
                        </div>

                        <!-- Nominal -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center"
                                    >
                                        <DollarSign
                                            class="w-4 h-4 text-green-600 dark:text-green-400"
                                        />
                                    </div>
                                    <label
                                        class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                    >
                                        Nominal Pembayaran
                                        <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                <span
                                    class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300"
                                >
                                    Maks: Rp
                                    {{
                                        bayarItem?.nominal?.toLocaleString(
                                            "id-ID"
                                        ) || 0
                                    }}
                                </span>
                            </div>
                            <div class="relative">
                                <div
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-900 dark:text-gray-100 font-semibold"
                                >
                                    Rp
                                </div>
                                <input
                                    type="number"
                                    v-model="bayarForm.nominal"
                                    required
                                    :max="bayarItem?.nominal"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                                />
                                <div
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2"
                                >
                                    <span class="text-sm text-gray-500"
                                        >IDR</span
                                    >
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="pt-2" v-if="bayarItem?.nominal">
                                <div
                                    class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-1"
                                >
                                    <span>Progress Pembayaran</span>
                                    <span
                                        >{{
                                            Math.min(
                                                (bayarForm.nominal /
                                                    bayarItem.nominal) *
                                                    100,
                                                100
                                            ).toFixed(1)
                                        }}%</span
                                    >
                                </div>
                                <div
                                    class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden"
                                >
                                    <div
                                        class="h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full transition-all duration-500"
                                        :style="{
                                            width:
                                                Math.min(
                                                    (bayarForm.nominal /
                                                        bayarItem.nominal) *
                                                        100,
                                                    100
                                                ) + '%',
                                        }"
                                    ></div>
                                </div>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400 mt-2"
                                >
                                    Sisa setelah bayar:
                                    <span
                                        class="font-semibold text-green-600 dark:text-green-400"
                                    >
                                        Rp
                                        {{
                                            Math.max(
                                                0,
                                                bayarItem.nominal -
                                                    bayarForm.nominal
                                            ).toLocaleString("id-ID")
                                        }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div
                        class="border-t border-gray-100 dark:border-gray-800 p-6 bg-gradient-to-r from-white/50 to-green-50/50 dark:from-gray-900 dark:to-gray-950"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <button
                                type="button"
                                @click="showBayar = false"
                                class="flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold shadow-sm hover:shadow-md transition-all duration-300"
                            >
                                Batalkan
                            </button>
                            <button
                                type="submit"
                                @click="submitBayar"
                                :disabled="
                                    !bayarForm.nominal ||
                                    bayarForm.nominal <= 0 ||
                                    bayarForm.nominal > bayarItem?.nominal
                                "
                                class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-semibold shadow-lg hover:shadow-xl hover:shadow-green-600/30 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                            >
                                <!-- NORMAL -->
                                <span v-if="!bayarForm.processing">
                                    Konfirmasi Bayar
                                </span>

                                <!-- LOADING -->
                                <span
                                    v-else
                                    class="flex items-center justify-center gap-2"
                                >
                                    <Loader2 class="w-4 h-4 animate-spin" />
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Modal Riwayat -->
        <Transition name="modal-fade">
            <div
                v-if="showRiwayat"
                class="fixed inset-0 z-[100] bg-black/60 dark:bg-black/70 backdrop-blur-lg flex items-center justify-center p-4"
                @click.self="showRiwayat = false"
            >
                <div
                    class="bg-gradient-to-br from-white to-green-50 dark:from-gray-900 dark:to-gray-950 w-full max-w-md rounded-2xl shadow-2xl border border-green-100 dark:border-gray-800 overflow-hidden transform transition-all duration-500"
                >
                    <!-- Header -->
                    <div
                        class="relative bg-gradient-to-r from-green-600 to-green-500 p-6"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm"
                                >
                                    <ClipboardList class="w-5 h-5 text-white" />
                                </div>
                                <h3
                                    class="text-xl font-bold text-white tracking-tight"
                                >
                                    Riwayat Pembayaran
                                </h3>
                            </div>
                            <button
                                @click="showRiwayat = false"
                                class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all duration-300 group"
                            >
                                <X
                                    class="w-4 h-4 text-white group-hover:scale-110 transition-transform"
                                />
                            </button>
                        </div>

                        <!-- Total -->
                        <div
                            class="mt-6 bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/20"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-green-100 font-medium"
                                    >Total Terbayar</span
                                >
                                <span class="text-2xl font-bold text-white">
                                    {{ rupiah(totalRiwayat) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat List -->
                    <div
                        class="p-6 max-h-[400px] overflow-y-auto custom-scrollbar"
                    >
                        <div
                            v-if="riwayat.length === 0"
                            class="text-center py-10"
                        >
                            <div
                                class="w-16 h-16 mx-auto bg-green-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4"
                            >
                                <Clock class="w-8 h-8 text-green-400" />
                            </div>
                            <p
                                class="text-gray-500 dark:text-gray-400 font-medium"
                            >
                                Belum ada riwayat pembayaran
                            </p>
                        </div>

                        <ul v-else class="space-y-4">
                            <li
                                v-for="(p, index) in riwayat"
                                :key="p.id"
                                class="group relative"
                            >
                                <div
                                    class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-green-50 dark:hover:bg-gray-800 hover:border-green-200 dark:hover:border-green-900 transition-all duration-300 hover:shadow-md"
                                >
                                    <!-- Number Badge -->
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center"
                                    >
                                        <span
                                            class="text-white font-bold text-sm"
                                            >{{ index + 1 }}</span
                                        >
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div
                                            class="flex items-center justify-between mb-1"
                                        >
                                            <span
                                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                            >
                                                Pembayaran #{{ index + 1 }}
                                            </span>
                                            <span
                                                class="text-xs px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 font-medium"
                                            >
                                                {{ tanggal(p.tanggal) }}
                                            </span>
                                        </div>

                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400 mb-2"
                                        >
                                            {{ formatWaktu(p.tanggal) }}
                                        </p>

                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <span
                                                class="text-lg font-bold text-green-600 dark:text-green-400"
                                            >
                                                {{ rupiah(p.nominal) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Footer -->
                    <div
                        class="border-t border-gray-100 dark:border-gray-800 p-6 bg-gradient-to-r from-white/50 to-green-50/50 dark:from-gray-900 dark:to-gray-950"
                    >
                        <div class="flex items-center justify-between">
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                Menampilkan total
                                <span class="font-medium">{{
                                    riwayat.length
                                }}</span>
                                transaksi
                            </div>
                            <button
                                @click="showRiwayat = false"
                                class="px-5 py-2.5 rounded-xl border border-green-600 bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0"
                            >
                                Tutup Riwayat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head, router, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import { rupiah, tanggal } from "@/helpers";
import SummaryCard from "@/Components/SummaryCard.vue";
import TrendHutangPiutang from "@/Components/Charts/TrendHutangPiutang.vue";
import {
    Search,
    Plus,
    ArrowUp,
    ArrowDown,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    Calendar,
    DollarSign,
    Loader2,
    ClipboardList,
    X,
    Clock,
} from "lucide-vue-next";

const props = defineProps({
    data: Object,
    filters: Object,
    summary: Object,
    trend: {
        type: Array,
        default: () => [],
    },
});

// FILTERS
const filters = ref({
    search: props.filters?.search || "",
    jenis: props.filters?.jenis || "",
    status: props.filters?.status || "",
    per_page: props.filters?.per_page || 10,
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
});

function applyFilters() {
    router.get(route("hutang-piutang.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function setJenis(jenis) {
    filters.value.jenis = jenis;
    applyFilters();
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
        <span class="inline-flex items-center ml-1">
            <ArrowUp
                v-if="filters.sort_by === field && filters.sort_direction === 'asc'"
                class="w-3 h-3"
            />
            <ArrowDown
                v-else-if="filters.sort_by === field && filters.sort_direction === 'desc'"
                class="w-3 h-3"
            />
            <span
                v-else
                class="text-white/70 dark:text-gray-400 text-xs leading-none"
            >
                ↕
            </span>
        </span>
    `,
};

// PAGINATION
function goTo(url) {
    if (url) {
        router.visit(url, {
            preserveScroll: true,
            preserveState: true,
        });
    }
}

// DROPDOWN ACTIONS
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });

function toggleDropdown(item, event) {
    const rect = event.currentTarget.getBoundingClientRect();
    const dropdownWidth = 224; // w-56

    if (openDropdown.value && selectedItem.value?.id === item.id) {
        openDropdown.value = false;
        selectedItem.value = null;
        return;
    }

    selectedItem.value = item;
    openDropdown.value = true;

    dropdownPosition.value = {
        top: rect.bottom + window.scrollY + 6,
        left: rect.right - dropdownWidth + window.scrollX,
    };
}

function handleGlobalClick(e) {
    if (showBayar.value || showRiwayat.value) return;

    if (!e.target.closest(".relative")) {
        openDropdown.value = false;
        selectedItem.value = null;
    }
}

onMounted(() => document.addEventListener("click", handleGlobalClick));
onBeforeUnmount(() => document.removeEventListener("click", handleGlobalClick));

// Lihat Bukti
function lihatBukti(item) {
    window.open(`/storage/${item.bukti_transaksi}`, "_blank");
    openDropdown.value = false;
}

// Bayar
const showBayar = ref(false);
const bayarItem = ref(null);

const bayarForm = useForm({
    tanggal: new Date().toISOString().split("T")[0],
    nominal: 0,
});

function openBayarModal(item) {
    bayarItem.value = { ...item };
    bayarForm.tanggal = new Date().toISOString().split("T")[0];
    bayarForm.nominal = item.nominal;

    showBayar.value = true;
    openDropdown.value = false;
}

function submitBayar() {
    bayarForm.post(
        route("hutang-piutang.pembayaran.store", bayarItem.value.id),
        {
            onSuccess: () => {
                showBayar.value = false;
                bayarForm.reset();
            },
        }
    );
}

function formatDatePreview(dateString) {
    if (!dateString) return "tanggal belum dipilih";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
    });
}

// Riwayat
const showRiwayat = ref(false);
const riwayat = ref([]);

function openRiwayat(item) {
    openDropdown.value = false;
    selectedItem.value = item;

    axios.get(route("hutang-piutang.riwayat", item.id)).then((res) => {
        riwayat.value = res.data;
        showRiwayat.value = true;
    });
}

const totalRiwayat = computed(() => {
    return riwayat.value.reduce(
        (sum, item) => sum + parseFloat(item.nominal),
        0
    );
});

function formatWaktu(tanggal) {
    return new Date(tanggal).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
}

// EDIT
function goToEdit(id) {
    router.visit(route("hutang-piutang.edit", id));
}

// DELETE
function destroy(id) {
    if (confirm("Yakin ingin menghapus data ini?")) {
        router.delete(route("hutang-piutang.destroy", id));
    }
}
</script>

<style scoped>
.modal-fade-enter-to,
.modal-fade-leave-from {
    opacity: 1;
    transform: scale(1);
}

/* Input number arrow hide */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
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

/* Animation for list items */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.space-y-4 > * {
    animation: slideIn 0.3s ease-out forwards;
    opacity: 0;
}

.space-y-4 > *:nth-child(1) {
    animation-delay: 0.05s;
}
.space-y-4 > *:nth-child(2) {
    animation-delay: 0.1s;
}
.space-y-4 > *:nth-child(3) {
    animation-delay: 0.15s;
}
.space-y-4 > *:nth-child(4) {
    animation-delay: 0.2s;
}
.space-y-4 > *:nth-child(5) {
    animation-delay: 0.25s;
}
.space-y-4 > *:nth-child(n + 6) {
    animation-delay: 0.3s;
}
</style>
