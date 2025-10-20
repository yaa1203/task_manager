@extends('admin.layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('workspaces.show', $workspace) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Task</h1>
                <p class="text-sm text-gray-600">in {{ $workspace->name }}</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800 mb-2">There were some errors with your submission:</p>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('workspace.tasks.update', [$workspace, $task]) }}" 
              method="POST" enctype="multipart/form-data" id="taskForm">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Task Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       required
                       value="{{ old('title', $task->title) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Description -->
            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $task->description) }}</textarea>
            </div>

            <!-- File Upload -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Attach File (Optional)
                </label>

                @if ($task->file_path)
                <div class="mb-3 flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <div>
                        <p class="text-sm text-gray-700">Current file:</p>
                        <a href="{{ asset('storage/' . $task->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">
                            {{ basename($task->file_path) }}
                        </a>
                    </div>
                    <label class="flex items-center text-sm text-red-600 cursor-pointer">
                        <input type="checkbox" name="remove_file" value="1" class="mr-2">
                        Remove file
                    </label>
                </div>
                @endif

                <div class="flex items-center justify-center w-full">
                    <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">Max file size: 10MB</p>
                        </div>
                        <input id="file" name="file" type="file" class="hidden" onchange="displayFileName(this)" />
                    </label>
                </div>
                <p id="file-name" class="mt-2 text-sm text-gray-600 hidden"></p>
            </div>

            <!-- Link -->
            <div class="mb-5">
                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">
                    Link (Optional)
                </label>
                <input type="url" 
                       name="link" 
                       id="link"
                       value="{{ old('link', $task->link) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="https://example.com">
            </div>

            <!-- Assign To -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Assign To <span class="text-red-500">*</span>
                </label>

                <div class="mb-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="assign_to_all" onchange="toggleUserSelection(this)"
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Assign to all users</span>
                    </label>
                </div>

                <div id="user-selection" class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto">
                    @foreach($users as $user)
                    <label class="flex items-center gap-2 py-2 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" 
                               name="user_ids[]" 
                               value="{{ $user->id }}"
                               {{ $task->assignedUsers->contains($user->id) ? 'checked' : '' }}
                               class="user-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <div class="flex items-center gap-2 flex-1">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-xs font-semibold text-indigo-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Hidden Status -->
            <input type="hidden" name="status" value="{{ old('status', $task->status ?? 'todo') }}">

            <!-- Priority -->
            <div class="mb-5">
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                    Priority <span class="text-red-500">*</span>
                </label>
                <select name="priority" id="priority" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <!-- Due Date -->
            <div class="mb-6">
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Due Date
                </label>
                <input type="date" name="due_date" id="due_date"
                       value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('workspaces.show', $workspace) }}" 
                   class="flex-1 px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="flex-1 px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Update Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function displayFileName(input) {
    const fileNameDisplay = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        fileNameDisplay.textContent = '📎 ' + input.files[0].name;
        fileNameDisplay.classList.remove('hidden');
    } else {
        fileNameDisplay.classList.add('hidden');
    }
}

function toggleUserSelection(checkbox) {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const userSelection = document.getElementById('user-selection');
    if (checkbox.checked) {
        userCheckboxes.forEach(cb => { cb.checked = true; cb.disabled = true; });
        userSelection.classList.add('opacity-50', 'pointer-events-none');
    } else {
        userCheckboxes.forEach(cb => { cb.checked = false; cb.disabled = false; });
        userSelection.classList.remove('opacity-50', 'pointer-events-none');
    }
}
</script>
@endsection
