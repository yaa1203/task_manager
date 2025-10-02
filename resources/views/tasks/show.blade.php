<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Tasks
                </a>
                <h2 class="font-semibold text-xl text-gray-800">Task Details</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Task
                </a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block" 
                      onsubmit="return confirm('Are you sure you want to delete this task?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Task Card -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <!-- Header with Title -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold mb-2">{{ $task->title }}</h1>
                            <div class="flex items-center gap-3 mt-4">
                                @php
                                    $statusColors = [
                                        'todo' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                                        'done' => 'bg-green-100 text-green-800',
                                    ];
                                    $statusIcons = [
                                        'todo' => '○',
                                        'in_progress' => '⟳',
                                        'done' => '✓',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$task->status] }}">
                                    {{ $statusIcons[$task->status] }} {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>

                                @php
                                    $priorityColors = [
                                        'urgent' => 'bg-red-100 text-red-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'medium' => 'bg-blue-100 text-blue-800',
                                        'low' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $priorityIcons = [
                                        'urgent' => '🔴',
                                        'high' => '🟠',
                                        'medium' => '🔵',
                                        'low' => '⚪',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $priorityColors[$task->priority] }}">
                                    {{ $priorityIcons[$task->priority] }} {{ ucfirst($task->priority) }}
                                </span>

                                @if($task->isOverdue())
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 animate-pulse">
                                        ⚠️ Overdue
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Project Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-sm text-gray-500 mb-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                                Project
                            </div>
                            @if($task->project)
                                <a href="{{ route('projects.show', $task->project) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $task->project->name }}
                                </a>
                            @else
                                <span class="text-gray-400">No project assigned</span>
                            @endif
                        </div>

                        <!-- Due Date -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-sm text-gray-500 mb-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Due Date
                            </div>
                            @if($task->due_date)
                                <p class="font-medium {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $task->due_date->format('M d, Y') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $task->due_date->diffForHumans() }}
                                </p>
                            @else
                                <span class="text-gray-400">No deadline set</span>
                            @endif
                        </div>

                        <!-- Created Date -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-sm text-gray-500 mb-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Created
                            </div>
                            <p class="font-medium text-gray-900">{{ $task->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $task->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($task->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                Description
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $task->description }}</p>
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                Description
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-400">
                                <p>No description provided</p>
                            </div>
                        </div>
                    @endif

                    <!-- Task Timeline -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Task Timeline
                        </h3>
                        <div class="space-y-4">
                            <!-- Created -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Task Created</p>
                                    <p class="text-xs text-gray-500">{{ $task->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>

                            <!-- Last Updated -->
                            @if($task->updated_at != $task->created_at)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                        <p class="text-xs text-gray-500">{{ $task->updated_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Completion Status -->
                            @if($task->status === 'done')
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Task Completed</p>
                                        <p class="text-xs text-gray-500">Marked as done</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @if($task->status !== 'in_progress')
                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="description" value="{{ $task->description }}">
                            <input type="hidden" name="status" value="in_progress">
                            <input type="hidden" name="priority" value="{{ $task->priority }}">
                            <input type="hidden" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                            <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Start Task
                            </button>
                        </form>
                    @endif

                    @if($task->status !== 'done')
                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="description" value="{{ $task->description }}">
                            <input type="hidden" name="status" value="done">
                            <input type="hidden" name="priority" value="{{ $task->priority }}">
                            <input type="hidden" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                            <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Complete
                            </button>
                        </form>
                    @endif

                    @if($task->project)
                        <a href="{{ route('projects.show', $task->project) }}" 
                           class="flex items-center justify-center px-4 py-3 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            View Project
                        </a>
                    @endif

                    <a href="{{ route('tasks.edit', $task) }}" 
                       class="flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>