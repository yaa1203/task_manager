<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $project->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <p><strong>Description:</strong> {{ $project->description }}</p>
                <p><strong>Start Date:</strong> {{ $project->start_date }}</p>
                <p><strong>End Date:</strong> {{ $project->end_date }}</p>
                <p><strong>Created At:</strong> {{ $project->created_at->format('d M Y') }}</p>

                <div class="mt-4">
                    <a href="{{ route('projects.edit', $project) }}" class="bg-green-600 text-white px-4 py-2 rounded">Edit</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
