<script setup>
import AppLayout from "@/Layouts/Admin/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    ArrowLeft,
    Mail,
    MailOpen,
    ReplyAll,
    Trash2,
    Download,
    Paperclip,
    Send,
    Clock,
    User,
    AtSign,
    Tag,
    MessageSquare,
} from "lucide-vue-next";
import Swal from "sweetalert2";

const props = defineProps({
    message: Object,
});

const replyForm = ref({
    reply_content: "",
});

const submitting = ref(false);

const categoryBadge = (cat) => {
    const map = {
        saran: "text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900/30",
        kritik: "text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900/30",
        aspirasi:
            "text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900/30",
        aduan: "text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900/30",
    };
    return map[cat] || "";
};

const statusBadge = (s) => {
    const map = {
        unread: "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
        read: "bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400",
        replied:
            "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
    };
    return map[s] || "";
};

function formatDate(date) {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function submitReply() {
    if (!replyForm.value.reply_content.trim()) return;
    submitting.value = true;
    router.post(
        route("messages.reply", props.message.id),
        {
            reply_content: replyForm.value.reply_content,
        },
        {
            preserveScroll: true,
            onFinish: () => {
                submitting.value = false;
                replyForm.value.reply_content = "";
            },
        },
    );
}

function updateStatus(newStatus) {
    router.patch(
        route("messages.update-status", props.message.id),
        { status: newStatus },
        { preserveScroll: true },
    );
}

function deleteMessage() {
    const isDark = document.documentElement.classList.contains("dark");
    Swal.fire({
        title: "Hapus pesan ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc2626",
        cancelButtonColor: isDark ? "#374151" : "#6b7280",
        background: isDark ? "#0f172a" : "#ffffff",
        color: isDark ? "#f1f5f9" : "#111827",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("messages.destroy", props.message.id), {
                onSuccess: () => router.visit(route("messages.index")),
            });
        }
    });
}
</script>

<template>
    <AppLayout>
        <Head :title="message.subject" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Back Button -->
            <Link
                :href="route('messages.index')"
                class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition"
            >
                <ArrowLeft class="w-4 h-4" />
                Kembali ke daftar pesan
            </Link>

            <!-- Message Detail -->
            <div
                class="rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900 overflow-hidden"
            >
                <!-- Header -->
                <div
                    class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 dark:border-slate-700"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-white">
                                {{ message.subject }}
                            </h1>
                            <div class="flex items-center gap-2 mt-2">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="categoryBadge(message.category)"
                                >
                                    {{ message.category }}
                                </span>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="statusBadge(message.status)"
                                >
                                    {{ message.status }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                @click="updateStatus('read')"
                                v-if="message.status === 'unread'"
                                class="p-2 rounded-lg bg-white/20 text-white hover:bg-white/30 transition"
                                title="Tandai dibaca"
                            >
                                <MailOpen class="w-4 h-4" />
                            </button>
                            <button
                                @click="deleteMessage"
                                class="p-2 rounded-lg bg-white/20 text-white hover:bg-white/30 transition"
                                title="Hapus"
                            >
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sender Info -->
                <div
                    class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-2 text-sm">
                            <User class="w-4 h-4 text-slate-400" />
                            <span class="text-slate-500 dark:text-slate-400"
                                >Nama:</span
                            >
                            <span
                                class="font-medium text-slate-800 dark:text-white"
                                >{{ message.name }}</span
                            >
                        </div>
                        <div
                            class="flex items-center gap-2 text-sm"
                            v-if="message.nim"
                        >
                            <Tag class="w-4 h-4 text-slate-400" />
                            <span class="text-slate-500 dark:text-slate-400"
                                >NIM:</span
                            >
                            <span
                                class="font-medium text-slate-800 dark:text-white"
                                >{{ message.nim }}</span
                            >
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <AtSign class="w-4 h-4 text-slate-400" />
                            <span class="text-slate-500 dark:text-slate-400"
                                >Email:</span
                            >
                            <span
                                class="font-medium text-slate-800 dark:text-white"
                                >{{ message.email }}</span
                            >
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <Clock class="w-4 h-4 text-slate-400" />
                            <span class="text-slate-500 dark:text-slate-400"
                                >Dikirim:</span
                            >
                            <span
                                class="font-medium text-slate-800 dark:text-white"
                                >{{ formatDate(message.created_at) }}</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-6">
                    <h3
                        class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3"
                    >
                        Isi Pesan
                    </h3>
                    <p
                        class="text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap"
                    >
                        {{ message.content }}
                    </p>

                    <!-- Attachment -->
                    <div v-if="message.attachment" class="mt-6">
                        <a
                            :href="`/storage/${message.attachment}`"
                            target="_blank"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 transition"
                        >
                            <Paperclip class="w-4 h-4" />
                            <span>Lihat Lampiran</span>
                            <Download class="w-4 h-4 ml-2" />
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reply Section -->
            <div
                class="rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
            >
                <div
                    class="border-b border-slate-200 px-6 py-4 dark:border-slate-700"
                >
                    <h3 class="font-semibold text-slate-800 dark:text-white">
                        Balas Pesan
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Balasan akan dikirim ke email pengirim.
                    </p>
                </div>
                <div class="p-6">
                    <textarea
                        v-model="replyForm.reply_content"
                        rows="5"
                        placeholder="Tulis balasan Anda..."
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 transition focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-600/10 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                    >
                    </textarea>
                    <div class="mt-4 flex justify-end">
                        <button
                            @click="submitReply"
                            :disabled="
                                submitting || !replyForm.reply_content.trim()
                            "
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <Send class="w-4 h-4" />
                            <span v-if="submitting">Mengirim...</span>
                            <span v-else>Kirim Balasan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Previous Reply -->
            <div
                v-if="message.reply_content"
                class="rounded-lg border border-green-200 bg-green-50 shadow-sm dark:border-green-800 dark:bg-green-900/20"
            >
                <div
                    class="px-6 py-4 border-b border-green-200 dark:border-green-800"
                >
                    <div class="flex items-center gap-2">
                        <ReplyAll
                            class="w-4 h-4 text-green-600 dark:text-green-400"
                        />
                        <h3
                            class="font-semibold text-green-800 dark:text-green-400"
                        >
                            Balasan Terkirim
                        </h3>
                    </div>
                    <p class="text-xs text-green-600 dark:text-green-500 mt-1">
                        Dibalas pada {{ formatDate(message.replied_at) }}
                    </p>
                </div>
                <div class="px-6 py-4">
                    <p
                        class="text-sm text-green-800 dark:text-green-300 leading-relaxed whitespace-pre-wrap"
                    >
                        {{ message.reply_content }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
