<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>TaskFlow Super Admin</title>
    <meta name="title" content="TaskFlow Super Admin">
    <meta name="description" content="Panel super admin untuk mengelola TaskFlow">
    <meta name="author" content="TaskFlow">
    
    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/logo72x72.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/logo.png">
    <link rel="shortcut icon" href="/favicon.ico">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
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
            outline: 2px solid #8b5cf6;
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
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
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

        /* Submenu animation */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .submenu-enter {
            animation: slideDown 0.2s ease-out;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100">

<div class="flex h-screen overflow-hidden">
    
    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden transition-all duration-300 opacity-0"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 w-72 bg-white shadow-2xl lg:shadow-none border-r border-gray-200 transition-transform duration-300 ease-out z-50 flex flex-col">
        
        {{-- Logo Header --}}
        <div class="p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-purple-600 to-pink-600">
            <div class="flex items-center justify-between">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white rounded-xl blur-md opacity-40 group-hover:opacity-60 transition-opacity"></div>
                        @if(file_exists(public_path('icons/logo72x72.png')))
                            <img src="{{ asset('icons/logo72x72.png') }}" alt="Logo" class="relative h-10 w-10 rounded-full shadow-lg" />
                        @else
                            {{-- Fallback SVG Logo --}}
                            <div class="relative h-10 w-10 rounded-xl shadow-lg bg-white flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="text-xl font-bold text-white tracking-tight">TaskFlow</span>
                        <p class="text-xs text-purple-100">Super Admin</p>
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
                            'route' => 'superadmin.dashboard', 
                            'url' => null, 
                            'label' => 'Dashboard', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                            'desc' => 'Ringkasan data'
                        ],
                        [
                            'route' => 'pengguna.*', 
                            'url' => null, 
                            'label' => 'Pengguna', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
                            'desc' => 'Kelola pengguna',
                            'submenu' => [
                                [
                                    'route' => 'pengguna.admin', 
                                    'url' => null, 
                                    'label' => 'Admin', 
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'
                                ],
                                [
                                    'route' => 'pengguna.user', 
                                    'url' => null, 
                                    'label' => 'User', 
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'
                                ]
                            ]
                        ],
                        [
                            'route' => 'space.*', 
                            'url' => 'space', 
                            'label' => 'Workspace', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
                            'desc' => 'Ruang kerja'
                        ],
                        [
                            'route' => 'categories.*', 
                            'url' => 'categories', 
                            'label' => 'Kategori', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
                            'desc' => 'Kategori pengguna'
                        ],
                        [
                            'route' => '#', 
                            'url' => 'superadmin/analytics', 
                            'label' => 'Analitik', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                            'desc' => 'Laporan & data'
                        ],
                    ];
                    @endphp

                    @foreach($menuItems as $item)
                        @if(isset($item['submenu']))
                            {{-- Menu dengan submenu --}}
                            @php
                                $isSubmenuActive = false;
                                foreach($item['submenu'] as $subitem) {
                                    if(request()->routeIs($subitem['route'])) {
                                        $isSubmenuActive = true;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="relative">
                                <button type="button"
                                        class="w-full group relative flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $isSubmenuActive ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'text-gray-700 hover:bg-gray-50 hover:scale-[1.01]' }}"
                                        onclick="toggleSubmenu('submenu-{{ $loop->index }}')">
                                    
                                    <span class="relative flex items-center gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0 {{ $isSubmenuActive ? 'bg-purple-100' : 'bg-gray-100 group-hover:bg-gray-200' }} p-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5 {{ $isSubmenuActive ? 'text-purple-700' : 'text-gray-600 group-hover:text-gray-800' }}" 
                                                 fill="none" 
                                                 stroke="currentColor" 
                                                 viewBox="0 0 24 24">
                                                {!! $item['icon'] !!}
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0 text-left">
                                            <div class="font-semibold truncate">{{ $item['label'] }}</div>
                                            <div class="text-xs {{ $isSubmenuActive ? 'text-purple-600' : 'text-gray-500' }} truncate hidden lg:block">
                                                {{ $item['desc'] }}
                                            </div>
                                        </div>
                                    </span>
                                    
                                    <svg id="submenu-arrow-{{ $loop->index }}" 
                                         class="w-4 h-4 flex-shrink-0 transform transition-transform duration-200 {{ $isSubmenuActive ? 'text-purple-700' : '' }}" 
                                         fill="none" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div id="submenu-{{ $loop->index }}" class="hidden mt-2 ml-4 space-y-1 submenu-enter">
                                    @foreach($item['submenu'] as $subitem)
                                        <a href="{{ route($subitem['route']) }}"
                                           class="group relative flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs($subitem['route']) ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg shadow-purple-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                                            @if(request()->routeIs($subitem['route']))
                                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg blur-lg opacity-20"></div>
                                            @endif
                                            <div class="flex-shrink-0 {{ request()->routeIs($subitem['route']) ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-gray-200' }} p-1.5 rounded-lg transition-colors">
                                                <svg class="w-4 h-4 {{ request()->routeIs($subitem['route']) ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}" 
                                                     fill="none" 
                                                     stroke="currentColor" 
                                                     viewBox="0 0 24 24">
                                                    {!! $subitem['icon'] !!}
                                                </svg>
                                            </div>
                                            <span class="relative truncate">{{ $subitem['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            {{-- Menu tanpa submenu --}}
                            <a href="{{ $item['url'] ? url($item['url']) : route($item['route']) }}"
                               class="group relative flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs($item['route']) ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg shadow-purple-500/30 scale-[1.02]' : 'text-gray-700 hover:bg-gray-50 hover:scale-[1.01]' }}">
                                
                                @if(request()->routeIs($item['route']))
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl blur-lg opacity-20"></div>
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
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </nav>

        {{-- User Profile & Logout --}}
        <div class="p-4 border-t border-gray-200 bg-gradient-to-br from-gray-50 to-white">
            <div class="mb-3 px-3 py-2 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-1">
                <a href="{{ url('superadmin/profile') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-purple-700 hover:bg-purple-50 rounded-lg">
                    <div class="w-8 h-8 bg-gray-100 group-hover:bg-purple-50 rounded-lg flex items-center justify-center transition-colors">
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
                        <img src="{{ asset('icons/logo72x72.png') }}" alt="Logo" class="w-8 h-8 rounded-lg shadow-md" />
                    @else
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-md flex items-center justify-center">
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

<script>
    // Toggle Submenu
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        const arrow = document.getElementById('submenu-arrow-' + id.split('-')[1]);
        
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }

    // Auto-expand submenu jika ada menu aktif di dalamnya
    document.addEventListener('DOMContentLoaded', function() {
        const activeSubmenuItems = document.querySelectorAll('.bg-purple-100');
        activeSubmenuItems.forEach(item => {
            const submenu = item.closest('[id^="submenu-"]');
            if (submenu) {
                const submenuId = submenu.id;
                const arrowId = 'submenu-arrow-' + submenuId.split('-')[1];
                submenu.classList.remove('hidden');
                const arrow = document.getElementById(arrowId);
                if (arrow) arrow.classList.add('rotate-180');
            }
        });
    });

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

    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Close sidebar with ESC key
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });

    // Prevent body scroll when modal/sidebar is open
    const preventScroll = () => {
        const scrollY = window.scrollY;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}px`;
        document.body.style.width = '100%';
    };

    const allowScroll = () => {
        const scrollY = document.body.style.top;
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        window.scrollTo(0, parseInt(scrollY || '0') * -1);
    };

    // Update open/close functions for better scroll handling
    const originalOpenSidebar = openSidebar;
    openSidebar = function(e) {
        originalOpenSidebar(e);
        if (window.innerWidth < 1024) {
            preventScroll();
        }
    };

    const originalCloseSidebar = closeSidebar;
    closeSidebar = function(e) {
        originalCloseSidebar(e);
        if (window.innerWidth < 1024) {
            allowScroll();
        }
    };

    // Add visual feedback for touch interactions
    document.querySelectorAll('button, a').forEach(element => {
        element.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        }, { passive: true });
        
        element.addEventListener('touchend', function() {
            this.style.transform = '';
        }, { passive: true });
    });

    // Performance optimization: Debounce scroll events
    let scrollTimer;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(function() {
            // Add any scroll-based logic here
        }, 150);
    }, { passive: true });

    // Check for reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) {
        document.querySelectorAll('*').forEach(el => {
            el.style.transition = 'none';
            el.style.animation = 'none';
        });
    }

    // Add loading state helper
    function setLoadingState(element, isLoading) {
        if (isLoading) {
            element.classList.add('loading');
            element.setAttribute('disabled', 'disabled');
        } else {
            element.classList.remove('loading');
            element.removeAttribute('disabled');
        }
    }

    // Form submission handler with loading state
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                setLoadingState(submitBtn, true);
                
                // Reset after 10 seconds as fallback
                setTimeout(() => {
                    setLoadingState(submitBtn, false);
                }, 10000);
            }
        });
    });

    // Toast notification helper (if needed)
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 z-[9999] px-6 py-4 rounded-lg shadow-2xl transform translate-x-0 transition-all duration-300 max-w-sm ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'warning' ? 'bg-yellow-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        toast.textContent = message;
        toast.style.transform = 'translateX(400px)';
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Expose toast function globally
    window.showToast = showToast;

    // Handle AJAX errors globally
    window.addEventListener('unhandledrejection', function(event) {
        console.error('Unhandled promise rejection:', event.reason);
        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
    });

    // Add connection status indicator
    function updateConnectionStatus() {
        const isOnline = navigator.onLine;
        if (!isOnline) {
            showToast('Koneksi internet terputus', 'warning');
        }
    }

    window.addEventListener('online', () => {
        showToast('Koneksi internet kembali', 'success');
    });

    window.addEventListener('offline', updateConnectionStatus);

    // Initialize tooltips (if using)
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-[9999] px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-sm';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.bottom = '100%';
            tooltip.style.left = '50%';
            tooltip.style.transform = 'translateX(-50%) translateY(-8px)';
            tooltip.style.whiteSpace = 'nowrap';
            
            this.style.position = 'relative';
            this.appendChild(tooltip);
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this.removeChild(this._tooltip);
                this._tooltip = null;
            }
        });
    });

    // Log initialization
    console.log('✅ TaskFlow Super Admin Panel initialized');
    console.log('📱 Viewport:', window.innerWidth, 'x', window.innerHeight);
    console.log('🎨 Theme: Purple-Pink Gradient');
</script>

</body>
</html>