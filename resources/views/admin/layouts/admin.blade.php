<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen overflow-hidden">
    
    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 hidden lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 w-64 bg-white border-r border-gray-200 shadow-lg transition-transform duration-300 ease-in-out z-30 flex flex-col">
        
        {{-- Logo Header --}}
        <div class="p-4 flex items-center justify-between border-b">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <x-application-logo class="h-8 w-8 text-indigo-600" />
                <span class="text-lg font-bold">Admin Panel</span>
            </a>
            <button id="close-sidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            @php
            $menuItems = [
                ['route' => 'admin.dashboard', 'url' => null, 'label' => 'Dashboard Admin', 'icon' => '📊'],
                ['route' => 'users.*', 'url' => 'users', 'label' => 'User Management', 'icon' => '👥'],
                ['route' => 'project.*', 'url' => 'project', 'label' => 'Projects', 'icon' => '📁'],
                ['route' => 'task.*', 'url' => 'task', 'label' => 'Tasks', 'icon' => '✅'],
                ['route' => 'analytict.*', 'url' => 'analytict', 'label' => 'Analytics', 'icon' => '📈'],
                ['route' => 'notifications.*', 'url' => 'notifications', 'label' => 'Notifications', 'icon' => '🔔', 'isNotification' => true]
            ];
            @endphp

            @foreach($menuItems as $item)
            <a href="{{ $item['url'] ? url($item['url']) : route($item['route']) }}"
               class="flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs($item['route']) ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="flex items-center gap-2">
                    <span>{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </span>
                @if(isset($item['isNotification']) && Auth::user()->unreadNotifications->count())
                <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                    {{ Auth::user()->unreadNotifications->count() }}
                </span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- User Profile & Logout --}}
        <div class="p-4 border-t bg-gray-50">
            <div class="mb-3">
                <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="space-y-2">
                <a href="{{ url('dashboard') }}" target="_blank" 
                   class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View Website
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        
        {{-- Mobile Header --}}
        <header class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
            <button id="open-sidebar" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="text-lg font-bold">Admin Panel</span>
            <div class="w-6"></div>
        </header>

        {{-- Main Content Area --}}
        <main class="flex-1 overflow-y-auto">
            <div class="p-4 sm:p-6">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    openBtn?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);

    // Close sidebar on route change (for SPA-like behavior)
    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            closeSidebar();
            document.body.style.overflow = '';
        }
    });
</script>

</body>
</html>