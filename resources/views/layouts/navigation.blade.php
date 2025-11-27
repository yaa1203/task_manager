{{-- Fixed Top Navbar - Versi Cantik & Responsif --}}
<nav class="fixed top-0 left-0 right-0 PY-4 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 shadow-lg z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo & Brand -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/30 rounded-full blur-md scale-150 group-hover:scale-175 transition-transform duration-300"></div>
                        @if(file_exists(public_path('icons/logo72x72.png')))
                        <img src="{{ asset('icons/logo72x72.png') }}" alt="Logo" class="relative h-10 w-10 rounded-full shadow-xl border-2 border-white/50" />
                        @else
                        <div class="relative h-10 w-10 rounded-full shadow-xl bg-white/20 backdrop-blur-sm border-2 border-white/50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        @endif
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">TaskFlow</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
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
                $current = request()->is(($item['url'] ?? $item['route'] ?? '') . '*')
                || (!empty($item['route']) && request()->routeIs($item['route']));
                @endphp
                <a href="{{ isset($item['url']) ? url($item['url']) : route($item['route']) }}"
                    class="flex items-center gap-2.5 px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 {{ $current ? 'bg-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
                @endforeach
            </div>

            <!-- Right Side: Notif + User + Hamburger -->
            <div class="flex items-center gap-2">

                <!-- Notifikasi (Desktop) -->
                <a href="{{ route('notifikasi.index') }}" class="hidden lg:flex relative p-2.5 rounded-full hover:bg-white/10 transition">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="absolute top-1 right-1 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center ring-2 ring-blue-600">
                        {{ Auth::user()->unreadNotifications->count() > 99 ? '99+' : Auth::user()->unreadNotifications->count() }}
                    </span>
                    @endif
                </a>

                <!-- User Dropdown (Desktop) -->
                <div class="hidden lg:block relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 p-2 rounded-full hover:bg-white/10 transition">
                        <div class="w-9 h-9 rounded-full overflow-hidden ring-2 ring-white/50">
                            @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-white/30 backdrop-blur-sm flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            @endif
                        </div>
                        <span class="hidden md:block text-white font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-white transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                        <div class="p-3 space-y-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">Profil Saya</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile: Notif + Profile Avatar + Hamburger -->
                <div class="lg:hidden flex items-center gap-2">
                    <!-- Notifikasi Mobile -->
                    <a href="{{ route('notifikasi.index') }}" class="relative p-2.5 rounded-full hover:bg-white/10 transition">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0.5 right-0.5 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center ring-2 ring-blue-600 shadow-lg">
                            {{ Auth::user()->unreadNotifications->count() > 99 ? '99+' : Auth::user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </a>

                    <!-- Hamburger Button with Avatar -->
                    <button x-data="{ mobileOpen: false }"
                        @click="mobileOpen = !mobileOpen; $dispatch('toggle-mobile-menu')"
                        class="flex items-center gap-2 p-1.5 rounded-full hover:bg-white/10 transition focus:outline-none">
                        <div class="w-9 h-9 rounded-full overflow-hidden ring-2 ring-white/50 shadow-lg">
                            @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-white/30 backdrop-blur-sm flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            @endif
                        </div>
                    </button>
                </div>
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
        @click.self="mobileMenuOpen = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-end lg:hidden"
        style="display: none;">

        <!-- Menu Slide from Bottom -->
        <div x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            @click.stop
            class="w-full bg-white rounded-t-3xl shadow-2xl max-h-[85vh] flex flex-col">

            <!-- Header dengan Handle Bar -->
            <div class="flex-shrink-0">
                <!-- Handle Bar -->
                <div class="pt-3 pb-2 flex justify-center">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <!-- User Info Header -->
                <div class="px-6 py-4 bg-gradient-to-br from-blue-50 to-indigo-50 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-full overflow-hidden ring-4 ring-white shadow-lg">
                                @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-lg truncate">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <button @click="mobileMenuOpen = false" class="p-2 hover:bg-white/70 rounded-xl transition">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu (Scrollable) -->
            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
                @foreach($navItems as $item)
                @php $isActive = request()->is(($item['url'] ?? $item['route']) . '*') || request()->routeIs($item['route'] ?? ''); @endphp
                <a href="{{ isset($item['url']) ? url($item['url']) : route($item['route']) }}"
                    @click="mobileMenuOpen = false"
                    class="flex items-center justify-between px-5 py-4 rounded-2xl text-base font-semibold transition-all {{ $isActive ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-700 hover:bg-gray-50 active:bg-gray-100' }}">
                    <div class="flex items-center gap-4">
                        <div class="{{ $isActive ? 'bg-white/20' : 'bg-gray-100' }} p-2.5 rounded-xl">
                            <svg class="w-6 h-6 {{ $isActive ? 'text-white' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                        </div>
                        <span>{{ $item['label'] }}</span>
                    </div>
                    @if($isActive)
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    @endif
                </a>
                @endforeach
            </nav>

            <!-- Footer Actions -->
            <div class="flex-shrink-0 p-4 border-t border-gray-100 bg-gray-50/50 space-y-2">
                <a href="{{ route('profile.edit') }}" 
                @click="mobileMenuOpen = false" 
                class="flex items-center gap-3 px-5 py-3.5 rounded-xl bg-white hover:bg-gray-50 text-gray-700 font-medium transition shadow-sm border border-gray-200">
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span>Profil Saya</span>
                    <svg class="w-4 h-4 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" @submit.prevent="mobileMenuOpen = false; $el.submit()">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-5 py-3.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-semibold transition shadow-sm border border-red-200">
                        <div class="bg-red-100 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
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