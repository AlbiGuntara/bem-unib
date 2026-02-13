import { ref, onMounted, watch } from "vue";

const theme = ref("system");

function applyTheme() {
    const root = document.documentElement;

    if (theme.value === "light") {
        root.classList.remove("dark");
    } else if (theme.value === "dark") {
        root.classList.add("dark");
    } else {
        // system
        const prefersDark = window.matchMedia(
            "(prefers-color-scheme: dark)"
        ).matches;
        root.classList.toggle("dark", prefersDark);
    }
}

export function useTheme() {
    onMounted(() => {
        theme.value = localStorage.getItem("theme") || "system";
        applyTheme();

        // dengarkan perubahan sistem kalau mode system
        window
            .matchMedia("(prefers-color-scheme: dark)")
            .addEventListener("change", () => {
                if (theme.value === "system") {
                    applyTheme();
                }
            });
    });

    watch(theme, (newTheme) => {
        localStorage.setItem("theme", newTheme);
        applyTheme();
    });

    return { theme };
}
