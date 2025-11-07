<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Primary Meta Tags -->
        <title>{{ config('app.name', 'Laravel') }}</title>
        <meta name="title" content="{{ config('app.name', 'Laravel') }}">
        <meta name="description" content="Aplikasi yang kuat untuk manajemen tugas dan proyek">
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
        
        <!-- Icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/logo72x72.png">
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
        
        <!-- PWA Styles -->
        <style>
            html, body {
                overflow-x: hidden;
                width: 100%;
                position: relative;
            }
            
            /* PWA Install Prompt */
            .pwa-install-prompt {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                padding: 20px;
                padding-bottom: calc(20px + env(safe-area-inset-bottom));
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
                display: none;
                z-index: 9999;
                transform: translateY(100%);
                transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
            }
            
            .pwa-install-prompt.show {
                display: block;
                transform: translateY(0);
            }
            
            @media (min-width: 640px) {
                .pwa-install-prompt {
                    bottom: 24px;
                    left: 50%;
                    right: auto;
                    transform: translateX(-50%) translateY(150%);
                    max-width: 420px;
                    width: calc(100% - 48px);
                    border-radius: 16px;
                    padding: 24px;
                }
                
                .pwa-install-prompt.show {
                    transform: translateX(-50%) translateY(0);
                }
            }
            
            .pwa-close-btn {
                position: absolute;
                top: 12px;
                right: 12px;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: transparent;
                border: none;
                cursor: pointer;
                color: #9ca3af;
                border-radius: 50%;
                transition: all 0.2s;
            }
            
            .pwa-close-btn:hover {
                background: #f3f4f6;
                color: #374151;
            }
            
            .pwa-content {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                margin-bottom: 20px;
                padding-right: 32px;
            }
            
            .pwa-icon {
                width: 64px;
                height: 64px;
                flex-shrink: 0;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
            }
            
            .pwa-icon img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .pwa-text {
                flex: 1;
            }
            
            .pwa-title {
                font-size: 18px;
                font-weight: 700;
                color: #111827;
                margin-bottom: 6px;
            }
            
            .pwa-description {
                font-size: 14px;
                color: #6b7280;
                line-height: 1.5;
            }
            
            .pwa-features {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin-bottom: 20px;
            }
            
            .pwa-feature {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 13px;
                color: #4b5563;
            }
            
            .pwa-feature-icon {
                width: 18px;
                height: 18px;
                flex-shrink: 0;
                color: #4f46e5;
            }
            
            .pwa-buttons {
                display: flex;
                gap: 12px;
            }
            
            .pwa-btn {
                flex: 1;
                padding: 14px 24px;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            
            .pwa-btn-primary {
                background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
                color: white;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            }
            
            .pwa-btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
            }
            
            .pwa-btn-secondary {
                background: #f3f4f6;
                color: #374151;
            }
            
            .pwa-btn-secondary:hover {
                background: #e5e7eb;
            }
            
            /* Mini Floating Button */
            .pwa-mini-prompt {
                position: fixed;
                bottom: 24px;
                right: 24px;
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
                border-radius: 50%;
                display: none;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
                z-index: 9998;
                transition: all 0.3s;
            }
            
            .pwa-mini-prompt.show {
                display: flex;
                animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55), pulse 2s infinite 1s;
            }
            
            .pwa-mini-prompt:hover {
                transform: scale(1.1) rotate(5deg);
            }
            
            @keyframes bounceIn {
                0% { transform: scale(0); opacity: 0; }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); opacity: 1; }
            }
            
            @keyframes pulse {
                0%, 100% { box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4); }
                50% { box-shadow: 0 8px 32px rgba(79, 70, 229, 0.6), 0 0 0 8px rgba(79, 70, 229, 0.1); }
            }
            
            /* Banners */
            .banner {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                padding: 14px 20px;
                text-align: center;
                z-index: 9997;
                display: none;
                font-size: 14px;
                font-weight: 600;
                animation: slideDown 0.3s ease-out;
            }
            
            .banner.show {
                display: block;
            }
            
            .offline-banner {
                background: linear-gradient(135deg, #fbbf24, #f59e0b);
                color: #78350f;
            }
            
            .update-banner {
                background: linear-gradient(135deg, #3b82f6, #2563eb);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }
            
            .update-btn {
                padding: 8px 20px;
                background: white;
                color: #2563eb;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 700;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            @keyframes slideDown {
                from { transform: translateY(-100%); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            
            .pwa-btn:focus-visible,
            .pwa-close-btn:focus-visible,
            .pwa-mini-prompt:focus-visible {
                outline: 3px solid #4f46e5;
                outline-offset: 2px;
            }
            
            @media (max-width: 640px) {
                main {
                    padding-bottom: env(safe-area-inset-bottom, 20px);
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Offline Banner -->
        <div id="offline-banner" class="banner offline-banner">
            ⚠️ Anda sedang offline. Beberapa fitur mungkin terbatas.
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
            <main class="pb-5 sm:pb-0">
                {{ $slot }}
            </main>
        </div>
        
        <!-- PWA Install Prompt -->
        <div id="pwa-install-prompt" class="pwa-install-prompt">
            <div class="pwa-content">
                <div class="pwa-icon">
                    <img src="/icons/logo72x72.png" alt="App Icon">
                </div>
                <div class="pwa-text">
                    <div class="pwa-title">Instal Aplikasi Kami</div>
                    <div class="pwa-description">Akses lebih cepat dan mudah dari home screen Anda</div>
                </div>
            </div>
            
            <div class="pwa-features">
                <div class="pwa-feature">
                    <svg class="pwa-feature-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Notifikasi push real-time</span>
                </div>
                <div class="pwa-feature">
                    <svg class="pwa-feature-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Pengalaman seperti aplikasi native</span>
                </div>
            </div>
            
            <div class="pwa-buttons">
                <button id="pwa-install-btn" class="pwa-btn pwa-btn-primary">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 3V13M10 13L6 9M10 13L14 9" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 17H16" stroke-linecap="round"/>
                    </svg>
                    Instal Sekarang
                </button>
                <button id="pwa-later-btn" class="pwa-btn pwa-btn-secondary">
                    Nanti
                </button>
            </div>
        </div>
        
        <!-- PWA Mini Prompt -->
        <div id="pwa-mini-prompt" class="pwa-mini-prompt" role="button" tabindex="0" aria-label="Instal Aplikasi">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                <path d="M12 3V15M12 15L7 10M12 15L17 10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 20H19" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        
        <script>
            // PWA Implementation - User Version (Optimized)
            (function() {
                'use strict';
                
                const CONFIG = {
                    SHOW_DELAY: 3000,
                    STORAGE_KEY: 'pwa-full-prompt-shown-user' // Unik per aplikasi/user
                };
                
                let deferredPrompt = null;
                
                const elements = {
                    fullPrompt: document.getElementById('pwa-install-prompt'),
                    miniPrompt: document.getElementById('pwa-mini-prompt'),
                    installBtn: document.getElementById('pwa-install-btn'),
                    laterBtn: document.getElementById('pwa-later-btn'),
                    offlineBanner: document.getElementById('offline-banner'),
                };
                
                const storage = {
                    get() {
                        try {
                            const val = localStorage.getItem(CONFIG.STORAGE_KEY);
                            return val === 'true';
                        } catch (e) { return false; }
                    },
                    set(value) {
                        try { localStorage.setItem(CONFIG.STORAGE_KEY, value); } catch (e) {}
                    },
                    remove() {
                        try { localStorage.removeItem(CONFIG.STORAGE_KEY); } catch (e) {}
                    }
                };
                
                // Cek apakah full prompt sudah pernah ditampilkan
                function shouldShowFullPrompt() {
                    if (storage.get()) return false;
                    if (window.matchMedia('(display-mode: standalone)').matches) return false;
                    return true;
                }
                
                function markFullPromptShown() {
                    storage.set('true');
                }
                
                function showFullPrompt() {
                    if (!deferredPrompt) return;
                    elements.miniPrompt.classList.remove('show');
                    elements.fullPrompt.classList.add('show');
                    markFullPromptShown();
                }
                
                function showMiniPrompt() {
                    if (!deferredPrompt) return;
                    elements.fullPrompt.classList.remove('show');
                    setTimeout(() => elements.miniPrompt.classList.add('show'), 300);
                }
                
                function hideAllPrompts() {
                    elements.fullPrompt.classList.remove('show');
                    elements.miniPrompt.classList.remove('show');
                }
                
                async function handleInstall() {
                    if (!deferredPrompt) return;
                    hideAllPrompts();
                    
                    try {
                        const { outcome } = await deferredPrompt.prompt();
                        if (outcome === 'accepted') {
                            storage.remove();
                        }
                        deferredPrompt = null;
                    } catch (e) {
                        console.error(e);
                    }
                }
                
                // Event Listeners
                elements.installBtn?.addEventListener('click', handleInstall);
                
                elements.laterBtn?.addEventListener('click', () => {
                    hideAllPrompts();
                    showMiniPrompt();
                });
                
                elements.miniPrompt?.addEventListener('click', () => {
                    elements.miniPrompt.classList.remove('show');
                    elements.fullPrompt.classList.add('show');
                });
                
                // beforeinstallprompt
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    deferredPrompt = e;
                    
                    setTimeout(() => {
                        if (shouldShowFullPrompt()) {
                            showFullPrompt();
                        } else {
                            showMiniPrompt();
                        }
                    }, CONFIG.SHOW_DELAY);
                });
                
                // appinstalled
                window.addEventListener('appinstalled', () => {
                    hideAllPrompts();
                    storage.remove();
                });
                
                // Reset saat logout (jika pakai form POST)
                document.addEventListener('DOMContentLoaded', () => {
                    const logoutForm = document.querySelector('form[action*="logout"]');
                    if (logoutForm) {
                        logoutForm.addEventListener('submit', () => {
                            storage.remove();
                        });
                    }
                });
                
                // Online/Offline
                function updateOnlineStatus() {
                    elements.offlineBanner?.classList.toggle('show', !navigator.onLine);
                }
                window.addEventListener('online', updateOnlineStatus);
                window.addEventListener('offline', updateOnlineStatus);
                updateOnlineStatus();
                
                console.log('PWA User: Full prompt hanya sekali per login');
            })();
        </script>
        <script>
            // Reset PWA prompt saat logout (untuk link)
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('a[href*="logout"]').forEach(link => {
                    link.addEventListener('click', () => {
                        try {
                            localStorage.removeItem('pwa-full-prompt-shown-user');
                        } catch (e) {}
                    });
                });
            });
        </script>
    </body>
</html>