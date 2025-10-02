<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Projects</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('projects.create') }}" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">+ New Project</a>

            <div class="bg-white shadow rounded p-4">
                @if (session('success'))
                    <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="w-full border-collapse border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Start Date</th>
                            <th class="border px-4 py-2">End Date</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td class="border px-4 py-2">{{ $project->name }}</td>
                                <td class="border px-4 py-2">{{ $project->start_date }}</td>
                                <td class="border px-4 py-2">{{ $project->end_date }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('projects.show', $project) }}" class="text-blue-600">View</a> |
                                    <a href="{{ route('projects.edit', $project) }}" class="text-green-600">Edit</a> |
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this project?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border px-4 py-2 text-center text-gray-500">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
