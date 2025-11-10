@extends('admin.layouts.admin')

@section('page-title', 'Notifikasi')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-4 sm:mb-6 bg-green-50 border border-green-200 rounded-lg sm:rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Bagian Header --}}
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1.5 sm:mb-2">Notifikasi</h1>
                <p class="text-xs sm:text-sm lg:text-base text-gray-600">Tetap up-to-date dengan aktivitas sistem</p>
            </div>
            @if($unreadCount > 0)
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 bg-indigo-50 text-indigo-700 text-xs sm:text-sm font-semibold rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                    {{ $unreadCount }} Baru
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-4 sm:mb-6">
        {{-- Total Notifikasi --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">Total</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Semua</h3>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $totalCount }}</p>
            </div>
        </div>

        {{-- Belum Dibaca --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full whitespace-nowrap">Belum</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Perhatian</h3>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $unreadCount }}</p>
            </div>
        </div>

        {{-- Sudah Dibaca --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full whitespace-nowrap">Dibaca</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Dibaca</h3>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $readCount }}</p>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi & Filter --}}
    <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm p-3 sm:p-4 lg:p-5 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            {{-- Tab Filter - Horizontal Scroll di Mobile --}}
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}" 
                   class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-colors whitespace-nowrap text-xs sm:text-sm font-medium shadow-sm {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Semua
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                   class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-colors whitespace-nowrap text-xs sm:text-sm font-medium {{ $filter === 'unread' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Belum Dibaca
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                   class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-colors whitespace-nowrap text-xs sm:text-sm font-medium {{ $filter === 'read' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Sudah Dibaca
                </a>
            </div>

            @if($unreadCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs sm:text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="hidden sm:inline">Tandai Semua Dibaca</span>
                    <span class="sm:hidden">Baca Semua</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Daftar Notifikasi --}}
    <div class="space-y-3 sm:space-y-4">
        @forelse($notifications as $notification)
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-all active:scale-[0.99] {{ $notification->read_at ? '' : 'ring-2 ring-indigo-100' }}">
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex items-start gap-3 sm:gap-4">
                    {{-- Ikon dengan Badge Tipe --}}
                    <div class="flex-shrink-0">
                        <div class="relative">
                            @if($notification->read_at)
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg sm:rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            @else
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg sm:rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 sm:h-4 sm:w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 sm:h-4 sm:w-4 bg-indigo-600 border-2 border-white"></span>
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 sm:gap-3 mb-2 sm:mb-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start flex-wrap gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                                    <h3 class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 break-words">
                                        {{ $notification->data['title'] }}
                                    </h3>
                                    @if(!$notification->read_at)
                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full whitespace-nowrap">
                                        Baru
                                    </span>
                                    @endif
                                </div>
                                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed break-words">
                                    {{ $notification->data['message'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Footer Aksi --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 pt-2.5 sm:pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-3 sm:gap-4 text-xs text-gray-500 overflow-x-auto">
                                <span class="flex items-center gap-1.5 whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $notification->created_at->format('d M, Y') }}
                                </span>
                                <span class="hidden sm:flex items-center gap-1.5 whitespace-nowrap">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            @if(!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tandai Dibaca
                                </button>
                            </form>
                            @else
                            <span class="inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 bg-green-50 text-green-700 text-xs font-medium rounded-lg">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Dibaca
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- State Kosong --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm p-8 sm:p-12 lg:p-16 text-center">
            <div class="max-w-sm mx-auto">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">
                    @if($filter === 'unread')
                        Semua Sudah Dibaca!
                    @elseif($filter === 'read')
                        Belum Ada Notifikasi Dibaca
                    @else
                        Semua Bersih!
                    @endif
                </h3>
                <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
                    @if($filter === 'unread')
                        Tidak ada notifikasi yang belum dibaca. Kami akan memberi tahu Anda ketika ada yang baru.
                    @elseif($filter === 'read')
                        Tidak ada notifikasi yang sudah dibaca saat ini.
                    @else
                        Anda tidak memiliki notifikasi saat ini. Kami akan memberi tahu Anda ketika ada yang baru.
                    @endif
                </p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ke Dashboard
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="mt-4 sm:mt-6">
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm px-3 sm:px-4 py-2.5 sm:py-3">
            {{ $notifications->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Animasi Kustom --}}
<style>
    /* Hide scrollbar for filter buttons on mobile */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* Smooth fade-in animation */
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