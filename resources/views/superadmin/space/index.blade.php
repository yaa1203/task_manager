@extends('superadmin.layouts.superadmin')

@section('page-title', 'Manajemen Workspace')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Manajemen Workspace</h1>
                <p class="text-sm sm:text-base text-gray-600">Kelola semua workspace dari seluruh admin di sistem</p>
            </div>
        </div>
        {{-- Info Global --}}
        <div class="mb-6 p-4 bg-purple-50 border-l-4 border-purple-500 rounded-lg mt-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-purple-900">Tampilan Global Super Admin</p>
                    <p class="text-xs text-purple-700 mt-0.5">Menampilkan semua workspace dari seluruh admin di sistem</p>
                </div>
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

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">Total</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Workspace</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $workspaces->total() }}</p>
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
                <h3 class="text-sm font-medium text-gray-600 mb-1">Workspace Aktif</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $workspaces->where('is_archived', false)->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-full">Admin</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Admin</h3>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-5 mb-6">
        <form method="GET" action="{{ route('space.index') }}" class="flex flex-col sm:flex-row gap-3">
            {{-- Hidden input untuk maintain sort_by dan archived --}}
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="archived" value="{{ $archived }}">
            
            {{-- Search Bar --}}
            <div class="flex-1 flex gap-2">
                <div class="relative flex-1">
                    <input type="text" 
                        name="search" 
                        placeholder="Cari workspace berdasarkan nama atau admin..." 
                        value="{{ $search }}"
                        class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all bg-gray-50 hover:bg-white">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                {{-- Tombol Search --}}
                <button type="submit" class="px-5 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors whitespace-nowrap font-medium text-sm">
                    Cari
                </button>
                
                {{-- Tombol Reset --}}
                @if($search || $archived === 'true')
                <a href="{{ route('space.index') }}" 
                   class="px-5 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors whitespace-nowrap font-medium text-sm">
                    Reset
                </a>
                @endif
            </div>

            {{-- Archive Filter --}}
            <div class="flex gap-2">
                <select name="archived" onchange="this.form.submit()" 
                        class="px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 hover:bg-white focus:ring-2 focus:ring-purple-500 text-sm whitespace-nowrap transition-all cursor-pointer">
                    <option value="false" {{ $archived === 'false' ? 'selected' : '' }}>Aktif</option>
                    <option value="true" {{ $archived === 'true' ? 'selected' : '' }}>Terarsip</option>
                    <option value="all" {{ $archived === 'all' ? 'selected' : '' }}>Semua</option>
                </select>

                {{-- Sort Filter --}}
                <select name="sort_by" onchange="this.form.submit()" 
                        class="px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 hover:bg-white focus:ring-2 focus:ring-purple-500 text-sm whitespace-nowrap transition-all cursor-pointer">
                    <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="updated_at" {{ $sortBy === 'updated_at' ? 'selected' : '' }}>Terupdate</option>
                </select>
            </div>
        </form>
    </div>

    {{-- Active Filter Indicator --}}
    @if($search || $archived !== 'false')
    <div class="mb-4 p-4 bg-purple-50 border border-purple-200 rounded-xl">
        <div class="flex items-center gap-2 flex-wrap">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
            </svg>
            <p class="text-sm text-purple-900">
                <span class="font-semibold">Filter Aktif:</span> 
                @if($search)
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                        Pencarian: {{ $search }}
                    </span>
                @endif
                @if($archived !== 'false')
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                        @if($archived === 'true') Terarsip @else Semua @endif
                    </span>
                @endif
            </p>
        </div>
    </div>
    @endif

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-purple-50 to-purple-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Workspace</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tugas</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($workspaces as $workspace)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md"> 
                                    @php
                                        $iconSvgs = [
                                            'folder' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                            'briefcase' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                            'chart' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                            'target' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                            'cog' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                            'clipboard' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                                        ];
                                    @endphp
                                    {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $workspace->name }}</div>
                                    @if($workspace->description)
                                    <p class="text-xs text-gray-500 line-clamp-1">{{ $workspace->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($workspace->admin->category->name ?? '-', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $workspace->admin->category->name ?? 'Tidak Ada Kategori' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($workspace->admin->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $workspace->admin->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $workspace->admin->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $workspace->tasks_count }}</div>
                            <div class="text-xs text-gray-500">Tugas</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($workspace->is_archived)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    Terarsip
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                    Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $workspace->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $workspace->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('space.show', $workspace) }}" 
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                                <form action="{{ route('space.toggle-archive', $workspace) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                        @if($workspace->is_archived)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Pulihkan
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                            </svg>
                                            Arsip
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('space.destroy', $workspace) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus workspace ini? Tindakan ini tidak dapat dibatalkan.');">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Workspace</h3>
                                <p class="text-sm text-gray-600 max-w-md">Tidak ada workspace yang ditemukan</p>
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
        @forelse($workspaces as $workspace)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300">
            <div class="p-5">
                {{-- Header --}}
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md"> 
                        @php
                            $iconSvgs = [
                                'folder' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                'briefcase' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                'chart' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                'target' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'cog' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                'clipboard' => '<svg class="w-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                            ];
                        @endphp
                        {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                    </div>              
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-gray-900 mb-1">{{ $workspace->name }}</h3>
                        @if($workspace->description)
                        <p class="text-xs text-gray-500 line-clamp-1 mb-2">{{ $workspace->description }}</p>
                        @endif
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($workspace->admin->category->name ?? '-', 0, 1)) }}
                                    </span>
                                </div>
                                {{ $workspace->admin->category->name ?? 'Tidak Ada Kategori' }}
                            </span>
                            @if($workspace->is_archived)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    Terarsip
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                    Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Additional Info --}}
                <div class="space-y-2 mb-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                    <div class="flex items-center gap-2 text-xs text-gray-700">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="text-gray-600">Kategori:</span>
                        <span class="font-semibold text-gray-900">{{ $workspace->admin->category->name ?? 'Tidak Ada Kategori' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-700">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="text-gray-600">Admin:</span>
                        <span class="font-semibold text-gray-900">{{ $workspace->admin->name }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-700">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="text-gray-600">Tugas:</span>
                        <span class="font-semibold text-gray-900">{{ $workspace->tasks_count }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-700">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="font-semibold text-gray-900">{{ $workspace->created_at->format('d F Y') }}</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2">
                    <a href="{{ route('space.show', $workspace) }}" 
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>
                    <form action="{{ route('space.toggle-archive', $workspace) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                            @if($workspace->is_archived)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Pulihkan
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                Arsip
                            @endif
                        </button>
                    </form>
                    <form action="{{ route('space.destroy', $workspace) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus workspace ini? Tindakan ini tidak dapat dibatalkan.');" class="flex-1">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Workspace</h3>
            <p class="text-sm text-gray-600 max-w-md mx-auto">Tidak ada workspace yang ditemukan</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($workspaces->hasPages())
    <div class="mt-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-4 py-3">
            {{ $workspaces->links() }}
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