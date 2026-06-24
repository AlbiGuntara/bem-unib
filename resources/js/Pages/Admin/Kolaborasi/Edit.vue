<script setup>
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
    kolaborasi: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    _method: "PUT",
    name: "",
    type: "external",
    url: "",
    logo: null,
    order: "",
    is_active: true,
});

watch(() => props.kolaborasi, (k) => {
    if (!k) return;
    form.name = k.name;
    form.type = k.type;
    form.url = k.url || "";
    form.order = k.order;
    form.is_active = k.is_active;
}, { immediate: true });

function submit() {
    form.post(route("kolaborasi.update", props.kolaborasi.id), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => emit("close"),
    });
}
</script>

<template>
    <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100"
        leave-to-class="opacity-0">
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div
                class="flex max-h-[90vh] w-full max-w-lg flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <div
                    class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-white">Edit Kolaborasi</h2>
                    <p class="mt-1 text-sm text-blue-100">Perbarui informasi mitra kolaborasi.</p>
                </div>

                <form @submit.prevent="submit" class="modal-scroll flex-1 overflow-y-auto">
                    <div class="space-y-5 p-6">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Mitra</label>
                            <input v-model="form.name" type="text"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Logo</label>
                            <div v-if="kolaborasi?.logo" class="mb-2">
                                <img :src="`/storage/${kolaborasi.logo}`"
                                    class="w-16 h-16 rounded-lg object-contain border bg-white p-1" />
                            </div>
                            <input type="file" accept="image/*" @input="form.logo = $event.target.files[0]"
                                class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white file:rounded-lg hover:file:bg-blue-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300" />
                            <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengganti logo.</p>
                            <p v-if="form.errors.logo" class="mt-1 text-sm text-red-500">{{ form.errors.logo }}</p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Tipe</label>
                            <select v-model="form.type"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="external">External</option>
                                <option value="internal">Internal</option>
                            </select>
                            <p v-if="form.errors.type" class="mt-1 text-sm text-red-500">{{ form.errors.type }}</p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">URL (opsional)</label>
                            <input v-model="form.url" type="url" placeholder="https://..."
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.url" class="mt-1 text-sm text-red-500">{{ form.errors.url }}</p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Urutan</label>
                            <input v-model="form.order" type="number" min="0"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.order" class="mt-1 text-sm text-red-500">{{ form.errors.order }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <input v-model="form.is_active" type="checkbox" :true-value="1" :false-value="0"
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Aktif</label>
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
.modal-scroll::-webkit-scrollbar-thumb { background: rgb(37 99 235 / 0.5); border-radius: 9999px; }
</style>
