@extends('admin.layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Bagian Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1.5 sm:mb-2">Workspace Saya</h1>
                <p class="text-xs sm:text-sm lg:text-base text-gray-600">Atur tugas Anda ke dalam workspace</p>
            </div>
            <a href="{{ route('workspaces.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-xs sm:text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Buat Workspace</span>
                <span class="sm:hidden">Buat</span>
            </a>
        </div>
    </div>

    <!-- Pesan Sukses -->
    @if(session('success'))
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
        <div class="flex items-center gap-2 sm:gap-3">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs sm:text-sm lg:text-base text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Workspace Aktif --}}
    @if($workspaces->count() > 0)
    <div class="mb-8 sm:mb-10">
        <div class="flex items-center gap-2 mb-4 sm:mb-5">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Workspace Aktif</h3>
            <span class="px-2 sm:px-2.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">
                {{ $workspaces->count() }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
            @foreach($workspaces as $workspace)
            <a href="{{ route('workspaces.show', $workspace) }}" 
               class="group block bg-white rounded-lg sm:rounded-xl border border-gray-200 hover:border-indigo-400 transition-all duration-200 overflow-hidden hover:shadow-lg active:scale-[0.99]">
                
                <!-- Header Workspace dengan Warna -->
                <div class="p-4 sm:p-5 lg:p-6 border-l-4" style="border-color: {{ $workspace->color }}; background-color: {{ $workspace->color }}08;">
                    <div class="flex items-start justify-between mb-3 sm:mb-4">
                        <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center flex-shrink-0 bg-white border border-gray-200 text-gray-700 group-hover:border-indigo-300 transition-all duration-200">
                                @php
                                $iconSvgs = [
                                    'folder' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                    'briefcase' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                    'chart' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                    'target' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                    'cog' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                    'clipboard' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                                ];
                                @endphp
                                {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-base sm:text-lg font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition">
                                    {{ $workspace->name }}
                                </h4>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                    Workspace Tugas
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($workspace->description)
                    <p class="text-xs sm:text-sm text-gray-600 line-clamp-2 mb-3 sm:mb-4">
                        {{ $workspace->description }}
                    </p>
                    @endif

                    <!-- Statistik -->
                    <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 text-xs">
                        <div class="flex items-center gap-1.5 text-gray-700 bg-white/80 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg border border-gray-200">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-semibold">{{ $workspace->tasks_count }}</span>
                            <span class="hidden sm:inline">Tugas</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-700 bg-white/80 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg border border-gray-200">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="font-semibold">{{ $workspace->members_count }}</span>
                            <span class="hidden sm:inline">Anggota</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 sm:px-5 lg:px-6 py-2.5 sm:py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-500 flex items-center gap-1.5">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ $workspace->updated_at->diffForHumans() }}</span>
                        <span class="sm:hidden">{{ $workspace->updated_at->format('d M') }}</span>
                    </span>
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <!-- State Kosong -->
    <div class="bg-white rounded-lg sm:rounded-xl border-2 border-dashed border-gray-300 p-8 sm:p-12 lg:p-16 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-5 shadow-inner">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 mb-2">Belum ada workspace</h3>
            <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 leading-relaxed">
                Mulai dengan membuat workspace pertama Anda untuk mengatur<br class="hidden sm:inline"> tugas dengan efisien
            </p>
            <a href="{{ route('workspaces.create') }}" 
               class="inline-flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-xs sm:text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Buat Workspace Pertama Anda</span>
                <span class="sm:hidden">Buat Workspace</span>
            </a>
        </div>
    </div>
    @endif

    {{-- Workspace Terarsip --}}
    @if($archivedWorkspaces->count() > 0)
    <div class="mt-8 sm:mt-10">
        <div class="flex items-center gap-2 mb-4 sm:mb-5">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Workspace Terarsip</h3>
            <span class="px-2 sm:px-2.5 py-0.5 bg-gray-200 text-gray-700 rounded-full text-xs font-medium">
                {{ $archivedWorkspaces->count() }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
            @foreach($archivedWorkspaces as $workspace)
            <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 overflow-hidden opacity-70 hover:opacity-100 transition-all duration-200 hover:shadow-lg">
                <div class="p-4 sm:p-5 lg:p-6 border-l-4 border-gray-400 bg-gray-50">
                    <div class="flex items-start justify-between mb-3 sm:mb-4">
                        <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center flex-shrink-0 bg-white border border-gray-200 text-gray-500">
                                @php
                                $iconSvgs = [
                                    'folder' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                    'briefcase' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                    'chart' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                    'target' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                    'cog' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                    'clipboard' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                                ];
                                @endphp
                                {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                                    {{ $workspace->name }}
                                </h4>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    Terarsip
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <a href="{{ route('workspaces.show', $workspace) }}" 
                           class="flex-1 text-center px-3 py-2 sm:py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 active:bg-gray-100 transition-all duration-200 text-xs sm:text-sm font-medium">
                            Lihat
                        </a>
                        <form action="{{ route('workspaces.toggle-archive', $workspace) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-3 py-2 sm:py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-all duration-200 text-xs sm:text-sm font-medium">
                            Pulihkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Better touch feedback on mobile */
        @media (max-width: 640px) {
            button:active, a:active {
                transform: scale(0.97);
                transition: transform 0.1s;
            }
        }

        /* Prevent text from breaking awkwardly */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Smooth transitions */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        a, button {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Smooth fade-in animation for cards */
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
        
        .grid > * {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</div>
@endsection