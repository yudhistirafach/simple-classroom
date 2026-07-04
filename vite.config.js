import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/main.css",
                "resources/js/app.js",
                "resources/css/auth.css",
            ],
            refresh: true,
        }),
    ],
});
