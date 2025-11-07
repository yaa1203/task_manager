<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>TaskFlow Admin</title>
    <meta name="title" content="TaskFlow Admin">
    <meta name="description" content="Panel administrasi untuk mengelola TaskFlow">
    <meta name="author" content="TaskFlow">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="TaskFlow Admin">
    <meta name="application-name" content="TaskFlow Admin">
    <meta name="msapplication-TileColor" content="#3b82f6">
    <meta name="msapplication-TileImage" content="/icons/logo.png">
    
    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/logo72x72.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/logo.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/logo.png">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    
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
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
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
            color: #3b82f6;
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
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .pwa-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
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
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
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
            0%, 100% { box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4); }
            50% { box-shadow: 0 8px 32px rgba(59, 130, 246, 0.6), 0 0 0 8px rgba(59, 130, 246, 0.1); }
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
            outline: 3px solid #3b82f6;
            outline-offset: 2px;
        }
        
        @media (max-width: 640px) {
            main {
                padding-bottom: env(safe-area-inset-bottom, 20px);
            }
        }
        
        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #cbd5e1, #94a3b8);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #94a3b8, #64748b);
        }

        /* Smooth transitions */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        /* Animation keyframes */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Focus styles for accessibility */
        button:focus-visible,
        a:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Loading state helper */
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.6;
        }

        /* Gradient text effect */
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Better touch targets for mobile */
        @media (max-width: 1023px) {
            button, a {
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* Ensure overlay covers everything including loading modals */
        #sidebar-overlay {
            position: fixed;
            inset: 0;
        }

        /* Fix for z-index stacking */
        #sidebar {
            position: fixed;
        }

        @media (min-width: 1024px) {
            #sidebar {
                position: static;
            }
        }

        /* Touch manipulation for better mobile responsiveness */
        .touch-manipulation {
            touch-action: manipulation;
            -webkit-user-select: none;
            user-select: none;
        }

        /* Prevent text selection on buttons */
        button {
            -webkit-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100">

<!-- Offline Banner -->
<div id="offline-banner" class="banner offline-banner">
    ⚠️ Anda sedang offline. Beberapa fitur mungkin terbatas.
</div>

<div class="flex h-screen overflow-hidden">
    
    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden transition-all duration-300 opacity-0"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 w-72 bg-white shadow-2xl lg:shadow-none border-r border-gray-200 transition-transform duration-300 ease-out z-50 flex flex-col">
        
        {{-- Logo Header --}}
        <div class="p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white rounded-xl blur-md opacity-40 group-hover:opacity-60 transition-opacity"></div>
                        @if(file_exists(public_path('icons/logo72x72.png')))
                            <img src="{{ asset('icons/logo72x72.png') }}" alt="Logo" class="relative h-10 w-10 rounded-xl shadow-lg" />
                        @else
                            {{-- Fallback SVG Logo --}}
                            <div class="relative h-10 w-10 rounded-xl shadow-lg bg-white flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="text-xl font-bold text-white tracking-tight">TaskFlow</span>
                        <p class="text-xs text-blue-100">Admin Panel</p>
                    </div>
                </a>
                <button id="close-sidebar" class="lg:hidden text-white/80 hover:text-white p-2 hover:bg-white/10 rounded-lg transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-8">
            {{-- Main Menu Section --}}
            <div>
                <h3 class="px-3 mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</h3>
                <div class="space-y-1">
                    @php
                    $menuItems = [
                        [
                            'route' => 'admin.dashboard', 
                            'url' => null, 
                            'label' => 'Dashboard', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                            'desc' => 'Ringkasan data'
                        ],
                        [
                            'route' => 'users.*', 
                            'url' => 'users', 
                            'label' => 'Pengguna', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
                            'desc' => 'Kelola user'
                        ],
                        [
                            'route' => 'workspaces.*', 
                            'url' => 'workspaces', 
                            'label' => 'Workspace', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
                            'desc' => 'Ruang kerja'
                        ],
                        [
                            'route' => 'analytict.*', 
                            'url' => 'analytict', 
                            'label' => 'Analitik', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                            'desc' => 'Laporan & data'
                        ],
                        [
                            'route' => 'notifications.*', 
                            'url' => 'notifications', 
                            'label' => 'Notifikasi', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
                            'isNotification' => true,
                            'desc' => 'Pesan masuk'
                        ]
                    ];
                    @endphp

                    @foreach($menuItems as $item)
                    <a href="{{ $item['url'] ? url($item['url']) : route($item['route']) }}"
                       class="group relative flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs($item['route']) ? 'bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-lg shadow-blue-500/30 scale-[1.02]' : 'text-gray-700 hover:bg-gray-50 hover:scale-[1.01]' }}">
                        
                        @if(request()->routeIs($item['route']))
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl blur-lg opacity-20"></div>
                        @endif
                        
                        <span class="relative flex items-center gap-3 flex-1 min-w-0">
                            <div class="flex-shrink-0 {{ request()->routeIs($item['route']) ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-gray-200' }} p-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5 {{ request()->routeIs($item['route']) ? 'text-white' : 'text-gray-600 group-hover:text-gray-800' }}" 
                                     fill="none" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    {!! $item['icon'] !!}
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold truncate">{{ $item['label'] }}</div>
                                <div class="text-xs {{ request()->routeIs($item['route']) ? 'text-white/70' : 'text-gray-500' }} truncate hidden lg:block">
                                    {{ $item['desc'] }}
                                </div>
                            </div>
                        </span>
                        
                        @if(isset($item['isNotification']) && Auth::user()->unreadNotifications->count())
                        <span class="relative flex items-center justify-center min-w-[22px] h-[22px] bg-red-500 to-pink-500 text-white text-xs font-bold px-1.5 rounded-full shadow-lg">
                            {{ Auth::user()->unreadNotifications->count() > 99 ? '99+' : Auth::user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
        </nav>

        {{-- User Profile & Logout --}}
        <div class="p-4 border-t border-gray-200 bg-gradient-to-br from-gray-50 to-white">
            <div class="mb-3 px-3 py-2 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-1">
                 <a href="{{ url('/admin/profile') }}" target="_blank" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-white hover:text-blue-600 rounded-lg transition-all group">
                    <div class="w-8 h-8 bg-gray-100 group-hover:bg-blue-50 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20a6 6 0 0112 0"/>
                        </svg>
                    </div>
                    <span>Lihat Profile</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 rounded-lg transition-all group">
                        <div class="w-8 h-8 bg-red-50 group-hover:bg-red-100 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top Navigation Bar (Mobile) --}}
        <header class="lg:hidden sticky top-0 z-[35] bg-white border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between px-4 py-3">
                <button id="open-sidebar" type="button" class="p-2 text-gray-600 hover:bg-gray-100 active:bg-gray-200 rounded-lg transition-colors touch-manipulation">
                    <svg class="w-6 h-6 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <div class="flex items-center gap-2">
                    @if(file_exists(public_path('icons/logo72x72.png')))
                        <img src="{{ asset('icons/logo72x72.png') }}" alt="Logo" class="relative w-8 h-8 rounded-full shadow-md" />
                    @else
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg shadow-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    @endif
                    <span class="text-lg font-bold text-gray-900">TaskFlow</span>
                </div>
                
                <div class="w-10">
                    {{-- Spacer for alignment --}}
                </div>
            </div>
        </header>

        {{-- Main Content Area --}}
        <main class="flex-1 overflow-y-auto relative">
            <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<!-- PWA Install Prompt -->
<div id="pwa-install-prompt" class="pwa-install-prompt">
    <div class="pwa-content">
        <div class="pwa-icon">
            <img src="/icons/logo72x72.png" alt="TaskFlow Admin Icon">
        </div>
        <div class="pwa-text">
            <div class="pwa-title">Instal TaskFlow Admin</div>
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
    // Sidebar Toggle with smooth animations
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');

    function openSidebar(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.add('opacity-100'), 10);
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        sidebar.classList.add('-translate-x-full');
        overlay.classList.remove('opacity-100');
        setTimeout(() => overlay.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    }

    // Add event listeners with better handling
    if (openBtn) {
        openBtn.addEventListener('click', openSidebar, { passive: false });
        openBtn.addEventListener('touchstart', openSidebar, { passive: false });
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar, { passive: false });
        closeBtn.addEventListener('touchstart', closeSidebar, { passive: false });
    }
    
    if (overlay) {
        overlay.addEventListener('click', closeSidebar, { passive: false });
        overlay.addEventListener('touchstart', closeSidebar, { passive: false });
    }

    // Close sidebar when clicking nav links on mobile
    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                setTimeout(closeSidebar, 150);
            }
        });
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
                document.body.style.overflow = '';
            }
        }, 250);
    });

    // Smooth scroll behavior
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    });

    // Add subtle animation on page load
    window.addEventListener('load', () => {
        document.body.style.opacity = '0';
        setTimeout(() => {
            document.body.style.transition = 'opacity 0.3s ease';
            document.body.style.opacity = '1';
        }, 100);
    });

    // Prevent scroll on body when sidebar is open (mobile)
    document.addEventListener('touchmove', function(e) {
        if (!sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
            if (!sidebar.contains(e.target)) {
                e.preventDefault();
            }
        }
    }, { passive: false });
    
    // PWA Implementation - Fully Fixed Version
    (function() {
        'use strict';
        
        const CONFIG = {
            SHOW_DELAY: 3000,
            STORAGE_KEYS: {
                PROMPT_SHOWN: 'pwa-full-prompt-shown'
            }
        };
        
        let deferredPrompt = null;
        
        const elements = {
            fullPrompt: document.getElementById('pwa-install-prompt'),
            miniPrompt: document.getElementById('pwa-mini-prompt'),
            installBtn: document.getElementById('pwa-install-btn'),
            laterBtn: document.getElementById('pwa-later-btn'),
            closeBtn: document.querySelector('.pwa-close-btn'),
            offlineBanner: document.getElementById('offline-banner'),
        };
        
        const storage = {
            get(key) {
                try {
                    const value = localStorage.getItem(key);
                    return value ? JSON.parse(value) : null;
                } catch (e) { 
                    console.error('Storage get error:', e);
                    return null; 
                }
            },
            set(key, value) {
                try { 
                    localStorage.setItem(key, JSON.stringify(value)); 
                    console.log('Storage set:', key, value);
                } catch (e) {
                    console.error('Storage set error:', e);
                }
            },
            remove(key) {
                try { 
                    localStorage.removeItem(key); 
                    console.log('Storage removed:', key);
                } catch (e) {
                    console.error('Storage remove error:', e);
                }
            }
        };
        
        // Cek apakah full prompt sudah pernah ditampilkan
        function hasFullPromptBeenShown() {
            const shown = storage.get(CONFIG.STORAGE_KEYS.PROMPT_SHOWN) === true;
            console.log('hasFullPromptBeenShown:', shown);
            return shown;
        }
        
        function markFullPromptAsShown() {
            storage.set(CONFIG.STORAGE_KEYS.PROMPT_SHOWN, true);
            console.log('Full prompt marked as shown');
        }
        
        function shouldShowFullPrompt() {
            if (window.matchMedia('(display-mode: standalone)').matches) {
                console.log('App already installed (standalone mode)');
                return false;
            }
            if (hasFullPromptBeenShown()) {
                console.log('Full prompt already shown before');
                return false;
            }
            return true;
        }
        
        function showFullPrompt() {
            console.log('showFullPrompt called, deferredPrompt:', !!deferredPrompt);
            if (!deferredPrompt) {
                console.warn('No deferredPrompt available');
                return;
            }
            
            console.log('Showing full prompt...');
            elements.miniPrompt?.classList.remove('show');
            elements.fullPrompt?.classList.add('show');
            markFullPromptAsShown();
        }
        
        function showMiniPrompt() {
            console.log('showMiniPrompt called, deferredPrompt:', !!deferredPrompt);
            if (!deferredPrompt) {
                console.warn('No deferredPrompt available');
                return;
            }
            
            console.log('Showing mini prompt...');
            elements.fullPrompt?.classList.remove('show');
            setTimeout(() => {
                elements.miniPrompt?.classList.add('show');
            }, 300);
        }
        
        function hideAllPrompts() {
            console.log('Hiding all prompts');
            elements.fullPrompt?.classList.remove('show');
            elements.miniPrompt?.classList.remove('show');
        }
        
        async function handleInstall() {
            console.log('handleInstall called');
            if (!deferredPrompt) {
                console.warn('No deferredPrompt in handleInstall');
                return;
            }
            
            hideAllPrompts();
            
            try {
                console.log('Showing native install prompt...');
                await deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log('User choice:', outcome);
                
                if (outcome === 'accepted') {
                    storage.remove(CONFIG.STORAGE_KEYS.PROMPT_SHOWN);
                }
                
                deferredPrompt = null;
            } catch (error) {
                console.error('Install error:', error);
            }
        }
        
        // Event Listeners
        if (elements.installBtn) {
            elements.installBtn.addEventListener('click', (e) => {
                console.log('Install button clicked');
                e.preventDefault();
                e.stopPropagation();
                handleInstall();
            });
        }
        
        if (elements.laterBtn) {
            elements.laterBtn.addEventListener('click', (e) => {
                console.log('Later button clicked');
                e.preventDefault();
                e.stopPropagation();
                hideAllPrompts();
                setTimeout(() => showMiniPrompt(), 300);
            });
        }
        
        if (elements.closeBtn) {
            elements.closeBtn.addEventListener('click', (e) => {
                console.log('Close button clicked');
                e.preventDefault();
                e.stopPropagation();
                hideAllPrompts();
                setTimeout(() => showMiniPrompt(), 300);
            });
        }
        
        // CRITICAL FIX: Mini prompt click handler dengan logging
        if (elements.miniPrompt) {
            elements.miniPrompt.addEventListener('click', (e) => {
                console.log('Mini prompt clicked!');
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Current deferredPrompt:', !!deferredPrompt);
                console.log('Mini prompt has "show" class:', elements.miniPrompt.classList.contains('show'));
                console.log('Full prompt has "show" class:', elements.fullPrompt.classList.contains('show'));
                
                // Force hide mini, force show full
                elements.miniPrompt.classList.remove('show');
                
                // Use timeout to ensure transition
                setTimeout(() => {
                    if (deferredPrompt) {
                        console.log('Adding show class to full prompt');
                        elements.fullPrompt.classList.add('show');
                    } else {
                        console.error('No deferredPrompt available when clicking mini prompt!');
                    }
                }, 100);
            });
            
            // Also add keyboard support
            elements.miniPrompt.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    console.log('Mini prompt activated via keyboard');
                    e.preventDefault();
                    elements.miniPrompt.click();
                }
            });
        }
        
        // beforeinstallprompt - Simpan event
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('beforeinstallprompt event fired');
            e.preventDefault();
            deferredPrompt = e;
            console.log('deferredPrompt saved:', !!deferredPrompt);
            
            // Tunggu sebentar, lalu cek apakah boleh tampilkan full prompt
            setTimeout(() => {
                if (shouldShowFullPrompt()) {
                    console.log('Will show full prompt');
                    showFullPrompt();
                } else {
                    console.log('Will show mini prompt');
                    showMiniPrompt();
                }
            }, CONFIG.SHOW_DELAY);
        });
        
        // appinstalled
        window.addEventListener('appinstalled', () => {
            console.log('App installed!');
            hideAllPrompts();
            storage.remove(CONFIG.STORAGE_KEYS.PROMPT_SHOWN);
        });
        
        // Service Worker Update
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(() => console.log('Service Worker registered'))
                .catch(err => console.error('SW registration failed:', err));
        }
        
        // Online/Offline Banner
        function updateOnlineStatus() {
            const isOnline = navigator.onLine;
            console.log('Online status:', isOnline);
            elements.offlineBanner?.classList.toggle('show', !isOnline);
        }
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        updateOnlineStatus();
        
        console.log('✅ PWA Script initialized with full debugging');
        console.log('Elements found:', {
            fullPrompt: !!elements.fullPrompt,
            miniPrompt: !!elements.miniPrompt,
            installBtn: !!elements.installBtn,
            laterBtn: !!elements.laterBtn,
            closeBtn: !!elements.closeBtn
        });
    })();
</script>
<script>
    // Reset PWA prompt saat logout
    document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.querySelector('form[action*="logout"] button');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function() {
                try {
                    localStorage.removeItem('pwa-full-prompt-shown');
                } catch (e) {}
            });
        }
    });
</script>
</body>
</html>