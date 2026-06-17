<script setup>
import { useForm } from "@inertiajs/vue3";
import { watch, ref } from "vue";

const props = defineProps({
    show: Boolean,
    proker: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    realization_participants: "",
    realization_budget: "",
});

const budgetDisplay = ref("");

function submit() {
    form.patch(
        route("proker.complete", props.proker.id),
        {
            preserveScroll: true,

            onSuccess: () => {
                form.reset();
                emit("close");
            },
        }
    );
}

// Format mata uang
function formatRupiah(value) {
    if (!value) return "";

    return new Intl.NumberFormat("id-ID").format(value);
}

function handleBudgetInput(event) {
    const raw = event.target.value.replace(/\D/g, "");

    form.realization_budget = raw;

    budgetDisplay.value = raw
        ? `Rp ${formatRupiah(raw)}`
        : "";
}

watch(
    () => props.show,
    (val) => {
        if (val && props.proker) {
            form.realization_participants =
                props.proker.realization_participants ?? "";

            form.realization_budget =
                props.proker.realization_budget ?? "";

            budgetDisplay.value =
                form.realization_budget
                    ? `Rp ${formatRupiah(
                        form.realization_budget
                    )}`
                    : "";
        }

        if (!val) {
            form.reset();
            budgetDisplay.value = "";
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
                <!-- HEADER -->
                <div
                    class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-white">
                        Selesaikan Program Kerja
                    </h2>

                    <p class="mt-1 text-sm text-blue-100">
                        Isi data realisasi kegiatan sebelum program kerja
                        ditandai selesai.
                    </p>
                </div>

                <!-- BODY -->
                <form @submit.prevent="submit" class="modal-scroll flex-1 overflow-y-auto">
                    <div class="space-y-5 p-6">

                        <!-- INFO PROGRAM -->
                        <div
                            class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                Program Kerja
                            </div>

                            <div class="mt-1 font-semibold text-slate-800 dark:text-slate-100">
                                {{ proker?.title }}
                            </div>
                        </div>

                        <!-- REALISASI PESERTA -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Jumlah Peserta
                            </label>

                            <input v-model="form.realization_participants" type="number" min="0"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />

                            <p v-if="form.errors.realization_participants" class="mt-1 text-sm text-red-500">
                                {{
                                    form.errors
                                        .realization_participants
                                }}
                            </p>
                        </div>

                        <!-- REALISASI ANGGARAN -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Total Anggaran
                            </label>

                            <div class="relative">
                                <input :value="budgetDisplay" @input="handleBudgetInput" type="text" placeholder="Rp 0"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                            </div>

                            <p v-if="form.errors.realization_budget" class="mt-1 text-sm text-red-500">
                                {{
                                    form.errors
                                        .realization_budget
                                }}
                            </p>
                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div
                        class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-end dark:border-slate-700 dark:bg-slate-900">
                        <button type="button" @click="emit('close')"
                            class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                            Batal
                        </button>

                        <button type="submit" :disabled="form.processing"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                            <span v-if="form.processing">
                                Menyimpan...
                            </span>

                            <span v-else>
                                Selesaikan Program
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