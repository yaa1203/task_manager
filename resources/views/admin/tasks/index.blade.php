@extends('admin.layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Task Management</h1>

<div class="bg-white p-6 rounded-lg shadow">
    @if(session('success'))
        <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tasks as $task)
                <tr>
                    <td class="px-6 py-4">{{ $loop->iteration + ($tasks->currentPage()-1)*$tasks->perPage() }}</td>
                    <td class="px-6 py-4">{{ $task->title }}</td>
                    <td class="px-6 py-4">{{ $task->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded 
                            {{ $task->status === 'done' ? 'bg-green-200 text-green-800' : 
                               ($task->status === 'in_progress' ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-200 text-gray-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 capitalize">{{ $task->priority }}</td>
                    <td class="px-6 py-4">{{ $task->due_date ?? '-' }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('task.show', $task) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">View</a>
                        <form action="{{ route('task.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</div>
@endsection
