<template>
    <teleport to="body">
        <!-- BACKDROP -->
        <Transition
            enter-active-class="transition-all duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-all duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="open"
                class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40"
                @click="$emit('close')"
            ></div>
        </Transition>

        <!-- MODAL -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 scale-95 translate-y-3"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-3"
        >
            <div
                v-if="open"
                class="fixed inset-0 flex items-center justify-center z-50 p-4"
            >
                <div
                    @click.stop
                    class="relative w-full max-w-lg rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden"
                >
                    <!-- HEADER -->
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-100 to-transparent dark:from-green-900/20"
                    >
                        <div class="flex items-center justify-between">
                            <h2
                                class="text-xl font-bold text-gray-900 dark:text-gray-100"
                            >
                                {{ title }}
                            </h2>

                            <button
                                @click="$emit('close')"
                                class="p-2 text-gray-400 hover:text-green-600 hover:bg-white dark:hover:bg-gray-800 rounded-xl transition-all duration-200 transform hover:scale-110"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- OPTIONAL SUBTITLE -->
                        <p
                            v-if="subtitle"
                            class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                        >
                            {{ subtitle }}
                        </p>
                    </div>

                    <!-- CONTENT -->
                    <div class="p-6">
                        <slot />
                    </div>

                    <!-- FOOTER (optional) -->
                    <div
                        v-if="$slots.footer"
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3"
                    >
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </Transition>
    </teleport>
</template>

<script setup>
defineProps({
    open: Boolean,
    title: {
        type: String,
        default: "Modal",
    },
    subtitle: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["close"]);
</script>
