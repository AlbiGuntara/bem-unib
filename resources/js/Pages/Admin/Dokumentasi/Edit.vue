<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    show: Boolean,
    dokumentasi: Object,
    programKerjas: Array,
});

const emit = defineEmits(["close"]);

const form = useForm({
    program_kerjas_id: "",
    link_drive: "",
    thumbnail_portrait: null,
    thumbnail_landscape: null,
});

const previewPortrait = ref(null);
const previewLandscape = ref(null);

watch(
    () => props.dokumentasi,
    (val) => {
        if (!val) return;

        form.program_kerjas_id = val.program_kerjas_id;
        form.link_drive = val.link_drive ?? "";

        form.thumbnail_portrait = null;
        form.thumbnail_landscape = null;

        previewPortrait.value = val.thumbnail_portrait
            ? `/storage/${val.thumbnail_portrait}`
            : null;

        previewLandscape.value = val.thumbnail_landscape
            ? `/storage/${val.thumbnail_landscape}`
            : null;
    },
    { immediate: true }
);

function handlePortrait(e) {
    const file = e.target.files[0];

    form.thumbnail_portrait = file;

    if (file) {
        previewPortrait.value =
            URL.createObjectURL(file);
    }
}

function handleLandscape(e) {
    const file = e.target.files[0];

    form.thumbnail_landscape = file;

    if (file) {
        previewLandscape.value =
            URL.createObjectURL(file);
    }
}

function submit() {
    form.transform((data) => ({
        ...data,
        _method: "PUT",
    })).post(
        route(
            "dokumentasi.update",
            props.dokumentasi.id
        ),
        {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                emit("close");
            },
        }
    );
}

watch(
    () => props.show,
    (val) => {
        if (!val) {
            form.clearErrors();
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
                class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <!-- HEADER -->
                <div
                    class="border-b border-slate-200 bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-white">
                        Edit Dokumentasi
                    </h2>

                    <p class="mt-1 text-sm text-amber-100">
                        Perbarui dokumentasi program kerja beserta link drive.
                    </p>
                </div>

                <!-- BODY -->
                <form @submit.prevent="submit" class="modal-scroll flex-1 overflow-y-auto">
                    <div class="space-y-5 p-6">

                        <!-- PROGRAM -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Program Kerja
                            </label>

                            <select v-model="form.program_kerjas_id"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-500/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="">
                                    Pilih Program Kerja
                                </option>

                                <option v-for="program in programKerjas" :key="program.id" :value="program.id">
                                    {{ program.title }}
                                </option>
                            </select>

                            <p v-if="form.errors.program_kerjas_id" class="mt-1 text-sm text-red-500">
                                {{ form.errors.program_kerjas_id }}
                            </p>
                        </div>

                        <!-- LINK DRIVE -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Link Google Drive
                            </label>

                            <input v-model="form.link_drive" type="url" placeholder="https://drive.google.com/..."
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-500/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                            <p v-if="form.errors.link_drive" class="mt-1 text-sm text-red-500">
                                {{ form.errors.link_drive }}
                            </p>
                        </div>

                        <!-- PREVIEW -->
                        <div v-if="
                            previewPortrait ||
                            previewLandscape
                        " class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-if="previewPortrait"
                                class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800">
                                <p class="mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Thumbnail Portrait
                                </p>

                                <img :src="previewPortrait" class="aspect-[3/4] w-full rounded-lg object-cover" />
                            </div>

                            <div v-if="previewLandscape"
                                class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800">
                                <p class="mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Thumbnail Landscape
                                </p>

                                <img :src="previewLandscape" class="aspect-video w-full rounded-lg object-cover" />
                            </div>
                        </div>

                        <!-- UPLOAD -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- PORTRAIT -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Ganti Thumbnail Portrait
                                </label>

                                <div
                                    class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-4 dark:border-slate-600 dark:bg-slate-800">
                                    <input type="file" accept="image/*" @change="handlePortrait"
                                        class="block w-full text-sm text-slate-700 file:mr-4 file:border-0 file:bg-amber-500 file:px-4 file:py-2 file:text-white hover:file:bg-amber-600 dark:text-slate-300" />
                                </div>

                                <p v-if="form.errors.thumbnail_portrait" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.thumbnail_portrait }}
                                </p>
                            </div>

                            <!-- LANDSCAPE -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Ganti Thumbnail Landscape
                                </label>

                                <div
                                    class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-4 dark:border-slate-600 dark:bg-slate-800">
                                    <input type="file" accept="image/*" @change="handleLandscape"
                                        class="block w-full text-sm text-slate-700 file:mr-4 file:border-0 file:bg-amber-500 file:px-4 file:py-2 file:text-white hover:file:bg-amber-600 dark:text-slate-300" />
                                </div>

                                <p v-if="form.errors.thumbnail_landscape" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.thumbnail_landscape }}
                                </p>
                            </div>
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
                            class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-amber-600 disabled:opacity-50">
                            <span v-if="form.processing">
                                Mengupdate...
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
    scrollbar-color: rgb(245 158 11 / 0.6) transparent;
}

.modal-scroll::-webkit-scrollbar {
    width: 6px;
}

.modal-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.modal-scroll::-webkit-scrollbar-thumb {
    background: rgb(245 158 11 / 0.5);
    border-radius: 9999px;
}

.modal-scroll::-webkit-scrollbar-thumb:hover {
    background: rgb(217 119 6 / 0.8);
}
</style>