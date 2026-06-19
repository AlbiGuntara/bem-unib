import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import path from "path";
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes("node_modules/@fullcalendar"))
                        return "vendor-fullcalendar";
                    if (id.includes("node_modules/@tiptap"))
                        return "vendor-tiptap";
                    if (
                        id.includes("node_modules/chart.js") ||
                        id.includes("node_modules/vue-chartjs")
                    )
                        return "vendor-chartjs";
                    if (id.includes("node_modules")) return "vendor";
                },
            },
        },
    },
});
