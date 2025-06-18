import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
// 1. Import the Tailwind CSS plugin for Vite
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        // 2. Add the plugin to your Vite configuration
        tailwindcss(),
    ],
});
