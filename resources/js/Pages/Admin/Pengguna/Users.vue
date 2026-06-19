<script setup>
import { router, Head } from "@inertiajs/vue3";
import {
    ref,
    computed,
    onMounted,
    onBeforeUnmount,
    reactive,
} from "vue";
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import Swal from "sweetalert2";
import {
    UserPlus,
    Save,
    X,
    Upload,
    Mail,
    Key,
    Edit,
    Pencil, Trash2, User, ChevronDown,
} from "lucide-vue-next";
import Table from "@/Components/Table.vue";

// === Modal Tambah User ===
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const openModal = ref(false);

const form = useForm({
    avatar: null,
    name: "",
    email: "",
    username: "",
    password: "",
});

const previewUrl = ref(null);

function handleFileChange(e) {
    const file = e.target.files[0];
    form.avatar = file;

    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);

    previewUrl.value = file ? URL.createObjectURL(file) : null;
}

function submit() {
    form.post(route("users.store"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            previewUrl.value = null;
            openModal.value = false;
        },
    });
}

watch(openModal, (val) => {
    if (!val) {
        form.reset();
        if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = null;
    }
});

// === Table Columns ===
const columns = [
    { key: "name", label: "Nama" },
    { key: "email", label: "Email" },
    { key: "username", label: "Username" },
    { key: "role", label: "Role" },
];

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

// === View, Edit, Delete ===
const openViewModal = ref(false);
const viewingUser = ref(null);
const openEditModal = ref(false);
const editForm = useForm({
    id: null,
    avatar: null,
    name: "",
    email: "",
    username: "",
    password: "",
});
const editPreviewUrl = ref(null);
const editExistingAvatar = ref(null);

function viewUser(user) {
    viewingUser.value = { ...user };
    openViewModal.value = true;
}

function editUser(user) {
    editForm.id = user.id;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.username = user.username;
    editForm.password = "";
    editForm.avatar = null;

    editExistingAvatar.value = user.avatar;

    openEditModal.value = true;
}

function handleEditFileChange(e) {
    const file = e.target.files[0];
    editForm.avatar = file;

    if (editPreviewUrl.value) URL.revokeObjectURL(editPreviewUrl.value);

    editPreviewUrl.value = file ? URL.createObjectURL(file) : null;
}

function update() {
    editForm
        .transform((data) => ({
            ...data,
            _method: "put",
        }))
        .post(route("users.update", editForm.id), {
            forceFormData: true,
            preserveScroll: true,
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
                editForm.reset();
                Swal.fire("Berhasil!", "Data user berhasil diperbarui.", "success");
            },
            onError: () => {
                Swal.close();
                Swal.fire("Gagal!", "Terjadi kesalahan saat menyimpan.", "error");
            },
        });
}

function deleteUser(id) {
    const isDark =
        document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Hapus user ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: "warning",

        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",

        confirmButtonColor: "#dc2626", // red-600
        cancelButtonColor: isDark ? "#374151" : "#6b7280",

        background: isDark ? "#0f172a" : "#ffffff", // slate-900 / white
        color: isDark ? "#f1f5f9" : "#111827",

        customClass: {
            popup: "rounded-xl shadow-2xl",
            title: "text-lg font-semibold",
            htmlContainer: "text-sm",
            confirmButton:
                "px-4 py-2 rounded-lg font-medium",
            cancelButton:
                "px-4 py-2 rounded-lg font-medium",
        },

        buttonsStyling: true,
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("users.destroy", id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Terhapus!",
                        text: "User berhasil dihapus.",
                        icon: "success",
                        background: isDark ? "#0f172a" : "#ffffff",
                        color: isDark ? "#f1f5f9" : "#111827",
                        confirmButtonColor: "#2563eb",
                    }),
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
</script>

<template>
    <AppLayout>

        <Head title="Manajemen User" />

        <!-- Table Container -->
        <Table :data="users" :columns="columns" routeName="users.index" :filters="props.filters">
            <!-- HEADER ACTION -->
            <template #header-action>
                <button @click="openModal = true"
                    class="group relative flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-800 to-blue-700 hover:from-blue-800/90 hover:to-blue-700/90 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <UserPlus class="h-5 w-5" />
                    <span>Tambah User</span>
                </button>
            </template>

            <!-- NAME COLUMN (avatar + name) -->
            <template #name="{ item }">
                <div class="flex items-center gap-3">
                    <img :src="item.avatar
                        ? `/storage/${item.avatar}`
                        : '/images/default-avatar.jpg'" class="w-9 h-9 rounded-full border object-cover" loading="lazy" />
                    <span class="font-medium">
                        {{ item.name }}
                    </span>
                </div>
            </template>

            <!-- EMAIL -->
            <template #email="{ item }">
                {{ item.email }}
            </template>

            <!-- USERNAME -->
            <template #username="{ item }">
                {{ item.username }}
            </template>

            <!-- ROLE -->
            <template #role="{ item }">
                <div class="relative w-full max-w-[160px]">

                    <select v-model="selectedRoles[item.id]" @change="updateRole(item)"
                        :disabled="item.roles.some(r => r.name === 'super-admin')"
                        class="w-full appearance-none rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 pr-8 text-sm text-gray-700 dark:text-gray-100 shadow-sm hover:border-blue-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-offset-0 focus:ring-blue-500/20 transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:hover:border-gray-200">
                        <option disabled value="">Pilih Role</option>

                        <option v-for="role in roles" :key="role.id" :value="role.name">
                            {{ role.name }}
                        </option>
                    </select>

                    <!-- Dropdown Icon -->
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <ChevronDown class="w-4 h-4" />
                    </div>
                </div>
            </template>

            <!-- AKSI -->
            <template #actions="{ item }">
                <div class="flex items-center gap-2">

                    <!-- View -->
                    <button @click="viewUser(item)"
                        class="p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 active:scale-95 transition"
                        title="View">
                        <User class="w-4 h-4" />
                    </button>

                    <!-- Edit -->
                    <button @click="editUser(item)"
                        class="p-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 active:scale-95 transition"
                        title="Edit">
                        <Pencil class="w-4 h-4" />
                    </button>

                    <!-- Delete -->
                    <button @click="deleteUser(item.id)"
                        class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700 active:scale-95 transition"
                        title="Delete">
                        <Trash2 class="w-4 h-4" />
                    </button>

                </div>
            </template>
        </Table>

        <!-- Modal Tambah User -->
        <teleport to="body">
            <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="openModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                    @click="openModal = false">
                    <div class="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900"
                        @click.stop>
                        <!-- HEADER -->
                        <div
                            class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">
                            <h2 class="text-lg font-semibold text-white">
                                Tambah User
                            </h2>
                            <p class="mt-1 text-sm text-blue-100">
                                Lengkapi informasi user baru yang akan ditambahkan.
                            </p>
                        </div>

                        <!-- BODY -->
                        <form @submit.prevent="submit" class="modal-scroll flex-1 overflow-y-auto">
                            <div class="space-y-5 p-6">

                                <!-- Avatar Preview -->
                                <div v-if="previewUrl">
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Preview Avatar
                                    </label>

                                    <div
                                        class="flex items-center gap-4 rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800">
                                        <img :src="previewUrl"
                                            class="h-16 w-16 rounded-lg border object-cover dark:border-slate-600" loading="lazy" />

                                        <div>
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                                {{ form.avatar?.name }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Preview sebelum upload
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Avatar Upload -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Avatar
                                    </label>

                                    <input type="file" accept="image/*" @change="handleFileChange" class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700
                file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white
                hover:file:bg-blue-700 transition
                dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300" />

                                    <p v-if="form.errors.avatar" class="mt-1 text-sm text-red-500">
                                        {{ form.errors.avatar }}
                                    </p>
                                </div>

                                <!-- Name -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Nama
                                    </label>

                                    <input v-model="form.name" type="text" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition
                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10
                dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Email
                                    </label>

                                    <input v-model="form.email" type="email" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition
                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10
                dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">
                                        {{ form.errors.email }}
                                    </p>
                                </div>

                                <!-- Username -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Username
                                    </label>

                                    <input v-model="form.username" type="text" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition
                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10
                dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                                    <p v-if="form.errors.username" class="mt-1 text-sm text-red-500">
                                        {{ form.errors.username }}
                                    </p>
                                </div>

                                <!-- Password -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Password
                                    </label>

                                    <input v-model="form.password" type="password" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition
                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10
                dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-500">
                                        {{ form.errors.password }}
                                    </p>
                                </div>

                            </div>

                            <!-- FOOTER -->
                            <div
                                class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-end dark:border-slate-700 dark:bg-slate-900">
                                <button type="button" @click="openModal = false"
                                    class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                                    Batal
                                </button>

                                <button type="submit" :disabled="form.processing"
                                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                    <span v-if="form.processing">Menyimpan...</span>
                                    <span v-else>Simpan User</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </teleport>

        <!-- Modal View User -->
        <teleport to="body">
            <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="openViewModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                    @click="openViewModal = false">
                    <div class="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900"
                        @click.stop>

                        <!-- HEADER (SAMA PERSIS CREATE) -->
                        <div
                            class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">
                            <h2 class="text-lg font-semibold text-white">
                                Detail User
                            </h2>
                            <p class="mt-1 text-sm text-blue-100">
                                Informasi lengkap data user.
                            </p>
                        </div>

                        <!-- BODY -->
                        <div class="modal-scroll flex-1 overflow-y-auto">
                            <div class="p-6 space-y-6">

                                <!-- HERO PROFILE CARD -->
                                <div
                                    class="relative overflow-hidden rounded-lg border border-slate-200 bg-gradient-to-br from-blue-50 via-white to-slate-50 p-6 shadow-sm dark:border-slate-700 dark:from-slate-800 dark:via-slate-900 dark:to-slate-900">

                                    <!-- Decorative blur -->
                                    <div
                                        class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-blue-400/20 blur-3xl">
                                    </div>

                                    <div class="relative flex flex-col items-center text-center">

                                        <!-- Avatar -->
                                        <div class="relative">
                                            <img :src="viewingUser?.avatar
                                                ? `/storage/${viewingUser.avatar}`
                                                : '/images/default-avatar.jpg'"
                                                class="h-24 w-24 rounded-2xl border-4 border-white object-cover shadow-lg dark:border-slate-700" loading="lazy" />

                                            <!-- Status dot -->
                                            <span
                                                class="absolute bottom-1 right-1 h-4 w-4 rounded-full border-2 border-white"
                                                :class="viewingUser?.google_id ? 'bg-green-500' : 'bg-slate-400'"></span>
                                        </div>

                                        <!-- Name -->
                                        <h3 class="mt-4 text-xl font-bold text-slate-900 dark:text-white">
                                            {{ viewingUser?.name }}
                                        </h3>

                                        <!-- Username -->
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            @{{ viewingUser?.username || '-' }}
                                        </p>

                                        <!-- Badge -->
                                        <div class="mt-3">
                                            <span
                                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                                :class="viewingUser?.google_id
                                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                                    : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'">
                                                {{ viewingUser?.google_id ? 'Google Connected' : 'Standard Account' }}
                                            </span>
                                        </div>

                                    </div>
                                </div>

                                <!-- DETAILS SECTION -->
                                <div class="space-y-4">

                                    <!-- Email Card -->
                                    <div
                                        class="group rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition dark:border-slate-700 dark:bg-slate-800">

                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300">
                                                <Mail class="h-4 w-4" />
                                            </div>

                                            <div>
                                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                                    Email
                                                </p>
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                                    {{ viewingUser?.email }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Username Card -->
                                    <div
                                        class="group rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition dark:border-slate-700 dark:bg-slate-800">

                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">
                                                <User class="h-4 w-4" />
                                            </div>

                                            <div>
                                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                                    Username
                                                </p>
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                                    {{ viewingUser?.username || '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-end dark:border-slate-700 dark:bg-slate-900">
                            <button type="button" @click="openViewModal = false"
                                class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                                Tutup
                            </button>

                            <button type="button" @click="editUser(viewingUser)"
                                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                                Edit User
                            </button>
                        </div>

                    </div>
                </div>
            </Transition>
        </teleport>

        <!-- Modal Edit User -->
        <teleport to="body">
            <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">

                <div v-if="openEditModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">

                    <div
                        class="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">

                        <!-- HEADER -->
                        <div
                            class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">

                            <h2 class="text-lg font-semibold text-white">
                                Edit User
                            </h2>

                            <p class="mt-1 text-sm text-blue-100">
                                Perbarui informasi user yang dipilih.
                            </p>
                        </div>

                        <!-- BODY -->
                        <form @submit.prevent="update" class="modal-scroll flex-1 overflow-y-auto">

                            <div class="space-y-5 p-6">

                                <!-- NAME -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Nama
                                    </label>

                                    <input v-model="editForm.name" type="text" placeholder="Masukkan nama user"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                                    <p v-if="editForm.errors?.name" class="mt-1 text-sm text-red-500">
                                        {{ editForm.errors.name }}
                                    </p>
                                </div>

                                <!-- EMAIL -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Email
                                    </label>

                                    <input v-model="editForm.email" type="email" placeholder="Masukkan email user"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                                    <p v-if="editForm.errors?.email" class="mt-1 text-sm text-red-500">
                                        {{ editForm.errors.email }}
                                    </p>
                                </div>

                                <!-- USERNAME -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Username
                                    </label>

                                    <input v-model="editForm.username" type="text" placeholder="Masukkan username"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                                    <p v-if="editForm.errors?.username" class="mt-1 text-sm text-red-500">
                                        {{ editForm.errors.username }}
                                    </p>
                                </div>

                                <!-- PASSWORD -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Password
                                    </label>

                                    <input v-model="editForm.password" type="password"
                                        placeholder="Kosongkan jika tidak ingin mengubah password"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                                </div>

                                <!-- AVATAR CURRENT -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Avatar Saat Ini
                                    </label>

                                    <div
                                        class="flex items-center gap-4 rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800">

                                        <img :src="editForm.avatar
                                            ? editPreviewUrl
                                            : (editExistingAvatar
                                                ? `/storage/${editExistingAvatar}`
                                                : '/images/default-avatar.jpg')"
                                            class="h-16 w-16 rounded-lg border object-cover" loading="lazy" />

                                        <div>
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                                Avatar User
                                            </p>

                                            <p class="text-xs text-slate-500">
                                                Upload baru jika ingin mengganti
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- AVATAR UPLOAD -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Ganti Avatar (Opsional)
                                    </label>

                                    <input type="file" accept="image/*" @change="handleEditFileChange" class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700
                                file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white
                                hover:file:bg-blue-700 transition
                                dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300" />

                                    <p v-if="editForm.avatar?.name" class="mt-2 text-sm text-slate-500">
                                        File: {{ editForm.avatar.name }}
                                    </p>

                                    <p v-if="editForm.errors?.avatar" class="mt-1 text-sm text-red-500">
                                        {{ editForm.errors.avatar }}
                                    </p>
                                </div>

                                <!-- FOOTER -->
                                <div
                                    class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-end dark:border-slate-700 dark:bg-slate-900">

                                    <button type="button" @click="openEditModal = false"
                                        class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                                        Batal
                                    </button>

                                    <button type="submit" :disabled="editForm.processing"
                                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">

                                        <span v-if="editForm.processing">
                                            Memperbarui...
                                        </span>

                                        <span v-else>
                                            Update User
                                        </span>
                                    </button>

                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </Transition>
        </teleport>
    </AppLayout>
</template>

<style scoped>
.modal-scroll {
    scrollbar-width: thin;
    scrollbar-color: rgb(37 99 235 / 0.6) transparent;
}

/* Chrome */
.modal-scroll::-webkit-scrollbar {
    width: 6px;
}

.modal-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.modal-scroll::-webkit-scrollbar-thumb {
    background: rgb(37 99 235 / 0.5);
    border-radius: 9999px;
    border: 2px solid transparent;
    background-clip: padding-box;
}

.modal-scroll::-webkit-scrollbar-thumb:hover {
    background: rgb(29 78 216 / 0.8);
}

/* Dark Mode */
:global(.dark) .modal-scroll {
    scrollbar-color: rgb(59 130 246 / 0.7) transparent;
}

:global(.dark) .modal-scroll::-webkit-scrollbar-thumb {
    background: rgb(59 130 246 / 0.6);
}

:global(.dark) .modal-scroll::-webkit-scrollbar-thumb:hover {
    background: rgb(96 165 250 / 0.8);
}
</style>