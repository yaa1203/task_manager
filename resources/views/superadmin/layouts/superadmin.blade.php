<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Super Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50">

<div class="flex h-screen overflow-hidden">

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden transition-opacity"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out z-30 flex flex-col">

        {{-- Logo Header --}}
        <div class="p-6 flex items-center justify-between border-b">
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-2">
                <img src="{{ asset('icons/logo72x72.png') }}" alt="Logo" class="h-8 w-8 rounded-full" />
                <span class="text-lg font-bold">Super Admin</span>
            </a>
            <button id="close-sidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <div class="space-y-1">
                @php
                    $menuItems = [
                        [
                            'route' => 'superadmin.dashboard', 
                            'url' => null, 
                            'label' => 'Dashboard', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'
                        ],
                        [
                            'route' => 'pengguna.*', 
                            'url' => null, 
                            'label' => 'Pengguna', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
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
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>'
                                ]
                            ]
                        ],
                        [
                            'route' => 'space.*', 
                            'url' => 'space', 
                            'label' => 'Workspace', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>'
                        ],
                        [
                            'route' => 'categories.*', 
                            'url' => 'categories', 
                            'label' => 'Kategori Pengguna', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'
                        ],
                        [
                            'route' => '#', 
                            'url' => '#', 
                            'label' => 'Analitiks', 
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'
                        ],
                    ];
                @endphp

                @foreach($menuItems as $item)
                    @if(isset($item['submenu']))
                        {{-- Menu dengan submenu --}}
                        <div class="relative">
                            <button class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ (request()->is('pengguna/admin') || request()->is('pengguna/admin/*') || request()->is('pengguna/user') || request()->is('pengguna/user/*')) ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}"
                                    onclick="toggleSubmenu('submenu-{{ $loop->index }}')">
                                <span class="flex items-center gap-3">
                                    <svg class="w-5 h-5 {{ (request()->is('pengguna/admin') || request()->is('pengguna/admin/*') || request()->is('pengguna/user') || request()->is('pengguna/user/*')) ? 'text-purple-600' : 'text-gray-400' }}" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24">
                                        {!! $item['icon'] !!}
                                    </svg>
                                    <span>{{ $item['label'] }}</span>
                                </span>
                                <svg id="submenu-arrow-{{ $loop->index }}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div id="submenu-{{ $loop->index }}" class="hidden mt-1 ml-4 space-y-1">
                                @foreach($item['submenu'] as $subitem)
                                    <a href="{{ route($subitem['route']) }}"
                                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->is('pengguna/admin') || request()->is('pengguna/admin/*') ? 'bg-purple-100 text-purple-700' : (request()->is('pengguna/user') || request()->is('pengguna/user/*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100') }}">
                                        <svg class="w-4 h-4 {{ request()->is('pengguna/admin') || request()->is('pengguna/admin/*') ? 'text-purple-600' : (request()->is('pengguna/user') || request()->is('pengguna/user/*') ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-600') }}" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24">
                                            {!! $subitem['icon'] !!}
                                        </svg>
                                        <span>{{ $subitem['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Menu tanpa submenu --}}
                        <a href="{{ $item['url'] ? url($item['url']) : route($item['route']) }}"
                           class="group flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs($item['route']) || (isset($item['route']) && str_contains($item['route'], '*') && request()->is($item['route'])) ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ request()->routeIs($item['route']) || (isset($item['route']) && str_contains($item['route'], '*') && request()->is($item['route'])) ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-600' }}" 
                                     fill="none" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    {!! $item['icon'] !!}
                                </svg>
                                <span>{{ $item['label'] }}</span>
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>
        </nav>

        {{-- User Profile & Logout --}}
        <div class="p-4 border-t border-gray-200 bg-gray-50/50">
            <div class="mb-3 px-2">
                <div class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
            </div>
            <div class="space-y-1">
                <a href="{{ url('/') }}" target="_blank" 
                   class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-600 hover:bg-white hover:text-gray-900 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Lihat Website</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2.5 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>
</div>

{{-- JavaScript untuk toggle submenu --}}
<script>
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
</script>

</body>
</html>