<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Task</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                        class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Description</label>
                    <textarea name="description" 
                        class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Priority</label>
                    <select name="priority" class="w-full border rounded px-3 py-2">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full border rounded px-3 py-2">
                </div>

                <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Save Task
                </button>
                <a href="{{ route('tasks.index') }}" class="ml-2 text-gray-600">Cancel</a>
            </form>
        </div>
    </div>
</x-app-layout>
