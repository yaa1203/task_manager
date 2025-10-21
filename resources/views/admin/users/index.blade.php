@extends('admin.layouts.admin')

@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Manajemen Pengguna</h1>
                <p class="text-sm sm:text-base text-gray-600">Pantau kinerja dan aktivitas pengguna secara real-time</p>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-xl shadow-sm animate-fade-in">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm sm:text-base text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl shadow-sm animate-fade-in">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm sm:text-base text-rose-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">Total</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Pengguna Terdaftar</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $users->total() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full">Aktif</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Pengguna Aktif</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $users->total() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-orange-50 text-orange-700 text-xs font-semibold rounded-full">Bulan</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Bulan Ini</h3>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'user')->whereHas('assignedTasks')->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-full">Minggu</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Minggu Ini</h3>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'user')->whereHas('assignedTasks')->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-5 mb-6">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row gap-3">
            {{-- Hidden input untuk maintain sort_by --}}
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            
            {{-- Search Bar --}}
            <div class="flex-1 flex gap-2">
                <div class="relative flex-1">
                    <input type="text" 
                        name="search" 
                        placeholder="Cari berdasarkan nama atau email..." 
                        value="{{ $search ?? '' }}"
                        class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                {{-- Tombol Search --}}
                <button type="submit" class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors whitespace-nowrap font-medium text-sm">
                    Cari
                </button>
                
                {{-- Tombol Reset --}}
                @if($search)
                <a href="{{ route('users.index', ['sort_by' => $sortBy]) }}" 
                   class="px-5 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors whitespace-nowrap font-medium text-sm">
                    Reset
                </a>
                @endif
            </div>

            {{-- Sort Filter --}}
            <div class="flex gap-2">
                <select name="sort_by" onchange="this.form.submit()" 
                        class="px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 hover:bg-white focus:ring-2 focus:ring-blue-500 text-sm whitespace-nowrap transition-all cursor-pointer">
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
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
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
    <div class="hidden lg:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tugas</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Performa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-md">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                {{ $user->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    {{ $user->assigned_tasks_count }} Tugas
                                </span>
                                <span class="text-xs text-gray-600 font-medium">
                                    {{ number_format($user->completion_rate, 1) }}% selesai
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5">
                                @php
                                    $scoreClass = $user->diligence_score >= 50 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                                                  ($user->diligence_score >= 20 ? 'bg-amber-50 text-amber-700 border-amber-200' : 
                                                  'bg-rose-50 text-rose-700 border-rose-200');
                                @endphp
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('users.show', $user) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mb-4 shadow-inner">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div class="lg:hidden space-y-4">
        @forelse($users as $user)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300">
            <div class="p-5">
                {{-- Header --}}
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-white font-bold text-lg">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-gray-900 mb-1">{{ $user->name }}</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span class="truncate">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                Aktif
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
                <div class="space-y-3 mb-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                    {{-- Performance Score --}}
                    @php
                        $scoreClass = $user->diligence_score >= 50 ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 
                                      ($user->diligence_score >= 20 ? 'bg-amber-50 text-amber-700 border-amber-300' : 
                                      'bg-rose-50 text-rose-700 border-rose-300');
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                        <span class="text-xs text-gray-700 font-semibold">Skor Performa:</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold {{ $scoreClass }} border">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ $user->diligence_score }}
                        </span>
                    </div>

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

                    {{-- Progress Bar --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-700 font-semibold">Tingkat Penyelesaian</span>
                            <span class="font-bold text-gray-900">{{ number_format($user->completion_rate, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 shadow-inner">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500 shadow-sm" 
                                 style="width: {{ $user->completion_rate }}%"></div>
                        </div>
                    </div>

                    {{-- Additional Info --}}
                    <div class="space-y-2 pt-3 border-t border-gray-200">
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-gray-600">ID Pengguna:</span>
                            <span class="font-semibold text-gray-900">{{ $user->id }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-600">Bergabung:</span>
                            <span class="font-semibold text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
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
                <div class="flex gap-2">
                    <a href="{{ route('users.show', $user) }}" 
                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-16 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pengguna</h3>
            <p class="text-sm text-gray-600 max-w-md mx-auto">Daftar pengguna akan muncul di sini setelah Anda memberikan tugas kepada mereka</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="mt-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-4 py-3">
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
</style>
@endsection