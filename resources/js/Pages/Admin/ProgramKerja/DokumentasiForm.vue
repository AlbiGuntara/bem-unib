<template>
    <div
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div
            class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-lg relative"
        >
            <button @click="$emit('close')" class="absolute top-4 right-4">
                <X class="w-6 h-6 text-gray-500 hover:text-gray-900" />
            </button>

            <h3 class="text-lg font-bold mb-4">Tambah Dokumentasi</h3>

            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Foto</label>
                    <input
                        type="file"
                        @change="handleFile"
                        class="w-full border px-3 py-2 rounded-xl"
                    />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1"
                        >Nama Kegiatan</label
                    >
                    <select
                        v-model="form.id_program_kerja"
                        class="w-full px-3 py-2 border rounded-xl bg-white dark:bg-gray-800"
                    >
                        <option value="">— Pilih Kegiatan —</option>
                        <option
                            v-for="program in programKerjas"
                            :key="program.id"
                            :value="program.id"
                        >
                            {{ program.nama_kegiatan }}
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Link</label>
                    <input
                        v-model="form.link"
                        type="text"
                        class="w-full border px-3 py-2 rounded-xl"
                        placeholder="https://..."
                    />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1"
                        >Deskripsi</label
                    >
                    <textarea
                        v-model="form.deskripsi"
                        rows="3"
                        class="w-full border px-3 py-2 rounded-xl"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-xl shadow hover:shadow-lg transition-all"
                >
                    <Plus class="w-5 h-5" />
                    Tambah
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { X, Plus } from "lucide-vue-next";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    programKerjaId: {
        type: Number,
        required: true,
    },
    programKerjas: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    id_program_kerja: props.programKerjaId,
    foto: null,
    link: "",
    deskripsi: "",
});

function handleFile(event) {
    form.foto = event.target.files[0];
}

function submit() {
    form.post(route("dokumentasi.store"), {
        onFinish: () => {
            form.reset();
            emit("close");
        },
    });
}
</script>
