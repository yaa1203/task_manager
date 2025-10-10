<x-app-layout>
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
            @if($workspace->type === 'task' || $workspace->type === 'mixed')
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Tasks ({{ $workspace->tasks->count() }})
                    </h3>
                    
                    @if($availableTasks->count() > 0)
                    <button onclick="toggleAddTask()" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Task
                    </button>
                    @endif
                </div>

                <!-- Add Task Form -->
                @if($availableTasks->count() > 0)
                <div id="addTaskForm" class="hidden mb-6 bg-white rounded-lg border-2 border-indigo-200 p-4">
                    <form action="{{ route('workspaces.add-task', $workspace) }}" method="POST" class="flex gap-3">
                        @csrf
                        <select name="task_id" required 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Select a task to add...</option>
                            @foreach($availableTasks as $task)
                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                            @endforeach
                        </select>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                            Add
                        </button>
                        <button type="button" onclick="toggleAddTask()" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                            Cancel
                        </button>
                    </form>
                </div>
                @endif

                <!-- Tasks List -->
                @if($workspace->tasks->count() > 0)
                <div class="grid gap-4">
                    @foreach($workspace->tasks as $task)
                    <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('tasks.show', $task) }}" 
                                   class="font-semibold text-gray-900 hover:text-indigo-600 transition">
                                    {{ $task->title }}
                                </a>
                                @if($task->description)
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                
                                <div class="flex flex-wrap items-center gap-3 mt-3">
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $task->status === 'done' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $task->status === 'todo' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>

                                    <!-- Priority Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $task->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>

                                    @if($task->due_date)
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                    </span>
                                    @endif

                                    @if($task->project)
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                        {{ $task->project->name }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <form action="{{ route('workspaces.remove-task', [$workspace, $task]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition p-2"
                                        onclick="return confirm('Remove this task from workspace?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-600 mb-4">No tasks in this workspace yet</p>
                    @if($availableTasks->count() > 0)
                    <button onclick="toggleAddTask()" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Your First Task
                    </button>
                    @else
                    <a href="{{ route('tasks.create') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        Create New Task
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endif

            <!-- Projects Section -->
            @if($workspace->type === 'project' || $workspace->type === 'mixed')
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Projects ({{ $workspace->projects->count() }})
                    </h3>
                    
                    @if($availableProjects->count() > 0)
                    <button onclick="toggleAddProject()" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Project
                    </button>
                    @endif
                </div>

                <!-- Add Project Form -->
                @if($availableProjects->count() > 0)
                <div id="addProjectForm" class="hidden mb-6 bg-white rounded-lg border-2 border-indigo-200 p-4">
                    <form action="{{ route('workspaces.add-project', $workspace) }}" method="POST" class="flex gap-3">
                        @csrf
                        <select name="project_id" required 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Select a project to add...</option>
                            @foreach($availableProjects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                            Add
                        </button>
                        <button type="button" onclick="toggleAddProject()" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                            Cancel
                        </button>
                    </form>
                </div>
                @endif

                <!-- Projects List -->
                @if($workspace->projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($workspace->projects as $project)
                    <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <a href="{{ route('projects.show', $project) }}" 
                               class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 hover:text-indigo-600 transition truncate">
                                    {{ $project->name }}
                                </h4>
                            </a>
                            <form action="{{ route('workspaces.remove-project', [$workspace, $project]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition p-1"
                                        onclick="return confirm('Remove this project from workspace?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        @if($project->description)
                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $project->description }}</p>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $project->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $project->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $project->status === 'planning' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $project->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                            
                            @if($project->tasks_count)
                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $project->tasks_count }} tasks
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <p class="text-gray-600 mb-4">No projects in this workspace yet</p>
                    @if($availableProjects->count() > 0)
                    <button onclick="toggleAddProject()" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Your First Project
                    </button>
                    @else
                    <a href="{{ route('projects.create') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        Create New Project
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>

    <script>
        function toggleAddTask() {
            const form = document.getElementById('addTaskForm');
            form.classList.toggle('hidden');
        }

        function toggleAddProject() {
            const form = document.getElementById('addProjectForm');
            form.classList.toggle('hidden');
        }

        function confirmDelete() {
            if (confirm('Are you sure you want to delete this workspace? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>