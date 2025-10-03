<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800">✓ My Tasks</h2>
            <a href="{{ route('tasks.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg flex items-center gap-2 transition text-sm">
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
            <div class="mb-4 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm sm:text-base">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            {{-- Empty State --}}
            @if($tasks->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 sm:p-12 text-center">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No tasks yet</h3>
                <p class="text-sm text-gray-500 mb-6">Get started by creating your first task</p>
                <a href="{{ route('tasks.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Task
                </a>
            </div>
            @else

            {{-- Desktop Table View --}}
            <div class="hidden md:block bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach(['Task', 'Project', 'Status', 'Priority', 'Due Date', 'Actions'] as $header)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $header === 'Actions' ? 'text-right' : '' }}">
                                    {{ $header }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                    @if($task->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->project)
                                    <a href="{{ route('projects.show', $task->project) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
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
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">
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
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $priorityBadges[$task->priority] ?? $priorityBadges['low'] }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $task->due_date && $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                    @if($task->due_date)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $task->due_date->format('M d, Y') }}
                                        @if($task->isOverdue())
                                        <span class="text-xs">(Overdue)</span>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Delete this task?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden space-y-3">
                @foreach($tasks as $task)
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 flex-1 pr-2">{{ $task->title }}</h3>
                        <div class="flex gap-1">
                            @php
                            $statusBadges = [
                                'done' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Done'],
                                'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Progress'],
                                'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Todo']
                            ];
                            $badge = $statusBadges[$task->status] ?? $statusBadges['pending'];
                            @endphp
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $badge['bg'] }} {{ $badge['text'] }} whitespace-nowrap">
                                {{ $badge['label'] }}
                            </span>
                        </div>
                    </div>

                    @if($task->description)
                    <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif

                    <div class="space-y-2 text-xs mb-3">
                        @if($task->project)
                        <div class="flex items-center gap-1 text-blue-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <a href="{{ route('projects.show', $task->project) }}" class="hover:underline">
                                {{ Str::limit($task->project->name, 25) }}
                            </a>
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @php
                                $priorityBadges = [
                                    'urgent' => 'bg-red-100 text-red-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'medium' => 'bg-blue-100 text-blue-800',
                                    'low' => 'bg-gray-100 text-gray-800'
                                ];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full {{ $priorityBadges[$task->priority] ?? $priorityBadges['low'] }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            @if($task->due_date)
                            <div class="flex items-center gap-1 {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $task->due_date->format('M d') }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            View Details →
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('tasks.edit', $task) }}" class="text-gray-600 hover:text-gray-800 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" 
                                  onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-1">
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
            <div class="mt-4 sm:mt-6">
                {{ $tasks->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>