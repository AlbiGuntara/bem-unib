<script setup>
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
    contact: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    _method: "PUT",
    type: "email",
    value: "",
});

const typeOptions = [
    { value: "email", label: "Email" },
    { value: "phone", label: "Telepon / WhatsApp" },
    { value: "address", label: "Alamat" },
    { value: "instagram", label: "Instagram" },
    { value: "tiktok", label: "TikTok" },
    { value: "youtube", label: "YouTube" },
    { value: "facebook", label: "Facebook" },
    { value: "maps", label: "Google Maps (Embed)" },
];

watch(() => props.contact, (c) => {
    if (!c) return;
    form.type = c.type;
    form.value = c.value;
}, { immediate: true });

function submit() {
    form.post(route("contacts.update", props.contact.id), {
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
                    <h2 class="text-lg font-semibold text-white">Edit Kontak</h2>
                    <p class="mt-1 text-sm text-blue-100">Perbarui kontak atau media sosial.</p>
                </div>

                <form @submit.prevent="submit" class="modal-scroll flex-1 overflow-y-auto">
                    <div class="space-y-5 p-6">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Tipe Kontak</label>
                            <select v-model="form.type"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option v-for="opt in typeOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                            </select>
                            <p v-if="form.errors.type" class="mt-1 text-sm text-red-500">{{ form.errors.type }}</p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Nilai
                                <span v-if="form.type === 'maps'" class="text-xs text-slate-500">(tempel iframe embed)</span>
                            </label>
                            <textarea v-if="form.type === 'maps'" v-model="form.value" rows="4"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <input v-else v-model="form.value" :type="form.type === 'email' ? 'email' : 'text'"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            <p v-if="form.errors.value" class="mt-1 text-sm text-red-500">{{ form.errors.value }}</p>
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
.modal-scroll::-webkit-scrollbar-thumb { background: rgb(37 99 235 / 0.5); border-radius: 9999px; }
</style>
