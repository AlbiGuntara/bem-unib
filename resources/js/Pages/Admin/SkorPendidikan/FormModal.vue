<template>
    <teleport to="body">
        <div
            v-if="show"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9999] px-4"
            @click.self="$emit('close')"
        >
            <div
                class="relative bg-white dark:bg-gray-900 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-md mx-auto animate-fade-in-up"
                @click.stop
            >
                <!-- Header -->
                <div
                    class="rounded-tl-xl px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-100 to-transparent dark:from-green-950/30"
                >
                    <div class="flex items-center justify-between">
                        <h2
                            class="text-xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{
                                editData
                                    ? "Edit Skor Pendidikan"
                                    : "Tambah Skor Pendidikan"
                            }}
                        </h2>
                        <button
                            @click="$emit('close')"
                            class="p-2 text-gray-400 hover:text-green-600 hover:bg-white dark:hover:bg-gray-800 rounded-xl transition-all duration-200 transform hover:scale-110"
                        >
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-5">
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Warga -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1"
                                >Pilih Santri</label
                            >
                            <select
                                v-model="form.id_warga"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 py-2 focus:outline-none"
                            >
                                <option value="">-- Pilih --</option>
                                <option
                                    v-for="w in warga"
                                    :key="w.id"
                                    :value="w.id"
                                >
                                    {{ w.nama }} - {{ w.nis }}
                                </option>
                            </select>
                        </div>

                        <!-- Jenis -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1"
                                >Jenis Pendidikan</label
                            >
                            <select
                                v-model="form.jenis"
                                @change="setPendidikan"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 py-2 focus:outline-none"
                            >
                                <option value="">-- Pilih --</option>
                                <option value="pagi">Pagi</option>
                                <option value="sore">Sore</option>
                                <option value="malam">Malam</option>
                            </select>
                        </div>

                        <!-- Lembaga -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1"
                                >Lembaga</label
                            >
                            <input
                                type="text"
                                v-model="lembaga"
                                disabled
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-3 py-2 cursor-not-allowed"
                            />
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1"
                                >Kelas</label
                            >
                            <input
                                type="text"
                                v-model="kelas"
                                disabled
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-3 py-2 cursor-not-allowed"
                            />
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1"
                                >Jumlah Skor</label
                            >
                            <input
                                type="number"
                                v-model="form.jumlah"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 py-2 focus:outline-none"
                            />
                        </div>

                        <!-- Bulan -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1"
                                >Bulan</label
                            >
                            <select
                                v-model="form.bulan"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 py-2"
                            >
                                <option disabled value="">
                                    -- Pilih Bulan --
                                </option>
                                <option
                                    v-for="b in bulanOptions"
                                    :key="b"
                                    :value="b"
                                >
                                    {{ b }}
                                </option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button
                                type="button"
                                @click="$emit('close')"
                                class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border font-semibold"
                            >
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-lg font-semibold"
                            >
                                {{ editData ? "Update" : "Simpan" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </teleport>
</template>

<script setup>
import { ref, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    show: Boolean,
    warga: Array,
    editData: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    id_warga: "",
    jumlah: 0,
    bulan: "",
    jenis: null,
});

const bulanOptions = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
];

const lembaga = ref("");
const kelas = ref("");

// Set lembaga dan kelas
const setPendidikan = () => {
    if (!form.id_warga) return;

    const w = props.warga.find((x) => x.id === form.id_warga);
    if (!w) return;

    switch (form.jenis) {
        case "pagi":
            lembaga.value = w.pendidikan_pagi;
            kelas.value = w.kelas_pendidikan_pagi;
            break;
        case "sore":
            lembaga.value = w.pendidikan_sore;
            kelas.value = w.kelas_pendidikan_sore;
            break;
        case "malam":
            lembaga.value = w.pendidikan_malam;
            kelas.value = w.kelas_pendidikan_malam;
            break;
    }
};

// Mode edit
watch(
    () => props.editData,
    (val) => {
        if (val) {
            form.id_warga = val.id_warga;
            form.jumlah = val.jumlah;
            form.bulan = val.bulan;
            form.jenis = val.jenis;

            setTimeout(() => {
                if (form.jenis) {
                    setPendidikan();
                }
            }, 50);
        } else {
            form.reset();
            lembaga.value = "";
            kelas.value = "";
        }
    },
    { immediate: true }
);

watch(
    () => form.id_warga,
    () => {
        setTimeout(() => {
            if (form.jenis) return;
            setPendidikan();
        }, 10);
    }
);

watch(
    () => form.jenis,
    () => {
        if (!form.id_warga) return;
        if (!form.jenis) return;
        setPendidikan();
    }
);

const submit = () => {
    if (props.editData) {
        router.put(route("skor-pendidikan.update", props.editData.id), form, {
            onSuccess: () => emit("close"),
        });
    } else {
        router.post(route("skor-pendidikan.store"), form, {
            onSuccess: () => emit("close"),
        });
    }
};
</script>

<style scoped>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out;
}
</style>
