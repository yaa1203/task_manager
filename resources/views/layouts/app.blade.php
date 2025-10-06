<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Primary Meta Tags -->
        <title>{{ config('app.name', 'Laravel') }}</title>
        <meta name="title" content="{{ config('app.name', 'Laravel') }}">
        <meta name="description" content="A powerful task and project management application">
        <meta name="author" content="{{ config('app.name') }}">
        
        <!-- PWA Meta Tags -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f46e5">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Laravel') }}">
        <meta name="application-name" content="{{ config('app.name', 'Laravel') }}">
        <meta name="msapplication-TileColor" content="#4f46e5">
        <meta name="msapplication-TileImage" content="/icons/logo.png">
        <meta name="msapplication-config" content="/browserconfig.xml">
        
        <!-- iOS Splash Screens -->
        <link rel="apple-touch-startup-image" href="/splash/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
        <link rel="apple-touch-startup-image" href="/splash/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)">
        <link rel="apple-touch-startup-image" href="/splash/splash-1242x2208.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)">
        <link rel="apple-touch-startup-image" href="/splash/splash-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)">
        <link rel="apple-touch-startup-image" href="/splash/splash-1536x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)">
        <link rel="apple-touch-startup-image" href="/splash/splash-1668x2224.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)">
        <link rel="apple-touch-startup-image" href="/splash/splash-2048x2732.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)">
        
        <!-- Icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/logo.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icons/logo.png">
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/logo.png">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- PWA Install Prompt Styles -->
        <style>
            /* Prevent horizontal scroll on mobile */
            html, body {
                overflow-x: hidden;
                width: 100%;
            }
            
            /* PWA Install Prompt */
            .pwa-install-prompt {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                display: none;
                flex-direction: column;
                gap: 12px;
                z-index: 1000;
                max-width: 90%;
                animation: slideUp 0.3s ease-out;
            }
            
            @media (min-width: 640px) {
                .pwa-install-prompt {
                    flex-direction: row;
                    align-items: center;
                }
            }
            
            @keyframes slideUp {
                from {
                    transform: translateX(-50%) translateY(100px);
                    opacity: 0;
                }
                to {
                    transform: translateX(-50%) translateY(0);
                    opacity: 1;
                }
            }
            
            .pwa-install-prompt.show {
                display: flex;
            }
            
            /* Offline Banner */
            .offline-banner {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #fbbf24;
                color: #78350f;
                padding: 8px;
                text-align: center;
                z-index: 999;
                display: none;
                font-size: 14px;
            }
            
            .offline-banner.show {
                display: block;
            }
            
            /* Update Banner */
            .update-banner {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #3b82f6;
                color: white;
                padding: 12px;
                text-align: center;
                z-index: 999;
                display: none;
            }
            
            .update-banner.show {
                display: block;
            }
            
            /* Improve touch targets for mobile */
            @media (max-width: 640px) {
                button, a {
                    min-height: 44px;
                    min-width: 44px;
                }
            }
            
            /* Smooth scrolling */
            html {
                scroll-behavior: smooth;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Offline Banner -->
        <div id="offline-banner" class="offline-banner">
            <span>⚠️ You are currently offline. Some features may be limited.</span>
        </div>
        
        <!-- Update Banner -->
        <div id="update-banner" class="update-banner">
            <span>A new version is available!</span>
            <button onclick="window.location.reload()" class="ml-4 px-3 py-1 bg-white text-blue-600 rounded text-sm font-semibold hover:bg-gray-100 transition">
                Update Now
            </button>
        </div>
        
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            
            <!-- Page Content -->
            <main class="pb-20 sm:pb-0">
                {{ $slot }}
            </main>
        </div>
        
        <!-- PWA Install Prompt -->
        <div id="pwa-install-prompt" class="pwa-install-prompt">
            <div class="flex items-center gap-3">
                <img src="/icons/logo.png" alt="App Icon" class="w-12 h-12 flex-shrink-0">
                <div>
                    <p class="font-semibold">Install Task App</p>
                    <p class="text-sm text-gray-600">Install our app for a better experience</p>
                </div>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button id="pwa-install-btn" class="flex-1 sm:flex-none px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    Install
                </button>
                <button id="pwa-dismiss-btn" class="flex-1 sm:flex-none px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition">
                    Later
                </button>
            </div>
        </div>
        
        <!-- PWA Scripts -->
        <script>
            let serviceWorkerRegistration = null;
            
            // Register Service Worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js')
                        .then(registration => {
                            console.log('ServiceWorker registration successful:', registration.scope);
                            serviceWorkerRegistration = registration;
                            
                            // Check for updates
                            registration.addEventListener('updatefound', () => {
                                const newWorker = registration.installing;
                                newWorker.addEventListener('statechange', () => {
                                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                        document.getElementById('update-banner').classList.add('show');
                                    }
                                });
                            });
                        })
                        .catch(err => {
                            console.log('ServiceWorker registration failed:', err);
                        });
                });
            }
            
            // Install Prompt
            let deferredPrompt;
            const installPrompt = document.getElementById('pwa-install-prompt');
            const installBtn = document.getElementById('pwa-install-btn');
            const dismissBtn = document.getElementById('pwa-dismiss-btn');
            
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                setTimeout(() => showInstallPrompt(), 30000);
                
                let scrolled = false;
                window.addEventListener('scroll', () => {
                    if (!scrolled && window.scrollY > 100) {
                        scrolled = true;
                        showInstallPrompt();
                    }
                }, { passive: true });
            });
            
            function showInstallPrompt() {
                if (deferredPrompt && !localStorage.getItem('pwa-install-dismissed')) {
                    installPrompt.classList.add('show');
                }
            }
            
            installBtn?.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                
                if (outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                }
                
                deferredPrompt = null;
                installPrompt.classList.remove('show');
            });
            
            dismissBtn?.addEventListener('click', () => {
                localStorage.setItem('pwa-install-dismissed', 'true');
                installPrompt.classList.remove('show');
                
                setTimeout(() => {
                    localStorage.removeItem('pwa-install-dismissed');
                }, 7 * 24 * 60 * 60 * 1000);
            });
            
            window.addEventListener('appinstalled', () => {
                console.log('PWA was installed');
                installPrompt.classList.remove('show');
                deferredPrompt = null;
            });
            
            // Offline/Online Detection
            const offlineBanner = document.getElementById('offline-banner');
            
            function updateOnlineStatus() {
                if (navigator.onLine) {
                    offlineBanner.classList.remove('show');
                    
                    if (serviceWorkerRegistration && 'sync' in serviceWorkerRegistration) {
                        serviceWorkerRegistration.sync.register('sync-data')
                            .then(() => console.log('Sync registered'))
                            .catch(err => console.log('Sync registration failed:', err));
                    }
                } else {
                    offlineBanner.classList.add('show');
                }
            }
            
            window.addEventListener('online', updateOnlineStatus);
            window.addEventListener('offline', updateOnlineStatus);
            updateOnlineStatus();
            
            // Request Notification Permission
            function requestNotificationPermission() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            console.log('Notification permission granted');
                            subscribeToPushNotifications();
                        }
                    });
                }
            }
            
            async function subscribeToPushNotifications() {
                if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                    return;
                }
                
                try {
                    const registration = await navigator.serviceWorker.ready;
                    const existingSubscription = await registration.pushManager.getSubscription();
                    if (existingSubscription) {
                        console.log('Already subscribed to push notifications');
                        return;
                    }
                    
                    console.log('Push notification subscription would be set up with valid VAPID key');
                } catch (error) {
                    console.error('Failed to subscribe to push notifications:', error);
                }
            }
            
            document.addEventListener('click', () => {
                requestNotificationPermission();
            }, { once: true });
            
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.addEventListener('message', (event) => {
                    if (event.data && event.data.type === 'SYNC_COMPLETE') {
                        console.log('Data sync completed:', event.data.message);
                        if (window.location.pathname.includes('/analytics')) {
                            if (typeof loadAnalytics === 'function') {
                                loadAnalytics();
                            }
                        }
                    }
                });
            }
            
            window.clearAppCache = function() {
                if ('caches' in window) {
                    caches.keys().then(names => {
                        names.forEach(name => {
                            caches.delete(name);
                            console.log('Cleared cache:', name);
                        });
                    }).then(() => {
                        console.log('All caches cleared');
                        if (serviceWorkerRegistration) {
                            serviceWorkerRegistration.unregister().then(() => {
                                console.log('Service worker unregistered');
                                window.location.reload();
                            });
                        } else {
                            window.location.reload();
                        }
                    });
                }
            };
            
            // Prevent double-tap zoom on buttons
            let lastTouchEnd = 0;
            document.addEventListener('touchend', (e) => {
                const now = Date.now();
                if (now - lastTouchEnd <= 300) {
                    e.preventDefault();
                }
                lastTouchEnd = now;
            }, { passive: false });
        </script>
    </body>
</html>