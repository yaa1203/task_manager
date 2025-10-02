<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Task</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('tasks.update', $task) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium">Title</label>
                    <input type="text" name="title" value="{{ old('title', $task->title) }}" 
                        class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Description</label>
                    <textarea name="description" 
                        class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="todo" {{ old('status', $task->status) == 'todo' ? 'selected' : '' }}>To Do</option>
                        <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="done" {{ old('status', $task->status) == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Priority</label>
                    <select name="priority" class="w-full border rounded px-3 py-2">
                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $task->due_date) }}" class="w-full border rounded px-3 py-2">
                </div>

                <button type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Update Task
                </button>
                <a href="{{ route('tasks.index') }}" class="ml-2 text-gray-600">Cancel</a>
            </form>
        </div>
    </div>
</x-app-layout>
