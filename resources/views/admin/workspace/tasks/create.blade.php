@extends('admin.layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 sm:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">
                        Add Task to Workspace: {{ $workspace->name }}
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">
                        Create a task for specific members in this workspace
                    </p>
                </div>
                <div>
                    <a href="{{ route('workspaces.show', $workspace->id) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Workspace
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('workspace.tasks.store', $workspace->id) }}" method="POST" id="taskForm">
                @csrf
                <div class="p-6 sm:p-8 space-y-6">
                    <!-- User Assignment -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            Assign to Workspace Members <span class="text-red-500">*</span>
                        </label>
                        <div class="border border-gray-300 rounded-lg divide-y divide-gray-200 max-h-64 overflow-y-auto">
                            @forelse($users as $user)
                                <label class="flex items-start gap-3 p-3 hover:bg-gray-50 cursor-pointer user-item">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                        class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </label>
                            @empty
                                <div class="p-6 text-center text-gray-500 text-sm">
                                    No members found in this workspace.
                                </div>
                            @endforelse
                        </div>
                        @error('user_ids')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Task Details -->
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-900 mb-1">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-900 mb-1">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-900 mb-1">
                                    Status
                                </label>
                                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="todo">To Do</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-900 mb-1">
                                    Priority
                                </label>
                                <select name="priority" id="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-900 mb-1">
                                Due Date
                            </label>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end gap-3">
                    <a href="{{ route('workspaces.show', $workspace->id) }}" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-700 font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('taskForm').addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('input[name="user_ids[]"]:checked');
    if (checked.length === 0) {
        e.preventDefault();
        alert('Please select at least one workspace member.');
    }
});
</script>
@endsection
