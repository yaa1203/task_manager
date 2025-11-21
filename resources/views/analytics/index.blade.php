<x-app-layout>

    {{-- FIXED TOP NAVBAR --}}
    <nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-100 z-50 h-16 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
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

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-2">
                    @php
                        $navItems = [
                            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['url' => 'my-workspaces', 'label' => 'Workspace', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H9m12 0a1 1 0 001 1h-3.5a1 1 0 01-1-1m-6.5 0a1 1 0 01-1-1H4a1 1 0 011-1m6 0a1 1 0 011 1h-3.5a1 1 0 01-1-1'],
                            ['url' => 'calendar', 'label' => 'Kalender', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['url' => 'analytics', 'label' => 'Analitik', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        @php
                            $isActive = request()->is(($item['url'] ?? $item['route']) . '*') || request()->routeIs($item['route'] ?? '');
                        @endphp
                        <a href="{{ isset($item['url']) ? url($item['url']) : route($item['route']) }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ $isActive ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Right Side: Notif + User + Hamburger -->
                <div class="flex items-center space-x-3">
                    <!-- Notification -->
                    <a href="{{ route('notifikasi.index') }}" class="relative p-2 rounded-full hover:bg-gray-100 transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center ring-2 ring-white">
                                {{ Auth::user()->unreadNotifications->count() > 9 ? '9+' : Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ userOpen: false }" @click.away="userOpen = false">
                        <button @click="userOpen = !userOpen" class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="userOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50"
                             style="display: none;">
                            <div class="p-3 space-y-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Hamburger -->
                    <button x-data="{ mobileOpen: false }"
                            @click="mobileOpen = !mobileOpen; $dispatch('toggle-mobile-menu')"
                            class="lg:hidden p-2 rounded-full hover:bg-gray-100 transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Full Overlay Menu -->
        <div x-data="{ mobileMenuOpen: false }" 
            @toggle-mobile-menu.window="mobileMenuOpen = !mobileMenuOpen"
            x-show="mobileMenuOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.away="mobileMenuOpen = false"
            class="fixed inset-0 bg-white z-40 flex flex-col lg:hidden"
            style="display: none;">
            
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <button @click="mobileMenuOpen = false" class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                @foreach($navItems as $item)
                    @php $isActive = request()->is(($item['url'] ?? $item['route']) . '*') || request()->routeIs($item['route'] ?? ''); @endphp
                    <a href="{{ isset($item['url']) ? url($item['url']) : route($item['route']) }}"
                    @click="mobileMenuOpen = false"
                    class="flex items-center justify-between p-4 rounded-xl text-base font-medium transition-all {{ $isActive ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 {{ $isActive ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </div>
                        @if(isset($item['badge']) && $item['badge'] > 0)
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $item['badge'] > 9 ? '9+' : $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="p-4 border-t border-gray-100 space-y-2">
                <a href="{{ route('profile.edit') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" @submit.prevent="mobileMenuOpen = false; $el.submit()">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-red-50 text-red-600 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- CONTENT WITH PADDING TO AVOID OVERLAP WITH FIXED NAVBAR --}}
    <div>
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800">Dasbor Analitik</h2>
                    <p class="text-sm text-gray-600 mt-1">Wawasan produktivitas dan statistik Anda</p>
                </div>
            </div>
        </x-slot>

        <div class="py-4 sm:py-6">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

                {{-- Alert Error --}}
                <div id="error-alert" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">Gagal memuat analitik</p>
                            <p id="error-message" class="text-xs text-red-600 mt-1"></p>
                        </div>
                        <button onclick="hideError()" class="ml-auto text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Summary Cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    @php
                        $cards = [
                            ['color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Total Tugas', 'id' => 'total-tasks'],
                            ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Selesai', 'id' => 'completed-tasks'],
                            ['color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Belum Selesai', 'id' => 'unfinished-tasks'],
                            ['color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Terlambat', 'id' => 'overdue-tasks']
                        ];
                    @endphp

                    @foreach($cards as $card)
                        <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 border-l-4 border-{{ $card['color'] }}-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-600 font-medium">{{ $card['label'] }}</p>
                                    <p id="{{ $card['id'] }}" class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">
                                        <span class="loading-dash">-</span>
                                    </p>
                                </div>
                                <div class="bg-{{ $card['color'] }}-100 p-2 sm:p-3 rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-{{ $card['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Grafik --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                    
                    <!-- Distribusi Tugas -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 sm:p-5">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base sm:text-lg font-semibold text-white">Distribusi Tugas</h3>
                                <span id="chart-update-time" class="text-xs text-blue-100">Memuat...</span>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            <div class="h-40 sm:h-48 lg:h-64 mb-4 relative">
                                <canvas id="taskChart"></canvas>
                                <div id="task-chart-empty" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-50 rounded-lg">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium text-sm sm:text-base">Belum ada data tugas</p>
                                    <p class="text-gray-400 text-xs sm:text-sm mt-1">Mulai tambahkan tugas untuk melihat statistik</p>
                                </div>
                                <div id="task-chart-loading" class="absolute inset-0 flex items-center justify-center bg-white">
                                    <div class="text-center">
                                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                                        <div class="text-gray-500 text-sm">Memuat data...</div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 sm:gap-3 text-center">
                                @foreach([
                                    ['id' => 'done-count', 'label' => 'Selesai', 'color' => 'green'], 
                                    ['id' => 'unfinished-count', 'label' => 'Belum Selesai', 'color' => 'gray'], 
                                    ['id' => 'overdue-count', 'label' => 'Terlambat', 'color' => 'red']
                                ] as $stat)
                                <div class="bg-{{ $stat['color'] }}-50 rounded-lg p-2 sm:p-3 border border-{{ $stat['color'] }}-100">
                                    <p class="text-xs text-gray-600">{{ $stat['label'] }}</p>
                                    <p id="{{ $stat['id'] }}" class="text-lg sm:text-xl font-bold text-{{ $stat['color'] }}-600">-</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tinjauan Ruang Kerja -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-4 sm:p-5">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base sm:text-lg font-semibold text-white">Tinjauan Ruang Kerja</h3>
                                <span class="text-xs text-purple-100">Diperbarui sekarang</span>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            <div class="h-40 sm:h-48 lg:h-64 mb-4 relative">
                                <canvas id="workspaceChart"></canvas>
                                <div id="workspace-chart-empty" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-50 rounded-lg">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium text-sm sm:text-base">Belum ada ruang kerja</p>
                                    <p class="text-gray-400 text-xs sm:text-sm mt-1">Buat ruang kerja untuk mengelola tugas Anda</p>
                                </div>
                                <div id="workspace-chart-loading" class="absolute inset-0 flex items-center justify-center bg-white">
                                    <div class="text-center">
                                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600 mb-2"></div>
                                        <div class="text-gray-500 text-sm">Memuat data...</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <div class="bg-purple-50 rounded-lg p-3 sm:p-4 text-center min-w-[120px] sm:min-w-[140px] border border-purple-100">
                                    <p class="text-xs text-gray-600">Total Ruang Kerja</p>
                                    <p id="total-workspaces" class="text-xl sm:text-2xl font-bold text-purple-600">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Metrics --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    @php
                        $metrics = [
                            ['title' => 'Tingkat Penyelesaian', 'color' => 'green', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'id' => 'completion', 'type' => 'progress', 'desc' => 'Tugas selesai'],
                            ['title' => 'Rata-rata Tugas', 'color' => 'blue', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'id' => 'avg-tasks', 'type' => 'decimal', 'desc' => 'Per ruang kerja'],
                            ['title' => 'Tingkat Keterlambatan', 'color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'id' => 'overdue-rate', 'type' => 'progress', 'desc' => 'Tugas terlambat']
                        ];
                    @endphp

                    @foreach($metrics as $metric)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-800">{{ $metric['title'] }}</h3>
                                <div class="bg-{{ $metric['color'] }}-100 p-2 rounded-full">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-{{ $metric['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $metric['icon'] }}"/>
                                    </svg>
                                </div>
                            </div>
                            @if($metric['type'] === 'progress')
                                <div>
                                    <span id="{{ $metric['id'] }}-percentage" class="text-2xl sm:text-3xl font-bold text-{{ $metric['color'] }}-600">0%</span>
                                    <div class="mt-3 overflow-hidden h-2 rounded-full bg-{{ $metric['color'] }}-100">
                                        <div id="{{ $metric['id'] }}-bar" style="width:0%" class="h-full bg-gradient-to-r from-{{ $metric['color'] }}-500 to-{{ $metric['color'] }}-600 transition-all duration-500"></div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-baseline gap-2">
                                    <span id="{{ $metric['id'] }}" class="text-2xl sm:text-3xl font-bold text-gray-800">-</span>
                                    <span class="text-sm text-gray-500">tugas</span>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">{{ $metric['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js + Analytics Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Simplified Analytics Script - No Auto Refresh
        let charts = { task: null, workspace: null };
        let isLoading = false;

        // Prevent multiple initializations
        let isInitialized = false;

        document.addEventListener("DOMContentLoaded", function() {
            if (isInitialized) {
                console.log('Already initialized, skipping...');
                return;
            }
            isInitialized = true;
            
            console.log('Analytics page loaded');
            loadAnalytics();
        });

        function showError(message) {
            const errorAlert = document.getElementById('error-alert');
            const errorMessage = document.getElementById('error-message');
            if (errorAlert && errorMessage) {
                errorMessage.textContent = message;
                errorAlert.classList.remove('hidden');
            }
            console.error('Analytics Error:', message);
        }

        function hideError() {
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                errorAlert.classList.add('hidden');
            }
        }

        function showEmptyState(chartId) {
            const emptyEl = document.getElementById(chartId + '-empty');
            const loadingEl = document.getElementById(chartId + '-loading');
            const canvas = document.getElementById(chartId === 'task-chart' ? 'taskChart' : 'workspaceChart');
            
            console.log(`Showing empty state for ${chartId}`);
            if (emptyEl) {
                emptyEl.classList.remove('hidden');
                emptyEl.style.display = 'flex';
            }
            if (loadingEl) loadingEl.style.display = 'none';
            if (canvas) {
                canvas.style.display = 'none';
                canvas.style.visibility = 'hidden';
            }
        }

        function hideEmptyState(chartId) {
            const emptyEl = document.getElementById(chartId + '-empty');
            const loadingEl = document.getElementById(chartId + '-loading');
            const canvas = document.getElementById(chartId === 'task-chart' ? 'taskChart' : 'workspaceChart');
            
            console.log(`Hiding empty state for ${chartId}, showing chart`);
            if (emptyEl) {
                emptyEl.classList.add('hidden');
                emptyEl.style.display = 'none';
            }
            if (loadingEl) loadingEl.style.display = 'none';
            if (canvas) {
                canvas.style.display = 'block';
                canvas.style.visibility = 'visible';
            }
        }

        async function loadAnalytics() {
            // Prevent concurrent loads
            if (isLoading) {
                console.log('Already loading, skipping...');
                return;
            }

            isLoading = true;
            hideError();
            
            const url = `${window.location.origin}/analytics/data`;
            console.log('Fetching analytics data from:', url);

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Analytics data received:', data);

                if (!data || typeof data !== 'object') {
                    throw new Error('Invalid data structure');
                }

                updateUI(data);

            } catch (error) {
                console.error('Error loading analytics:', error);
                showError('Gagal memuat data analitik. Silakan periksa koneksi Anda.');
                updateUI(getDefaultData());
            } finally {
                isLoading = false;
            }
        }

        function getDefaultData() {
            return {
                tasks: { done: 0, unfinished: 0, overdue: 0 },
                workspaces: { total: 0, breakdown: [] },
                weekly_trend: [],
                summary: { total_tasks: 0, completion_rate: 0 }
            };
        }

        function updateUI(data) {
            try {
                const { tasks, workspaces, weekly_trend, summary } = data;
                
                const done = parseInt(tasks?.done) || 0;
                const unfinished = parseInt(tasks?.unfinished) || 0;
                const overdue = parseInt(tasks?.overdue) || 0;
                const total = done + unfinished + overdue;
                
                const completionRate = parseFloat(summary?.completion_rate) || 0;
                const overdueRate = total > 0 ? Math.round((overdue / total) * 100) : 0;
                const totalWorkspaces = parseInt(workspaces?.total) || 0;
                const avgTasks = totalWorkspaces > 0 ? (total / totalWorkspaces).toFixed(1) : '0.0';
                
                const updates = {
                    'total-tasks': total,
                    'completed-tasks': done,
                    'unfinished-tasks': unfinished,
                    'overdue-tasks': overdue,
                    'done-count': done,
                    'unfinished-count': unfinished,
                    'overdue-count': overdue,
                    'total-workspaces': totalWorkspaces,
                    'completion-percentage': completionRate + '%',
                    'overdue-rate-percentage': overdueRate + '%',
                    'avg-tasks': avgTasks
                };

                Object.entries(updates).forEach(([id, value]) => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.textContent = value;
                    }
                });

                const completionBar = document.getElementById('completion-bar');
                const overdueBar = document.getElementById('overdue-rate-bar');
                
                if (completionBar) completionBar.style.width = completionRate + '%';
                if (overdueBar) overdueBar.style.width = overdueRate + '%';
                
                const timestamp = document.getElementById('chart-update-time');
                if (timestamp) {
                    timestamp.textContent = 'Diperbarui ' + new Date().toLocaleTimeString('id-ID', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                }
                
                updateCharts(data);
                
                console.log('UI successfully updated');
            } catch (error) {
                console.error('Error updating UI:', error);
                showError('Error displaying data: ' + error.message);
            }
        }

        function updateCharts(data) {
            try {
                console.log('=== UPDATE CHARTS CALLED ===');
                console.log('Raw data received:', data);
                
                // Parse task data dengan validasi ketat
                const done = parseInt(data.tasks?.done) || 0;
                const unfinished = parseInt(data.tasks?.unfinished) || 0;
                const overdue = parseInt(data.tasks?.overdue) || 0;
                
                const taskData = [done, unfinished, overdue];
                const taskTotal = done + unfinished + overdue;
                
                console.log('Task data parsed:', {
                    done: done,
                    unfinished: unfinished,
                    overdue: overdue,
                    total: taskTotal,
                    array: taskData
                });

                const workspaceData = Array.isArray(data.workspaces?.breakdown) ? data.workspaces.breakdown : [];
                const totalWorkspacesCount = parseInt(data.workspaces?.total) || 0;
                console.log('Workspace data parsed:', {
                    totalWorkspaces: totalWorkspacesCount,
                    breakdownCount: workspaceData.length,
                    data: workspaceData
                });

                const isMobile = window.innerWidth < 640;
                const fontSize = isMobile ? 9 : 11;
                const legendPadding = isMobile ? 5 : 10;

                // === TASK CHART ===
                const taskCanvas = document.getElementById('taskChart');
                console.log('Task canvas element:', taskCanvas);
                
                if (taskCanvas) {
                    // Destroy existing chart
                    if (charts.task) {
                        console.log('Destroying existing task chart');
                        charts.task.destroy();
                        charts.task = null;
                    }
                    
                    if (taskTotal === 0) {
                        console.log('Task total is 0, showing empty state');
                        showEmptyState('task-chart');
                    } else {
                        console.log('Task total is', taskTotal, ', creating chart');
                        hideEmptyState('task-chart');
                        
                        // Pastikan canvas terlihat
                        taskCanvas.style.display = 'block';
                        taskCanvas.style.visibility = 'visible';
                        
                        try {
                            const ctx = taskCanvas.getContext('2d');
                            console.log('Got canvas context:', ctx);
                            
                            charts.task = new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Selesai', 'Belum Selesai', 'Terlambat'],
                                    datasets: [{
                                        data: taskData,
                                        backgroundColor: ['#10b981', '#6b7280', '#ef4444'],
                                        borderWidth: isMobile ? 1 : 2,
                                        borderColor: '#ffffff'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { 
                                            position: 'bottom', 
                                            labels: { 
                                                padding: legendPadding, 
                                                font: { size: fontSize },
                                                boxWidth: isMobile ? 10 : 12
                                            } 
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(ctx) {
                                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                                    const pct = total > 0 ? Math.round((ctx.parsed / total) * 100) : 0;
                                                    return `${ctx.label}: ${ctx.parsed} (${pct}%)`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                            console.log('✅ Task chart created successfully:', charts.task);
                        } catch (chartError) {
                            console.error('❌ Error creating task chart:', chartError);
                            showError('Gagal membuat chart distribusi tugas: ' + chartError.message);
                        }
                    }
                }

                // === WORKSPACE CHART ===
                const workspaceCanvas = document.getElementById('workspaceChart');
                console.log('Workspace canvas element:', workspaceCanvas);
                
                if (workspaceCanvas) {
                    // Destroy existing chart
                    if (charts.workspace) {
                        console.log('Destroying existing workspace chart');
                        charts.workspace.destroy();
                        charts.workspace = null;
                    }
                    
                    if (workspaceData.length === 0) {
                        console.log('No workspace data, showing empty state');
                        showEmptyState('workspace-chart');
                    } else {
                        console.log('Workspace data exists, creating chart');
                        hideEmptyState('workspace-chart');
                        
                        // Pastikan canvas terlihat
                        workspaceCanvas.style.display = 'block';
                        workspaceCanvas.style.visibility = 'visible';
                        
                        try {
                            const ctx = workspaceCanvas.getContext('2d');
                            console.log('Got workspace canvas context:', ctx);
                            
                            charts.workspace = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: workspaceData.map(w => {
                                        const name = w.name || 'Unknown';
                                        const maxLen = isMobile ? 10 : 15;
                                        return name.length > maxLen ? name.substring(0, maxLen) + '...' : name;
                                    }),
                                    datasets: [{
                                        label: 'Tugas',
                                        data: workspaceData.map(w => parseInt(w.tasks) || 0),
                                        backgroundColor: '#8b5cf6',
                                        borderRadius: isMobile ? 4 : 8
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { 
                                        legend: { display: false },
                                        tooltip: {
                                            callbacks: {
                                                label: function(ctx) { return `${ctx.parsed.y} tugas`; }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: { 
                                            beginAtZero: true, 
                                            ticks: { 
                                                stepSize: 1,
                                                font: { size: fontSize }
                                            }, 
                                            grid: { color: '#f3f4f6' } 
                                        },
                                        x: { 
                                            grid: { display: false },
                                            ticks: {
                                                font: { size: fontSize }
                                            }
                                        }
                                    }
                                }
                            });
                            console.log('✅ Workspace chart created successfully:', charts.workspace);
                        } catch (chartError) {
                            console.error('❌ Error creating workspace chart:', chartError);
                            showError('Gagal membuat chart workspace: ' + chartError.message);
                        }
                    }
                }
                
                console.log('=== CHARTS UPDATE COMPLETE ===');
            } catch (error) {
                console.error('❌ FATAL ERROR in updateCharts:', error);
                console.error('Error stack:', error.stack);
                showError('Gagal membuat diagram: ' + error.message);
            }
        }

        // Handle window resize for responsive charts
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (charts.task || charts.workspace) {
                    console.log('Window resized, re-rendering charts');
                    const taskCanvas = document.getElementById('taskChart');
                    const workspaceCanvas = document.getElementById('workspaceChart');
                    if (taskCanvas && charts.task) {
                        charts.task.resize();
                    }
                    if (workspaceCanvas && charts.workspace) {
                        charts.workspace.resize();
                    }
                }
            }, 250);
        });
    </script>

    <style>
        * { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        canvas { image-rendering: crisp-edges; }
        .loading-dash { display: inline-block; }
        [id$="-bar"] { transition: width 0.5s ease-in-out; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .animate-spin { animation: spin 1s linear infinite; }
    </style>
</x-app-layout>