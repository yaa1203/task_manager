<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Notification') }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('View and manage your notification details') }}</p>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if (is_null($notification->read_at))
                        <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                    @else
                        <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                    @endif
                </div>
                <div class="ml-3 w-0 flex-1">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Type') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $notification->type }}
                            </dd>
                        </div>
                        
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Title') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $notification->data['title'] ?? __('Notification') }}
                            </dd>
                        </div>
                        
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Message') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $notification->data['message'] ?? __('You have a new notification') }}
                            </dd>
                        </div>
                        
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $notification->created_at->format('F j, Y, g:i A') }}
                            </dd>
                        </div>
                        
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Read At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $notification->read_at ? $notification->read_at->format('F j, Y, g:i A') : __('Not read yet') }}
                            </dd>
                        </div>
                    </dl>
                    
                    @if (isset($notification->data['action']) && $notification->data['action'])
                        <div class="mt-6">
                            <a href="{{ $notification->data['action'] }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('View Details') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-between">
                <a href="{{ route('notifikasi.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Back to Notifications') }}
                </a>
                
                @if (is_null($notification->read_at))
                    <form action="{{ route('notifications.read', $notification) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            {{ __('Mark as Read') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>