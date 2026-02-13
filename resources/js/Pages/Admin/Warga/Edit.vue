<template>
    <AppLayout>
        <Head title="Edit Data Warga" />

        <div
            class="p-6 lg:p-8 bg-white dark:bg-gray-900 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 transition-all duration-500 backdrop-blur-sm bg-opacity-95"
        >
            <form @submit.prevent="submit" class="space-y-8">
                <!-- Foto Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                        >
                            <Camera class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Foto Profil
                        </h3>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center gap-6"
                    >
                        <div class="relative">
                            <div
                                class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden group hover:border-green-600 transition-all duration-300"
                            >
                                <img
                                    v-if="preview || form.foto_old"
                                    :src="
                                        preview || `/storage/${form.foto_old}`
                                    "
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                                <Camera v-else class="w-8 h-8 text-gray-400" />
                            </div>
                            <div
                                v-if="preview || form.foto_old"
                                class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white dark:border-gray-900 flex items-center justify-center"
                            >
                                <Check class="w-3 h-3 text-white" />
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <label
                                class="block text-sm font-medium mb-3 text-gray-900 dark:text-gray-100"
                            >
                                Ganti Foto
                            </label>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <label
                                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white rounded-xl cursor-pointer shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 active:scale-95 font-medium text-sm"
                                >
                                    <Upload class="w-4 h-4" />
                                    <span>Pilih File Baru</span>
                                    <input
                                        type="file"
                                        @change="handleFileChange"
                                        class="hidden"
                                        accept="image/*"
                                    />
                                </label>
                                <button
                                    v-if="preview"
                                    type="button"
                                    @click="
                                        preview = null;
                                        form.foto = null;
                                    "
                                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95 font-medium text-sm"
                                >
                                    <Trash2 class="w-4 h-4" />
                                    <span>Hapus</span>
                                </button>
                            </div>
                            <p
                                class="text-xs text-gray-500 dark:text-gray-400 mt-2"
                            >
                                Biarkan kosong jika tidak ingin mengubah foto
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Identitas Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                        >
                            <User class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Identitas Diri
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nama"
                                type="text"
                                required
                                placeholder="Masukkan nama lengkap"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                NIS <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nis"
                                type="text"
                                required
                                placeholder="Nomor Induk Santri"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Asrama
                            </label>
                            <input
                                v-model="form.asrama"
                                type="text"
                                placeholder="Nama asrama"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300 appearance-none cursor-pointer"
                            >
                                <option value="Santri">Santri</option>
                                <option value="Alumni">Alumni</option>
                                <option value="Tidak Jelas">Tidak Jelas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Media Sosial Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                        >
                            <Globe class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Media Sosial
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Email
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="contoh@email.com"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Instagram
                            </label>
                            <input
                                v-model="form.ig"
                                type="text"
                                placeholder="@username"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                WhatsApp
                            </label>
                            <input
                                v-model="form.wa"
                                type="text"
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>
                    </div>
                </div>

                <!-- Pendidikan Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                        >
                            <GraduationCap class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Pendidikan
                        </h3>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"
                    >
                        <div
                            v-for="(pend, key) in pendidikanList"
                            :key="key"
                            class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300"
                        >
                            <h4
                                class="font-semibold text-gray-900 dark:text-gray-100 mb-3 text-sm uppercase tracking-wide"
                            >
                                {{ pend.label }}
                            </h4>

                            <div class="space-y-3">
                                <select
                                    v-model="form[pend.field]"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none text-sm transition-all duration-200"
                                >
                                    <option value="">- Pilih Jenjang -</option>
                                    <option
                                        v-for="opt in pend.options"
                                        :key="opt"
                                        :value="opt"
                                    >
                                        {{ opt }}
                                    </option>
                                </select>

                                <input
                                    v-model="form[pend.no]"
                                    type="text"
                                    placeholder="No Induk"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none text-sm transition-all duration-200"
                                />

                                <input
                                    v-model="form[pend.kelas]"
                                    type="text"
                                    placeholder="Kelas"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none text-sm transition-all duration-200"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wali Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                        >
                            <Users class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Data Wali
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Nama Wali
                            </label>
                            <input
                                v-model="form.wali"
                                type="text"
                                placeholder="Nama lengkap wali"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                No. Telepon Wali
                            </label>
                            <input
                                v-model="form.no_telp_wali"
                                type="text"
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>
                    </div>
                </div>

                <!-- Alamat Section -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                        >
                            <MapPin class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Alamat
                        </h3>
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6"
                    >
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-gray-100"
                                >Kampung</label
                            >
                            <input
                                v-model="form.kampung"
                                placeholder="Nama kampung"
                                class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none transition-all duration-200"
                            />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-gray-100"
                                >RT</label
                            >
                            <input
                                v-model="form.rt"
                                placeholder="RT"
                                class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none transition-all duration-200"
                            />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-gray-100"
                                >RW</label
                            >
                            <input
                                v-model="form.rw"
                                placeholder="RW"
                                class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none transition-all duration-200"
                            />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-gray-100"
                                >Desa</label
                            >
                            <input
                                v-model="form.desa"
                                placeholder="Nama desa"
                                class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none transition-all duration-200"
                            />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-gray-100"
                                >Kecamatan</label
                            >
                            <input
                                v-model="form.kecamatan"
                                placeholder="Nama kecamatan"
                                class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none transition-all duration-200"
                            />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-gray-100"
                                >Kabupaten</label
                            >
                            <input
                                v-model="form.kabupaten"
                                placeholder="Nama kabupaten"
                                class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none transition-all duration-200"
                            />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-gray-100"
                                >Provinsi</label
                            >
                            <input
                                v-model="form.provinsi"
                                placeholder="Nama provinsi"
                                class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none transition-all duration-200"
                            />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-gray-100"
                                >Kode Pos</label
                            >
                            <input
                                v-model="form.kode_pos"
                                placeholder="Kode pos"
                                class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none transition-all duration-200"
                            />
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="mt-6">
                        <label
                            class="block text-sm font-semibold mb-3 text-gray-900 dark:text-gray-100"
                        >
                            Tandai Lokasi di Peta
                        </label>
                        <div
                            class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm"
                        >
                            <div id="map" class="h-72 w-full"></div>
                            <div
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700"
                            >
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <MapPin class="w-4 h-4 text-green-600" />
                                    <span>Koordinat: </span>
                                    <span class="font-mono"
                                        >{{ form.latitude || "-" }},
                                        {{ form.longitude || "-" }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-6 h-6 bg-green-600 rounded-lg flex items-center justify-center"
                        >
                            <Info class="w-3 h-3 text-white" />
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Informasi Tambahan
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Tahun Mondok
                            </label>
                            <input
                                v-model="form.tahun_mondok"
                                type="text"
                                placeholder="2020"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Tahun Lulus
                            </label>
                            <input
                                v-model="form.tahun_lulus"
                                type="text"
                                placeholder="2020"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-600 focus:border-transparent focus:outline-none shadow-sm hover:shadow-md transition-all duration-300"
                            />
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-gray-200 dark:border-gray-700"
                >
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="text-red-500">*</span> Menandakan field
                        wajib diisi
                    </div>

                    <div
                        class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto"
                    >
                        <Link
                            :href="route('warga.index')"
                            class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-900 dark:text-gray-100 transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold order-2 sm:order-1"
                        >
                            <ArrowLeft class="w-4 h-4" />
                            Kembali
                        </Link>

                        <button
                            type="submit"
                            class="flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-600 text-white shadow-lg hover:shadow-xl hover:shadow-green-600/30 transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold order-1 sm:order-2"
                            :disabled="form.processing"
                            :class="
                                form.processing
                                    ? 'opacity-70 cursor-not-allowed'
                                    : ''
                            "
                        >
                            <Save class="w-4 h-4" />
                            <span v-if="form.processing">Memperbarui...</span>
                            <span v-else>Perbarui Data</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style scoped>
#map {
    min-height: 16rem;
}

/* Custom select arrow */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

select:focus {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23009a44' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
}

.dark select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239bb9a5' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
}

.dark select:focus {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2300b855' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
}
</style>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { ref, onMounted } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";
import {
    ArrowLeft,
    Camera,
    Check,
    GraduationCap,
    Info,
    MapPin,
    Save,
    Trash2,
    Upload,
    User,
    Users,
    Globe,
} from "lucide-vue-next";

const props = defineProps({ warga: Object });

const preview = ref(null);
const map = ref(null);
const marker = ref(null);

const pendidikanList = [
    {
        label: "Pendidikan Pagi",
        field: "pendidikan_pagi",
        no: "no_induk_pendidikan_pagi",
        kelas: "kelas_pendidikan_pagi",
        options: ["MI", "MTs", "MA", "MTI", "MDI", "Maly", "Lulus"],
    },
    {
        label: "Pendidikan Sore",
        field: "pendidikan_sore",
        no: "no_induk_pendidikan_sore",
        kelas: "kelas_pendidikan_sore",
        options: ["SD", "SMP", "SMA", "SMK", "PT", "Lulus"],
    },
    {
        label: "Pendidikan Malam",
        field: "pendidikan_malam",
        no: "no_induk_pendidikan_malam",
        kelas: "kelas_pendidikan_malam",
        options: ["Qiraati", "Amtsilati", "Pengajian"],
    },
];

const form = useForm({
    foto: null,
    foto_old: props.warga.foto || null,
    nama: props.warga.nama || "",
    nis: props.warga.nis || "",
    asrama: props.warga.asrama || "",
    status: props.warga.status || "Santri",
    pendidikan_pagi: props.warga.pendidikan_pagi || "",
    no_induk_pendidikan_pagi: props.warga.no_induk_pendidikan_pagi || "",
    kelas_pendidikan_pagi: props.warga.kelas_pendidikan_pagi || "",
    pendidikan_sore: props.warga.pendidikan_sore || "",
    no_induk_pendidikan_sore: props.warga.no_induk_pendidikan_sore || "",
    kelas_pendidikan_sore: props.warga.kelas_pendidikan_sore || "",
    pendidikan_malam: props.warga.pendidikan_malam || "",
    no_induk_pendidikan_malam: props.warga.no_induk_pendidikan_malam || "",
    kelas_pendidikan_malam: props.warga.kelas_pendidikan_malam || "",
    wali: props.warga.wali || "",
    email: props.warga.email || "",
    ig: props.warga.ig || "",
    wa: props.warga.wa || "",
    no_telp_wali: props.warga.no_telp_wali || "",
    kampung: props.warga.kampung || "",
    rt: props.warga.rt || "",
    rw: props.warga.rw || "",
    desa: props.warga.desa || "",
    kecamatan: props.warga.kecamatan || "",
    kabupaten: props.warga.kabupaten || "",
    provinsi: props.warga.provinsi || "",
    kode_pos: props.warga.kode_pos || "",
    latitude: props.warga.latitude || "",
    longitude: props.warga.longitude || "",
    jabatan: props.warga.jabatan || "",
    tahun_mondok: props.warga.tahun_mondok || "",
});

function handleFileChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.foto = file;
        preview.value = URL.createObjectURL(file);
    }
}

function submit() {
    const payload = { ...form.data() };

    if (form.foto === null) {
        delete payload.foto;
    }

    delete payload.foto_old;
    payload._method = "PUT";

    form.transform(() => payload).post(route("warga.update", props.warga.id), {
        forceFormData: true,
        onSuccess: () => {
            preview.value = null;
        },
        onError: (err) => {
            console.error("Gagal memperbarui:", err);
        },
    });
}

onMounted(() => {
    const lat = parseFloat(form.latitude) || -6.3667;
    const lng = parseFloat(form.longitude) || 106.2491;

    map.value = L.map("map").setView([lat, lng], 10);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap contributors",
    }).addTo(map.value);

    marker.value = L.marker([lat, lng], { draggable: true }).addTo(map.value);

    marker.value.on("dragend", (e) => {
        const pos = e.target.getLatLng();
        form.latitude = pos.lat.toFixed(7);
        form.longitude = pos.lng.toFixed(7);
    });
});
</script>
