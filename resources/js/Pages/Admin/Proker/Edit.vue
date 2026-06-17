<script setup>
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
    proker: Object,
    departments: Array,
    pengurus: Array,
});

const emit = defineEmits(["close"]);

const form = useForm({
    title: "",
    description: "",
    start_date: "",
    end_date: "",
    location: "",
    departemen_id: "",
    pengurus_id: "",
    participants: "",
    budget: "",
    realization_participants: "",
    realization_budget: "",
    time: "",
    is_public: true,
    status: "rencana",
});

function submit() {
    form.put(
        route(
            "program-kerja.update",
            props.proker.id
        ),
        {
            preserveScroll: true,

            onSuccess: () => {
                emit("close");
            },

            onError: (errors) => {
                console.log(errors);
            },
        }
    );
}

function formatDateInput(date) {
    return date
        ? new Date(date)
            .toISOString()
            .split("T")[0]
        : "";
}

function formatTimeInput(time) {
    return time
        ? time.substring(0, 5)
        : "";
}

watch(
    () => props.show,
    (val) => {
        if (val && props.proker) {
            form.title =
                props.proker.title ?? "";

            form.description =
                props.proker.description ?? "";

            form.start_date = formatDateInput(
                props.proker.start_date
            );

            form.end_date = formatDateInput(
                props.proker.end_date
            );

            form.location =
                props.proker.location ?? "";

            form.departemen_id =
                props.proker.departemen_id ?? "";

            form.pengurus_id =
                props.proker.pengurus_id ?? "";

            form.participants =
                props.proker.participants ?? "";

            form.budget =
                props.proker.budget ?? "";

            form.realization_participants =
                props.proker.realization_participants ?? "";

            form.realization_budget =
                props.proker.realization_budget ?? "";

            form.time = formatTimeInput(
                props.proker.time
            );

            form.is_public =
                props.proker.is_public ?? true;

            form.status =
                props.proker.status ?? "rencana";
        }

        if (!val) {
            form.reset();
        }
    },
    { immediate: true }
);
</script>

<template>
    <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100"
        leave-to-class="opacity-0">
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div
                class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <!-- HEADER -->
                <div
                    class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-white">
                        Edit Program Kerja
                    </h2>

                    <p class="mt-1 text-sm text-blue-100">
                        Perbarui data program kerja BEM.
                    </p>
                </div>

                <!-- BODY -->
                <form @submit.prevent="submit" class="modal-scroll flex-1 overflow-y-auto">
                    <div class="space-y-5 p-6">

                        <!-- TITLE -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Judul Program
                            </label>

                            <input v-model="form.title" type="text"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-500">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <!-- DESKRIPSI -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Deskripsi
                            </label>

                            <textarea v-model="form.description" rows="4"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <!-- DEPARTEMEN & USER -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Departemen
                                </label>

                                <select v-model="form.departemen_id"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                    <option value="">
                                        Pilih Departemen
                                    </option>

                                    <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                                        {{ dept.name }}
                                    </option>
                                </select>

                                <p v-if="form.errors.departemen_id" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.departemen_id }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Ketua Panitia
                                </label>

                                <select v-model="form.pengurus_id"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                    <option value="">
                                        Pilih Ketua
                                    </option>

                                    <option v-for="item in pengurus" :key="item.id" :value="item.id">
                                        {{ item.name }}
                                        ({{ item.position }})
                                    </option>
                                </select>

                                <p v-if="form.errors.pengurus_id" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.pengurus_id }}
                                </p>
                            </div>

                        </div>

                        <!-- DATE -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Tanggal Mulai
                                </label>

                                <input type="date" v-model="form.start_date"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Tanggal Selesai
                                </label>

                                <input type="date" v-model="form.end_date"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                        </div>

                        <!-- LOKASI & TIME -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Lokasi
                                </label>

                                <input v-model="form.location" type="text"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Waktu
                                </label>

                                <input type="time" v-model="form.time"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                        </div>

                        <!-- PARTICIPANT & BUDGET -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Target Peserta
                                </label>

                                <input type="number" v-model="form.participants"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Budget
                                </label>

                                <input type="number" v-model="form.budget"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                        </div>

                        <!-- REALIZATION -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Realisasi Peserta
                                </label>

                                <input type="number" v-model="form.realization_participants"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Realisasi Budget
                                </label>

                                <input type="number" v-model="form.realization_budget"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                        </div>

                        <!-- STATUS -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Status
                            </label>

                            <select v-model="form.status"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="rencana">
                                    Rencana
                                </option>

                                <option value="berjalan">
                                    Berjalan
                                </option>

                                <option value="selesai">
                                    Selesai
                                </option>

                                <option value="batal">
                                    Batal
                                </option>
                            </select>
                        </div>

                        <!-- IS PUBLIC -->
                        <div class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.is_public" />

                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                Publikasikan Program
                            </span>
                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div
                        class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-end dark:border-slate-700 dark:bg-slate-900">
                        <button type="button" @click="emit('close')"
                            class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                            Batal
                        </button>

                        <button type="submit" :disabled="form.processing"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">
                            <span v-if="form.processing">
                                Menyimpan...
                            </span>

                            <span v-else>
                                Update
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </Transition>
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