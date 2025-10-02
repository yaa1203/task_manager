<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 shadow-lg">
        <div class="p-4 flex items-center justify-between border-b">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <x-application-logo class="h-8 w-8 text-indigo-600" />
                <span class="text-lg font-bold">Admin</span>
            </a>
        </div>

        <nav class="mt-4 flex flex-col space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                📊 Dashboard Admin
            </a>

            <a href="{{ url('users') }}"
               class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                👥 User Management
            </a>

            <a href="{{ url('project') }}"
               class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('project.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                📁 Projects Management
            </a>

            <a href="{{ url('task') }}"
               class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('task.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                ✅ Tasks Management
            </a>

            <a href="{{ url('analytict') }}"
               class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('analytict.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                📈 Analytics (Global)
            </a>

            
            <a href="{{ route('notifications.index') }}"
            class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('notifications.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                🔔 System Notifications 
                @if(Auth::user()->unreadNotifications->count())
                    <span class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
        </nav>

        <div class="absolute bottom-0 w-64 p-4 border-t">
            <div class="text-sm text-gray-600">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-red-50">
                    🚪 Logout
                </button>
                <a href="{{ url('dashboard') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:text-white hover:bg-slate-600/50 transition-all">
                    <i class="fas fa-external-link-alt w-4 text-center"></i>
                    Lihat Website
                </a>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-6">
            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
