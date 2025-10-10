@extends('admin.layouts.admin')

@section('content')
<x-slot name="header">
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('workspaces.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center text-xl sm:text-2xl shadow-sm" 
                         style="background-color: {{ $workspace->color }}30;">
                        {{ $workspace->icon }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="font-bold text-xl sm:text-3xl text-gray-900 truncate">{{ $workspace->name }}</h2>
                        <p class="text-xs sm:text-sm text-gray-600 capitalize">{{ $workspace->type }} workspace</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($workspace->description)
        <p class="text-sm sm:text-base text-gray-600 ml-0 sm:ml-14">{{ $workspace->description }}</p>
        @endif
    </div>
</x-slot>

<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm sm:text-base text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('workspaces.edit', $workspace) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>

            <form action="{{ route('workspaces.toggle-archive', $workspace) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    {{ $workspace->is_archived ? 'Restore' : 'Archive' }}
                </button>
            </form>

            <button onclick="confirmDelete()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>

            <form id="deleteForm" action="{{ route('workspaces.destroy', $workspace) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>

        <!-- Tasks Section -->
        @if(in_array($workspace->type, ['task', 'mixed']))
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Tasks ({{ $workspace->tasks->count() }})
                </h3>

                {{-- Tombol Add Task yang mengarah ke halaman Add Task Workspace --}}
                <a href="{{ route('workspace.tasks.create', $workspace->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Task
                </a>
            </div>

            @if($workspace->tasks->isEmpty())
                <div class="p-6 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-center text-gray-500">
                    No tasks in this workspace yet.
                </div>
            @else
                <div class="grid gap-4">
                    @foreach($workspace->tasks as $task)
                        <div class="p-4 bg-white rounded-lg shadow border border-gray-200">
                            <div class="flex justify-between items-center">
                                <h4 class="font-semibold text-gray-900">{{ $task->title }}</h4>
                                <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                    {{ str_replace('_', ' ', $task->status) }}
                                </span>
                            </div>
                            @if($task->description)
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $task->description }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : '-' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        <!-- Projects Section -->
        @if(in_array($workspace->type, ['project', 'mixed']))
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Projects ({{ $workspace->projects->count() }})
                </h3>

                <a href="{{ route('projects.create', ['workspace_id' => $workspace->id]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Project
                </a>
            </div>

            @if($workspace->projects->isEmpty())
                <div class="p-6 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-center text-gray-500">
                    No projects in this workspace yet.
                </div>
            @else
                <div class="grid gap-4">
                    @foreach($workspace->projects as $project)
                        <div class="p-4 bg-white rounded-lg shadow border border-gray-200">
                            <h4 class="font-semibold text-gray-900">{{ $project->name }}</h4>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $project->description ?? 'No description.' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this workspace? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.3s ease-out; }
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
