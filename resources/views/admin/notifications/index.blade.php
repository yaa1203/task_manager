@extends('admin.layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">System Notifications</h1>

    <div class="mb-4">
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Mark All as Read
            </button>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <ul class="space-y-4">
            @forelse($notifications as $notification)
                <li class="border-b pb-4 flex justify-between items-center {{ $notification->read_at ? 'opacity-50' : '' }}">
                    <div>
                        <p class="font-semibold">{{ $notification->data['title'] }}</p>
                        <p class="text-sm text-gray-600">{{ $notification->data['message'] }}</p>
                        @if(!empty($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" class="text-blue-500 text-sm">View</a>
                        @endif
                    </div>
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button class="text-sm text-gray-500 hover:text-green-600">
                            Mark as Read
                        </button>
                    </form>
                </li>
            @empty
                <li class="text-gray-500">No notifications yet.</li>
            @endforelse
        </ul>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
