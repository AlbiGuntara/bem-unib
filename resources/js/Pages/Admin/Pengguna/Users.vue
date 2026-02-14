<template>
    <AppLayout>
        <Head title="Manajemen User" />

        <div
            class="p-6 bg-white dark:bg-gray-900 backdrop-blur-sm rounded-2xl shadow-sm border border-electric-blue/20 transition-all duration-300"
        >
            <!-- Header -->
            <div class="flex gap-4 justify-between items-center mb-6">
                <div class="relative w-full md:w-80">
                    <Search
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                    />
                    <input
                        type="text"
                        v-model="search"
                        @input="updateSearch"
                        placeholder="Cari user..."
                        class="pl-10 pr-4 py-2 w-full rounded-xl max-w-xs md:max-w-sm bg-cream dark:bg-[#1F1F1F] border border-electric-blue/30 text-deep-blue dark:text-cream placeholder-deep-blue/40 dark:placeholder-cream/40 focus:ring-2 focus:ring-electric-blue focus:outline-none transition-all duration-300 shadow-sm hover:shadow-md"
                    />
                </div>

                <button
                    @click="openModal = true"
                    class="group relative flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-deep-blue to-electric-blue hover:from-deep-blue/90 hover:to-electric-blue/90 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                    ></div>
                    <UserPlus class="h-5 w-5 transition-transform" />
                    <span class="hidden md:block">Tambah User</span>
                </button>
            </div>

            <!-- Table Container -->
            <div
                class="bg-white dark:bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl border border-gray-200 dark:border-electric-blue/30 shadow-sm overflow-hidden"
            >
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-deep-blue to-electric-blue text-cream uppercase text-xs tracking-wide"
                            >
                                <th class="px-4 py-3">No</th>
                                <th
                                    v-for="col in columns"
                                    :key="col.key"
                                    class="px-4 py-3 cursor-pointer select-none"
                                    @click="sortBy(col.key)"
                                >
                                    <div class="flex items-center gap-1">
                                        <span>{{ col.label }}</span>
                                        <ArrowUp
                                            v-if="
                                                propsFilters.sort === col.key &&
                                                propsFilters.order === 'asc'
                                            "
                                            class="w-3 h-3 text-cream/70"
                                        />
                                        <ArrowDown
                                            v-if="
                                                propsFilters.sort === col.key &&
                                                propsFilters.order === 'desc'
                                            "
                                            class="w-3 h-3 text-cream/70"
                                        />
                                        <ChevronsUpDown
                                            v-if="propsFilters.sort !== col.key"
                                            class="w-3 h-3 opacity-40"
                                        />
                                    </div>
                                </th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(user, index) in users.data"
                                :key="user.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-electric-blue/10 dark:hover:bg-electric-blue/20 transition-all"
                            >
                                <td
                                    class="px-4 py-3 text-gray-900 dark:text-gray-100"
                                >
                                    {{ (users.from || 0) + index }}
                                </td>
                                <td class="px-4 py-3 flex items-center gap-3">
                                    <img
                                        :src="
                                            user.avatar
                                                ? `/storage/${user.avatar}`
                                                : '/images/default-avatar.jpg'
                                        "
                                        alt="avatar"
                                        class="w-9 h-9 rounded-full border border-gray-200 dark:border-gray-700 object-cover"
                                    />
                                    <span
                                        class="text-gray-900 dark:text-gray-100 font-medium"
                                    >
                                        {{ user.name }}
                                    </span>
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-900 dark:text-gray-100"
                                >
                                    {{ user.email }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-900 dark:text-gray-100"
                                >
                                    {{ user.username }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="relative">
                                        <select
                                            v-model="selectedRoles[user.id]"
                                            @change="updateRole(user)"
                                            class="w-full appearance-none rounded-lg border border-electric-blue/30 bg-cream dark:bg-[#1F1F1F] pl-2 pr-8 py-2 text-sm text-deep-blue dark:text-cream shadow-sm transition hover:border-electric-blue focus:outline-none focus:ring-1 focus:ring-electric-blue disabled:bg-electric-blue/10 disabled:text-deep-blue/40 dark:disabled:text-cream/40 disabled:cursor-not-allowed"
                                            :disabled="
                                                user.roles.some(
                                                    (r) =>
                                                        r.name ===
                                                        'super-admin',
                                                )
                                            "
                                        >
                                            <option
                                                v-for="role in roles"
                                                :key="role.id"
                                                :value="role.name"
                                                class="dark:bg-gray-800"
                                            >
                                                {{ role.name }}
                                            </option>
                                        </select>

                                        <!-- arrow -->
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                                        >
                                            <ChevronDown
                                                class="h-4 w-4 text-gray-400 dark:text-white"
                                            />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div
                                        class="relative inline-block text-left"
                                    >
                                        <button
                                            @click="
                                                toggleDropdown(user, $event)
                                            "
                                            class="flex items-center gap-2 px-3 py-1 bg-electric-blue/10 dark:bg-electric-blue/20 hover:bg-electric-blue/20 dark:hover:bg-electric-blue/30 text-gray-900 dark:text-gray-100 rounded-xl border border-electric-blue/20 shadow-sm transition-all duration-300 transform group/btn"
                                        >
                                            <span class="font-medium"
                                                >Actions</span
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
                                                    v-if="openDropdown"
                                                    class="absolute z-50 w-36 bg-white dark:bg-[#1F1F1F] border border-electric-blue/20 rounded-xl shadow-lg overflow-hidden"
                                                    :style="{
                                                        position: 'absolute',
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
                                                                viewUser(
                                                                    selectedUser,
                                                                )
                                                            "
                                                            class="px-4 py-2 hover:bg-electric-blue/20 dark:hover:bg-electric-blue/30 cursor-pointer transition-all"
                                                        >
                                                            View
                                                        </li>
                                                        <li
                                                            @click="
                                                                editUser(
                                                                    selectedUser,
                                                                )
                                                            "
                                                            class="px-4 py-2 hover:bg-electric-blue/20 dark:hover:bg-electric-blue/30 cursor-pointer transition-all"
                                                        >
                                                            Edit
                                                        </li>
                                                        <li
                                                            @click="
                                                                deleteUser(
                                                                    selectedUser.id,
                                                                )
                                                            "
                                                            class="px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 cursor-pointer transition-all"
                                                        >
                                                            Delete
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

                <!-- Mobile Cards -->
                <div
                    class="md:hidden space-y-4 p-4 bg-cream dark:bg-[#1f1f1f] rounded-b-lg"
                >
                    <div
                        v-for="(user, index) in users.data"
                        :key="user.id"
                        class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-electric-blue/30 p-4 shadow-sm"
                    >
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <img
                                    :src="
                                        user.avatar
                                            ? `/storage/${user.avatar}`
                                            : '/images/default-avatar.jpg'
                                    "
                                    alt="avatar"
                                    class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 object-cover"
                                />
                                <div>
                                    <h3
                                        class="font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        {{ user.name }}
                                    </h3>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        #{{ (users.from || 0) + index }}
                                    </p>
                                </div>
                            </div>
                            <div class="relative inline-block text-left">
                                <button
                                    @click="toggleDropdown(user, $event)"
                                    class="flex items-center gap-1 px-2 py-1 bg-electric-blue/10 dark:bg-electric-blue/20 hover:bg-electric-blue/20 dark:hover:bg-electric-blue/30 text-gray-900 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm transition-all duration-300 transform group/btn"
                                >
                                    <span class="text-xs">Actions</span>
                                    <ChevronDown
                                        class="w-3 h-3 transition-transform duration-300 group-hover/btn:rotate-180"
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
                                            v-if="openDropdown"
                                            class="absolute z-50 w-36 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden"
                                            :style="{
                                                position: 'absolute',
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
                                                    @click="
                                                        viewUser(selectedUser)
                                                    "
                                                    class="px-4 py-2 hover:bg-electric-blue/20 dark:hover:bg-electric-blue/30 cursor-pointer transition-all"
                                                >
                                                    View
                                                </li>
                                                <li
                                                    @click="
                                                        editUser(selectedUser)
                                                    "
                                                    class="px-4 py-2 hover:bg-electric-blue/20 dark:hover:bg-electric-blue/30 cursor-pointer transition-all"
                                                >
                                                    Edit
                                                </li>
                                                <li
                                                    @click="
                                                        deleteUser(
                                                            selectedUser.id,
                                                        )
                                                    "
                                                    class="px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 cursor-pointer transition-all"
                                                >
                                                    Delete
                                                </li>
                                            </ul>
                                        </div>
                                    </Transition>
                                </teleport>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400"
                                    >Email:</span
                                >
                                <span
                                    class="text-gray-900 dark:text-gray-100 font-medium truncate max-w-[180px]"
                                >
                                    {{ user.email }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400"
                                    >Username:</span
                                >
                                <span
                                    class="text-gray-900 dark:text-gray-100 font-medium"
                                >
                                    {{ user.username }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400"
                                    >Google:</span
                                >
                                <span
                                    :class="[
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                        user.google_id
                                            ? 'bg-electric-blue/10 text-electric-blue dark:bg-electric-blue/20 dark:text-electric-blue'
                                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                    ]"
                                >
                                    {{
                                        user.google_id
                                            ? "Connected"
                                            : "Not Connected"
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!users.data.length" class="text-center py-12">
                <div
                    class="w-24 h-24 mx-auto mb-4 bg-electric-blue/10 dark:bg-electric-blue/20 rounded-full flex items-center justify-center"
                >
                    <Users class="w-10 h-10 text-gray-400" />
                </div>
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2"
                >
                    Tidak ada user ditemukan
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Coba ubah pencarian atau tambah user baru
                </p>
                <button
                    @click="openModal = true"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-electric-blue hover:bg-coral text-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300"
                >
                    <UserPlus class="w-5 h-5" />
                    Tambah User Pertama
                </button>
            </div>

            <!-- Pagination -->
            <div
                v-if="users.data.length"
                class="flex justify-between items-center mt-6"
            >
                <div class="flex items-center gap-4">
                    <span
                        class="hidden md:block text-sm text-gray-900 dark:text-gray-100"
                    >
                        Show
                    </span>
                    <select
                        v-model="perPage"
                        @change="updatePerPage"
                        class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-electric-blue focus:outline-none bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 cursor-pointer"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in users.links"
                        :key="index"
                        @click="goTo(link.url)"
                        :disabled="!link.url"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200 font-medium"
                        :class="[
                            link.active
                                ? 'bg-electric-blue text-white border-deep-blue shadow-lg scale-105'
                                : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 hover:bg-electric-blue/10 dark:hover:bg-electric-blue/20 hover:shadow-md',
                            !link.url ? 'opacity-40 cursor-not-allowed' : '',
                        ]"
                    >
                        <ChevronLeft v-if="index === 0" class="w-4 h-4" />
                        <ChevronRight
                            v-else-if="index === users.links.length - 1"
                            class="w-4 h-4"
                        />
                        <span v-else v-html="link.label"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Tambah User -->
        <teleport to="body">
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
                    class="fixed inset-0 bg-black/50 backdrop-blur-md z-50"
                    @click="openModal = false"
                ></div>
            </Transition>

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
                    class="fixed inset-0 flex items-center justify-center z-50 p-4"
                    @click="openModal = false"
                >
                    <div
                        class="relative bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl shadow-2xl shadow-black/10 border border-white/20 dark:border-gray-700/50 w-full max-w-md mx-auto"
                        @click.stop
                    >
                        <!-- Header Modal -->
                        <div
                            class="rounded-tl-xl px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-electric-blue/15 via-electric-blue/5 to-transparent"
                        >
                            <div class="flex items-center justify-between">
                                <h2
                                    class="text-xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    Tambah User
                                </h2>
                                <button
                                    @click="openModal = false"
                                    class="p-2 text-gray-400 hover:text-electric-blue hover:bg-white dark:hover:bg-gray-800 rounded-xl transition-all duration-200 transform hover:scale-110"
                                >
                                    <X class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="submit" class="p-5 space-y-4">
                            <div class="space-y-3">
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1"
                                    >
                                        Avatar
                                    </label>
                                    <div
                                        class="flex items-center gap-4 p-2 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg hover:border-electric-blue transition-all duration-200"
                                    >
                                        <Upload class="w-5 h-5 text-gray-400" />
                                        <input
                                            type="file"
                                            @change="handleFileUpload"
                                            class="flex-1 text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-electric-blue/10 file:text-electric-blue hover:file:bg-electric-blue hover:file:text-white transition-all"
                                        />
                                    </div>
                                </div>

                                <div
                                    v-for="field in [
                                        'name',
                                        'email',
                                        'username',
                                        'password',
                                    ]"
                                    :key="field"
                                >
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1 capitalize"
                                    >
                                        {{
                                            field === "password"
                                                ? "Password"
                                                : field
                                        }}
                                    </label>
                                    <input
                                        v-model="form[field]"
                                        :type="
                                            field === 'password'
                                                ? 'password'
                                                : 'text'
                                        "
                                        :placeholder="`Masukkan ${
                                            field === 'password'
                                                ? 'password'
                                                : field
                                        }`"
                                        class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-electric-blue focus:ring-offset-1 focus:border-transparent focus:outline-none transition-all duration-200 shadow-sm hover:shadow-md"
                                        :required="field !== 'username'"
                                    />
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="w-full flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-electric-blue to-coral hover:from-coral hover:to-electric-blue text-white rounded-lg shadow-lg hover:shadow-xl hover:shadow-electric-blue/30 transition-all duration-300 transform hover:scale-103 active:scale-97 font-semibold"
                            >
                                <Save class="w-5 h-5" />
                                <span>Simpan User</span>
                            </button>
                        </form>
                    </div>
                </div>
            </Transition>
        </teleport>

        <!-- Modal View User -->
        <teleport to="body">
            <Transition
                enter-active-class="transition-all duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-all duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="openViewModal"
                    class="fixed inset-0 bg-black/50 backdrop-blur-md z-50"
                    @click="openViewModal = false"
                ></div>
            </Transition>

            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-4"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-4"
            >
                <div
                    v-if="openViewModal"
                    class="fixed inset-0 flex items-center justify-center z-50 p-4"
                    @click="openViewModal = false"
                >
                    <div
                        class="relative bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl shadow-2xl shadow-black/10 border border-white/20 dark:border-gray-700/50 w-full max-w-md mx-auto"
                        @click.stop
                    >
                        <!-- Header Modal -->
                        <div
                            class="rounded-tl-xl px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-electric-blue/15 via-electric-blue/5 to-transparent"
                        >
                            <div class="flex items-center justify-between">
                                <h2
                                    class="text-xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    Detail User
                                </h2>
                                <button
                                    @click="openViewModal = false"
                                    class="p-2 text-gray-400 hover:text-electric-blue hover:bg-white dark:hover:bg-gray-800 rounded-xl transition-all duration-200 transform hover:scale-110"
                                >
                                    <X class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-6">
                            <div class="flex flex-col items-center text-center">
                                <div class="relative mb-4">
                                    <img
                                        :src="
                                            viewingUser?.avatar
                                                ? `/storage/${viewingUser.avatar}`
                                                : '/images/default-avatar.jpg'
                                        "
                                        alt="avatar"
                                        class="w-24 h-24 rounded-2xl border-2 border-gray-200 dark:border-gray-700 object-cover shadow-lg"
                                    />
                                    <div
                                        class="absolute -bottom-2 -right-2 w-6 h-6 bg-electric-blue rounded-full border-2 border-white dark:border-gray-900"
                                    ></div>
                                </div>
                                <h3
                                    class="text-xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    {{ viewingUser?.name }}
                                </h3>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    ID: {{ viewingUser?.id }}
                                </p>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <div
                                        class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4"
                                    >
                                        <div
                                            class="flex items-center gap-3 mb-2"
                                        >
                                            <Mail
                                                class="w-4 h-4 text-electric-blue"
                                            />
                                            <span
                                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                                >Email</span
                                            >
                                        </div>
                                        <p
                                            class="text-gray-900 dark:text-gray-100 pl-7"
                                        >
                                            {{ viewingUser?.email }}
                                        </p>
                                    </div>

                                    <div
                                        class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4"
                                    >
                                        <div
                                            class="flex items-center gap-3 mb-2"
                                        >
                                            <User
                                                class="w-4 h-4 text-electric-blue"
                                            />
                                            <span
                                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                                >Username</span
                                            >
                                        </div>
                                        <p
                                            class="text-gray-900 dark:text-gray-100 pl-7"
                                        >
                                            {{ viewingUser?.username || "-" }}
                                        </p>
                                    </div>

                                    <div
                                        class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4"
                                    >
                                        <div
                                            class="flex items-center gap-3 mb-2"
                                        >
                                            <Key
                                                class="w-4 h-4 text-electric-blue"
                                            />
                                            <span
                                                class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                                >Google ID</span
                                            >
                                        </div>
                                        <span
                                            :class="[
                                                'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ml-7',
                                                viewingUser?.google_id
                                                    ? 'bg-electric-blue/10 text-electric-blue dark:bg-electric-blue/20 dark:text-electric-blue'
                                                    : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                            ]"
                                        >
                                            {{
                                                viewingUser?.google_id
                                                    ? "Connected"
                                                    : "Not Connected"
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3 pt-4">
                                <button
                                    @click="editUser(viewingUser)"
                                    class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-electric-blue to-coral hover:from-coral hover:to-electric-blue text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold"
                                >
                                    <Edit class="w-4 h-4" />
                                    Edit User
                                </button>
                                <button
                                    @click="openViewModal = false"
                                    class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold"
                                >
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </teleport>

        <!-- Modal Edit User -->
        <teleport to="body">
            <Transition
                enter-active-class="transition-all duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-all duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="openEditModal"
                    class="fixed inset-0 bg-black/50 backdrop-blur-md z-50"
                    @click="openEditModal = false"
                ></div>
            </Transition>

            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-4"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-4"
            >
                <div
                    v-if="openEditModal"
                    class="fixed inset-0 flex items-center justify-center z-50 p-4"
                    @click="openEditModal = false"
                >
                    <div
                        class="relative bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl shadow-2xl shadow-black/10 border border-white/20 dark:border-gray-700/50 w-full max-w-md mx-auto"
                        @click.stop
                    >
                        <!-- Header Modal -->
                        <div
                            class="rounded-tl-xl px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-electric-blue/15 via-electric-blue/5 to-transparent"
                        >
                            <div class="flex items-center justify-between">
                                <h2
                                    class="text-xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    Edit User
                                </h2>
                                <button
                                    @click="openEditModal = false"
                                    class="p-2 text-gray-400 hover:text-electric-blue hover:bg-white dark:hover:bg-gray-800 rounded-xl transition-all duration-200 transform hover:scale-110"
                                >
                                    <X class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="update" class="p-5 space-y-4">
                            <div class="space-y-3">
                                <!-- Current Avatar Preview -->
                                <div class="flex flex-col items-center mb-4">
                                    <div class="relative mb-3">
                                        <img
                                            :src="
                                                editingUser.avatar
                                                    ? `/storage/${editingUser.avatar}`
                                                    : '/images/default-avatar.jpg'
                                            "
                                            alt="avatar"
                                            class="w-16 h-16 rounded-xl border-2 border-gray-200 dark:border-gray-700 object-cover"
                                        />
                                        <div
                                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-electric-blue rounded-full border-2 border-white dark:border-gray-900"
                                        ></div>
                                    </div>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                        >Current Avatar</span
                                    >
                                </div>

                                <!-- Avatar Upload -->
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1"
                                    >
                                        Ganti Avatar
                                    </label>
                                    <div
                                        class="flex items-center gap-4 p-2 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg hover:border-electric-blue transition-all duration-200"
                                    >
                                        <Upload class="w-5 h-5 text-gray-400" />
                                        <input
                                            type="file"
                                            @change="handleEditFileUpload"
                                            class="flex-1 text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-electric-blue/10 file:text-electric-blue hover:file:bg-electric-blue hover:file:text-white transition-all"
                                        />
                                    </div>
                                </div>

                                <!-- Form Fields -->
                                <div
                                    v-for="field in [
                                        'name',
                                        'email',
                                        'username',
                                        'password',
                                    ]"
                                    :key="field"
                                >
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1 capitalize"
                                    >
                                        {{
                                            field === "password"
                                                ? "Password Baru (Opsional)"
                                                : field
                                        }}
                                    </label>
                                    <input
                                        v-model="editingUser[field]"
                                        :type="
                                            field === 'password'
                                                ? 'password'
                                                : 'text'
                                        "
                                        :placeholder="
                                            field === 'password'
                                                ? 'Kosongkan jika tidak ingin mengubah'
                                                : `Masukkan ${field}`
                                        "
                                        class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-electric-blue focus:ring-offset-1 focus:border-transparent focus:outline-none transition-all duration-200 shadow-sm hover:shadow-md"
                                        :required="
                                            field !== 'username' &&
                                            field !== 'password'
                                        "
                                    />
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3 pt-2">
                                <button
                                    type="submit"
                                    class="flex-1 flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-electric-blue to-coral hover:from-coral hover:to-electric-blue text-white rounded-lg shadow-lg hover:shadow-xl hover:shadow-electric-blue/30 transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold"
                                >
                                    <Save class="w-5 h-5" />
                                    <span>Update User</span>
                                </button>
                                <button
                                    type="button"
                                    @click="openEditModal = false"
                                    class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:scale-105 active:scale-95 font-semibold"
                                >
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </teleport>
    </AppLayout>
</template>

<script setup>
import { router, Head } from "@inertiajs/vue3";
import {
    ref,
    computed,
    onMounted,
    onBeforeUnmount,
    nextTick,
    reactive,
} from "vue";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Swal from "sweetalert2";
import {
    UserPlus,
    Save,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    X,
    Upload,
    Mail,
    User,
    Key,
    Edit,
    Search,
    ArrowUp,
    ArrowDown,
    ChevronsUpDown,
} from "lucide-vue-next";

// === Modal Tambah User ===
const openModal = ref(false);
const form = ref({
    avatar: null,
    name: "",
    email: "",
    username: "",
    password: "",
});

function handleFileUpload(e) {
    form.value.avatar = e.target.files[0];
}

function submit() {
    const data = new FormData();
    Object.keys(form.value).forEach((key) => {
        if (form.value[key] !== null && form.value[key] !== "") {
            data.append(key, form.value[key]);
        }
    });

    router.post(route("users.store"), data, {
        onSuccess: () => {
            openModal.value = false;
            Swal.fire("Berhasil!", "User berhasil ditambahkan.", "success");
            form.value = {
                avatar: null,
                name: "",
                email: "",
                username: "",
                password: "",
            };
        },
        preserveScroll: true,
    });
}

// === Table Columns ===
const columns = [
    { key: "name", label: "Nama" },
    { key: "email", label: "Email" },
    { key: "username", label: "Username" },
];

// === Dropdown Actions ===
const openDropdown = ref(false);
const dropdownPosition = ref({ top: 0, left: 0 });
const selectedUser = ref(null);

async function toggleDropdown(user, event) {
    event.stopPropagation(); // penting agar tidak tertutup langsung
    selectedUser.value = user;
    const rect = event.currentTarget.getBoundingClientRect();
    dropdownPosition.value = {
        top: rect.bottom + window.scrollY,
        left: rect.left + window.scrollX,
    };
    await nextTick();
    openDropdown.value = !openDropdown.value;
}

// === Props dari Server ===
const props = defineProps({
    users: { type: Object, required: true },
    roles: Array,
    filters: {
        type: Object,
        default: () => ({
            search: "",
            sort: null,
            order: null,
            perPage: 10,
        }),
    },
});

const propsFilters = computed(() => props.filters);
const search = ref(props.filters.search || "");
const perPage = ref(props.filters.perPage || 10);

// === Sorting ===
function sortBy(column) {
    let sort = propsFilters.value.sort;
    let order = propsFilters.value.order;

    if (sort !== column) {
        sort = column;
        order = "asc";
    } else if (order === "asc") {
        order = "desc";
    } else if (order === "desc") {
        sort = null;
        order = null;
    } else {
        sort = column;
        order = "asc";
    }

    router.get(
        route("users.index"),
        {
            search: search.value,
            sort,
            order,
            perPage: perPage.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
}

// === Search & Pagination ===
function updateSearch() {
    router.get(
        route("users.index"),
        { search: search.value, perPage: perPage.value },
        { preserveState: true, replace: true },
    );
}

function updatePerPage() {
    router.get(
        route("users.index"),
        { search: search.value, perPage: perPage.value },
        { preserveState: true, replace: true },
    );
}

function goTo(url) {
    if (!url) return;
    router.visit(url, { preserveState: true, replace: true });
}

// === View, Edit, Delete ===
const openViewModal = ref(false);
const viewingUser = ref(null);
const openEditModal = ref(false);
const editingUser = ref(null);

function viewUser(user) {
    openDropdown.value = false;
    viewingUser.value = { ...user };
    openViewModal.value = true;
}

function editUser(user) {
    openDropdown.value = false;
    editingUser.value = {
        id: user.id,
        name: user.name,
        email: user.email,
        username: user.username,
        avatar: user.avatar,
        password: "",
        avatar_file: null,
    };
    openEditModal.value = true;
}

function handleEditFileUpload(e) {
    editingUser.value.avatar_file = e.target.files[0];
}

function update() {
    if (!editingUser.value) return;

    const data = new FormData();

    data.append("name", editingUser.value.name);
    data.append("email", editingUser.value.email);
    data.append("username", editingUser.value.username);

    if (editingUser.value.password !== "") {
        data.append("password", editingUser.value.password);
    }

    if (editingUser.value.avatar_file) {
        data.append("avatar", editingUser.value.avatar_file);
    }

    data.append("_method", "put");

    router.post(route("users.update", editingUser.value.id), data, {
        onStart: () => {
            Swal.fire({
                title: "Menyimpan...",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });
        },
        onSuccess: () => {
            Swal.close();
            openEditModal.value = false;
            Swal.fire("Berhasil!", "Data user berhasil diperbarui.", "success");
        },
        onError: (errors) => {
            Swal.close();
            Swal.fire("Gagal!", "Terjadi kesalahan saat menyimpan.", "error");
            console.error(errors);
        },
        preserveScroll: true,
    });
}

function deleteUser(id) {
    openDropdown.value = false;
    Swal.fire({
        title: "Hapus user ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#e3342f",
        cancelButtonColor: "#6b7280",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("users.destroy", id), {
                onSuccess: () =>
                    Swal.fire("Terhapus!", "User berhasil dihapus.", "success"),
            });
        }
    });
}

// Role Assignment
const selectedRoles = reactive({});

props.users.data.forEach((user) => {
    selectedRoles[user.id] = user.roles[0]?.name ?? "";
});

function updateRole(user) {
    router.put(
        route("users.update-role", user.id),
        { role: selectedRoles[user.id] },
        { preserveScroll: true },
    );
}

// === Tutup dropdown saat klik di luar ===
function handleClickOutside(e) {
    if (
        openDropdown.value &&
        !e.target.closest(".swal2-container") &&
        !e.target.closest(".dropdown") &&
        !e.target.closest(".swal2-popup")
    ) {
        openDropdown.value = false;
    }
}

onMounted(() => document.addEventListener("click", handleClickOutside));
onBeforeUnmount(() =>
    document.removeEventListener("click", handleClickOutside),
);
</script>
