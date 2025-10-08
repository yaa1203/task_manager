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
                position: relative;
            }
            
            /* PWA Install Prompt - Mobile First Design */
            .pwa-install-prompt {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                padding: 16px;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
                box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.15);
                display: none;
                flex-direction: column;
                gap: 16px;
                z-index: 1000;
                animation: slideUp 0.4s cubic-bezier(0.4, 0.0, 0.2, 1);
                transform: translateY(0);
                transition: transform 0.3s ease-out;
                safe-area-inset-bottom: env(safe-area-inset-bottom);
                padding-bottom: calc(16px + env(safe-area-inset-bottom));
            }
            
            /* Tablet and Desktop */
            @media (min-width: 640px) {
                .pwa-install-prompt {
                    bottom: 20px;
                    left: 50%;
                    right: auto;
                    transform: translateX(-50%);
                    max-width: 420px;
                    width: calc(100% - 40px);
                    border-radius: 12px;
                    padding: 20px;
                    padding-bottom: 20px;
                }
            }
            
            @keyframes slideUp {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            
            @media (min-width: 640px) {
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
            }
            
            .pwa-install-prompt.show {
                display: flex;
            }
            
            .pwa-install-prompt.hide {
                transform: translateY(120%);
            }
            
            @media (min-width: 640px) {
                .pwa-install-prompt.hide {
                    transform: translateX(-50%) translateY(120%);
                }
            }
            
            /* Minimize Button */
            .pwa-minimize-btn {
                position: absolute;
                top: 12px;
                right: 12px;
                width: 28px;
                height: 28px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: transparent;
                border: none;
                cursor: pointer;
                color: #6b7280;
                transition: all 0.2s;
                border-radius: 50%;
                padding: 0;
            }
            
            .pwa-minimize-btn:hover {
                background: #f3f4f6;
                color: #374151;
            }
            
            /* Mini Prompt (Collapsed State) */
            .pwa-mini-prompt {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 56px;
                height: 56px;
                background: #4f46e5;
                border-radius: 50%;
                display: none;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
                z-index: 999;
                transition: all 0.3s ease;
                animation: pulse 2s infinite;
            }
            
            .pwa-mini-prompt:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
            }
            
            .pwa-mini-prompt.show {
                display: flex;
            }
            
            @keyframes pulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
                }
            }
            
            /* Content Wrapper */
            .pwa-content {
                display: flex;
                align-items: flex-start;
                gap: 12px;
            }
            
            .pwa-icon {
                width: 48px;
                height: 48px;
                flex-shrink: 0;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .pwa-text {
                flex: 1;
                margin-right: 20px;
            }
            
            .pwa-title {
                font-size: 16px;
                font-weight: 600;
                color: #111827;
                margin-bottom: 4px;
                line-height: 1.2;
            }
            
            .pwa-description {
                font-size: 14px;
                color: #6b7280;
                line-height: 1.4;
            }
            
            /* Buttons Container */
            .pwa-buttons {
                display: flex;
                gap: 12px;
                width: 100%;
            }
            
            .pwa-btn {
                flex: 1;
                padding: 12px 20px;
                border-radius: 8px;
                font-size: 15px;
                font-weight: 500;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .pwa-btn-primary {
                background: #4f46e5;
                color: white;
            }
            
            .pwa-btn-primary:hover {
                background: #4338ca;
            }
            
            .pwa-btn-primary:active {
                transform: scale(0.98);
            }
            
            .pwa-btn-secondary {
                background: #f3f4f6;
                color: #374151;
            }
            
            .pwa-btn-secondary:hover {
                background: #e5e7eb;
            }
            
            .pwa-btn-secondary:active {
                transform: scale(0.98);
            }
            
            /* Offline Banner */
            .offline-banner {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: linear-gradient(90deg, #fbbf24, #f59e0b);
                color: #78350f;
                padding: 10px 16px;
                text-align: center;
                z-index: 999;
                display: none;
                font-size: 14px;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .offline-banner.show {
                display: block;
                animation: slideDown 0.3s ease-out;
            }
            
            @keyframes slideDown {
                from {
                    transform: translateY(-100%);
                }
                to {
                    transform: translateY(0);
                }
            }
            
            /* Update Banner */
            .update-banner {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: linear-gradient(90deg, #3b82f6, #2563eb);
                color: white;
                padding: 12px 16px;
                z-index: 999;
                display: none;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .update-banner.show {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                animation: slideDown 0.3s ease-out;
            }
            
            .update-btn {
                padding: 6px 16px;
                background: white;
                color: #2563eb;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .update-btn:hover {
                background: #f0f9ff;
                transform: translateY(-1px);
            }
            
            /* Mobile Optimizations */
            @media (max-width: 640px) {
                button, a {
                    min-height: 44px;
                    min-width: 44px;
                    touch-action: manipulation;
                }
                
                /* Adjust main content padding for mobile */
                main {
                    padding-bottom: env(safe-area-inset-bottom, 20px);
                }
            }
            
            /* Smooth scrolling */
            html {
                scroll-behavior: smooth;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            
            /* Focus styles for accessibility */
            .pwa-btn:focus-visible,
            .pwa-minimize-btn:focus-visible,
            .pwa-mini-prompt:focus-visible {
                outline: 2px solid #4f46e5;
                outline-offset: 2px;
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
            <span>🎉 A new version is available!</span>
            <button onclick="window.location.reload()" class="update-btn">
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
        
        <!-- PWA Install Prompt (Expanded) -->
        <div id="pwa-install-prompt" class="pwa-install-prompt">
            <!-- Minimize Button -->
            <button id="pwa-minimize-btn" class="pwa-minimize-btn" aria-label="Minimize">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 7.5L10 12.5L15 7.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            
            <!-- Content -->
            <div class="pwa-content">
                <img src="/icons/logo.png" alt="App Icon" class="pwa-icon">
                <div class="pwa-text">
                    <div class="pwa-title">Install Task App</div>
                    <div class="pwa-description">Get quick access and work offline</div>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="pwa-buttons">
                <button id="pwa-install-btn" class="pwa-btn pwa-btn-primary">
                    Install App
                </button>
                <button id="pwa-later-btn" class="pwa-btn pwa-btn-secondary">
                    Maybe Later
                </button>
            </div>
        </div>
        
        <!-- PWA Mini Prompt (Collapsed) -->
        <div id="pwa-mini-prompt" class="pwa-mini-prompt" aria-label="Install App">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M12 2L12 14M12 14L7 9M12 14L17 9" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 19H19" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
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
            
            // Install Prompt Management
            let deferredPrompt;
            const installPrompt = document.getElementById('pwa-install-prompt');
            const miniPrompt = document.getElementById('pwa-mini-prompt');
            const installBtn = document.getElementById('pwa-install-btn');
            const laterBtn = document.getElementById('pwa-later-btn');
            const minimizeBtn = document.getElementById('pwa-minimize-btn');
            
            // Session storage for temporary dismissal
            const PROMPT_STATE_KEY = 'pwa-prompt-state';
            const PROMPT_LAST_SHOWN_KEY = 'pwa-prompt-last-shown';
            const SHOW_DELAY = 30000; // 30 seconds
            const REMIND_INTERVAL = 24 * 60 * 60 * 1000; // 24 hours
            
            // Get prompt state from session storage
            function getPromptState() {
                const state = sessionStorage.getItem(PROMPT_STATE_KEY);
                return state ? JSON.parse(state) : { minimized: false, dismissed: false };
            }
            
            // Save prompt state to session storage
            function savePromptState(state) {
                sessionStorage.setItem(PROMPT_STATE_KEY, JSON.stringify(state));
            }
            
            // Check if should show prompt
            function shouldShowPrompt() {
                const state = getPromptState();
                const lastShown = localStorage.getItem(PROMPT_LAST_SHOWN_KEY);
                const now = Date.now();
                
                // If dismissed in this session, don't show
                if (state.dismissed) return false;
                
                // If shown recently (within 24 hours), only show mini version
                if (lastShown && (now - parseInt(lastShown)) < REMIND_INTERVAL) {
                    return 'mini';
                }
                
                return true;
            }
            
            // Show install prompt
            function showInstallPrompt() {
                if (!deferredPrompt) return;
                
                const shouldShow = shouldShowPrompt();
                if (!shouldShow) return;
                
                const state = getPromptState();
                
                if (shouldShow === 'mini' || state.minimized) {
                    miniPrompt.classList.add('show');
                } else {
                    installPrompt.classList.add('show');
                    localStorage.setItem(PROMPT_LAST_SHOWN_KEY, Date.now().toString());
                }
            }
            
            // Handle beforeinstallprompt event
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                // Initial delay before showing
                setTimeout(() => showInstallPrompt(), SHOW_DELAY);
                
                // Show on scroll (once)
                let scrolled = false;
                window.addEventListener('scroll', () => {
                    if (!scrolled && window.scrollY > 200) {
                        scrolled = true;
                        showInstallPrompt();
                    }
                }, { passive: true });
                
                // Show on user engagement (after 3 page interactions)
                let interactions = 0;
                const interactionHandler = () => {
                    interactions++;
                    if (interactions >= 3) {
                        showInstallPrompt();
                        document.removeEventListener('click', interactionHandler);
                    }
                };
                document.addEventListener('click', interactionHandler);
            });
            
            // Install button click
            installBtn?.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                
                // Hide prompts
                installPrompt.classList.remove('show');
                miniPrompt.classList.remove('show');
                
                // Show install prompt
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                
                if (outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                    // Clear all storage
                    sessionStorage.removeItem(PROMPT_STATE_KEY);
                    localStorage.removeItem(PROMPT_LAST_SHOWN_KEY);
                } else {
                    // User cancelled, show mini prompt
                    savePromptState({ minimized: true, dismissed: false });
                    setTimeout(() => miniPrompt.classList.add('show'), 1000);
                }
                
                deferredPrompt = null;
            });
            
            // Later button click - minimize to mini prompt
            laterBtn?.addEventListener('click', () => {
                installPrompt.classList.add('hide');
                setTimeout(() => {
                    installPrompt.classList.remove('show', 'hide');
                    miniPrompt.classList.add('show');
                }, 300);
                
                savePromptState({ minimized: true, dismissed: false });
            });
            
            // Minimize button click
            minimizeBtn?.addEventListener('click', () => {
                installPrompt.classList.add('hide');
                setTimeout(() => {
                    installPrompt.classList.remove('show', 'hide');
                    miniPrompt.classList.add('show');
                }, 300);
                
                savePromptState({ minimized: true, dismissed: false });
            });
            
            // Mini prompt click - expand full prompt
            miniPrompt?.addEventListener('click', () => {
                miniPrompt.classList.remove('show');
                installPrompt.classList.add('show');
                savePromptState({ minimized: false, dismissed: false });
            });
            
            // App installed event
            window.addEventListener('appinstalled', () => {
                console.log('PWA was installed');
                installPrompt.classList.remove('show');
                miniPrompt.classList.remove('show');
                deferredPrompt = null;
                
                // Clear storage
                sessionStorage.removeItem(PROMPT_STATE_KEY);
                localStorage.removeItem(PROMPT_LAST_SHOWN_KEY);
            });
            
            // Check if app is already installed
            if (window.matchMedia('(display-mode: standalone)').matches) {
                console.log('App is already installed');
            }
            
            // Offline/Online Detection
            const offlineBanner = document.getElementById('offline-banner');
            
            function updateOnlineStatus() {
                if (navigator.onLine) {
                    offlineBanner.classList.remove('show');
                    
                    // Sync data when back online
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
                    // Ask after user interaction
                    setTimeout(() => {
                        Notification.requestPermission().then(permission => {
                            if (permission === 'granted') {
                                console.log('Notification permission granted');
                                subscribeToPushNotifications();
                            }
                        });
                    }, 5000);
                }
            }
            
            // Push Notification Subscription
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
                    
                    // Note: You'll need to add your VAPID key here
                    console.log('Push notification subscription would be set up with valid VAPID key');
                } catch (error) {
                    console.error('Failed to subscribe to push notifications:', error);
                }
            }
            
            // Request notifications after user interaction
            document.addEventListener('click', () => {
                requestNotificationPermission();
            }, { once: true });
            
            // Handle service worker messages
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.addEventListener('message', (event) => {
                    if (event.data && event.data.type === 'SYNC_COMPLETE') {
                        console.log('Data sync completed:', event.data.message);
                        // Reload analytics if on analytics page
                        if (window.location.pathname.includes('/analytics')) {
                            if (typeof loadAnalytics === 'function') {
                                loadAnalytics();
                            }
                        }
                    }
                });
            }
            
            // Clear cache function
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
            
            // Prevent double-tap zoom on mobile
            let lastTouchEnd = 0;
            document.addEventListener('touchend', (e) => {
                const now = Date.now();
                if (now - lastTouchEnd <= 300) {
                    e.preventDefault();
                }
                lastTouchEnd = now;
            }, { passive: false });
            
            // Handle viewport resize for mobile keyboards
            let viewportHeight = window.innerHeight;
            window.addEventListener('resize', () => {
                const currentHeight = window.innerHeight;
                if (Math.abs(currentHeight - viewportHeight) > 100) {
                    document.documentElement.style.setProperty('--vh', `${currentHeight * 0.01}px`);
                }
            });
        </script>
    </body>
</html>