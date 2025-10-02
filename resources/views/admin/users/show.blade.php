@extends('admin.layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">User Detail</h1>

<div class="bg-white p-6 rounded-lg shadow mb-6">
    <h2 class="text-xl font-semibold text-gray-700 mb-2">User Information</h2>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Registered At:</strong> {{ $user->created_at->format('d M Y') }}</p>
    <p><strong>Status:</strong>
        @if($user->is_active ?? true)
            <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
        @else
            <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
        @endif
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Projects -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Projects</h2>
        @if($projects->count() > 0)
            <ul class="space-y-2">
                @foreach ($projects as $project)
                    <li class="border-b pb-2 flex justify-between items-center">
                        <span>{{ $project->name }}</span>
                        <span class="text-sm text-gray-500">{{ $project->created_at->format('d M Y') }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No projects found.</p>
        @endif
    </div>

    <!-- Tasks -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Tasks</h2>
        @if($tasks->count() > 0)
            <ul class="space-y-2">
                @foreach ($tasks as $task)
                    <li class="border-b pb-2 flex justify-between items-center">
                        <span>{{ $task->title }}</span>
                        <span class="text-sm text-gray-500">{{ $task->status }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No tasks found.</p>
        @endif
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Back to Users</a>
</div>
@endsection
