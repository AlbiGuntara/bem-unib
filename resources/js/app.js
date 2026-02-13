import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

// Ziggy
import { ZiggyVue } from "ziggy-js";
import { Ziggy } from "./ziggy";

// AOS
import AOS from "aos";
import "aos/dist/aos.css";

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy);

        app.mount(el);

        // AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
        });
    },
});
