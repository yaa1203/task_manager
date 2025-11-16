@extends('admin.layouts.admin')

@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1.5 sm:mb-2">Manajemen Pengguna</h1>
                <p class="text-xs sm:text-sm lg:text-base text-gray-600">Pantau kinerja dan aktivitas pengguna secara real-time</p>
            </div>
        </div>
        
        {{-- Info Kategori --}}
        <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg sm:rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-900">Menampilkan pengguna kategori: <span class="font-bold">{{ Auth::user()->category->name ?? 'Tidak ada kategori' }}</span></p>
                    <p class="text-xs text-blue-700 mt-0.5">Hanya user dengan kategori yang sama yang ditampilkan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-lg sm:rounded-xl shadow-sm animate-fade-in">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm sm:text-base text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-rose-50 border-l-4 border-rose-500 rounded-lg sm:rounded-xl shadow-sm animate-fade-in">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm sm:text-base text-rose-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-4 sm:mb-6">
        {{-- Total Pengguna --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">Total</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Pengguna Terdaftar</h3>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $users->total() }}</p>
            </div>
        </div>

        {{-- Pengguna Aktif --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full">Aktif</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Pengguna Aktif</h3>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">
                    {{ \App\Models\User::where('role', 'user')
                        ->where('category_id', Auth::user()->category_id)
                        ->where('is_blocked', false)
                        ->count() }}
                </p>
            </div>
        </div>

        {{-- Pengguna Diblokir (NEW) --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full">Blokir</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Pengguna Diblokir</h3>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">
                    {{ \App\Models\User::where('role', 'user')
                        ->where('category_id', Auth::user()->category_id)
                        ->where('is_blocked', true)
                        ->count() }}
                </p>
            </div>
        </div>

        {{-- Bergabung Bulan Ini --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-orange-50 text-orange-700 text-xs font-semibold rounded-full">Bulan</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Bulan Ini</h3>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">
                    {{ \App\Models\User::where('role', 'user')
                        ->where('category_id', Auth::user()->category_id)
                        ->where('created_at', '>=', now()->startOfMonth())
                        ->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm p-3 sm:p-4 lg:p-5 mb-4 sm:mb-6">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-col gap-3">
            {{-- Hidden input untuk maintain sort_by --}}
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            
            {{-- Search Bar --}}
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <input type="text" 
                        name="search" 
                        placeholder="Cari berdasarkan nama atau email..." 
                        value="{{ $search ?? '' }}"
                        class="w-full pl-10 sm:pl-11 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white text-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute left-3.5 sm:left-4 top-3 sm:top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                {{-- Tombol Search & Reset --}}
                <div class="flex gap-2">
                    <button type="submit" class="px-4 sm:px-5 py-2.5 sm:py-3 bg-blue-600 text-white rounded-lg sm:rounded-xl hover:bg-blue-700 transition-colors whitespace-nowrap font-medium text-sm">
                        Cari
                    </button>
                    
                    @if($search)
                    <a href="{{ route('users.index', ['sort_by' => $sortBy]) }}" 
                       class="px-4 sm:px-5 py-2.5 sm:py-3 bg-gray-200 text-gray-700 rounded-lg sm:rounded-xl hover:bg-gray-300 transition-colors whitespace-nowrap font-medium text-sm">
                        Reset
                    </a>
                    @endif
                </div>
            </div>

            {{-- Sort Filter --}}
            <div class="flex gap-2">
                <select name="sort_by" onchange="this.form.submit()" 
                        class="flex-1 sm:flex-none px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl bg-gray-50 hover:bg-white focus:ring-2 focus:ring-blue-500 text-sm transition-all cursor-pointer">
                    <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="email" {{ $sortBy == 'email' ? 'selected' : '' }}>Email (A-Z)</option>
                    <optgroup label="Berdasarkan Performa">
                        <option value="most_diligent" {{ $sortBy == 'most_diligent' ? 'selected' : '' }}>Performa Terbaik</option>
                        <option value="least_diligent" {{ $sortBy == 'least_diligent' ? 'selected' : '' }}>Perlu Perhatian</option>
                        <option value="most_completed" {{ $sortBy == 'most_completed' ? 'selected' : '' }}>Penyelesaian Tertinggi</option>
                        <option value="least_late" {{ $sortBy == 'least_late' ? 'selected' : '' }}>Paling Tepat Waktu</option>
                        <option value="most_late" {{ $sortBy == 'most_late' ? 'selected' : '' }}>Sering Terlambat</option>
                    </optgroup>
                </select>
            </div>
        </form>
    </div>

    {{-- Active Sort Indicator --}}
    @if($sortBy != 'created_at')
    <div class="mb-4 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg sm:rounded-xl">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
            </svg>
            <p class="text-sm text-blue-900">
                <span class="font-semibold">Pengurutan Aktif:</span> 
                @switch($sortBy)
                    @case('most_diligent') Performa Terbaik @break
                    @case('least_diligent') Perlu Perhatian @break
                    @case('most_completed') Penyelesaian Tertinggi @break
                    @case('least_late') Paling Tepat Waktu @break
                    @case('most_late') Sering Terlambat @break
                    @case('name') Nama (A-Z) @break
                    @case('email') Email (A-Z) @break
                @endswitch
            </p>
        </div>
    </div>
    @endif

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pengguna</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontak</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tugas</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Performa</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bergabung</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $user->is_blocked ? 'bg-red-50' : '' }}">
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl {{ $user->is_blocked ? 'bg-gradient-to-br from-gray-400 to-gray-500' : 'bg-gradient-to-br from-blue-500 to-blue-600' }} flex items-center justify-center flex-shrink-0 shadow-md">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold {{ $user->is_blocked ? 'text-gray-500' : 'text-gray-900' }}">
                                        {{ $user->name }}
                                        @if($user->is_blocked)
                                        <span class="ml-2 text-xs text-red-600 font-normal">(Diblokir)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm {{ $user->is_blocked ? 'text-gray-500' : 'text-gray-700' }}">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                {{ $user->email }}
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    {{ $user->assigned_tasks_count }} Tugas
                                </span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $user->on_time_submissions_count }}
                                    </span> 
                                    <span class="inline-flex items-center gap-1 text-rose-700 font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $user->late_submissions_count }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-sm font-medium {{ $user->is_blocked ? 'text-gray-500' : 'text-gray-900' }}">{{ $user->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            @if($user->is_blocked)
                            <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                </svg>
                                Diblokir
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                Aktif
                            </span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $user->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                @if($user->is_blocked)
                                <form action="{{ route('users.unblock', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                        </svg>
                                        Buka Blokir
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('users.show', $user) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>

                                <form action="{{ route('users.block', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memblokir akun {{ $user->name }}? User tidak akan bisa login ke sistem.');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Blokir
                                    </button>
                                </form>

                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 sm:px-6 py-12 sm:py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4 sm:mb-6 shadow-inner">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pengguna</h3>
                                <p class="text-sm text-gray-600 max-w-md">Daftar pengguna akan muncul di sini setelah Anda memberikan tugas kepada mereka</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Card View --}}
    <div class="lg:hidden space-y-3 sm:space-y-4">
        @forelse($users as $user)
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 {{ $user->is_blocked ? 'bg-red-50 border-red-200' : '' }}">
            <div class="p-4 sm:p-5">
                {{-- Header --}}
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg sm:rounded-xl {{ $user->is_blocked ? 'bg-gradient-to-br from-gray-400 to-gray-500' : 'bg-gradient-to-br from-blue-500 to-blue-600' }} flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-white font-bold text-lg">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold {{ $user->is_blocked ? 'text-gray-600' : 'text-gray-900' }} mb-1">
                            {{ $user->name }}
                            @if($user->is_blocked)
                            <span class="ml-2 text-xs text-red-600 font-normal">(Diblokir)</span>
                            @endif
                        </h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span class="truncate">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            @if($user->is_blocked)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 border border-red-300">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                </svg>
                                Diblokir
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                Aktif
                            </span>
                            @endif
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $user->category->name ?? 'N/A' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $user->assigned_tasks_count }} Tugas
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Performance Statistics --}}
                <div class="space-y-3 mb-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg sm:rounded-xl p-4 border border-gray-200">
                    {{-- On-time vs Late --}}
                    <div class="flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Tepat Waktu: {{ $user->on_time_submissions_count }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-rose-700 font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Terlambat: {{ $user->late_submissions_count }}
                        </span>
                    </div>

                    {{-- Additional Info --}}
                    <div class="space-y-2 pt-3 border-t border-gray-200">
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-600">Bergabung:</span>
                            <span class="font-semibold text-gray-900">{{ $user->created_at->format('d F Y H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-600 font-medium">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 flex-wrap">
                    @if($user->is_blocked)
                    <form action="{{ route('users.unblock', $user) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                            Buka Blokir
                        </button>
                    </form>
                    @else
                    <a href="{{ route('users.show', $user) }}" 
                       class="flex-1 inline-flex items-center justify-center gap-2 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>

                    <form action="{{ route('users.block', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memblokir akun {{ $user->name }}? User tidak akan bisa login ke sistem.');" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                            </svg>
                            Blokir
                        </button>
                    </form>

                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm p-8 sm:p-12 lg:p-16 text-center">
            <div class="max-w-sm mx-auto">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pengguna</h3>
                <p class="text-sm text-gray-600 max-w-md mx-auto">Daftar pengguna akan muncul di sini setelah Anda memberikan tugas kepada mereka</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="mt-4 sm:mt-6">
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm px-3 sm:px-4 py-2.5 sm:py-3">
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Custom Animations --}}
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Hide scrollbar for filter buttons on mobile */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* Smooth slide-in animation */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .space-y-3 > *, .space-y-4 > * {
        animation: slideIn 0.3s ease-out;
    }
    
    /* Better touch feedback on mobile */
    @media (max-width: 640px) {
        button:active, a:active {
            transform: scale(0.97);
            transition: transform 0.1s;
        }
    }

    /* Prevent text from breaking awkwardly */
    .break-words {
        word-break: break-word;
        overflow-wrap: break-word;
    }

    /* Line clamp utility */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Smooth transitions */
    * {
        -webkit-tap-highlight-color: transparent;
    }
</style>
@endsection