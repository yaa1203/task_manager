<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - Kelola Tugas Anda dengan Mudah</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .mockup-container {
            perspective: 1000px;
        }
        .mockup {
            transition: transform 0.5s ease;
        }
        .mockup:hover {
            transform: rotateY(5deg) rotateX(5deg);
        }
        .mobile-nav {
            display: none;
        }
        
        /* Animasi untuk hamburger menu */
        .hamburger-line {
            transition: all 0.3s ease;
        }
        
        @media (max-width: 640px) {
            .desktop-nav {
                display: none;
            }
            .mobile-nav {
                display: block;
            }
            .hero-image {
                max-width: 280px;
            }
            /* Perbaikan responsivitas mobile */
            h1 {
                font-size: 1.75rem !important;
            }
            h2 {
                font-size: 1.5rem !important;
            }
            .text-lg {
                font-size: 1rem !important;
            }
            .px-6 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .py-3 {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }
            .rounded-xl {
                border-radius: 0.75rem !important;
            }
            .grid-cols-2 {
                grid-template-columns: 1fr !important;
            }
            .max-w-7xl {
                max-width: 100% !important;
            }
            .max-w-4xl {
                max-width: 100% !important;
            }
            .space-x-6 > * + * {
                margin-left: 1rem !important;
            }
            .space-x-3 > * + * {
                margin-left: 0.75rem !important;
            }
            .gap-6 {
                gap: 1rem !important;
            }
            .gap-8 {
                gap: 1.5rem !important;
            }
            .mb-8 {
                margin-bottom: 1.5rem !important;
            }
            .mb-12 {
                margin-bottom: 2rem !important;
            }
            .py-16 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
            .py-20 {
                padding-top: 2.5rem !important;
                padding-bottom: 2.5rem !important;
            }
            .text-4xl {
                font-size: 1.875rem !important;
            }
            .text-5xl {
                font-size: 2.25rem !important;
            }
            .p-6 {
                padding: 1rem !important;
            }
            .w-12 {
                width: 2.5rem !important;
                height: 2.5rem !important;
            }
            .h-12 {
                width: 2.5rem !important;
                height: 2.5rem !important;
            }
        }
        
        /* Perbaikan untuk layar medium */
        @media (min-width: 641px) and (max-width: 768px) {
            .hero-image {
                max-width: 320px;
            }
            h1 {
                font-size: 2rem !important;
            }
            h2 {
                font-size: 1.75rem !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <!-- Mobile Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 mobile-nav">
        <div class="px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <span class="bg-blue-600 text-white rounded-lg w-8 h-8 flex items-center justify-center font-bold">T</span>
                <span class="font-bold text-lg ml-2 gradient-text">Taskly</span>
            </div>
            <button id="mobile-menu-button" class="text-gray-600 focus:outline-none p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="hamburger-line" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden bg-white border-t">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('login')}}" class="block text-blue-600 font-medium py-2">Masuk</a>
                <a href="{{ route('register')}}" class="block bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-medium text-center">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    <!-- Desktop Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 desktop-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="bg-blue-600 text-white rounded-lg w-10 h-10 flex items-center justify-center font-bold text-xl">T</span>
                    <span class="font-bold text-xl ml-2 gradient-text">Taskly</span>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-blue-600 font-medium hover:underline">Masuk</a>
                    <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        Daftar Gratis
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-16 md:py-20">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-50 to-purple-50 opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                <div class="text-center lg:text-left">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
                        Kelola Tugas. <span class="gradient-text">Selesaikan Lebih Cepat.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600 mb-6 max-w-lg">
                        Atur prioritas, delegasikan, dan raih fokus — semua dalam satu aplikasi ringan yang dirancang untuk produktivitas Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-5 md:px-8 py-3 rounded-xl font-semibold text-base md:text-lg transition transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
                        </a>
                        <a href="#features" class="bg-white hover:bg-gray-50 text-blue-600 border border-blue-600 px-5 md:px-8 py-3 rounded-xl font-semibold text-base md:text-lg transition flex items-center justify-center">
                            Lihat Fitur
                        </a>
                    </div>
                </div>
                <div class="mockup-container flex justify-center">
                    <div class="mockup float-animation">
                        <div class="hero-image w-full max-w-xs md:max-w-sm rounded-2xl shadow-2xl bg-gradient-to-br from-blue-400 to-purple-500 aspect-square flex items-center justify-center text-white text-6xl">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <h2 class="text-xl md:text-2xl font-bold mb-3">Fitur Unggulan Taskly</h2>
                <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                    Dibuat khusus untuk membantu Anda mengelola tugas dengan efisien
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <div class="feature-card bg-gray-50 p-4 md:p-5 rounded-xl">
                    <div class="bg-blue-100 w-10 h-10 rounded-lg flex items-center justify-center mb-3 md:mb-4">
                        <i class="fas fa-sync text-blue-600 text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2">Sinkron Otomatis</h3>
                    <p class="text-gray-600 text-sm">
                        Sinkronisasi real-time antar perangkat, jadi tugas Anda selalu up-to-date dimanapun Anda berada.
                    </p>
                </div>
                
                <div class="feature-card bg-gray-50 p-4 md:p-5 rounded-xl">
                    <div class="bg-purple-100 w-10 h-10 rounded-lg flex items-center justify-center mb-3 md:mb-4">
                        <i class="fas fa-bell text-purple-600 text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2">Pengingat Pintar</h3>
                    <p class="text-gray-600 text-sm">
                        Sistem pengingat cerdas dengan AI yang mempelajari pola kerja Anda untuk notifikasi yang tepat waktu.
                    </p>
                </div>
                
                <div class="feature-card bg-gray-50 p-4 md:p-5 rounded-xl">
                    <div class="bg-green-100 w-10 h-10 rounded-lg flex items-center justify-center mb-3 md:mb-4">
                        <i class="fas fa-calendar-alt text-green-600 text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2">Integrasi Kalender</h3>
                    <p class="text-gray-600 text-sm">
                        Terintegrasi sempurna dengan kalender pribadi dan tim, jadi semua jadwal Anda terkoordinasi.
                    </p>
                </div>
                
                <div class="feature-card bg-gray-50 p-4 md:p-5 rounded-xl">
                    <div class="bg-yellow-100 w-10 h-10 rounded-lg flex items-center justify-center mb-3 md:mb-4">
                        <i class="fas fa-users text-yellow-600 text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2">Kolaborasi Tim</h3>
                    <p class="text-gray-600 text-sm">
                        Delegasikan tugas, berikan feedback, dan lacak progress tim dalam satu platform yang mudah digunakan.
                    </p>
                </div>
                
                <div class="feature-card bg-gray-50 p-4 md:p-5 rounded-xl">
                    <div class="bg-red-100 w-10 h-10 rounded-lg flex items-center justify-center mb-3 md:mb-4">
                        <i class="fas fa-chart-line text-red-600 text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2">Analisis Produktivitas</h3>
                    <p class="text-gray-600 text-sm">
                        Laporan mendetail tentang produktivitas Anda dengan grafik dan statistik yang mudah dipahami.
                    </p>
                </div>
                
                <div class="feature-card bg-gray-50 p-4 md:p-5 rounded-xl">
                    <div class="bg-indigo-100 w-10 h-10 rounded-lg flex items-center justify-center mb-3 md:mb-4">
                        <i class="fas fa-shield-alt text-indigo-600 text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2">Keamanan Terjamin</h3>
                    <p class="text-gray-600 text-sm">
                        Enkripsi end-to-end dan keamanan enterprise untuk melindungi data pribadi dan bisnis Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <h2 class="text-xl md:text-2xl font-bold mb-3">Mengapa Pilih Taskly?</h2>
                <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                    Ribuan pengguna profesional telah meningkatkan produktivitas mereka dengan Taskly
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="text-center bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">85%</div>
                    <p class="text-gray-600 font-medium text-sm md:text-base">Peningkatan produktivitas</p>
                </div>
                
                <div class="text-center bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <div class="text-3xl md:text-4xl font-bold text-purple-600 mb-2">10K+</div>
                    <p class="text-gray-600 font-medium text-sm md:text-base">Tugas selesai setiap hari</p>
                </div>
                
                <div class="text-center bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <div class="text-3xl md:text-4xl font-bold text-green-600 mb-2">50K+</div>
                    <p class="text-gray-600 font-medium text-sm md:text-base">Pengguna aktif</p>
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
                <a href="#" class="bg-white text-blue-600 hover:bg-gray-100 px-5 md:px-8 py-3 md:py-4 rounded-xl font-semibold text-base md:text-lg transition transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>Buat Akun Gratis
                </a>
            </div>
        </div>
    </section>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>