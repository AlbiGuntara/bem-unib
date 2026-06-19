<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(["close"]);

const form = useForm({
    letter_number: "",
    subject: "",
    receiver: "",
    date: "",
    file: null,
});

const previewFileName = ref(null);

function handleFileChange(e) {
    const file = e.target.files[0];
    form.file = file;
    previewFileName.value = file ? file.name : null;
}

function submit() {
    form.post(route("surat-keluar.store"), {
        forceFormData: true,
        preserveScroll: true,

        onSuccess: () => {
            form.reset();
            emit("close");
        },
    });
}

watch(
    () => props.show,
    (val) => {
        if (!val) {
            form.reset();
            previewFileName.value = null;
        }
    }
);
</script>

<template>
    <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100"
        leave-to-class="opacity-0">
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div
                class="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <div
                    class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-white">
                        Tambah Surat Keluar
                    </h2>
                    <p class="mt-1 text-sm text-blue-100">
                        Lengkapi informasi surat keluar.
                    </p>
                </div>

                <form @submit.prevent="submit" class="modal-scroll flex-1 overflow-y-auto">
                    <div class="space-y-5 p-6">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                No Surat
                            </label>
                            <input v-model="form.letter_number" type="text"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.letter_number" class="mt-1 text-sm text-red-500">
                                {{ form.errors.letter_number }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Perihal
                            </label>
                            <input v-model="form.subject" type="text"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.subject" class="mt-1 text-sm text-red-500">
                                {{ form.errors.subject }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Penerima
                            </label>
                            <input v-model="form.receiver" type="text"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.receiver" class="mt-1 text-sm text-red-500">
                                {{ form.errors.receiver }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Tanggal Surat
                            </label>
                            <input v-model="form.date" type="date"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.date" class="mt-1 text-sm text-red-500">
                                {{ form.errors.date }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                File Surat (PDF, max 5MB)
                            </label>
                            <input type="file" accept=".pdf,.doc,.docx,.jpg,.png" @change="handleFileChange"
                                class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white hover:file:bg-blue-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300" />
                            <p v-if="previewFileName" class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                File dipilih: {{ previewFileName }}
                            </p>
                            <p v-if="form.errors.file" class="mt-1 text-sm text-red-500">
                                {{ form.errors.file }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-end dark:border-slate-700 dark:bg-slate-900">
                        <button type="button" @click="emit('close')"
                            class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.modal-scroll { scrollbar-width: thin; scrollbar-color: rgb(37 99 235 / 0.6) transparent; }
.modal-scroll::-webkit-scrollbar { width: 6px; }
.modal-scroll::-webkit-scrollbar-track { background: transparent; }
.modal-scroll::-webkit-scrollbar-thumb { background: rgb(37 99 235 / 0.5); border-radius: 9999px; border: 2px solid transparent; background-clip: padding-box; }
.modal-scroll::-webkit-scrollbar-thumb:hover { background: rgb(29 78 216 / 0.8); }
:global(.dark) .modal-scroll { scrollbar-color: rgb(59 130 246 / 0.7) transparent; }
:global(.dark) .modal-scroll::-webkit-scrollbar-thumb { background: rgb(59 130 246 / 0.6); }
:global(.dark) .modal-scroll::-webkit-scrollbar-thumb:hover { background: rgb(96 165 250 / 0.8); }
</style>
