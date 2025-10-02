@extends('admin.layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Project Management</h1>

<div class="bg-white p-6 rounded-lg shadow">
    @if (session('success'))
        <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($projects as $project)
                <tr>
                    <td class="px-6 py-4">{{ $loop->iteration + ($projects->currentPage()-1)*$projects->perPage() }}</td>
                    <td class="px-6 py-4">{{ $project->name }}</td>
                    <td class="px-6 py-4">{{ $project->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $project->start_date ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $project->end_date ?? '-' }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('project.show', $project) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">View</a>
                        <form action="{{ route('project.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project?');">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No projects found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $projects->links() }}
    </div>
</div>
@endsection
