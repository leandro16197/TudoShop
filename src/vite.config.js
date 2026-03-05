import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: '8a9f-152-170-28-108.ngrok-free.app',
            protocol: 'wss',
        },
        watch: {
            usePolling: true,
        },
    },
    plugins: [
        react(), 
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/frontend/main.jsx' 
            ],
            refresh: true,
        }),
    ],
});
