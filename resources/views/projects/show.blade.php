<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Projects
                </a>
                <h2 class="font-semibold text-xl text-gray-800">{{ $project->name }}</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('projects.edit', $project) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    Edit Project
                </a>
                <a href="{{ route('tasks.create', ['project_id' => $project->id, 'from' => 'project']) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Project Info -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Progress</h3>
                        <div class="flex items-center">
                            <div class="flex-1 mr-3">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                                         style="width: {{ $project->progress }}%"></div>
                                </div>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">{{ $project->progress }}%</span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Timeline</h3>
                        <div class="flex items-center text-sm text-gray-700">
                            @if($project->start_date && $project->end_date)
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $project->start_date->format('M d') }} - {{ $project->end_date->format('M d, Y') }}</span>
                            @else
                                <span class="text-gray-400">No timeline set</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Task Summary</h3>
                        <div class="flex gap-3 text-sm">
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                                {{ $project->task_stats['todo'] }} Todo
                            </span>
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1"></span>
                                {{ $project->task_stats['in_progress'] }} In Progress
                            </span>
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                {{ $project->task_stats['done'] }} Done
                            </span>
                        </div>
                    </div>
                </div>

                @if($project->description)
                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                        <p class="text-gray-700">{{ $project->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Tasks List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Tasks</h3>
                </div>

                @if($project->tasks->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No tasks yet</h4>
                        <p class="text-gray-500 mb-6">Start by adding your first task to this project</p>
                        <a href="{{ route('tasks.create', ['project_id' => $project->id, 'from' => 'project']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add First Task
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($project->tasks as $task)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $task->title }}</h4>
                                            
                                            @if($task->status === 'done')
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                    ✓ Done
                                                </span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                                    ⟳ In Progress
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                                    ○ Todo
                                                </span>
                                            @endif

                                            @php
                                                $priorityColors = [
                                                    'urgent' => 'bg-red-100 text-red-800',
                                                    'high' => 'bg-orange-100 text-orange-800',
                                                    'medium' => 'bg-blue-100 text-blue-800',
                                                    'low' => 'bg-gray-100 text-gray-800',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 text-xs rounded-full {{ $priorityColors[$task->priority] }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>

                                            @if($task->isOverdue())
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                    ⚠ Overdue
                                                </span>
                                            @endif
                                        </div>

                                        @if($task->description)
                                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($task->description, 100) }}</p>
                                        @endif

                                        @if($task->due_date)
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Due: {{ $task->due_date->format('M d, Y') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 ml-4">
                                        <a href="{{ route('tasks.show', $task) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('tasks.edit', $task) }}" 
                                           class="text-gray-600 hover:text-gray-800 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block" 
                                              onsubmit="return confirm('Delete this task?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>