<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    My Tasks
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage and track your tasks efficiently</p>
            </div>
            <a href="{{ route('tasks.create') }}" 
               class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-md hover:shadow-lg text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Task
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
            @if(!$tasks->isEmpty())
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                @php
                    $totalTasks = $tasks->total();
                    $completedCount = App\Models\Task::where('user_id', auth()->id())->where('status', 'done')->count();
                    $inProgressCount = App\Models\Task::where('user_id', auth()->id())->where('status', 'in_progress')->count();
                    $overdueCount = App\Models\Task::where('user_id', auth()->id())
                        ->where('status', '!=', 'done')
                        ->whereDate('due_date', '<', now())
                        ->count();
                @endphp

                {{-- Total Tasks --}}
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
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

                {{-- Completed --}}
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm opacity-90 font-medium">Completed</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $completedCount }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- In Progress --}}
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm opacity-90 font-medium">In Progress</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $inProgressCount }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Overdue --}}
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm opacity-90 font-medium">Overdue</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $overdueCount }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter & Search Section --}}
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('tasks.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        {{-- Search --}}
                        <div class="md:col-span-2">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search tasks..." 
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
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Todo</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>

                        {{-- Priority Filter --}}
                        <div>
                            <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">All Priority</option>
                                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            Apply Filters
                        </button>
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
            @endif

            {{-- Empty State --}}
            @if($tasks->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-12 text-center">
                <div class="bg-white w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No tasks yet</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-6 max-w-md mx-auto">
                    Start organizing your work by creating your first task. Stay productive and achieve your goals!
                </p>
                <a href="{{ route('tasks.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Your First Task
                </a>
            </div>
            @else

            {{-- Desktop List View --}}
            <div class="hidden md:block bg-white shadow-sm rounded-xl overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach(['Task', 'Project', 'Status', 'Priority', 'Due Date', 'Actions'] as $header)
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider {{ $header === 'Actions' ? 'text-right' : '' }}">
                                    {{ $header }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $task->title }}</div>
                                    @if($task->description)
                                    <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($task->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->project)
                                    <a href="{{ route('projects.show', $task->project) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                        {{ Str::limit($task->project->name, 20) }}
                                    </a>
                                    @else
                                    <span class="text-sm text-gray-400">No project</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusBadges = [
                                        'done' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Done'],
                                        'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'In Progress'],
                                        'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Todo']
                                    ];
                                    $badge = $statusBadges[$task->status] ?? $statusBadges['pending'];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $priorityBadges = [
                                        'urgent' => 'bg-red-100 text-red-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'medium' => 'bg-blue-100 text-blue-800',
                                        'low' => 'bg-gray-100 text-gray-800'
                                    ];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $priorityBadges[$task->priority] ?? $priorityBadges['low'] }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $task->due_date && $task->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    @if($task->due_date)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $task->due_date->format('M d, Y') }}
                                        @if($task->isOverdue())
                                        <span class="text-xs font-bold">(Overdue)</span>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                                        <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Delete this task?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Grid/Card View --}}
            <div class="md:hidden grid grid-cols-1 gap-3 mb-6">
                @foreach($tasks as $task)
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition-all">
                    {{-- Priority Indicator --}}
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-1 h-16 rounded-full {{ $task->priority == 'urgent' ? 'bg-red-500' : ($task->priority == 'high' ? 'bg-orange-500' : ($task->priority == 'medium' ? 'bg-blue-500' : 'bg-gray-400')) }}"></div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-sm font-bold text-gray-900 flex-1 pr-2">{{ $task->title }}</h3>
                                @php
                                $statusBadges = [
                                    'done' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Done'],
                                    'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Progress'],
                                    'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Todo']
                                ];
                                $badge = $statusBadges[$task->status] ?? $statusBadges['pending'];
                                @endphp
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }} whitespace-nowrap">
                                    {{ $badge['label'] }}
                                </span>
                            </div>

                            @if($task->description)
                            <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2 text-xs mb-3">
                        @if($task->project)
                        <div class="flex items-center gap-1.5 text-blue-600 bg-blue-50 px-2 py-1.5 rounded-lg">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <a href="{{ route('projects.show', $task->project) }}" class="hover:underline font-medium truncate">
                                {{ $task->project->name }}
                            </a>
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @php
                                $priorityBadges = [
                                    'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500'],
                                    'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'dot' => 'bg-orange-500'],
                                    'medium' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
                                    'low' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'dot' => 'bg-gray-500']
                                ];
                                $pBadge = $priorityBadges[$task->priority] ?? $priorityBadges['low'];
                                @endphp
                                <span class="flex items-center gap-1.5 px-2 py-1 {{ $pBadge['bg'] }} {{ $pBadge['text'] }} rounded-md font-medium">
                                    <span class="w-1.5 h-1.5 {{ $pBadge['dot'] }} rounded-full"></span>
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            @if($task->due_date)
                            <div class="flex items-center gap-1 {{ $task->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $task->due_date->format('M d') }}
                                @if($task->isOverdue())
                                <span class="text-xs">!</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800 text-xs font-semibold flex items-center gap-1">
                            View Details
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('tasks.edit', $task) }}" class="text-gray-600 hover:text-gray-900 p-1.5 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" 
                                  onsubmit="return confirm('Delete this task?')">
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
                {{ $tasks->links() }}
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

        .view-btn {
            color: #6b7280;
        }

        .view-btn.active {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .view-btn {
            color: #6b7280;
        }

        .view-btn.active {
            background-color: #dbeafe;
            color: #2563eb;
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
        // Auto-dismiss success message after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.animate-fade-in');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    </script>
</x-app-layout>