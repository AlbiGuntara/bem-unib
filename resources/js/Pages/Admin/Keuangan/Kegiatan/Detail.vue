<template>
    <AppLayout>
        <Head :title="`Kas Kegiatan - ${props.kegiatan.nama_kegiatan}`" />

        <!-- HEADER + SUMMARY -->
        <div
            class="p-6 rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-800"
        >
            <!-- Title Section -->
            <div class="mb-8">
                <div
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4"
                >
                    <div>
                        <h1
                            class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ props.kegiatan.nama_kegiatan }}
                        </h1>

                        <div class="flex items-center gap-2 mt-2">
                            <div
                                class="w-2 h-2 rounded-full bg-green-500"
                            ></div>
                            <p class="text-gray-600 dark:text-gray-400">
                                Tanggal Pelaksanaan:
                                <span
                                    class="font-semibold text-gray-900 dark:text-gray-100 ml-1"
                                >
                                    {{
                                        tanggal(
                                            props.kegiatan.tanggal_pelaksanaan
                                        ) ?? "Belum ditentukan"
                                    }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div
                        class="px-4 py-2 rounded-full bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800"
                    >
                        <span
                            class="text-sm font-medium text-green-700 dark:text-green-400"
                        >
                            {{
                                props.summary.saldo >= 0
                                    ? "Saldo Positif"
                                    : "Saldo Negatif"
                            }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Total Debet -->
                <div
                    class="group p-5 rounded-xl bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-900 border border-green-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="p-2 rounded-lg bg-green-100 dark:bg-green-900/40 flex"
                        >
                            <ArrowDownCircle
                                class="w-5 h-5 text-green-600 dark:text-green-400"
                            />
                        </div>

                        <span
                            class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400"
                        >
                            Pemasukan
                        </span>
                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                        Total Debet
                    </p>

                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-green-700 dark:group-hover:text-green-400 transition-colors"
                    >
                        {{ rupiah(props.summary.debet) }}
                    </h2>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        Total uang masuk untuk acara
                    </p>
                </div>

                <!-- Total Kredit -->
                <div
                    class="group p-5 rounded-xl bg-gradient-to-br from-red-50 to-pink-50 dark:from-gray-800 dark:to-gray-900 border border-red-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="p-2 rounded-lg bg-red-100 dark:bg-red-900/40 flex"
                        >
                            <ArrowUpCircle
                                class="w-5 h-5 text-red-600 dark:text-red-400"
                            />
                        </div>

                        <span
                            class="text-xs font-medium px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400"
                        >
                            Pengeluaran
                        </span>
                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                        Total Kredit
                    </p>

                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors"
                    >
                        {{ rupiah(props.summary.kredit) }}
                    </h2>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        Total pengeluaran acara
                    </p>
                </div>

                <!-- SALDO -->
                <div
                    class="group p-5 rounded-xl border shadow-sm hover:shadow-md transition-shadow relative overflow-hidden"
                    :class="{
                        'bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 border-green-200 dark:border-green-700':
                            props.summary.saldo >= 0,
                        'bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/10 dark:to-pink-900/10 border-red-200 dark:border-red-700':
                            props.summary.saldo < 0,
                    }"
                >
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="p-2 rounded-lg flex"
                            :class="{
                                'bg-green-100 dark:bg-green-900/40':
                                    props.summary.saldo >= 0,
                                'bg-red-100 dark:bg-red-900/40':
                                    props.summary.saldo < 0,
                            }"
                        >
                            <component
                                :is="
                                    props.summary.saldo >= 0
                                        ? TrendingUp
                                        : TrendingDown
                                "
                                class="w-5 h-5"
                                :class="{
                                    'text-green-600 dark:text-green-400':
                                        props.summary.saldo >= 0,
                                    'text-red-600 dark:text-red-400':
                                        props.summary.saldo < 0,
                                }"
                            />
                        </div>

                        <span
                            class="text-xs font-medium px-2 py-1 rounded-full"
                            :class="{
                                'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400':
                                    props.summary.saldo >= 0,
                                'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400':
                                    props.summary.saldo < 0,
                            }"
                        >
                            {{
                                props.summary.saldo >= 0 ? "Surplus" : "Defisit"
                            }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                        Sisa Saldo
                    </p>

                    <h2
                        class="text-2xl font-bold group-hover:scale-105 transition-transform origin-left"
                        :class="{
                            'text-green-700 dark:text-green-400':
                                props.summary.saldo >= 0,
                            'text-red-700 dark:text-red-400':
                                props.summary.saldo < 0,
                        }"
                    >
                        {{ rupiah(Math.abs(props.summary.saldo)) }}
                    </h2>

                    <p
                        class="text-xs mt-3"
                        :class="{
                            'text-green-600 dark:text-green-400':
                                props.summary.saldo >= 0,
                            'text-red-600 dark:text-red-400':
                                props.summary.saldo < 0,
                        }"
                    >
                        {{
                            props.summary.saldo >= 0
                                ? "✓ Saldo aman"
                                : "⚠ Perlu penambahan dana"
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Tabel -->
        <div
            class="mt-4 p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 transition-all duration-500"
        >
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row flex-wrap gap-4 justify-between items-center mb-6"
            >
                <!-- Search -->
                <div class="flex gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <Search
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                        />
                        <input
                            type="text"
                            v-model="query.search"
                            @input="applyFilters"
                            placeholder="Cari uraian, kategori..."
                            class="pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl w-full md:w-80 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 placeholder-gray-400 transition-all shadow-sm hover:shadow-md focus:outline-none"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Export -->
                    <div class="relative" ref="exportRef">
                        <button
                            @click.stop="toggleExportDropdown"
                            class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:from-green-50 hover:to-green-100 dark:hover:from-gray-700 dark:hover:to-gray-800 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95"
                        >
                            <Download class="h-5 w-5" />
                            <span class="hidden md:block">Export</span>
                            <ChevronDown
                                class="w-4 h-4 transition-transform"
                                :class="{ 'rotate-180': showExport }"
                            />
                        </button>

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
                                        class="flex items-center gap-2 px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer transition-all"
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

                    <!-- Tambah -->
                    <button
                        @click="openAdd = true"
                        class="group relative flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform overflow-hidden"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                        ></div>
                        <Plus
                            class="h-5 w-5 transition-transform group-hover:rotate-90"
                        />
                        <span class="hidden md:block">Tambah Transaksi</span>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div
                class="md:bg-white md:dark:bg-gray-900 rounded-2xl md:border md:border-gray-200 md:dark:border-gray-700 md:shadow-lg overflow-hidden"
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
                                    @click="sort('tanggal')"
                                >
                                    Tanggal
                                    <SortIcon
                                        field="tanggal"
                                        :filters="query"
                                    />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('uraian')"
                                >
                                    Uraian
                                    <SortIcon field="uraian" :filters="query" />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('jenis')"
                                >
                                    Jenis
                                    <SortIcon field="jenis" :filters="query" />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('nominal')"
                                >
                                    Nominal
                                    <SortIcon
                                        field="nominal"
                                        :filters="query"
                                    />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('kategori')"
                                >
                                    Kategori
                                    <SortIcon
                                        field="kategori"
                                        :filters="query"
                                    />
                                </th>

                                <th class="px-4 py-3">Bukti</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(item, index) in transaksi.data"
                                :key="item.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                            >
                                <td class="px-4 py-3">
                                    {{ transaksi.from + index }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ tanggal(item.tanggal) }}
                                </td>

                                <td class="px-4 py-3">{{ item.uraian }}</td>

                                <td class="px-4 py-3">
                                    <span
                                        :class="
                                            item.jenis === 'Debet'
                                                ? 'text-green-600 font-semibold'
                                                : 'text-red-600 font-semibold'
                                        "
                                    >
                                        {{ item.jenis }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    Rp
                                    {{
                                        Number(item.nominal).toLocaleString(
                                            "id-ID"
                                        )
                                    }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ item.kategori?.kategori }}
                                </td>

                                <td class="px-4 py-3">
                                    <img
                                        v-if="item.bukti_transaksi"
                                        :src="`/storage/${item.bukti_transaksi}`"
                                        class="w-14 h-14 rounded-lg object-cover shadow cursor-pointer hover:scale-105 transition"
                                        @click="
                                            openPreview(
                                                `/storage/${item.bukti_transaksi}`
                                            )
                                        "
                                    />
                                    <span v-else>-</span>
                                </td>

                                <td class="px-4 py-3">
                                    <div
                                        class="relative inline-block text-left"
                                    >
                                        <button
                                            @click.stop="
                                                toggleDropdown(item, $event)
                                            "
                                            class="aksi-button flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-gray-800 hover:bg-green-100 dark:hover:bg-green-900/20 text-gray-900 dark:text-gray-100 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all"
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
                                                    class="absolute w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden dropdown-menu-active"
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
                                                            class="px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20 cursor-pointer"
                                                        >
                                                            Edit
                                                        </li>
                                                        <li
                                                            @click="
                                                                destroy(item.id)
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
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="transaksi.data.length"
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
                        v-for="(link, index) in transaksi.links"
                        :key="index"
                        @click="goTo(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all font-medium"
                        :class="[
                            link.active
                                ? 'bg-green-600 text-white border-green-700 shadow-lg scale-105'
                                : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-gray-100 hover:shadow',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="index === transaksi.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH -->
        <Modal
            :open="openAdd"
            title="Tambah Transaksi Kegiatan"
            subtitle="Isi data transaksi untuk kegiatan ini"
            @close="openAdd = false"
        >
            <!-- CONTENT -->
            <div class="grid grid-cols-2 gap-4">
                <input
                    v-model="formAdd.tanggal"
                    type="date"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-green-600"
                />

                <select
                    v-model="formAdd.jenis"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm"
                >
                    <option value="Debet">Debet</option>
                    <option value="Kredit">Kredit</option>
                </select>

                <select
                    v-model="formAdd.kategori_id"
                    class="col-span-2 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-900 bg-white text-gray-900 dark:text-gray-100 shadow-sm"
                >
                    <option value="">-- Pilih Kategori --</option>
                    <option
                        v-for="k in props.kategoriList"
                        :key="k.id"
                        :value="k.id"
                    >
                        {{ k.kategori }}
                    </option>
                </select>

                <input
                    v-model="formAdd.nominal"
                    type="number"
                    placeholder="Nominal"
                    class="col-span-2 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm"
                />

                <input
                    type="file"
                    @change="formAdd.bukti_transaksi = $event.target.files[0]"
                    class="col-span-2 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm"
                />

                <textarea
                    v-model="formAdd.uraian"
                    placeholder="Uraian transaksi"
                    class="col-span-2 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 h-24 resize-none shadow-sm"
                ></textarea>
            </div>

            <!-- FOOTER -->
            <template #footer>
                <button
                    @click="openAdd = false"
                    class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                >
                    Batal
                </button>

                <button
                    @click="submitAdd"
                    class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-lg shadow-md font-semibold flex items-center gap-2 transition-all duration-300"
                >
                    Simpan
                </button>
            </template>
        </Modal>

        <!-- MODAL EDIT -->
        <Modal
            :open="openEdit"
            title="Edit Transaksi Kegiatan"
            subtitle="Perbarui informasi transaksi"
            @close="openEdit = false"
        >
            <!-- CONTENT -->
            <div class="grid grid-cols-2 gap-4">
                <input
                    v-model="formEdit.tanggal"
                    type="date"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-green-600"
                />

                <select
                    v-model="formEdit.jenis"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm"
                >
                    <option value="Debet">Debet</option>
                    <option value="Kredit">Kredit</option>
                </select>

                <select
                    v-model="formEdit.kategori_id"
                    class="col-span-2 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm"
                >
                    <option value="">-- Pilih Kategori --</option>
                    <option
                        v-for="k in props.kategoriList"
                        :key="k.id"
                        :value="k.id"
                    >
                        {{ k.kategori }}
                    </option>
                </select>

                <input
                    v-model="formEdit.nominal"
                    type="number"
                    placeholder="Nominal"
                    class="col-span-2 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm"
                />

                <input
                    type="file"
                    @change="formEdit.bukti_transaksi = $event.target.files[0]"
                    class="col-span-2 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm"
                />

                <textarea
                    v-model="formEdit.uraian"
                    placeholder="Uraian transaksi"
                    class="col-span-2 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 h-24 resize-none shadow-sm"
                ></textarea>
            </div>

            <!-- FOOTER -->
            <template #footer>
                <button
                    @click="openEdit = false"
                    class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                >
                    Batal
                </button>

                <button
                    @click="submitEdit"
                    class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-lg shadow-md font-semibold flex items-center gap-2 transition-all duration-300"
                >
                    Update
                </button>
            </template>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount } from "vue";
import { router, Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Modal from "@/Components/Modal.vue";
import { tanggal, rupiah } from "@/helpers";
import {
    Plus,
    Search,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    ArrowUp,
    ArrowDown,
    ArrowDownCircle,
    ArrowUpCircle,
    TrendingUp,
    TrendingDown,
    Download,
    FileSpreadsheet,
} from "lucide-vue-next";

const props = defineProps({
    kegiatan: Object,
    transaksi: Object,
    kategoriList: Array,
    summary: Object,
    filters: Object,
});

const showExport = ref(false);
const exportRef = ref(null);

function toggleExportDropdown() {
    showExport.value = !showExport.value;
}

function handleClickOutside(e) {
    if (exportRef.value && !exportRef.value.contains(e.target)) {
        showExport.value = false;
    }
}

onMounted(() => document.addEventListener("click", handleClickOutside));
onBeforeUnmount(() =>
    document.removeEventListener("click", handleClickOutside)
);

// EXPORT EXCEL
function exportToExcel() {
    showExport.value = false;

    // kirim filter yang sedang aktif
    const params = {
        search: query.search,
        sort: query.sort,
        direction: query.direction,
    };

    window.location.href =
        route("kas.export.excel") + "?" + new URLSearchParams(params);
}

// Filters
const query = reactive({
    search: props.filters.search ?? "",
    sort_by: props.filters.sort_by ?? "",
    sort_direction: props.filters.sort_direction ?? "",
    per_page: props.filters.per_page ?? 10,
});

function applyFilters() {
    router.get(window.location.pathname, query, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function sort(field) {
    if (query.sort_by === field) {
        if (query.sort_direction === "asc") {
            query.sort_direction = "desc";
        } else if (query.sort_direction === "desc") {
            query.sort_by = "";
            query.sort_direction = "";
        } else {
            query.sort_direction = "asc";
        }
    } else {
        query.sort_by = field;
        query.sort_direction = "asc";
    }

    applyFilters();
}

const SortIcon = {
    props: {
        field: String,
        filters: Object,
    },
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
            <span v-else class="text-gray-300 dark:text-gray-500">↕</span>
        </span>
    `,
};

function goTo(url) {
    if (url) router.visit(url);
}

/* Dropdown Aksi */
const openDropdown = ref(false);
const selectedItem = ref(null);
const dropdownPosition = reactive({ top: 0, left: 0 });
const dropdownRef = ref(null);

function toggleDropdown(item, event) {
    if (openDropdown.value && selectedItem.value?.id === item.id) {
        openDropdown.value = false;
        return;
    }
    selectedItem.value = item;
    const rect = event.target.getBoundingClientRect();
    dropdownPosition.top = rect.bottom + window.scrollY;
    dropdownPosition.left = rect.left + window.scrollX;
    openDropdown.value = true;
}

function destroy(id) {
    router.delete(`/admin/kas-kegiatan/transaksi/${id}`);
}

function openEditModal(item) {
    emit("edit", item);
}

function openPreview(url) {
    window.open(url, "_blank");
}

// Modal states
const openAdd = ref(false);
const openEdit = ref(false);

// Forms
const formAdd = ref({
    tanggal: "",
    uraian: "",
    jenis: "Debet",
    kategori_id: "",
    nominal: "",
    bukti_transaksi: null,
});

const formEdit = ref({});
let editId = null;

// Submit Tambah
const submitAdd = () => {
    router.post(
        `/admin/kas-kegiatan/${props.kegiatan.id}/store`,
        formAdd.value,
        {
            onSuccess: () => (openAdd.value = false),
        }
    );
};

// Submit Edit
const submitEdit = () => {
    router.post(`/admin/kas-kegiatan/transaksi/${editId}`, formEdit.value, {
        method: "put",
        onSuccess: () => (openEdit.value = false),
    });
};
</script>
