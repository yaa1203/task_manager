@extends('admin.layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Notifications</h1>
        <p class="text-sm sm:text-base text-gray-600">Stay updated with your system activities</p>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        @if($notifications->where('read_at', null)->count() > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST" class="flex-1 sm:flex-initial">
            @csrf
            <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">Mark All as Read</span>
            </button>
        </form>
        @endif

        {{-- Filter Tabs --}}
        <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0">
            <button class="px-4 py-2.5 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 transition whitespace-nowrap font-medium">
                All ({{ $notifications->total() }})
            </button>
            <button class="px-4 py-2.5 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 transition whitespace-nowrap font-medium">
                Unread ({{ $notifications->where('read_at', null)->count() }})
            </button>
        </div>
    </div>

    {{-- Notifications List --}}
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        @forelse($notifications as $notification)
        <div class="notification-item {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50' }} border-b border-gray-200 last:border-b-0 hover:bg-gray-50 transition">
            <div class="p-4 sm:p-6">
                <div class="flex items-start gap-3 sm:gap-4">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-1">
                        @if($notification->read_at)
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        @else
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                            <div class="flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1">
                                    {{ $notification->data['title'] }}
                                    @if(!$notification->read_at)
                                    <span class="inline-block w-2 h-2 bg-indigo-600 rounded-full ml-2"></span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    {{ $notification->data['message'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap items-center gap-3 mt-3">
                            {{-- Timestamp --}}
                            <span class="text-xs sm:text-sm text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>

                            @if(!empty($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" class="text-xs sm:text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                View Details
                            </a>
                            @endif

                            @if(!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="ml-auto">
                                @csrf
                                <button type="submit" class="text-xs sm:text-sm text-gray-600 hover:text-indigo-600 font-medium flex items-center gap-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Mark as Read
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- Empty State --}}
        <div class="p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No notifications yet</h3>
            <p class="text-sm text-gray-600">You're all caught up! Check back later for updates.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="mt-6">
        <div class="bg-white rounded-lg border border-gray-200 px-4 py-3">
            {{ $notifications->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Custom Styles for Better Mobile Experience --}}
<style>
    @media (max-width: 640px) {
        .notification-item {
            transition: transform 0.2s ease;
        }
        
        .notification-item:active {
            transform: scale(0.98);
        }
    }
</style>
@endsection