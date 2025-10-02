<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Project</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name', $project->name) }}" class="w-full border rounded px-3 py-2" required>
                        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Description</label>
                        <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description', $project->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $project->start_date) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $project->end_date) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
