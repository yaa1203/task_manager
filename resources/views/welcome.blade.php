<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - Kelola Tugas Anda dengan Mudah</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-lg w-10 h-10 flex items-center justify-center font-bold text-xl shadow-md">
                        T
                    </div>
                    <span class="font-bold text-xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Taskly</span>
                </div>
                
                <!-- Desktop & Mobile Buttons -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <a href="{{ route('login')}}" class="text-gray-700 hover:text-blue-600 font-medium px-3 sm:px-4 py-2 rounded-lg transition-colors duration-200 text-sm sm:text-base">
                        Masuk
                    </a>
                    <a href="{{ route('register')}}" class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-3 sm:px-6 py-2 rounded-lg font-medium shadow-md transition-all duration-300 transform hover:scale-105 text-sm sm:text-base">
                        Daftar Gratis
                    </a>
                </div>
            </div>
        </div>
    </nav>

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
                        Atur prioritas, delegasikan, dan raih fokus — semua dalam satu aplikasi ringan yang dirancang untuk produktivitas Anda.
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
    <section id="features" class="py-16 md:py-20 lg:py-24 bg-white">
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

    <style>
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
</body>
</html>