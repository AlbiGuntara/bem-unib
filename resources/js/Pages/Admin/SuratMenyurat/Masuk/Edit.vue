<script setup>
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
    item: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    _method: "PUT",
    letter_number: "",
    subject: "",
    sender: "",
    date: "",
    file: null,
});

watch(
    () => props.item,
    (item) => {
        if (!item) return;

        form.letter_number = item.letter_number;
        form.subject = item.subject;
        form.sender = item.sender;
        form.date = item.date;
        form.file = null;
    },
    { immediate: true }
);

function submit() {
    form.post(
        route("surat-masuk.update", props.item.id),
        {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => emit("close"),
        }
    );
}
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
                        Edit Surat Masuk
                    </h2>
                    <p class="mt-1 text-sm text-blue-100">
                        Perbarui informasi surat masuk.
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
                                Pengirim
                            </label>
                            <input v-model="form.sender" type="text"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.sender" class="mt-1 text-sm text-red-500">
                                {{ form.errors.sender }}
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

                        <div v-if="item?.file_path">
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                File Saat Ini
                            </label>
                            <div
                                class="flex items-center gap-4 rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">PDF</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">File Surat</p>
                                    <p class="text-xs text-slate-500">Upload file baru jika ingin mengganti.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Ganti File (Opsional)
                            </label>
                            <input type="file" accept=".pdf,.doc,.docx,.jpg,.png" @change="form.file = $event.target.files[0]"
                                class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white hover:file:bg-blue-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300" />
                            <p v-if="form.file" class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                File dipilih: {{ form.file.name }}
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
                            <span v-if="form.processing">Memperbarui...</span>
                            <span v-else>Update</span>
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
