<template>
    <AppLayout>
        <Head title="Dashboard Program Kerja" />

        <!-- Header Dashboard -->
        <div class="px-8 pt-8 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Dashboard Program Kerja
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Monitor dan kelola seluruh program kerja perusahaan
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <button
                        class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center"
                    >
                        <Plus class="w-5 h-5 mr-2" />
                        Program Baru
                    </button>
                    <button
                        class="p-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        <Settings class="w-5 h-5 text-gray-600" />
                    </button>
                </div>
            </div>

            <!-- Filter & Search -->
            <div
                class="mt-8 flex flex-col lg:flex-row lg:items-center justify-between gap-4"
            >
                <div class="flex flex-wrap items-center gap-3">
                    <button
                        class="px-4 py-2 bg-indigo-50 text-indigo-700 font-medium rounded-lg hover:bg-indigo-100 transition-colors"
                    >
                        Semua Program
                    </button>
                    <button
                        class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        Berjalan
                    </button>
                    <button
                        class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        Perencanaan
                    </button>
                    <button
                        class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        Selesai
                    </button>
                    <button
                        class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        Tertunda
                    </button>
                </div>

                <div class="relative max-w-md w-full">
                    <Search
                        class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                    />
                    <input
                        type="text"
                        placeholder="Cari program kerja..."
                        class="pl-11 pr-4 py-2.5 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                    />
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="px-8 pb-8">
            <!-- Statistik Ringkasan -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
            >
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-6"
                >
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-600">
                                Total Program
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                24
                            </p>
                            <p
                                class="text-sm text-green-600 mt-1 flex items-center"
                            >
                                <TrendingUp class="w-4 h-4 mr-1" />
                                +12% dari bulan lalu
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <FolderKanban class="w-6 h-6 text-blue-600" />
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 rounded-xl p-6"
                >
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-600">
                                Dalam Proses
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                14
                            </p>
                            <p class="text-sm text-blue-600 mt-1">
                                72% progres rata-rata
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <RefreshCw class="w-6 h-6 text-emerald-600" />
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 rounded-xl p-6"
                >
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-600">
                                Tenggat Dekat
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                5
                            </p>
                            <p class="text-sm text-red-600 mt-1">
                                3 program terlambat
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <AlertTriangle class="w-6 h-6 text-amber-600" />
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-purple-50 to-violet-50 border border-purple-100 rounded-xl p-6"
                >
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-600">
                                Anggaran Digunakan
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                82%
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Rp 3.2M dari Rp 3.9M
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <PieChart class="w-6 h-6 text-purple-600" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart & Daftar Program -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Grafik Progress -->
                <div
                    class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6"
                >
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">
                            Progress Program Kerja
                        </h2>
                        <div class="flex items-center space-x-2">
                            <select
                                class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option>Bulan Ini</option>
                                <option>3 Bulan Terakhir</option>
                                <option>Tahun Ini</option>
                            </select>
                        </div>
                    </div>

                    <!-- Chart Area (Placeholder) -->
                    <div
                        class="h-72 w-full bg-gradient-to-b from-gray-50 to-white rounded-lg border border-gray-200 flex items-center justify-center"
                    >
                        <div class="text-center">
                            <BarChart3
                                class="w-12 h-12 text-gray-300 mx-auto mb-3"
                            />
                            <p class="text-gray-500">
                                Visualisasi grafik progress program kerja
                            </p>
                            <p class="text-sm text-gray-400">
                                (Akan ditampilkan dengan library chart seperti
                                Chart.js)
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-700">42%</p>
                            <p class="text-sm text-gray-600 mt-1">On Track</p>
                        </div>
                        <div class="text-center p-4 bg-amber-50 rounded-lg">
                            <p class="text-2xl font-bold text-amber-700">33%</p>
                            <p class="text-sm text-gray-600 mt-1">
                                Perlu Perhatian
                            </p>
                        </div>
                        <div class="text-center p-4 bg-emerald-50 rounded-lg">
                            <p class="text-2xl font-bold text-emerald-700">
                                25%
                            </p>
                            <p class="text-sm text-gray-600 mt-1">Selesai</p>
                        </div>
                    </div>
                </div>

                <!-- Program Prioritas -->
                <div
                    class="bg-white rounded-xl border border-gray-200 shadow-sm p-6"
                >
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">
                            Program Prioritas
                        </h2>
                        <ChevronRight class="w-5 h-5 text-gray-400" />
                    </div>

                    <div class="space-y-5">
                        <div class="border-l-4 border-blue-500 pl-4 py-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        Digitalisasi Proses HR
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Departemen SDM
                                    </p>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"
                                    >85%</span
                                >
                            </div>
                            <div class="mt-3">
                                <div
                                    class="flex justify-between text-xs text-gray-500 mb-1"
                                >
                                    <span>Progress</span>
                                    <span>Deadline: 15 Des 2023</span>
                                </div>
                                <div
                                    class="h-2 bg-gray-200 rounded-full overflow-hidden"
                                >
                                    <div
                                        class="h-full bg-blue-500 rounded-full"
                                        style="width: 85%"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <div class="border-l-4 border-emerald-500 pl-4 py-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        Ekspansi Pasar Regional
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Departemen Pemasaran
                                    </p>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full"
                                    >62%</span
                                >
                            </div>
                            <div class="mt-3">
                                <div
                                    class="flex justify-between text-xs text-gray-500 mb-1"
                                >
                                    <span>Progress</span>
                                    <span>Deadline: 30 Jan 2024</span>
                                </div>
                                <div
                                    class="h-2 bg-gray-200 rounded-full overflow-hidden"
                                >
                                    <div
                                        class="h-full bg-emerald-500 rounded-full"
                                        style="width: 62%"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <div class="border-l-4 border-amber-500 pl-4 py-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        Migrasi Server Cloud
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Departemen IT
                                    </p>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-medium rounded-full"
                                    >45%</span
                                >
                            </div>
                            <div class="mt-3">
                                <div
                                    class="flex justify-between text-xs text-gray-500 mb-1"
                                >
                                    <span>Progress</span>
                                    <span class="text-red-600"
                                        >Deadline: 5 Des 2023</span
                                    >
                                </div>
                                <div
                                    class="h-2 bg-gray-200 rounded-full overflow-hidden"
                                >
                                    <div
                                        class="h-full bg-amber-500 rounded-full"
                                        style="width: 45%"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <div class="border-l-4 border-purple-500 pl-4 py-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        Pelatihan Leadership
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Departemen Pengembangan
                                    </p>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full"
                                    >28%</span
                                >
                            </div>
                            <div class="mt-3">
                                <div
                                    class="flex justify-between text-xs text-gray-500 mb-1"
                                >
                                    <span>Progress</span>
                                    <span>Deadline: 28 Feb 2024</span>
                                </div>
                                <div
                                    class="h-2 bg-gray-200 rounded-full overflow-hidden"
                                >
                                    <div
                                        class="h-full bg-purple-500 rounded-full"
                                        style="width: 28%"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        class="w-full mt-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Lihat Semua Program
                    </button>
                </div>
            </div>

            <!-- Tabel Program Kerja -->
            <div
                class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
            >
                <div
                    class="px-6 py-5 border-b border-gray-200 flex justify-between items-center"
                >
                    <h2 class="text-xl font-bold text-gray-900">
                        Daftar Program Kerja
                    </h2>
                    <div class="flex items-center space-x-3">
                        <button
                            class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors flex items-center"
                        >
                            <Download class="w-4 h-4 mr-2" />
                            Export
                        </button>
                        <button
                            class="px-4 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors flex items-center"
                        >
                            <Filter class="w-4 h-4 mr-2" />
                            Filter Lanjutan
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900"
                                >
                                    Nama Program
                                </th>
                                <th
                                    class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900"
                                >
                                    Departemen
                                </th>
                                <th
                                    class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900"
                                >
                                    Timeline
                                </th>
                                <th
                                    class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900"
                                >
                                    Progress
                                </th>
                                <th
                                    class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900"
                                >
                                    Status
                                </th>
                                <th
                                    class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900"
                                >
                                    Anggaran
                                </th>
                                <th
                                    class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            Revitalisasi Brand Perusahaan
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            #PRG-2023-001
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-medium rounded-full"
                                        >Marketing</span
                                    >
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="text-sm text-gray-900">
                                            1 Okt - 15 Des 2023
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            67 hari tersisa
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden mr-3"
                                        >
                                            <div
                                                class="h-full bg-blue-500 rounded-full"
                                                style="width: 78%"
                                            ></div>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900"
                                            >78%</span
                                        >
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-3 py-1.5 bg-emerald-100 text-emerald-800 text-sm font-medium rounded-full"
                                        >On Track</span
                                    >
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-medium text-gray-900">
                                        Rp 850 JT
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        72% digunakan
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </button>
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </button>
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            Implementasi ERP System
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            #PRG-2023-002
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-3 py-1.5 bg-purple-100 text-purple-800 text-sm font-medium rounded-full"
                                        >IT</span
                                    >
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="text-sm text-gray-900">
                                            15 Sep - 30 Jan 2024
                                        </p>
                                        <p class="text-xs text-red-600 mt-1">
                                            10 hari terlambat
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden mr-3"
                                        >
                                            <div
                                                class="h-full bg-amber-500 rounded-full"
                                                style="width: 65%"
                                            ></div>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900"
                                            >65%</span
                                        >
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-3 py-1.5 bg-amber-100 text-amber-800 text-sm font-medium rounded-full"
                                        >Tertunda</span
                                    >
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-medium text-gray-900">
                                        Rp 1.2 M
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        85% digunakan
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </button>
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </button>
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            Pengembangan Produk Baru
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            #PRG-2023-003
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-3 py-1.5 bg-emerald-100 text-emerald-800 text-sm font-medium rounded-full"
                                        >R&D</span
                                    >
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="text-sm text-gray-900">
                                            1 Nov - 1 Mar 2024
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            112 hari tersisa
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden mr-3"
                                        >
                                            <div
                                                class="h-full bg-blue-500 rounded-full"
                                                style="width: 32%"
                                            ></div>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900"
                                            >32%</span
                                        >
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-medium rounded-full"
                                        >Berjalan</span
                                    >
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-medium text-gray-900">
                                        Rp 950 JT
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        41% digunakan
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </button>
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </button>
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            Program CSR Lingkungan
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            #PRG-2023-004
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-3 py-1.5 bg-amber-100 text-amber-800 text-sm font-medium rounded-full"
                                        >CSR</span
                                    >
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="text-sm text-gray-900">
                                            1 Jul - 30 Nov 2023
                                        </p>
                                        <p
                                            class="text-xs text-emerald-600 mt-1"
                                        >
                                            Selesai
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden mr-3"
                                        >
                                            <div
                                                class="h-full bg-emerald-500 rounded-full"
                                                style="width: 100%"
                                            ></div>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900"
                                            >100%</span
                                        >
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-3 py-1.5 bg-gray-100 text-gray-800 text-sm font-medium rounded-full"
                                        >Selesai</span
                                    >
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-medium text-gray-900">
                                        Rp 500 JT
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        98% digunakan
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </button>
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </button>
                                        <button
                                            class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-6 py-4 border-t border-gray-200 flex justify-between items-center"
                >
                    <p class="text-sm text-gray-600">
                        Menampilkan 4 dari 24 program kerja
                    </p>
                    <div class="flex items-center space-x-2">
                        <button
                            class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <ChevronLeft class="w-4 h-4 text-gray-600" />
                        </button>
                        <button
                            class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                        >
                            1
                        </button>
                        <button
                            class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            2
                        </button>
                        <button
                            class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            3
                        </button>
                        <button
                            class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            4
                        </button>
                        <button
                            class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <ChevronRight class="w-4 h-4 text-gray-600" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    Plus,
    Settings,
    Search,
    TrendingUp,
    FolderKanban,
    RefreshCw,
    AlertTriangle,
    PieChart,
    BarChart3,
    ChevronRight,
    Download,
    Filter,
    Eye,
    Edit,
    Trash2,
    ChevronLeft,
    ChevronRight as ChevronRightIcon,
} from "lucide-vue-next";
</script>

<style scoped>
/* Custom scrollbar untuk tabel */
table {
    min-width: 1000px;
}

::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
