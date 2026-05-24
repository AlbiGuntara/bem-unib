<script setup>
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { ChevronDown } from "lucide-vue-next";

const props = defineProps({
    label: String,
    icon: [Function, Object],
    items: Array,
    activeMenu: String,
    collapsed: Boolean,
    isDesktop: Boolean,
});

const emit = defineEmits(["select"]);

const open = ref(false);

const isActive = computed(() => {
    return props.items.some((i) => i.name === props.activeMenu);
});

watch(
    () => isActive.value,
    (val) => {
        if (val && (!props.isDesktop || !props.collapsed)) {
            open.value = true;
        }
    },
    { immediate: true },
);

function toggle() {
    if (props.isDesktop && props.collapsed) {
        emit("select", props.items[0]);
        return;
    }
    open.value = !open.value;
}

function handleClick(item) {
    emit("select", item);
    router.visit(item.link);
    open.value = false;
}
</script>

<template>
    <div class="relative">
        <!-- Dropdown Button -->
        <button
            @click="toggle"
            class="group flex items-center gap-4 px-4 py-3 rounded-xl w-full transition-all duration-300 cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-800"
        >
            <!-- Icon -->
            <component
                :is="icon"
                class="w-5 h-5 transition-all duration-300 text-gray-500 dark:text-gray-400 group-hover:scale-110"
            />

            <!-- Label -->
            <span
                v-if="!isDesktop || !collapsed"
                class="text-sm font-medium text-left transition-colors duration-300 text-blue-600 dark:text-blue-400 text-gray-700 dark:text-gray-200"
            >
                {{ label }}
            </span>

            <!-- Chevron -->
            <ChevronDown
                v-if="!isDesktop || !collapsed"
                :class="[
                    'w-4 h-4 ml-auto transition-all duration-300',
                    open ? 'rotate-180' : '',
                    isActive
                        ? 'text-blue-600 dark:text-blue-400'
                        : 'text-gray-500 dark:text-gray-400',
                ]"
            />

            <!-- Tooltip (collapsed desktop) -->
            <div
                v-if="isDesktop && collapsed"
                class="absolute left-full ml-4 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg text-gray-700 dark:text-gray-200 text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 pointer-events-none z-50 whitespace-nowrap"
            >
                {{ label }}

                <div
                    class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-white dark:border-r-gray-800"
                />
            </div>
        </button>

        <!-- Dropdown Content -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-if="open && (!isDesktop || !collapsed)"
                :class="[
                    'ml-6 mt-2 pl-3 space-y-1 border-l-2',
                    isActive
                        ? 'border-blue-500'
                        : 'border-gray-300 dark:border-gray-700',
                ]"
            >
                <div
                    v-for="item in items"
                    :key="item.name"
                    @click="handleClick(item)"
                    :class="[
                        'group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm cursor-pointer transition-all duration-200',
                        activeMenu === item.name
                            ? 'text-blue-600 dark:text-blue-400 font-semibold bg-blue-50 dark:bg-blue-900/20'
                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800',
                    ]"
                >
                    <component
                        :is="item.icon"
                        v-if="item.icon"
                        :class="[
                            'w-4 h-4 transition-colors duration-200',
                            activeMenu === item.name
                                ? 'text-blue-600 dark:text-blue-400'
                                : 'text-gray-400 dark:text-gray-500',
                        ]"
                    />

                    <span>
                        {{ item.name }}
                    </span>
                </div>
            </div>
        </Transition>
    </div>
</template>
