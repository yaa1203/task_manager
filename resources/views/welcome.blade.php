<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <title>Taskly - Kelola Tugas Anda dengan Mudah</title>
    <meta name="description" content="Aplikasi manajemen tugas dan proyek yang powerful untuk meningkatkan produktivitas Anda">
    
    <!-- PWA Meta -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="/icons/logo.png">
    
    <!-- Fonts & Styles -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { font-family: 'Figtree', sans-serif; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .float-anim { animation: float 6s ease-in-out infinite; }
        .gradient-text { background: linear-gradient(135deg, #3b82f6, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .pwa-prompt {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            border-radius: 1rem 1rem 0 0;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.15);
            display: none;
            z-index: 1000;
            animation: slideUp 0.4s ease;
        }
        
        .pwa-prompt.show { display: flex; flex-direction: column; gap: 1rem; }
        .pwa-prompt.hide { transform: translateY(120%); }
        
        .pwa-mini {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            background: #3b82f6;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(59,130,246,0.4);
            z-index: 999;
            animation: pulse 2s infinite;
        }
        
        .pwa-mini.show { display: flex; }
        
        .banner {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 0.75rem 1rem;
            text-align: center;
            z-index: 999;
            display: none;
            animation: slideDown 0.3s ease;
        }
        
        .banner.show { display: block; }
        
        @media (min-width: 640px) {
            .pwa-prompt {
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                max-width: 420px;
                border-radius: 12px;
            }
            
            .pwa-prompt.hide { transform: translateX(-50%) translateY(120%); }
        }
        
        @media (max-width: 640px) {
            html, body { overflow-x: hidden; }
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <!-- Offline Banner -->
    <div id="offline-banner" class="banner bg-gradient-to-r from-yellow-400 to-orange-400 text-yellow-900 font-medium text-sm">
        ⚠️ Anda sedang offline. Beberapa fitur mungkin terbatas.
    </div>
    
    <!-- Update Banner -->
    <div id="update-banner" class="banner bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="flex items-center justify-center gap-3">
            <span>🎉 Versi baru tersedia!</span>
            <button onclick="location.reload()" class="px-4 py-1 bg-white text-blue-600 rounded-md text-sm font-semibold hover:bg-gray-100 transition">
                Perbarui
            </button>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <span class="bg-blue-600 text-white rounded-lg w-10 h-10 flex items-center justify-center font-bold text-xl">T</span>
                    <span class="font-bold text-xl gradient-text">Taskly</span>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden sm:flex items-center gap-6">
                    <a href="#" class="text-blue-600 font-medium hover:underline">Masuk</a>
                    <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                        Daftar Gratis
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="menu-btn" class="sm:hidden p-2 text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden sm:hidden pb-3 space-y-2">
                <a href="#" class="block text-blue-600 font-medium py-2">Masuk</a>
                <a href="#" class="block bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-center">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-12 sm:py-16 lg:py-20">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-purple-50 opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                        Kelola Tugas. <span class="gradient-text">Selesaikan Lebih Cepat.</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 mb-6 max-w-lg mx-auto lg:mx-0">
                        Atur prioritas, delegasikan, dan raih fokus — semua dalam satu aplikasi ringan yang dirancang untuk produktivitas Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                        <a href="#" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold text-lg transition transform hover:scale-105">
                            <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center bg-white hover:bg-gray-50 text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-xl font-semibold text-lg transition">
                            Lihat Fitur
                        </a>
                    </div>
                </div>
                
                <!-- Hero Image -->
                <div class="flex justify-center">
                    <div class="float-anim">
                        <img 
                            src="/icons/logo.png" 
                            alt="Taskly Dashboard Preview" 
                            class="w-64 sm:w-80 lg:w-96 rounded-2xl shadow-2xl object-cover"
                            onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 400%22%3E%3Cdefs%3E%3ClinearGradient id=%22grad%22 x1=%220%25%22 y1=%220%25%22 x2=%22100%25%22 y2=%22100%25%22%3E%3Cstop offset=%220%25%22 style=%22stop-color:%2360a5fa%22/%3E%3Cstop offset=%22100%25%22 style=%22stop-color:%23a78bfa%22/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width=%22400%22 height=%22400%22 fill=%22url(%23grad)%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-size=%2280%22 fill=%22white%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 font-family=%22Arial%22%3E%E2%9C%93%3C/text%3E%3C/svg%3E';"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold mb-3">Fitur Unggulan Taskly</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Dibuat khusus untuk membantu Anda mengelola tugas dengan efisien
                </p>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Feature Cards -->
                <div class="bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-sync text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Sinkron Otomatis</h3>
                    <p class="text-gray-600 text-sm">
                        Sinkronisasi real-time antar perangkat, jadi tugas Anda selalu up-to-date dimanapun Anda berada.
                    </p>
                </div>
                
                <div class="bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Pengingat Pintar</h3>
                    <p class="text-gray-600 text-sm">
                        Sistem pengingat cerdas dengan AI yang mempelajari pola kerja Anda untuk notifikasi yang tepat waktu.
                    </p>
                </div>
                
                <div class="bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Integrasi Kalender</h3>
                    <p class="text-gray-600 text-sm">
                        Terintegrasi sempurna dengan kalender pribadi dan tim, jadi semua jadwal Anda terkoordinasi.
                    </p>
                </div>
                
                <div class="bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="bg-yellow-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-users text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Kolaborasi Tim</h3>
                    <p class="text-gray-600 text-sm">
                        Delegasikan tugas, berikan feedback, dan lacak progress tim dalam satu platform yang mudah digunakan.
                    </p>
                </div>
                
                <div class="bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="bg-red-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Analisis Produktivitas</h3>
                    <p class="text-gray-600 text-sm">
                        Laporan mendetail tentang produktivitas Anda dengan grafik dan statistik yang mudah dipahami.
                    </p>
                </div>
                
                <div class="bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="bg-indigo-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Keamanan Terjamin</h3>
                    <p class="text-gray-600 text-sm">
                        Enkripsi end-to-end dan keamanan enterprise untuk melindungi data pribadi dan bisnis Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-12 sm:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold mb-3">Mengapa Pilih Taskly?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Ribuan pengguna profesional telah meningkatkan produktivitas mereka dengan Taskly
                </p>
            </div>
            
            <div class="grid sm:grid-cols-3 gap-4 sm:gap-6">
                <div class="text-center bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-4xl lg:text-5xl font-bold text-blue-600 mb-2">85%</div>
                    <p class="text-gray-600 font-medium">Peningkatan produktivitas</p>
                </div>
                
                <div class="text-center bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-4xl lg:text-5xl font-bold text-purple-600 mb-2">10K+</div>
                    <p class="text-gray-600 font-medium">Tugas selesai setiap hari</p>
                </div>
                
                <div class="text-center bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-4xl lg:text-5xl font-bold text-green-600 mb-2">50K+</div>
                    <p class="text-gray-600 font-medium">Pengguna aktif</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-4">Siap untuk Meningkatkan Produktivitas Anda?</h2>
            <p class="text-lg mb-6 opacity-90">
                Bergabung dengan ribuan pengguna yang telah mengubah cara mereka bekerja
            </p>
            <a href="#" class="inline-flex items-center justify-center bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-xl font-semibold text-lg transition transform hover:scale-105">
                <i class="fas fa-user-plus mr-2"></i>Buat Akun Gratis
            </a>
        </div>
    </section>

    <!-- PWA Install Prompt -->
    <div id="pwa-prompt" class="pwa-prompt">
        <button id="pwa-close" class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <div class="flex gap-3">
            <img src="/icons/logo.png" alt="Taskly" class="w-12 h-12 rounded-xl shadow">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 mb-1">Install Taskly di Perangkat Anda</h3>
                <p class="text-sm text-gray-600">Akses cepat dan bekerja offline dengan aplikasi Taskly</p>
            </div>
        </div>
        
        <div class="flex gap-3">
            <button id="pwa-install" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition">
                Install Aplikasi
            </button>
            <button id="pwa-later" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-medium transition">
                Nanti Saja
            </button>
        </div>
    </div>
    
    <!-- PWA Mini Prompt -->
    <div id="pwa-mini" class="pwa-mini">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-8 8h8"/>
        </svg>
    </div>

    <script>
        // Mobile Menu
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
        
        // PWA Install
        let deferredPrompt;
        const pwaPrompt = document.getElementById('pwa-prompt');
        const pwaMini = document.getElementById('pwa-mini');
        const installBtn = document.getElementById('pwa-install');
        const laterBtn = document.getElementById('pwa-later');
        const closeBtn = document.getElementById('pwa-close');
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            setTimeout(() => pwaPrompt.classList.add('show'), 3000);
        });
        
        installBtn?.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            pwaPrompt.classList.remove('show');
            deferredPrompt.prompt();
            await deferredPrompt.userChoice;
            deferredPrompt = null;
        });
        
        laterBtn?.addEventListener('click', () => {
            pwaPrompt.classList.add('hide');
            setTimeout(() => {
                pwaPrompt.classList.remove('show', 'hide');
                pwaMini.classList.add('show');
            }, 300);
        });
        
        closeBtn?.addEventListener('click', () => {
            pwaPrompt.classList.add('hide');
            setTimeout(() => pwaPrompt.classList.remove('show', 'hide'), 300);
        });
        
        pwaMini?.addEventListener('click', () => {
            pwaMini.classList.remove('show');
            pwaPrompt.classList.add('show');
        });
        
        window.addEventListener('appinstalled', () => {
            pwaPrompt.classList.remove('show');
            pwaMini.classList.remove('show');
        });
        
        // Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(reg => {
                    console.log('SW registered:', reg.scope);
                    reg.addEventListener('updatefound', () => {
                        const newWorker = reg.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                document.getElementById('update-banner').classList.add('show');
                            }
                        });
                    });
                })
                .catch(err => console.log('SW failed:', err));
        }
        
        // Online/Offline
        const offlineBanner = document.getElementById('offline-banner');
        const updateStatus = () => {
            offlineBanner.classList.toggle('show', !navigator.onLine);
        };
        window.addEventListener('online', updateStatus);
        window.addEventListener('offline', updateStatus);
        updateStatus();
    </script>
</body>
</html>