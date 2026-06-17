<script setup>
import { watch, ref, onMounted, onBeforeUnmount } from "vue";

import { EditorContent, useEditor } from "@tiptap/vue-3";

import StarterKit from "@tiptap/starter-kit";
import Underline from "@tiptap/extension-underline";
import Image from "@tiptap/extension-image";
import { mergeAttributes } from "@tiptap/core";
import Link from "@tiptap/extension-link";
import Highlight from "@tiptap/extension-highlight";
import Placeholder from "@tiptap/extension-placeholder";
import { TextStyle } from "@tiptap/extension-text-style";
import Color from "@tiptap/extension-color";
import TextAlign from "@tiptap/extension-text-align";

import {
    Bold,
    Italic,
    Underline as UnderlineIcon,
    Strikethrough,
    Heading1,
    Heading2,
    Heading3,
    List,
    ListOrdered,
    Quote,
    Link2,
    ImagePlus,
    Undo2,
    Redo2,
    AlignLeft,
    AlignCenter,
    AlignRight,
    AlignJustify,
    Minimize2,
    Maximize2,
} from "lucide-vue-next";

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);

const isCtrlPressed = ref(false);

function handleKeyDown(e) {
    if (e.ctrlKey || e.metaKey) {
        isCtrlPressed.value = true;
    }
}

function handleKeyUp() {
    isCtrlPressed.value = false;
}

onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
    window.addEventListener("keyup", handleKeyUp);
});

onBeforeUnmount(() => {
    window.removeEventListener("keydown", handleKeyDown);
    window.removeEventListener("keyup", handleKeyUp);
});

const showLinkModal = ref(false);
const linkUrl = ref("");

function addImage() {
    const input = document.createElement("input");

    input.type = "file";
    input.accept = "image/*";

    input.onchange = () => {
        const file = input.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = () => {
            editor.value
                ?.chain()
                .focus()
                .setImage({
                    src: reader.result,
                    size: "large",
                })
                .run();
        };

        reader.readAsDataURL(file);
    };

    input.click();
}

const imageMenu = ref({
    show: false,
    top: 0,
    left: 0,
});

function addLink() {
    const previousUrl = editor.value?.getAttributes("link").href || "";

    linkUrl.value = previousUrl;

    showLinkModal.value = true;
}

function saveLink() {
    if (!linkUrl.value) {
        editor.value?.chain().focus().unsetLink().run();

        showLinkModal.value = false;

        return;
    }

    editor.value
        ?.chain()
        .focus()
        .extendMarkRange("link")
        .setLink({
            href: linkUrl.value,
        })
        .run();

    showLinkModal.value = false;
}

function setImageSize(size) {
    if (!editor.value?.isActive("image")) return;

    editor.value
        .chain()
        .focus()
        .updateAttributes("image", {
            size,
        })
        .run();
}

const CustomImage = Image.extend({
    addAttributes() {
        return {
            ...this.parent?.(),

            size: {
                default: "large",

                renderHTML: (attributes) => ({
                    "data-size": attributes.size,
                }),
            },
        };
    },
});

const editor = useEditor({
    content: props.modelValue,

    extensions: [
        StarterKit,
        Underline,
        TextStyle,
        Color,
        Highlight,

        CustomImage.configure({
            allowBase64: true,
        }),

        ,
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                rel: "noopener noreferrer",
            },
        }),

        Placeholder.configure({
            placeholder: "Mulai menulis berita...",
        }),

        TextAlign.configure({
            types: ["heading", "paragraph"],
        }),
    ],

    editorProps: {
        attributes: {
            class: "prose dark:prose-invert max-w-none min-h-[600px] p-8 focus:outline-none",
        },

        handleClick(view, pos, event) {
            const target = event.target;

            // IMAGE CLICK
            if (target.tagName === "IMG") {
                const rect = target.getBoundingClientRect();

                imageMenu.value = {
                    show: true,
                    top: rect.top + window.scrollY - 48,
                    left: rect.left + rect.width / 2,
                };

                return false;
            }

            imageMenu.value.show = false;

            // LINK CLICK
            const link = target.closest("a");

            if (!link) return false;

            if (event.ctrlKey || event.metaKey) {
                window.open(link.href, "_blank");
                return true;
            }

            event.preventDefault();

            return true;
        },
    },

    onUpdate({ editor }) {
        emit("update:modelValue", editor.getHTML());
    },
});

watch(
    () => props.modelValue,
    (value) => {
        if (editor.value && value !== editor.value.getHTML()) {
            editor.value.commands.setContent(value, false);
        }
    },
);
</script>

<template>
    <div class="bg-white dark:bg-slate-900 rounded-lg overflow-hidden">
        <!-- TOOLBAR -->

        <div
            v-if="editor"
            class="sticky top-0 z-20 flex flex-wrap gap-2 p-3 bg-gray-50 dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700"
        >
            <!-- Undo Redo -->

            <button
                @click="editor.chain().focus().undo().run()"
                class="toolbar-btn"
            >
                <Undo2 class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().redo().run()"
                class="toolbar-btn"
            >
                <Redo2 class="w-4 h-4" />
            </button>

            <div class="divider" />

            <!-- Heading -->

            <button
                @click="
                    editor
                        .chain()
                        .focus()
                        .toggleHeading({
                            level: 1,
                        })
                        .run()
                "
                :class="[
                    'toolbar-btn',
                    editor.isActive('heading', { level: 1 })
                        ? 'toolbar-active'
                        : '',
                ]"
            >
                <Heading1 class="w-4 h-4" />
            </button>

            <button
                @click="
                    editor
                        .chain()
                        .focus()
                        .toggleHeading({
                            level: 2,
                        })
                        .run()
                "
                :class="[
                    'toolbar-btn',
                    editor.isActive('heading', { level: 2 })
                        ? 'toolbar-active'
                        : '',
                ]"
            >
                <Heading2 class="w-4 h-4" />
            </button>

            <button
                @click="
                    editor
                        .chain()
                        .focus()
                        .toggleHeading({
                            level: 3,
                        })
                        .run()
                "
                :class="[
                    'toolbar-btn',
                    editor.isActive('heading', { level: 3 })
                        ? 'toolbar-active'
                        : '',
                ]"
            >
                <Heading3 class="w-4 h-4" />
            </button>

            <div class="divider" />

            <!-- Text -->

            <button
                @click="editor.chain().focus().toggleBold().run()"
                :class="[
                    'toolbar-btn',
                    editor.isActive('bold') ? 'toolbar-active' : '',
                ]"
            >
                <Bold class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().toggleItalic().run()"
                :class="[
                    'toolbar-btn',
                    editor.isActive('italic') ? 'toolbar-active' : '',
                ]"
            >
                <Italic class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().toggleUnderline().run()"
                :class="[
                    'toolbar-btn',
                    editor.isActive('underline') ? 'toolbar-active' : '',
                ]"
            >
                <UnderlineIcon class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().toggleStrike().run()"
                :class="[
                    'toolbar-btn',
                    editor.isActive('strike') ? 'toolbar-active' : '',
                ]"
            >
                <Strikethrough class="w-4 h-4" />
            </button>

            <div class="divider" />

            <!-- List -->

            <button
                @click="editor.chain().focus().toggleBulletList().run()"
                class="toolbar-btn"
            >
                <List class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().toggleOrderedList().run()"
                class="toolbar-btn"
            >
                <ListOrdered class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().toggleBlockquote().run()"
                class="toolbar-btn"
            >
                <Quote class="w-4 h-4" />
            </button>

            <div class="divider" />

            <!-- Alignment -->

            <button
                @click="editor.chain().focus().setTextAlign('left').run()"
                class="toolbar-btn"
            >
                <AlignLeft class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().setTextAlign('center').run()"
                class="toolbar-btn"
            >
                <AlignCenter class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().setTextAlign('right').run()"
                class="toolbar-btn"
            >
                <AlignRight class="w-4 h-4" />
            </button>

            <button
                @click="editor.chain().focus().setTextAlign('justify').run()"
                class="toolbar-btn"
            >
                <AlignJustify class="w-4 h-4" />
            </button>

            <div class="divider" />

            <!-- Link -->

            <button @click="addLink" class="toolbar-btn">
                <Link2 class="w-4 h-4" />
            </button>

            <!-- Image -->

            <button @click="addImage" class="toolbar-btn">
                <ImagePlus class="w-4 h-4" />
            </button>
        </div>

        <!-- EDITOR -->

        <EditorContent
            :editor="editor"
            :class="[
                'bg-white dark:bg-slate-900 dark:text-white',
                isCtrlPressed ? 'ctrl-mode' : '',
            ]"
        />

        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="imageMenu.show"
                    class="fixed z-[9999] flex items-center gap-1 px-2 py-1 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-xl"
                    :style="{
                        top: imageMenu.top + 'px',
                        left: imageMenu.left + 'px',
                        transform: 'translateX(-50%)',
                    }"
                >
                    <button
                        @click="setImageSize('small')"
                        class="image-size-btn"
                    >
                        Small
                    </button>

                    <button
                        @click="setImageSize('medium')"
                        class="image-size-btn"
                    >
                        Medium
                    </button>

                    <button
                        @click="setImageSize('large')"
                        class="image-size-btn"
                    >
                        Large
                    </button>
                </div>
            </Transition>
        </Teleport>

        <Teleport to="body">
            <div
                v-if="showLinkModal"
                class="fixed inset-0 z-[999] flex items-center justify-center"
            >
                <!-- Backdrop -->

                <div
                    class="absolute inset-0 bg-black/40"
                    @click="showLinkModal = false"
                />

                <!-- Content -->

                <div
                    class="relative w-full max-w-md rounded-lg bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 shadow-2xl p-6"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        Tambah Link
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Masukkan URL tujuan
                    </p>

                    <input
                        v-model="linkUrl"
                        type="url"
                        placeholder="https://..."
                        class="mt-4 w-full rounded-lg border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    />

                    <div class="flex justify-end gap-3 mt-6">
                        <button
                            @click="showLinkModal = false"
                            class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-slate-800"
                        >
                            Batal
                        </button>

                        <button
                            @click="saveLink"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white"
                        >
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<style scoped>
.toolbar-btn {
    padding: 8px;
    border-radius: 8px;
    color: rgb(75 85 99);
    transition: all 0.2s;
}

.toolbar-btn:hover {
    background: rgb(243 244 246);
}

.dark .toolbar-btn {
    color: rgb(203 213 225);
}

.dark .toolbar-btn:hover {
    background: rgb(30 41 59);
}

.toolbar-active {
    background: rgb(219 234 254);
    color: rgb(37 99 235);
}

.dark .toolbar-active {
    background: rgba(37, 99, 235, 0.25);
    color: rgb(96 165 250);
}

.divider {
    width: 1px;
    background: rgb(229 231 235);
    margin: 0 4px;
}

.dark .divider {
    background: rgb(51 65 85);
}

:deep(.ProseMirror h1) {
    font-size: 2.25rem;
    line-height: 1.2;
    font-weight: 800;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

:deep(.ProseMirror h2) {
    font-size: 1.75rem;
    line-height: 1.3;
    font-weight: 700;
    margin-top: 1.25rem;
    margin-bottom: 0.75rem;
}

:deep(.ProseMirror h3) {
    font-size: 1.35rem;
    line-height: 1.4;
    font-weight: 700;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

:deep(.ProseMirror p) {
    margin: 1rem 0;
}

:deep(.ProseMirror ul) {
    list-style: disc;
    padding-left: 1.5rem;
}

:deep(.ProseMirror ol) {
    list-style: decimal;
    padding-left: 1.5rem;
}

:deep(.ProseMirror blockquote) {
    border-left: 4px solid #2563eb;
    padding-left: 1rem;
    color: #64748b;
    margin: 1rem 0;
}

:deep(.ProseMirror a) {
    color: #2563eb;
    pointer-events: none;
    text-decoration: underline;
    text-decoration-thickness: 1px;
    text-underline-offset: 3px;
    transition: all 0.2s ease;
    cursor: text;
}

:deep(.ProseMirror a:hover) {
    color: #1d4ed8;
}

.dark :deep(.ProseMirror a) {
    color: #60a5fa;
}

.dark :deep(.ProseMirror a:hover) {
    color: #93c5fd;
}

.ctrl-mode :deep(.ProseMirror a) {
    pointer-events: auto;
    cursor: pointer;
}

.ctrl-mode :deep(.ProseMirror a:hover)::after {
    content: "Ctrl + Click untuk membuka";
    position: absolute;
    margin-left: 8px;
    background: #0f172a;
    color: white;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 6px;
    white-space: nowrap;
    z-index: 999;
}

:deep(.ProseMirror img) {
    border-radius: 8px;
    margin: 1.5rem auto;
    max-width: 100%;
}

:deep(.ProseMirror img[data-size="small"]) {
    width: 30%;
    max-width: 300px;
}

:deep(.ProseMirror img[data-size="medium"]) {
    width: 60%;
    max-width: 700px;
}

:deep(.ProseMirror img[data-size="large"]) {
    width: 100%;
}

:deep(.ProseMirror img) {
    display: block;
    border-radius: 12px;
    margin: 1.5rem auto;
    transition: all 0.25s ease;
}

.image-size-btn {
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    transition: 0.2s;
}

.image-size-btn:hover {
    background: rgb(239 246 255);
    color: rgb(37 99 235);
}

.dark .image-size-btn:hover {
    background: rgb(30 41 59);
}

.fade-enter-active,
.fade-leave-active {
    transition: all 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateX(-50%) translateY(8px);
}

:deep(.ProseMirror img.ProseMirror-selectednode) {
    outline: 2px solid #2563eb;
    outline-offset: 3px;
    border-radius: 12px;
    opacity: 0.8;
}
</style>
