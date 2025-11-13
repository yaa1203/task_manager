{{-- Fixed Top Navbar --}}
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
                    <span class="hidden sm:block text-lg font-bold text-gray-900">TaskFlow</span>
                </a>
            </div>

            <!-- Desktop Nav -->
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
                        @if(isset($item['badge']) && $item['badge'] > 0)
                            <span class="ml-1 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                {{ $item['badge'] > 9 ? '9+' : $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right: Notifikasi + User + Hamburger -->
            <div class="flex items-center space-x-3">
                <!-- Notifikasi (Langsung ke Halaman) -->
                <a href="{{ route('notifikasi.index') }}" 
                class="relative p-2 rounded-full hover:bg-gray-100 transition focus:outline-none">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0 right-0 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center ring-2 ring-white text-center leading-none">
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
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-90"
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

                <!-- Hamburger Button (Mobile) -->
                <button x-data="{ mobileOpen: false }" 
                        @click="mobileOpen = !mobileOpen; $dispatch('toggle-mobile-menu')" 
                        class="lg:hidden p-2 rounded-full hover:bg-gray-100 transition focus:outline-none">
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

{{-- Add padding to content so it doesn't hide under fixed navbar --}}
<div class="pt-16">
    {{-- Your page content goes here --}}
</div>

<script>

    function notifDropdown() {
        return {
            open: false,
            left: 0,

            init() {
                // Update saat resize
                window.addEventListener('resize', () => this.updatePosition());
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.$nextTick(() => this.updatePosition());
                }
            },

            updatePosition() {
                if (!this.open) return;

                const trigger = this.$refs.trigger;
                const dropdown = this.$refs.dropdown;

                if (!trigger || !dropdown) return;

                const triggerRect = trigger.getBoundingClientRect();
                const dropdownWidth = dropdown.offsetWidth;
                const viewportWidth = window.innerWidth;

                // Hitung posisi tengah tombol
                const triggerCenter = triggerRect.left + triggerRect.width / 2;
                let idealLeft = triggerCenter - dropdownWidth / 2;

                // Batasi agar tidak keluar layar
                const padding = 16;
                const maxLeft = viewportWidth - dropdownWidth - padding;
                const minLeft = padding;

                this.left = Math.max(minLeft, Math.min(maxLeft, idealLeft));
            }
        };
    }

    // Pastikan Alpine sudah loaded
    document.addEventListener('alpine:init', () => {
        // Opsional: debounce resize
    });
</script>