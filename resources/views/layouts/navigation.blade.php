<nav class="bg-white border-b border-gray-100">
    <!-- Menu Navigasi Utama -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" 
                    class="focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                        <img src="{{ asset('icons/logo72x72.png') }}" 
                            alt="Logo" 
                            class="h-9 w-auto rounded-full" />
                    </a>
                </div>

                <!-- Tautan Navigasi - Desktop -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="url('my-workspaces')" :active="request()->routeIs('my-workspaces.*')">
                        {{ __('Workspace') }}
                    </x-nav-link>

                    <x-nav-link :href="url('calendar')" :active="request()->routeIs('calendar.*')">
                        {{ __('Calendar') }}
                    </x-nav-link>

                    <x-nav-link :href="url('analytics')" :active="request()->routeIs('analytics.*')">
                        {{ __('Analytics') }}
                    </x-nav-link>

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

            <div class="flex items-center">
                <!-- Dropdown Notifikasi - Desktop & Tablet -->
                <div class="hidden md:flex items-center md:ms-4">
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="relative inline-flex items-center p-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                                type="button"
                                aria-expanded="false"
                                aria-label="Lihat notifikasi">
                            <span class="sr-only">Lihat notifikasi</span>
                            @if (Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 h-5 w-5 rounded-full bg-red-500 flex items-center justify-center ring-2 ring-white">
                                    <span class="text-xs text-white font-bold">
                                        {{ Auth::user()->unreadNotifications->count() > 9 ? '9+' : Auth::user()->unreadNotifications->count() }}
                                    </span>
                                </span>
                            @endif
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- Menu Dropdown -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 sm:w-96 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="rounded-lg overflow-hidden">
                                <!-- Header -->
                                <div class="px-4 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 border-b border-indigo-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-white">{{ __('Notifications') }}</h3>
                                        @if (Auth::user()->unreadNotifications()->count() > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-indigo-600">
                                                {{ Auth::user()->unreadNotifications()->count() }} Baru
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @php
                                    $unreadNotifications = Auth::user()->unreadNotifications()->latest()->take(5)->get();
                                    $readNotifications = Auth::user()->readNotifications()->latest()->take(3)->get();
                                @endphp
                                
                                <!-- Notifikasi Belum Dibaca -->
                                @if ($unreadNotifications->count() > 0)
                                    <div class="max-h-72 overflow-y-auto">
                                        @foreach ($unreadNotifications as $notification)
                                            <a href="@if (isset($notification->data['task_id']) && isset($notification->data['workspace_id'])){{ route('my-workspaces.task.show', ['workspace' => $notification->data['workspace_id'], 'task' => $notification->data['task_id']]) }}@else#@endif"
                                               onclick="event.preventDefault(); markAsReadAndRedirect('{{ route('notifikasi.read', $notification) }}', '{{ isset($notification->data['task_id']) && isset($notification->data['workspace_id']) ? route('my-workspaces.task.show', ['workspace' => $notification->data['workspace_id'], 'task' => $notification->data['task_id']]) : '#' }}')"
                                               class="block px-4 py-3 hover:bg-indigo-50 border-b border-gray-100 transition-colors">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 mt-1">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between gap-2">
                                                            <p class="text-sm font-semibold text-gray-900">
                                                                {{ $notification->data['title'] ?? __('Notification') }}
                                                            </p>
                                                            <span class="flex-shrink-0 h-2 w-2 rounded-full bg-indigo-600 mt-1.5"></span>
                                                        </div>
                                                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                                            {{ $notification->data['message'] ?? __('You have a new notification') }}
                                                        </p>
                                                        
                                                        <div class="flex items-center gap-2 mt-2">
                                                            @if (isset($notification->data['task_title']))
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                                                    📋 {{ Str::limit($notification->data['task_title'], 20) }}
                                                                </span>
                                                            @endif
                                                            <span class="text-xs text-gray-500">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="px-4 py-8 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">{{ __('No unread notifications') }}</p>
                                    </div>
                                @endif
                                
                                <!-- Notifikasi Sudah Dibaca -->
                                @if ($readNotifications->count() > 0)
                                    <div class="bg-gray-50">
                                        <div class="px-4 py-2 border-t border-gray-200">
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                {{ __('Earlier') }}
                                            </h4>
                                        </div>
                                        <div class="max-h-48 overflow-y-auto">
                                            @foreach ($readNotifications as $notification)
                                                <a href="@if (isset($notification->data['task_id']) && isset($notification->data['workspace_id'])){{ route('my-workspaces.task.show', ['workspace' => $notification->data['workspace_id'], 'task' => $notification->data['task_id']]) }}@else#@endif"
                                                   class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-200 transition-colors">
                                                    <div class="flex items-start gap-3 opacity-75">
                                                        <div class="flex-shrink-0 mt-1">
                                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-700">
                                                                {{ $notification->data['title'] ?? __('Notification') }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Footer -->
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                                    <a href="{{ route('notifikasi.index') }}" 
                                       class="block text-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">
                                        {{ __('View all notifications') }} →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Pengaturan - Desktop & Tablet -->
                <div class="hidden md:flex md:items-center md:ms-4">
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150"
                                type="button">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" 
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        <!-- Menu Dropdown -->
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

                <!-- Notifikasi Mobile - Tampil di luar hamburger -->
                <div class="flex items-center md:hidden">
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="relative inline-flex items-center p-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                                type="button"
                                aria-expanded="false"
                                aria-label="Lihat notifikasi">
                            <span class="sr-only">Lihat notifikasi</span>
                            @if (Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 flex items-center justify-center ring-2 ring-white">
                                    <span class="text-xs text-white font-bold">
                                        {{ Auth::user()->unreadNotifications->count() > 9 ? '9+' : Auth::user()->unreadNotifications->count() }}
                                    </span>
                                </span>
                            @endif
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- Menu Dropdown Mobile -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="rounded-lg overflow-hidden">
                                <!-- Header -->
                                <div class="px-4 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 border-b border-indigo-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-white">{{ __('Notifications') }}</h3>
                                        @if (Auth::user()->unreadNotifications()->count() > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-indigo-600">
                                                {{ Auth::user()->unreadNotifications()->count() }} Baru
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @php
                                    $unreadNotifications = Auth::user()->unreadNotifications()->latest()->take(5)->get();
                                    $readNotifications = Auth::user()->readNotifications()->latest()->take(3)->get();
                                @endphp
                                
                                <!-- Notifikasi Belum Dibaca -->
                                @if ($unreadNotifications->count() > 0)
                                    <div class="max-h-72 overflow-y-auto">
                                        @foreach ($unreadNotifications as $notification)
                                            <a href="@if (isset($notification->data['task_id']) && isset($notification->data['workspace_id'])){{ route('my-workspaces.task.show', ['workspace' => $notification->data['workspace_id'], 'task' => $notification->data['task_id']]) }}@else#@endif"
                                               onclick="event.preventDefault(); markAsReadAndRedirect('{{ route('notifikasi.read', $notification) }}', '{{ isset($notification->data['task_id']) && isset($notification->data['workspace_id']) ? route('my-workspaces.task.show', ['workspace' => $notification->data['workspace_id'], 'task' => $notification->data['task_id']]) : '#' }}')"
                                               class="block px-4 py-3 hover:bg-indigo-50 border-b border-gray-100 transition-colors">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 mt-1">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between gap-2">
                                                            <p class="text-sm font-semibold text-gray-900">
                                                                {{ $notification->data['title'] ?? __('Notification') }}
                                                            </p>
                                                            <span class="flex-shrink-0 h-2 w-2 rounded-full bg-indigo-600 mt-1.5"></span>
                                                        </div>
                                                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                                            {{ $notification->data['message'] ?? __('You have a new notification') }}
                                                        </p>
                                                        
                                                        <div class="flex items-center gap-2 mt-2">
                                                            @if (isset($notification->data['task_title']))
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                                                    📋 {{ Str::limit($notification->data['task_title'], 20) }}
                                                                </span>
                                                            @endif
                                                            <span class="text-xs text-gray-500">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="px-4 py-8 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">{{ __('No unread notifications') }}</p>
                                    </div>
                                @endif
                                
                                <!-- Notifikasi Sudah Dibaca -->
                                @if ($readNotifications->count() > 0)
                                    <div class="bg-gray-50">
                                        <div class="px-4 py-2 border-t border-gray-200">
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                {{ __('Earlier') }}
                                            </h4>
                                        </div>
                                        <div class="max-h-48 overflow-y-auto">
                                            @foreach ($readNotifications as $notification)
                                                <a href="@if (isset($notification->data['task_id']) && isset($notification->data['workspace_id'])){{ route('my-workspaces.task.show', ['workspace' => $notification->data['workspace_id'], 'task' => $notification->data['task_id']]) }}@else#@endif"
                                                   class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-200 transition-colors">
                                                    <div class="flex items-start gap-3 opacity-75">
                                                        <div class="flex-shrink-0 mt-1">
                                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-700">
                                                                {{ $notification->data['title'] ?? __('Notification') }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Footer -->
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                                    <a href="{{ route('notifikasi.index') }}" 
                                       class="block text-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">
                                        {{ __('View all notifications') }} →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hamburger - Mobile & Tablet -->
                <div class="flex items-center md:hidden ms-2">
                    <button id="mobile-menu-button" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition duration-150 ease-in-out"
                            type="button"
                            aria-expanded="false"
                            aria-label="Toggle navigation menu">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="inline-flex hamburger-icon" 
                                  stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M4 6h16M4 12h16M4 18h16" />
                            <path class="hidden close-icon" 
                                  stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi Responsif - Mobile & Tablet -->
    <div id="mobile-menu" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Dashboard') }}
            </a>

            <a href="{{ url('my-workspaces') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('my-workspaces.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Workspace') }}
            </a>

            <a href="{{ url('calendar') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('calendar.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Calendar') }}
            </a>

            <a href="{{ url('analytics') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('analytics.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('Analytics') }}
            </a>

            <a href="{{ url('notifikasi') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('notifikasi.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition">
                {{ __('All Notifications') }}
            </a>
        </div>

        <!-- Opsi Pengaturan Responsif -->
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

    <!-- Hidden form for marking as read -->
    <form id="markAsReadForm" method="POST" class="hidden">
        @csrf
        @method('PUT')
    </form>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = mobileMenuButton?.querySelector('.hamburger-icon');
    const closeIcon = mobileMenuButton?.querySelector('.close-icon');

    if (mobileMenuButton && mobileMenu) {
        // Toggle mobile menu
        mobileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = !mobileMenu.classList.contains('hidden');
            
            if (isOpen) {
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                hamburgerIcon.classList.add('inline-flex');
                closeIcon.classList.add('hidden');
                closeIcon.classList.remove('inline-flex');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            } else {
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
                if (window.innerWidth >= 768) {
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

// Function for marking notification as read
function markAsReadAndRedirect(markAsReadUrl, redirectUrl) {
    if (redirectUrl === '#') {
        return;
    }

    fetch(markAsReadUrl, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
        },
    })
    .then(() => {
        window.location.href = redirectUrl;
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.href = redirectUrl;
    });
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>