<template>
    <AppLayout>
        <Head title="Surat Masuk" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Card Total Surat Masuk -->
            <div
                class="group relative p-8 bg-yellow-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-500 overflow-hidden"
            >
                <!-- Background Effects -->
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-yellow-500/5 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-700"
                ></div>
                <div
                    class="absolute bottom-0 left-0 w-24 h-24 bg-yellow-400/5 rounded-full translate-y-12 -translate-x-12 group-hover:scale-125 transition-transform duration-700 delay-100"
                ></div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="space-y-2">
                            <h3
                                class="text-lg font-semibold text-gray-200 flex items-center gap-2"
                            >
                                <Inbox class="w-5 h-5 text-yellow-500" />
                                Surat Masuk
                            </h3>
                            <p class="text-3xl font-bold text-white">
                                {{ totalMasuk }}
                            </p>
                        </div>

                        <button
                            @click="goToMasuk"
                            class="p-2 text-white rounded-full bg-white/20 hover:bg-white/30 text-whitexs transition-all duration-300 backdrop-blur-sm transform hover:scale-110 active:scale-95"
                        >
                            <ArrowUpRight class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Total Surat Keluar -->
            <div
                class="group relative p-8 bg-gradient-to-r from-white via-green-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-500 overflow-hidden"
            >
                <!-- Background Effects -->
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-700"
                ></div>
                <div
                    class="absolute bottom-0 left-0 w-24 h-24 bg-green-400/5 rounded-full translate-y-12 -translate-x-12 group-hover:scale-125 transition-transform duration-700 delay-100"
                ></div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="space-y-2">
                            <h3
                                class="text-lg font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2"
                            >
                                <Send class="w-5 h-5 text-green-500" />
                                Surat Keluar
                            </h3>
                            <p
                                class="text-3xl font-bold text-gray-800 dark:text-white"
                            >
                                {{ totalKeluar }}
                            </p>
                        </div>

                        <button
                            @click="goToKeluar"
                            class="p-2 text-green-800 rounded-full bg-green-800/20 hover:bg-green-800/30 transition-all duration-300 backdrop-blur-sm transform hover:scale-110 active:scale-95"
                        >
                            <ArrowUpRight class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Tabel -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 transition-all duration-500"
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
                        class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl w-full max-w-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 placeholder-gray-400 transition-all shadow-sm hover:shadow-md focus:outline-none"
                    />
                </div>

                <button
                    @click="goToCreate"
                    class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105"
                >
                    <PlusCircle class="h-6.5 md:h-5" />
                    <span class="hidden md:block">Tambah Surat Masuk</span>
                </button>
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
                                    @click="sort('no_surat')"
                                >
                                    No. Surat
                                    <SortIcon
                                        field="no_surat"
                                        :filters="filters"
                                    />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('asal')"
                                >
                                    Asal
                                    <SortIcon field="asal" :filters="filters" />
                                </th>

                                <th
                                    class="px-4 py-3 cursor-pointer"
                                    @click="sort('perihal')"
                                >
                                    Perihal
                                    <SortIcon
                                        field="perihal"
                                        :filters="filters"
                                    />
                                </th>

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

                                <th class="px-4 py-3">File</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(item, index) in surat.data"
                                :key="item.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                            >
                                <td class="px-4 py-3">
                                    {{ surat.from + index }}
                                </td>

                                <td class="px-4 py-3">{{ item.no_surat }}</td>
                                <td class="px-4 py-3">{{ item.asal }}</td>
                                <td class="px-4 py-3">{{ item.perihal }}</td>
                                <td class="px-4 py-3">{{ item.tanggal }}</td>

                                <td class="px-4 py-3">
                                    <img
                                        v-if="item.foto"
                                        :src="`/storage/${item.foto}`"
                                        @click="
                                            openPreview(`/storage/${item.foto}`)
                                        "
                                        class="w-14 h-14 rounded-lg object-cover shadow cursor-pointer hover:scale-105 transition"
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
                                            <span>Aksi</span>
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
                                                                goToEdit(
                                                                    item.id,
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

            <!-- Cards Mobile -->
            <div class="lg:hidden space-y-4">
                <div
                    v-for="(item, index) in surat.data"
                    :key="item.id"
                    class="p-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-3 h-3 bg-green-500 rounded-full"
                            ></div>
                            <p
                                class="font-semibold text-gray-900 dark:text-gray-100 text-sm"
                            >
                                No: {{ surat.from + index }}
                            </p>
                        </div>

                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300"
                        >
                            Surat Masuk
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="mt-4 space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-300"
                                >Nomor Surat:</span
                            >
                            <p
                                class="font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ item.no_surat }}
                            </p>
                        </div>

                        <div>
                            <span class="text-gray-600 dark:text-gray-300"
                                >Asal:</span
                            >
                            <p
                                class="font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ item.asal }}
                            </p>
                        </div>

                        <div>
                            <span class="text-gray-600 dark:text-gray-300"
                                >Perihal:</span
                            >
                            <p
                                class="font-medium text-gray-900 dark:text-gray-100 line-clamp-2"
                            >
                                {{ item.perihal }}
                            </p>
                        </div>

                        <div>
                            <span class="text-gray-600 dark:text-gray-300"
                                >Tanggal:</span
                            >
                            <p
                                class="font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ item.tanggal }}
                            </p>
                        </div>
                    </div>

                    <!-- Lampiran -->
                    <div class="mt-4">
                        <p
                            class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-2"
                        >
                            Lampiran:
                        </p>

                        <div class="flex items-center gap-3">
                            <img
                                v-if="item.foto"
                                :src="`/storage/${item.foto}`"
                                @click="openPreview(`/storage/${item.foto}`)"
                                class="w-16 h-16 rounded-xl object-cover shadow-md cursor-pointer hover:scale-105 transition-transform duration-200 border border-gray-200 dark:border-gray-700"
                                alt="Lampiran surat"
                            />

                            <div
                                v-else
                                class="flex items-center gap-2 text-gray-400 dark:text-gray-500"
                            >
                                <div
                                    class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center border border-gray-200 dark:border-gray-600"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <span class="text-sm">Tidak ada foto</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            @click="goToEdit(item.id)"
                            class="px-4 py-2 rounded-xl text-sm font-medium bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/40 dark:text-green-300 dark:hover:bg-green-900/60 transition-all duration-200"
                        >
                            Edit
                        </button>

                        <button
                            @click="destroy(item.id)"
                            class="px-4 py-2 rounded-xl text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-300 dark:hover:bg-red-900/60 transition-all duration-200"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <!-- If empty -->
                <div v-if="!surat.data.length" class="text-center py-6">
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
                v-if="surat.data.length"
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
                        v-for="(link, index) in surat.links"
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
                            v-else-if="index === surat.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Create / Edit Surat Masuk -->
        <teleport to="body">
            <!-- BACKDROP -->
            <Transition
                enter-active-class="transition-all duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-all duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="openModal"
                    class="fixed inset-0 bg-black/40 backdrop-blur-sm z-51"
                    @click="closeModal"
                ></div>
            </Transition>

            <!-- MODAL BOX -->
            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-4"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-4"
            >
                <div
                    v-if="openModal"
                    class="fixed inset-0 flex items-center justify-center z-52 p-4"
                    @click="closeModal"
                >
                    <div
                        class="relative bg-white dark:bg-gray-900 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg mx-auto"
                        @click.stop
                    >
                        <!-- Header Modal -->
                        <div
                            class="rounded-tl-xl px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-100 to-transparent dark:from-green-950/30"
                        >
                            <div class="flex items-center justify-between">
                                <h2
                                    class="text-xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    {{
                                        isEdit
                                            ? "Edit Surat Masuk"
                                            : "Tambah Surat Masuk"
                                    }}
                                </h2>

                                <button
                                    @click="closeModal"
                                    class="p-2 text-gray-400 hover:text-green-600 hover:bg-white dark:hover:bg-gray-800 rounded-xl transition-all duration-200 transform hover:scale-110"
                                >
                                    <X class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <!-- FORM -->
                        <form @submit.prevent="submit" class="p-5 space-y-4">
                            <!-- INPUTS -->
                            <div class="space-y-3">
                                <div>
                                    <label
                                        class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                    >
                                        No. Surat
                                    </label>
                                    <input
                                        v-model="form.no_surat"
                                        type="text"
                                        placeholder="Masukkan nomor surat"
                                        required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                    />
                                </div>

                                <div>
                                    <label
                                        class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                    >
                                        Asal
                                    </label>
                                    <input
                                        v-model="form.asal"
                                        type="text"
                                        placeholder="Masukkan asal surat"
                                        required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                    />
                                </div>

                                <div>
                                    <label
                                        class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                    >
                                        Perihal
                                    </label>
                                    <input
                                        v-model="form.perihal"
                                        type="text"
                                        placeholder="Masukkan perihal surat"
                                        required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                    />
                                </div>

                                <div>
                                    <label
                                        class="font-semibold text-sm mb-1 block text-gray-900 dark:text-gray-100"
                                    >
                                        Tanggal
                                    </label>
                                    <input
                                        v-model="form.tanggal"
                                        type="date"
                                        placeholder="Masukkan tanggal diterima"
                                        required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 shadow-sm focus:outline-none"
                                    />
                                </div>
                            </div>

                            <!-- FOTO -->
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1"
                                >
                                    Foto Surat
                                </label>

                                <div
                                    class="flex items-center gap-4 p-2 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-600 transition-all duration-200"
                                >
                                    <Upload class="w-5 h-5 text-gray-400" />

                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="handleFileUpload"
                                        class="flex-1 text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-600 hover:file:bg-green-600 hover:file:text-white transition-all"
                                    />
                                </div>

                                <!-- PREVIEW -->
                                <div v-if="previewUrl" class="mt-3">
                                    <img
                                        :src="previewUrl"
                                        class="w-32 h-32 object-cover rounded-lg shadow-md"
                                    />
                                </div>
                            </div>

                            <!-- SUBMIT BUTTON -->
                            <button
                                type="submit"
                                class="w-full flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-103 font-semibold"
                            >
                                <Save class="w-5 h-5" />
                                <span>{{
                                    isEdit ? "Update Surat" : "Simpan Surat"
                                }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </Transition>
        </teleport>

        <!-- Modal Preview Foto -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[9999]"
            @click="showModal = false"
        >
            <div class="relative" @click.stop>
                <img
                    :src="selectedImage"
                    class="max-w-[90vw] max-h-[85vh] rounded-xl shadow-2xl"
                />

                <!-- Tombol Close -->
                <button
                    class="absolute -top-4 -right-4 bg-white text-black rounded-full w-10 h-10 flex items-center justify-center shadow-lg"
                    @click="showModal = false"
                >
                    ✕
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { router, Head } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount } from "vue";
import {
    ChevronLeft,
    ChevronRight,
    ChevronDown,
    ArrowUp,
    ArrowDown,
    PlusCircle,
    X,
    Save,
    Upload,
    Inbox,
    Send,
    ArrowUpRight,
} from "lucide-vue-next";

const props = defineProps({
    surat: Object,
    filters: Object,
    totalMasuk: Number,
    totalKeluar: Number,
});

/* -----------------------------
   FOTO PREVIEW MODAL
----------------------------- */
const showModal = ref(false);
const selectedImage = ref(null);

const openPreview = (img) => {
    selectedImage.value = img;
    showModal.value = true;
};

const closePreview = () => {
    showModal.value = false;
    selectedImage.value = null;
};

// FILTERS
const filters = ref({
    search: props.filters?.search || "",
    sort_by: props.filters?.sort_by || "",
    sort_direction: props.filters?.sort_direction || "",
    per_page: props.filters?.per_page || 10,
});

function applyFilters() {
    router.get(route("surat-masuk.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

// Sorting
function sort(column) {
    if (filters.value.sort_by === column) {
        filters.value.sort_direction =
            filters.value.sort_direction === "asc"
                ? "desc"
                : filters.value.sort_direction === "desc"
                  ? ""
                  : "asc";
    } else {
        filters.value.sort_by = column;
        filters.value.sort_direction = "asc";
    }

    applyFilters();
}

// Sort Icon Component
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
const dropdownRef = ref(null);

function toggleDropdown(item, event) {
    const rect = event.target.getBoundingClientRect();

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

function handleGlobalClick(e) {
    const dropdownEl = document.querySelector(".dropdown-menu-active");

    if (
        openDropdown.value &&
        dropdownEl &&
        !dropdownEl.contains(e.target) &&
        !e.target.closest(".aksi-button")
    ) {
        openDropdown.value = false;
        selectedItem.value = null;
    }
}

onMounted(() => document.addEventListener("click", handleGlobalClick));
onBeforeUnmount(() => document.removeEventListener("click", handleGlobalClick));

// PAGINATION
function goTo(url) {
    if (url) router.visit(url, { preserveScroll: true });
}

const goToMasuk = () => {
    router.visit(route("surat-masuk.index"));
};

const goToKeluar = () => {
    router.visit(route("surat-keluar.index"));
};

const destroy = (id) => {
    if (confirm("Yakin ingin menghapus data ini?")) {
        router.delete(route("surat-masuk.destroy", id));
    }
};

/* -----------------------------
   MODAL STATE
----------------------------- */
const openModal = ref(false);
const isEdit = ref(false);
const editId = ref(null);
const previewUrl = ref(null);

/* FORM STATE */
const form = ref({
    no_surat: "",
    asal: "",
    perihal: "",
    tanggal: "",
    foto: null,
});

/* -----------------------------
   OPEN CREATE MODAL
----------------------------- */
const openCreateModal = () => {
    isEdit.value = false;
    editId.value = null;
    previewUrl.value = null;

    form.value = {
        no_surat: "",
        asal: "",
        perihal: "",
        tanggal: "",
        foto: null,
    };

    openModal.value = true;
};

/* -----------------------------
   OPEN EDIT MODAL
----------------------------- */
const openEditModal = (item) => {
    isEdit.value = true;
    openModal.value = true;

    editId.value = item.id;

    form.value.no_surat = item.no_surat;
    form.value.asal = item.asal;
    form.value.perihal = item.perihal;
    form.value.tanggal = item.tanggal;

    previewUrl.value = item.foto ? `/storage/${item.foto}` : null;
};

/* -----------------------------
   CLOSE MODAL
----------------------------- */
const closeModal = () => {
    openModal.value = false;
};

/* -----------------------------
   FILE UPLOAD + PREVIEW
----------------------------- */
const handleFileUpload = (e) => {
    const file = e.target.files[0];

    if (!file) return;

    form.value.foto = file;
    previewUrl.value = URL.createObjectURL(file);
};

/* -----------------------------
   SUBMIT FORM
----------------------------- */
const submit = () => {
    const data = new FormData();
    data.append("no_surat", form.value.no_surat);
    data.append("asal", form.value.asal);
    data.append("perihal", form.value.perihal);
    data.append("tanggal", form.value.tanggal);

    if (form.value.foto instanceof File) {
        data.append("foto", form.value.foto);
    }

    if (isEdit.value) {
        data.append("_method", "PUT");

        router.post(route("surat-masuk.update", editId.value), data, {
            forceFormData: true,
            onSuccess: closeModal,
        });
    } else {
        router.post(route("surat-masuk.store"), data, {
            forceFormData: true,
            onSuccess: closeModal,
        });
    }
};

/* Override tombol lama */
const goToCreate = openCreateModal;
const goToEdit = (id) => {
    const item = props.surat.data.find((s) => s.id === id);
    openEditModal(item);
};
</script>
