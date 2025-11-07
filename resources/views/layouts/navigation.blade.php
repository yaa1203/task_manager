<nav class="bg-white border-b border-gray-100 z-50 h-16">
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
                    <span class="hidden sm:block text-lg font-bold text-gray-900">TaskFlow</span>
                </a>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden lg:flex items-center space-x-2">
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['url' => 'my-workspaces', 'label' => 'Workspace', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H9m12 0a1 1 0 001 1h-3.5a1 1 0 01-1-1m-6.5 0a1 1 0 01-1-1H4a1 1 0 011-1m6 0a1 1 0 011 1h-3.5a1 1 0 01-1-1'],
                        ['url' => 'calendar', 'label' => 'Calendar', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['url' => 'analytics', 'label' => 'Analytics', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ['url' => 'notifikasi', 'label' => 'Notifikasi', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'badge' => Auth::user()->unreadNotifications->count()],
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
                        @if(isset($item['badge']) && $item['badge'] > 0)
                            <span class="ml-1 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                {{ $item['badge'] > 9 ? '9+' : $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right: Notifikasi + User -->
            <div class="flex items-center space-x-3">
                <!-- Notifikasi -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false" x-on:keydown.escape="open = false">
                    <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 transition relative focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center ring-2 ring-white">
                                {{ Auth::user()->unreadNotifications->count() > 9 ? '9+' : Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Notifikasi -->
                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-150" 
                        x-transition:enter-start="opacity-0 scale-90" 
                        x-transition:enter-end="opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-100" 
                        x-transition:leave-start="opacity-100 scale-100" 
                        x-transition:leave-end="opacity-0 scale-90" 
                        class="absolute mt-2 w-80 sm:w-96 max-w-[90vw] bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 origin-top
                                md:right-0 md:left-auto md:translate-x-0
                                left-1/2 -translate-x-1/2 right-auto mobile-center-dropdown"
                        style="min-width: 280px;">
                        <div class="p-3 sm:p-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                            <div class="flex justify-between items-center">
                                <h3 class="text-sm font-bold text-white">Notifikasi</h3>
                                @if (Auth::user()->unreadNotifications->count() > 0)
                                    <span class="text-xs bg-white text-indigo-600 px-1.5 py-0.5 rounded-full font-bold">
                                        {{ Auth::user()->unreadNotifications->count() }} baru
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            @php $notifications = Auth::user()->unreadNotifications()->latest()->take(5)->get(); @endphp
                            @forelse($notifications as $n)
                                <a href="#" onclick="event.preventDefault(); markAsReadAndRedirect('{{ route('notifikasi.read', $n) }}', '{{ $n->data['url'] ?? '#' }}')"
                                class="block p-2 sm:p-3 hover:bg-gray-50 border-b border-gray-100 transition">
                                    <div class="flex gap-2 sm:gap-3">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900">{{ $n->data['title'] ?? 'Notifikasi' }}</p>
                                            <p class="text-xs text-gray-600 line-clamp-2">{{ $n->data['message'] ?? '' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 sm:p-6 text-center text-gray-500">
                                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="mt-2 text-sm">Tidak ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2 sm:p-3 bg-gray-50 border-t">
                            <a href="{{ route('notifikasi.index') }}" class="block text-center text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                                Lihat semua
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50">
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

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-full hover:bg-gray-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="hamburger" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path class="close hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Full Overlay Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-white z-40 hidden flex flex-col lg:hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <button id="close-mobile-menu" class="p-2 hover:bg-gray-100 rounded-lg">
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
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Profil</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
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

<!-- Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const close = document.getElementById('close-mobile-menu');
        const hamburger = btn?.querySelector('.hamburger');
        const x = btn?.querySelector('.close');

        const open = () => {
            menu.classList.remove('hidden');
            setTimeout(() => menu.classList.add('opacity-100'), 10);
            document.body.style.overflow = 'hidden';
            hamburger?.classList.add('hidden');
            x?.classList.remove('hidden');
        };

        const closeMenu = () => {
            menu.classList.remove('opacity-100');
            setTimeout(() => {
                menu.classList.add('hidden');
                document.body.style.overflow = '';
                hamburger?.classList.remove('hidden');
                x?.classList.add('hidden');
            }, 300);
        };

        btn?.addEventListener('click', open);
        close?.addEventListener('click', closeMenu);

        menu.querySelectorAll('a, button[type="submit"]').forEach(el => {
            el.addEventListener('click', () => setTimeout(closeMenu, 150));
        });
    });

    function markAsReadAndRedirect(url, redirect) {
        if (redirect === '#') return;
        fetch(url, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
        }).then(() => location.href = redirect).catch(() => location.href = redirect);
    }
</script>

<style>
    #mobile-menu { transition: opacity 0.3s ease; opacity: 0; }
    #mobile-menu.opacity-100 { opacity: 1; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    @media (max-width: 767px) {
        .mobile-center-dropdown {
            left: auto !important;
            right: auto !important;
            transform: translateX(-50%) !important;
        }
    }
</style>