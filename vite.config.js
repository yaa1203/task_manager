import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
      server: {
    host: '0.0.0.0',   // biar bisa diakses dari device lain
    port: 5173,
    strictPort: true,
    allowedHosts: [
      '.ngrok-free.app', // izinkan semua host ngrok
    ],
  },
});
