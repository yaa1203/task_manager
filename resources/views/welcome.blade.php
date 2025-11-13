<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - Kelola Tugas Anda dengan Mudah</title>
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Taskly">
    <meta name="application-name" content="Taskly">
    <meta name="msapplication-TileColor" content="#4f46e5">
    <meta name="msapplication-TileImage" content="/icons/logo.png">
    
    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/logo72x72.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/logo.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/logo.png">
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- PWA Styles -->
    <style>
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
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    
    <!-- Offline Banner -->
    <div id="offline-banner" class="banner offline-banner">
        ⚠️ Anda sedang offline. Beberapa fitur mungkin terbatas.
    </div>
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-100 z-50 h-16 shadow-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="/" class="flex items-center space-x-2 sm:space-x-3 group">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full blur-md opacity-40 group-hover:opacity-60 transition-opacity"></div>
                            @if(file_exists(public_path('icons/logo72x72.png')))
                                <img src="{{ asset('icons/logo72x72.png') }}" alt="Logo" class="relative h-9 w-9 rounded-full shadow-md" />
                            @else
                                <div class="relative h-9 w-9 rounded-full shadow-md bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <span class="text-base sm:text-lg font-bold text-gray-900 whitespace-nowrap">TaskFlow</span>
                    </a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <a href="{{ route('login')}}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 sm:px-4 py-2 rounded-full transition-colors duration-200 whitespace-nowrap">
                        Masuk
                    </a>
                    <a href="{{ route('register')}}" class="flex items-center gap-1 sm:gap-2 px-4 sm:px-5 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 whitespace-nowrap">
                        <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>Daftar</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Add padding to content so it doesn't hide under fixed navbar -->
    <div class="pt-12"></div>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-12 md:py-20 lg:py-24">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 opacity-60"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left space-y-6 opacity-0 animate-fade-in-up">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                        Kelola Tugas. <br class="hidden sm:block">
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Selesaikan Lebih Cepat.</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-2xl mx-auto lg:mx-0">
                        Atur prioritas, delegasikan, dan raih fokus semua dalam satu aplikasi ringan yang dirancang untuk produktivitas Anda.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                        <a href="{{ route('register')}}" class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg transform hover:scale-105 transition-all duration-300 inline-flex items-center justify-center group">
                            <i class="fas fa-rocket mr-2 group-hover:animate-bounce"></i>
                            <span>Mulai Sekarang</span>
                        </a>
                        <a href="#features" class="bg-white hover:bg-gray-50 text-blue-600 border-2 border-blue-600 px-8 py-4 rounded-xl font-semibold text-lg shadow-md transition-all duration-300 transform hover:scale-105 inline-flex items-center justify-center">
                            <span>Lihat Fitur</span>
                            <i class="fas fa-arrow-down ml-2 animate-bounce"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Hero Image -->
                <div class="flex justify-center lg:justify-end mt-8 lg:mt-0">
                    <div class="w-full max-w-sm md:max-w-md lg:max-w-lg animate-float">
                        <img src="icons/banner.jpg" 
                             alt="Taskly App Preview" 
                             class="w-full rounded-2xl shadow-2xl hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 md:py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">
                    Fitur Unggulan <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Taskly</span>
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                    Dibuat khusus untuk membantu Anda mengelola tugas dengan efisien
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Feature Card 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-6 md:p-8 rounded-2xl border border-gray-100 transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-14 h-14 rounded-xl flex items-center justify-center mb-5 shadow-lg">
                        <i class="fas fa-sync text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Sinkron Otomatis</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sinkronisasi real-time antar perangkat, jadi tugas Anda selalu up-to-date dimanapun Anda berada.
                    </p>
                </div>
                
                <!-- Feature Card 2 -->
                <div class="bg-gradient-to-br from-purple-50 to-white p-6 md:p-8 rounded-2xl border border-gray-100 transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 w-14 h-14 rounded-xl flex items-center justify-center mb-5 shadow-lg">
                        <i class="fas fa-bell text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Pengingat Pintar</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem pengingat cerdas dengan AI yang mempelajari pola kerja Anda untuk notifikasi yang tepat waktu.
                    </p>
                </div>
                
                <!-- Feature Card 3 -->
                <div class="bg-gradient-to-br from-green-50 to-white p-6 md:p-8 rounded-2xl border border-gray-100 transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 w-14 h-14 rounded-xl flex items-center justify-center mb-5 shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Integrasi Kalender</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Terintegrasi sempurna dengan kalender pribadi dan tim, jadi semua jadwal Anda terkoordinasi.
                    </p>
                </div>
                
                <!-- Feature Card 4 -->
                <div class="bg-gradient-to-br from-yellow-50 to-white p-6 md:p-8 rounded-2xl border border-gray-100 transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 w-14 h-14 rounded-xl flex items-center justify-center mb-5 shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Kolaborasi Tim</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Delegasikan tugas, berikan feedback, dan lacak progress tim dalam satu platform yang mudah digunakan.
                    </p>
                </div>
                
                <!-- Feature Card 5 -->
                <div class="bg-gradient-to-br from-red-50 to-white p-6 md:p-8 rounded-2xl border border-gray-100 transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gradient-to-br from-red-500 to-red-600 w-14 h-14 rounded-xl flex items-center justify-center mb-5 shadow-lg">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Analisis Produktivitas</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Laporan mendetail tentang produktivitas Anda dengan grafik dan statistik yang mudah dipahami.
                    </p>
                </div>
                
                <!-- Feature Card 6 -->
                <div class="bg-gradient-to-br from-indigo-50 to-white p-6 md:p-8 rounded-2xl border border-gray-100 transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 w-14 h-14 rounded-xl flex items-center justify-center mb-5 shadow-lg">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Keamanan Terjamin</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Enkripsi end-to-end dan keamanan enterprise untuk melindungi data pribadi dan bisnis Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-16 md:py-20 lg:py-24 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">
                    Mengapa Pilih <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Taskly?</span>
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                    Ribuan pengguna profesional telah meningkatkan produktivitas mereka dengan Taskly
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-8">
                <div class="bg-white p-8 md:p-10 rounded-2xl shadow-lg text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-3">
                        85%
                    </div>
                    <p class="text-gray-700 font-semibold text-lg">Peningkatan Produktivitas</p>
                </div>
                
                <div class="bg-white p-8 md:p-10 rounded-2xl shadow-lg text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-3">
                        10K+
                    </div>
                    <p class="text-gray-700 font-semibold text-lg">Tugas Selesai Setiap Hari</p>
                </div>
                
                <div class="bg-white p-8 md:p-10 rounded-2xl shadow-lg text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent mb-3">
                        50K+
                    </div>
                    <p class="text-gray-700 font-semibold text-lg">Pengguna Aktif</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 md:py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-xl md:text-2xl font-bold mb-4">Siap untuk Meningkatkan Produktivitas Anda?</h2>
            <p class="text-base md:text-lg mb-6 opacity-90">
                Bergabung dengan ribuan pengguna yang telah mengubah cara mereka bekerja
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register')}}" class="bg-white text-blue-600 hover:bg-gray-100 px-5 md:px-8 py-3 md:py-4 rounded-xl font-semibold text-base md:text-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>Buat Akun Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- PWA Install Prompt -->
    <div id="pwa-install-prompt" class="pwa-install-prompt">
        <div class="pwa-content">
            <div class="pwa-icon">
                <img src="/icons/logo72x72.png" alt="Taskly Icon">
            </div>
            <div class="pwa-text">
                <div class="pwa-title">Instal TaskFlow</div>
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
        (function() {
            'use strict';
            
            const CONFIG = {
                SHOW_DELAY: 5000,
                SCROLL_TRIGGER: 300,
                INTERACTION_COUNT: 3,
                STORAGE_KEYS: {
                    DISMISSED: 'pwa-prompt-dismissed',
                    MINIMIZED: 'pwa-prompt-minimized',
                    LAST_SHOWN: 'pwa-prompt-last-shown'
                }
            };
            
            let deferredPrompt = null;
            let hasShownPrompt = false;
            let interactionCount = 0;
            
            const elements = {
                fullPrompt: document.getElementById('pwa-install-prompt'),
                miniPrompt: document.getElementById('pwa-mini-prompt'),
                installBtn: document.getElementById('pwa-install-btn'),
                laterBtn: document.getElementById('pwa-later-btn'),
                closeBtn: document.querySelector('.pwa-close-btn'),
                offlineBanner: document.getElementById('offline-banner'),
            };
            
            const storage = {
                get(key, isLocal = false) {
                    try {
                        const store = isLocal ? localStorage : sessionStorage;
                        const value = store.getItem(key);
                        return value ? JSON.parse(value) : null;
                    } catch (e) {
                        return null;
                    }
                },
                set(key, value, isLocal = false) {
                    try {
                        const store = isLocal ? localStorage : sessionStorage;
                        store.setItem(key, JSON.stringify(value));
                        return true;
                    } catch (e) {
                        return false;
                    }
                },
                remove(key, isLocal = false) {
                    try {
                        const store = isLocal ? localStorage : sessionStorage;
                        store.removeItem(key);
                    } catch (e) {}
                }
            };
            
            function shouldShowPrompt() {
                // Jika sudah diinstall, jangan tampilkan
                if (window.matchMedia('(display-mode: standalone)').matches) {
                    return false;
                }
                
                // Jika user klik X (dismiss), jangan tampilkan sampai refresh
                const isDismissed = storage.get(CONFIG.STORAGE_KEYS.DISMISSED);
                if (isDismissed) {
                    return false;
                }
                
                // Jika user klik "Nanti", tampilkan mini prompt
                const isMinimized = storage.get(CONFIG.STORAGE_KEYS.MINIMIZED);
                if (isMinimized) {
                    return 'mini';
                }
                
                // Tampilkan full prompt
                return true;
            }
            
            function showFullPrompt() {
                if (!deferredPrompt) return;
                
                elements.miniPrompt.classList.remove('show');
                elements.fullPrompt.classList.add('show');
                storage.set(CONFIG.STORAGE_KEYS.LAST_SHOWN, Date.now(), true);
                hasShownPrompt = true;
            }
            
            function showMiniPrompt() {
                if (!deferredPrompt) return;
                elements.fullPrompt.classList.remove('show');
                elements.miniPrompt.classList.add('show');
            }
            
            function hideAllPrompts() {
                elements.fullPrompt.classList.remove('show');
                elements.miniPrompt.classList.remove('show');
            }
            
            async function handleInstall() {
                if (!deferredPrompt) return;
                
                hideAllPrompts();
                
                try {
                    await deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    
                    if (outcome === 'accepted') {
                        storage.remove(CONFIG.STORAGE_KEYS.DISMISSED);
                        storage.remove(CONFIG.STORAGE_KEYS.MINIMIZED);
                        storage.remove(CONFIG.STORAGE_KEYS.LAST_SHOWN, true);
                    } else {
                        setTimeout(showMiniPrompt, 1000);
                    }
                    
                    deferredPrompt = null;
                } catch (error) {
                    console.error('Install error:', error);
                }
            }
            
            // Event Listeners
            elements.installBtn?.addEventListener('click', handleInstall);
            
            elements.laterBtn?.addEventListener('click', () => {
                // Set flag minimized di sessionStorage (hilang saat refresh)
                storage.set(CONFIG.STORAGE_KEYS.MINIMIZED, true);
                // Hapus flag dismissed jika ada
                storage.remove(CONFIG.STORAGE_KEYS.DISMISSED);
                
                elements.fullPrompt.classList.remove('show');
                hasShownPrompt = false; // Reset flag agar mini prompt bisa muncul
                setTimeout(showMiniPrompt, 300);
            });
            
            elements.closeBtn?.addEventListener('click', () => {
                // Set flag dismissed di sessionStorage (hilang saat refresh)
                storage.set(CONFIG.STORAGE_KEYS.DISMISSED, true);
                // Hapus flag minimized jika ada
                storage.remove(CONFIG.STORAGE_KEYS.MINIMIZED);
                hideAllPrompts();
            });
            
            const miniPromptClickHandler = () => {
                if (!deferredPrompt) return;
                // Hapus flag minimized dan dismissed
                storage.remove(CONFIG.STORAGE_KEYS.MINIMIZED);
                storage.remove(CONFIG.STORAGE_KEYS.DISMISSED);
                
                elements.miniPrompt.classList.remove('show');
                elements.fullPrompt.classList.add('show');
                hasShownPrompt = true;
            };
            
            elements.miniPrompt?.addEventListener('click', miniPromptClickHandler);
            elements.miniPrompt?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    miniPromptClickHandler();
                }
            });
            
            // Scroll trigger
            let hasScrolled = false;
            window.addEventListener('scroll', () => {
                if (!hasScrolled && window.scrollY > CONFIG.SCROLL_TRIGGER && !hasShownPrompt) {
                    hasScrolled = true;
                    const shouldShow = shouldShowPrompt();
                    if (shouldShow === true) {
                        showFullPrompt();
                    } else if (shouldShow === 'mini') {
                        showMiniPrompt();
                    }
                }
            }, { passive: true });
            
            // Interaction trigger
            const interactionHandler = () => {
                interactionCount++;
                if (interactionCount >= CONFIG.INTERACTION_COUNT && !hasShownPrompt) {
                    const shouldShow = shouldShowPrompt();
                    if (shouldShow === true) showFullPrompt();
                    else if (shouldShow === 'mini') showMiniPrompt();
                    document.removeEventListener('click', interactionHandler);
                }
            };
            document.addEventListener('click', interactionHandler);
            
            // Clear dismissed flag on page load (untuk memastikan prompt muncul lagi setelah refresh)
            window.addEventListener('load', () => {
                // Hapus flag dismissed agar prompt bisa muncul lagi setelah refresh
                storage.remove(CONFIG.STORAGE_KEYS.DISMISSED);
                // Hapus flag minimized juga
                storage.remove(CONFIG.STORAGE_KEYS.MINIMIZED);
            });
            
            // beforeinstallprompt
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                setTimeout(() => {
                    const shouldShow = shouldShowPrompt();
                    if (shouldShow === true) showFullPrompt();
                    else if (shouldShow === 'mini') showMiniPrompt();
                }, CONFIG.SHOW_DELAY);
            });
            
            // appinstalled
            window.addEventListener('appinstalled', () => {
                hideAllPrompts();
                // Hapus semua storage terkait PWA prompt
                storage.remove(CONFIG.STORAGE_KEYS.DISMISSED);
                storage.remove(CONFIG.STORAGE_KEYS.MINIMIZED);
                storage.remove(CONFIG.STORAGE_KEYS.LAST_SHOWN, true);
            });
            
            // Service Worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js')
                        .then(registration => {
                            registration.addEventListener('updatefound', () => {
                                const newWorker = registration.installing;
                                newWorker.addEventListener('statechange', () => {
                                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                        elements.updateBanner?.classList.add('show');
                                    }
                                });
                            });
                        })
                        .catch(err => console.error('SW registration failed:', err));
                });
            }
            
            // Online/Offline
            function updateOnlineStatus() {
                if (navigator.onLine) {
                    elements.offlineBanner?.classList.remove('show');
                } else {
                    elements.offlineBanner?.classList.add('show');
                }
            }
            
            window.addEventListener('online', updateOnlineStatus);
            window.addEventListener('offline', updateOnlineStatus);
            updateOnlineStatus();
            
            // Notification Permission
            if ('Notification' in window && Notification.permission === 'default') {
                document.addEventListener('click', () => {
                    setTimeout(() => {
                        Notification.requestPermission();
                    }, 10000);
                }, { once: true });
            }
            
            // Prevent double-tap zoom
            let lastTouchEnd = 0;
            document.addEventListener('touchend', (e) => {
                const now = Date.now();
                if (now - lastTouchEnd <= 300) {
                    e.preventDefault();
                }
                lastTouchEnd = now;
            }, { passive: false });
            
            console.log('PWA initialized');
        })();
    </script>
</body>
</html>