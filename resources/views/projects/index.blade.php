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
                <p class="text-sm text-gray-600 mt-1">Organize and manage your projects efficiently</p>
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
            @if(session('success'))
            <div class="mb-4 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm animate-fade-in">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm sm:text-base font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            {{-- Quick Stats --}}
            @if(!$projects->isEmpty())
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                @php
                    $totalProjects = $projects->total();
                    $allProjects = App\Models\Project::where('user_id', auth()->id())->get();
                    $completedProjects = $allProjects->where('progress', 100)->count();
                    $inProgressProjects = $allProjects->where('progress', '>', 0)->where('progress', '<', 100)->count();
                    $totalTasks = App\Models\Task::whereIn('project_id', $allProjects->pluck('id'))->count();
                @endphp

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

                {{-- In Progress --}}
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm opacity-90 font-medium">In Progress</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $inProgressProjects }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Completed Projects --}}
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
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
            @endif

            {{-- Empty State --}}
            @if($projects->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-12 text-center">
                <div class="bg-white w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No projects yet</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-6 max-w-md mx-auto">
                    Get started by creating your first project and organize your tasks effectively!
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
            
            {{-- Desktop Grid View --}}
            <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all border border-gray-100 overflow-hidden group">
                    {{-- Status Strip --}}
                    @php
                        $statusColor = $project->progress == 100 ? 'bg-green-500' : ($project->progress > 0 ? 'bg-blue-500' : 'bg-gray-400');
                    @endphp
                    <div class="h-1.5 {{ $statusColor }}"></div>
                    
                    <div class="p-4 sm:p-6">
                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 pr-2">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-blue-600 transition line-clamp-2 mb-1">
                                    {{ $project->name }}
                                </h3>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span>{{ $project->tasks_count }} {{ Str::plural('task', $project->tasks_count) }}</span>
                                </div>
                            </div>
                            @php
                            $statusLabel = $project->progress == 100 ? 'completed' : ($project->progress > 0 ? 'active' : 'planning');
                            $statusBadges = [
                                'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'M5 13l4 4L19 7'],
                                'active' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                'planning' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2']
                            ];
                            $badge = $statusBadges[$statusLabel];
                            @endphp
                            <div class="flex items-center gap-1 px-2 py-1 {{ $badge['bg'] }} {{ $badge['text'] }} rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badge['icon'] }}"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if($project->description)
                        <p class="text-xs sm:text-sm text-gray-600 mb-4 line-clamp-2">{{ $project->description }}</p>
                        @endif

                        {{-- Progress Bar --}}
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-2">
                                <span class="font-medium">Progress</span>
                                <span class="font-bold text-blue-600">{{ $project->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500 shadow-sm" 
                                     style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>

                        {{-- Dates --}}
                        <div class="flex flex-col gap-2 mb-4 text-xs">
                            @if($project->start_date)
                            <div class="flex items-center gap-2 text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">Start:</span>
                                <span>{{ $project->start_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                            @if($project->end_date)
                            <div class="flex items-center gap-2 text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium">End:</span>
                                <span>{{ $project->end_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('projects.show', $project) }}" 
                               class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-xs font-medium">
                                View
                            </a>
                            <a href="{{ route('projects.edit', $project) }}" 
                               class="flex-1 text-center px-3 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition text-xs font-medium">
                                Edit
                            </a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                                  onsubmit="return confirm('Delete this project and all its tasks?')" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="w-full px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-xs font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden space-y-3 mb-6">
                @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition-all">
                    {{-- Status Indicator --}}
                    @php
                        $statusLabel = $project->progress == 100 ? 'completed' : ($project->progress > 0 ? 'active' : 'planning');
                        $statusColor = $project->progress == 100 ? 'bg-green-500' : ($project->progress > 0 ? 'bg-blue-500' : 'bg-gray-400');
                        $statusBadges = [
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Done'],
                            'active' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Active'],
                            'planning' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Planning']
                        ];
                        $badge = $statusBadges[$statusLabel];
                    @endphp
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-1 h-20 rounded-full {{ $statusColor }}"></div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-sm font-bold text-gray-900 flex-1 pr-2">{{ $project->name }}</h3>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }} whitespace-nowrap">
                                    {{ $badge['label'] }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>{{ $project->tasks_count }} {{ Str::plural('task', $project->tasks_count) }}</span>
                            </div>

                            @if($project->description)
                            <p class="text-xs text-gray-600 mb-3">{{ Str::limit($project->description, 60) }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mb-3">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span class="font-medium">Progress</span>
                            <span class="font-bold text-blue-600">{{ $project->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $project->progress }}%"></div>
                        </div>
                    </div>

                    {{-- Dates --}}
                    @if($project->start_date || $project->end_date)
                    <div class="flex items-center justify-between text-xs text-gray-600 mb-3">
                        @if($project->start_date)
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $project->start_date->format('M d') }}
                        </div>
                        @endif
                        @if($project->end_date)
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $project->end_date->format('M d') }}
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 text-xs font-semibold flex items-center gap-1">
                            View Details
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('projects.edit', $project) }}" class="text-gray-600 hover:text-gray-900 p-1.5 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                                  onsubmit="return confirm('Delete this project and all its tasks?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1.5 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>

    {{-- JavaScript --}}
    <script>
        // Auto-dismiss success message
        setTimeout(function() {
            const alert = document.querySelector('.animate-fade-in');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    </script>
</x-app-layout>