<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Notifications') }}</h1>
        <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Mark all as read') }}
            </button>
        </form>
    </div>
    
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if ($notifications->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach ($notifications as $notification)
                    <li>
                        <a href="{{ route('notifikasi.show', $notification) }}" 
                           class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if (is_null($notification->read_at))
                                                <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                                            @else
                                                <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $notification->data['title'] ?? __('Notification') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $notification->data['message'] ?? __('You have a new notification') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex">
                                        <span class="text-sm text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="{{ $notifications->onFirstPage() ? '#' : $notifications->previousPageUrl() }}" 
                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('Previous') }}
                    </a>
                    <a href="{{ $notifications->hasMorePages() ? $notifications->nextPageUrl() : '#' }}" 
                       class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('Next') }}
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ $notifications->firstItem() }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ $notifications->lastItem() }}</span>
                            {{ __('of') }}
                            <span class="font-medium">{{ $notifications->total() }}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    <div>
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="px-4 py-12 sm:px-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No notifications') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('You have no notifications yet.') }}</p>
            </div>
        @endif
    </div>
</div>
</x-app-layout>