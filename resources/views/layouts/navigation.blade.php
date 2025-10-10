<nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links - Desktop -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="url('tasks')" :active="request()->routeIs('tasks.*')">
                        {{ __('Tasks') }}
                    </x-nav-link>

                    <x-nav-link :href="url('projects')" :active="request()->routeIs('projects.*')">
                        {{ __('Projects') }}
                    </x-nav-link>

                    <x-nav-link :href="url('calendar')" :active="request()->routeIs('calendar.*')">
                        {{ __('Calendar') }}
                    </x-nav-link>

                    <x-nav-link :href="url('analytics')" :active="request()->routeIs('analytics.*')">
                        {{ __('Analytics') }}
                    </x-nav-link>
                    <!-- Di bagian desktop navigation -->
                    <x-nav-link :href="url('notifikasi')" :active="request()->routeIs('notifikasi.*')">
                        {{ __('Notifications') }}
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </x-nav-link>
                </div>
            </div>

            <!-- Notifications Dropdown - Desktop -->
            <div class="hidden sm:flex sm:items-center sm:ms-4">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" 
                            class="inline-flex items-center p-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                            type="button"
                            aria-expanded="false"
                            aria-label="View notifications">
                        <span class="sr-only">View notifications</span>
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
                                <span class="text-xs text-white font-bold">{{ Auth::user()->unreadNotifications->count() }}</span>
                            </span>
                        @endif
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                        style="display: none;">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900">{{ __('Notifications') }}</h3>
                            </div>
                            
                            @php
                                $unreadNotifications = Auth::user()->unreadNotifications()->latest()->take(5)->get();
                                $readNotifications = Auth::user()->readNotifications()->latest()->take(5)->get();
                            @endphp
                            
                            @if ($unreadNotifications->count() > 0)
                                <div class="max-h-60 overflow-y-auto">
                                    @foreach ($unreadNotifications as $notification)
                                        <a href="{{ route('notifikasi.show', $notification) }}" 
                                        class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $notification->data['title'] ?? __('Notification') }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $notification->data['message'] ?? __('You have a new notification') }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="px-4 py-3 text-center text-sm text-gray-500">
                                    {{ __('No unread notifications') }}
                                </div>
                            @endif
                            
                            @if ($readNotifications->count() > 0)
                                <div class="px-4 py-2 border-t border-gray-200">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Earlier') }}</h4>
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    @foreach ($readNotifications as $notification)
                                        <a href="{{ route('notifikasi.show', $notification) }}" 
                                        class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $notification->data['title'] ?? __('Notification') }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $notification->data['message'] ?? __('You have a new notification') }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="px-4 py-3 border-t border-gray-200">
                                <a href="{{ route('notifikasi.index') }}" 
                                class="block text-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    {{ __('View all notifications') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <!-- Settings Dropdown - Desktop -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150"
                                type="button">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    {{ __('Profile') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hamburger - Mobile -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button id="mobile-menu-button" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition duration-150 ease-in-out"
                            type="button"
                            aria-expanded="false"
                            aria-label="Toggle navigation menu">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="inline-flex hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path class="hidden close-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu - Mobile -->
    <div id="mobile-menu" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Dashboard') }}
            </a>

            <a href="{{ url('tasks') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('tasks.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Tasks') }}
            </a>

            <a href="{{ url('projects') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('projects.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Projects') }}
            </a>

            <a href="{{ url('calendar') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('calendar.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Calendar') }}
            </a>

            <a href="{{ url('analytics') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('analytics.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Analytics') }}
            </a>
            <!-- Notifications - Mobile -->
            <a href="{{ url('notifications') }}" 
            class="flex items-center px-4 py-2 border-l-4 {{ request()->routeIs('notifications.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                {{ __('Notifications') }}
                @if (Auth::user()->unreadNotifications->count() > 0)
                    <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" 
                   class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle functionality
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = mobileMenuButton.querySelector('.hamburger-icon');
    const closeIcon = mobileMenuButton.querySelector('.close-icon');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = !mobileMenu.classList.contains('hidden');
            
            if (isOpen) {
                // Close menu
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                hamburgerIcon.classList.add('inline-flex');
                closeIcon.classList.add('hidden');
                closeIcon.classList.remove('inline-flex');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            } else {
                // Open menu
                mobileMenu.classList.remove('hidden');
                hamburgerIcon.classList.add('hidden');
                hamburgerIcon.classList.remove('inline-flex');
                closeIcon.classList.remove('hidden');
                closeIcon.classList.add('inline-flex');
                mobileMenuButton.setAttribute('aria-expanded', 'true');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    hamburgerIcon.classList.remove('hidden');
                    hamburgerIcon.classList.add('inline-flex');
                    closeIcon.classList.add('hidden');
                    closeIcon.classList.remove('inline-flex');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Close mobile menu when clicking a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a, button[type="submit"]');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                hamburgerIcon.classList.add('inline-flex');
                closeIcon.classList.add('hidden');
                closeIcon.classList.remove('inline-flex');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            });
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth >= 640) { // sm breakpoint
                    mobileMenu.classList.add('hidden');
                    hamburgerIcon.classList.remove('hidden');
                    hamburgerIcon.classList.add('inline-flex');
                    closeIcon.classList.add('hidden');
                    closeIcon.classList.remove('inline-flex');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                }
            }, 250);
        });
    }
});
</script>