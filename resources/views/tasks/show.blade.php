<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Task Details: {{ $task->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <div class="mb-4">
                    <p><strong>Title:</strong> {{ $task->title }}</p>
                </div>

                <div class="mb-4">
                    <p><strong>Description:</strong></p>
                    <p class="text-gray-700">{{ $task->description ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p><strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-white
                            @if($task->status === 'done') bg-green-600
                            @elseif($task->status === 'in_progress') bg-yellow-500
                            @else bg-gray-500 @endif">
                            {{ ucfirst($task->status) }}
                        </span>
                    </p>
                </div>

                <div class="mb-4">
                    <p><strong>Priority:</strong>
                        <span class="px-2 py-1 rounded text-white
                            @if($task->priority === 'urgent') bg-red-600
                            @elseif($task->priority === 'high') bg-orange-600
                            @elseif($task->priority === 'medium') bg-yellow-600
                            @else bg-green-600 @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </p>
                </div>

                <div class="mb-4">
                    <p><strong>Due Date:</strong> {{ $task->due_date ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p><strong>Assigned User:</strong> {{ $task->user->name ?? '-' }}</p>
                </div>

                @if($task->project)
                <div class="mb-4">
                    <p><strong>Project:</strong> {{ $task->project->name }}</p>
                </div>
                @endif

                <div class="flex space-x-2 mt-6">
                    <a href="{{ route('tasks.edit', $task) }}" class="bg-green-600 text-white px-4 py-2 rounded">Edit</a>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                    </form>

                    <a href="{{ route('tasks.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
