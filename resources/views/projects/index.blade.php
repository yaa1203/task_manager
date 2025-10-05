<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    My Projects
                </h2>
                <p class="text-sm text-gray-600 mt-1">Organize and track your project portfolio</p>
            </div>
            <a href="{{ route('projects.create') }}" 
               class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-md hover:shadow-lg text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Project
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            {{-- Success Alert --}}
            @if (session('success'))
            <div class="mb-4 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm animate-fade-in">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm sm:text-base font-medium">{{ session('success') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                {{-- Total Projects --}}
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm opacity-90 font-medium">Total Projects</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $totalProjects }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Active Projects --}}
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm opacity-90 font-medium">Active</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $activeProjects }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Completed Projects --}}
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm opacity-90 font-medium">Completed</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $completedProjects }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Tasks --}}
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm opacity-90 font-medium">Total Tasks</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $totalTasks }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter & Search --}}
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('projects.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        {{-- Search --}}
                        <div class="md:col-span-2">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search projects by name or description..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            Apply Filters
                        </button>
                        <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                            Clear Filters
                        </a>
                        
                        {{-- Sort Options --}}
                        <select name="sort" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            <option value="">Sort by: Default</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            <option value="progress_high" {{ request('sort') == 'progress_high' ? 'selected' : '' }}>Progress (High-Low)</option>
                            <option value="progress_low" {{ request('sort') == 'progress_low' ? 'selected' : '' }}>Progress (Low-High)</option>
                            <option value="tasks_high" {{ request('sort') == 'tasks_high' ? 'selected' : '' }}>Tasks (Most)</option>
                        </select>
                    </div>
                </form>
            </div>

            {{-- View Toggle --}}
            <div class="flex justify-between items-center mb-4">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-semibold">{{ $projects->count() }}</span> of <span class="font-semibold">{{ $projects->total() }}</span> projects
                </p>
                <div class="bg-white rounded-lg shadow-sm p-1 flex gap-1">
                    <button onclick="switchView('grid')" id="gridViewBtn" class="view-btn px-3 py-2 rounded-md text-sm font-medium transition-all bg-blue-100 text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button onclick="switchView('list')" id="listViewBtn" class="view-btn px-3 py-2 rounded-md text-sm font-medium transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            {{-- Empty State --}}
            @if($projects->isEmpty())
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-sm p-8 sm:p-12 text-center">
                <div class="bg-white w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No projects yet</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-6 max-w-md mx-auto">
                    Create your first project to organize tasks and track progress efficiently
                </p>
                <a href="{{ route('projects.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Your First Project
                </a>
            </div>
            @else
            
            {{-- Grid View --}}
            <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach ($projects as $project)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group transform hover:-translate-y-1">
                    {{-- Status Strip --}}
                    @php
                        $statusColors = [
                            'active' => 'bg-gradient-to-r from-blue-500 to-blue-600',
                            'completed' => 'bg-gradient-to-r from-green-500 to-green-600',
                            'on_hold' => 'bg-gradient-to-r from-gray-400 to-gray-500',
                        ];
                        $statusColor = $statusColors[$project->status] ?? 'bg-gradient-to-r from-gray-400 to-gray-500';
                    @endphp
                    <div class="h-2 {{ $statusColor }}"></div>
                    
                    <div class="p-5">
                        {{-- Header --}}
                        <div class="flex justify-between items-start mb-4 gap-3">
                            <div class="flex-1">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-blue-600 transition mb-1 line-clamp-1">
                                    {{ $project->name }}
                                </h3>
                                <div class="flex items-center gap-2 flex-wrap">
                                    @php
                                        $statusBadges = [
                                            'active' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Active'],
                                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Completed'],
                                            'on_hold' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'On Hold'],
                                        ];
                                        $statusBadge = $statusBadges[$project->status] ?? $statusBadges['active'];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                        {{ $project->tasks_count }} {{ Str::plural('task', $project->tasks_count) }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Quick Actions --}}
                            <div class="flex items-center gap-1">
                                <a href="{{ route('projects.edit', $project) }}" 
                                   class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                   title="Edit project">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                                      onsubmit="return confirm('Delete this project and all its tasks?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Delete project">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if($project->description)
                        <p class="text-gray-600 text-xs sm:text-sm mb-4 line-clamp-2 leading-relaxed">
                            {{ $project->description }}
                        </p>
                        @endif

                        {{-- Progress Section --}}
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex justify-between text-xs font-semibold text-gray-700 mb-2">
                                <span>Project Progress</span>
                                <span class="text-blue-600">{{ $project->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500 shadow-sm" 
                                     style="width: {{ $project->progress }}%"></div>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $project->completed_tasks }} completed</span>
                                <span>{{ $project->tasks_count - $project->completed_tasks }} remaining</span>
                            </div>
                        </div>

                        {{-- Dates --}}
                        <div class="flex flex-col gap-2 text-xs text-gray-600 mb-4 p-3 bg-blue-50 rounded-lg">
                            @if($project->start_date)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">Start:</span>
                                <span>{{ $project->start_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                            @if($project->end_date)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium">End:</span>
                                <span class="{{ $project->end_date->isPast() && $project->status != 'completed' ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $project->end_date->format('M d, Y') }}
                                </span>
                            </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('projects.show', $project) }}" 
                               class="flex-1 text-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-medium text-sm shadow-sm hover:shadow-md">
                                View Details
                            </a>
                            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" 
                               class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all font-medium text-sm"
                               title="Add task">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- List View --}}
            <div id="listView" class="hidden space-y-3">
                @foreach ($projects as $project)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all overflow-hidden border border-gray-100">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            {{-- Project Info --}}
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-2">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">{{ $project->name }}</h3>
                                        @if($project->description)
                                        <p class="text-sm text-gray-600 line-clamp-1">{{ $project->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                                    @php
                                        $statusBadges = [
                                            'active' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Active'],
                                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Completed'],
                                            'on_hold' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'On Hold'],
                                        ];
                                        $statusBadge = $statusBadges[$project->status] ?? $statusBadges['active'];
                                    @endphp
                                    <span class="px-2 py-1 {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} rounded-full font-medium">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        {{ $project->tasks_count }} tasks
                                    </span>
                                    @if($project->start_date)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $project->start_date->format('M d, Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Progress --}}
                            <div class="flex-1 max-w-xs">
                                <div class="flex justify-between text-xs font-semibold text-gray-700 mb-1">
                                    <span>Progress</span>
                                    <span class="text-blue-600">{{ $project->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $project->progress }}%"></div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2">
                                <a href="{{ route('projects.show', $project) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium whitespace-nowrap">
                                    View
                                </a>
                                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" 
                                   class="p-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
                                   title="Add task">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </a>
                                <a href="{{ route('projects.edit', $project) }}" 
                                   class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                                      onsubmit="return confirm('Delete this project and all its tasks?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $projects->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Custom Styles --}}
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

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .view-btn {
            color: #6b7280;
        }

        .view-btn.active {
            background-color: #f3e8ff;
            color: #9333ea;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Progress bar animation */
        @keyframes progress-animation {
            from {
                width: 0%;
            }
        }

        .bg-gradient-to-r.from-blue-500.to-blue-600 {
            animation: progress-animation 1s ease-out;
        }
    </style>

    {{-- JavaScript for View Switching --}}
    <script>
        // Initialize view
        let currentView = localStorage.getItem('projectView') || 'grid';
        
        document.addEventListener('DOMContentLoaded', function() {
            switchView(currentView);
            
            // Animate progress bars on load
            document.querySelectorAll('[style*="width"]').forEach(function(bar) {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });

        function switchView(view) {
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');

            // Hide all views
            if (gridView) gridView.classList.add('hidden');
            if (listView) listView.classList.add('hidden');
            
            // Remove active state from buttons
            gridBtn?.classList.remove('active', 'bg-blue-100', 'text-blue-600');
            listBtn?.classList.remove('active', 'bg-blue-100', 'text-blue-600');
            gridBtn?.classList.add('text-gray-600');
            listBtn?.classList.add('text-gray-600');

            if (view === 'grid') {
                if (gridView) {
                    gridView.classList.remove('hidden');
                    gridView.classList.add('grid');
                }
                gridBtn?.classList.add('active', 'bg-blue-100', 'text-blue-600');
                gridBtn?.classList.remove('text-gray-600');
            } else {
                if (listView) {
                    listView.classList.remove('hidden');
                    listView.classList.add('block');
                }
                listBtn?.classList.add('active', 'bg-blue-100', 'text-blue-600');
                listBtn?.classList.remove('text-gray-600');
            }

            // Save preference
            localStorage.setItem('projectView', view);
            currentView = view;
        }

        // Auto-dismiss success message
        setTimeout(function() {
            const alert = document.querySelector('.animate-fade-in');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);

        // Add hover effect for cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.group');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</x-app-layout>