<x-app-layout>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Workspace -->
    <div class="mb-6">
        <a href="{{ route('my-workspaces.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Workspace
        </a>
        
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 flex items-center justify-center text-3xl rounded-xl" 
                     style="background-color: {{ $workspace->color }}20;">
                    {{ $workspace->icon }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $workspace->name }}</h1>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                            {{ ucfirst($workspace->type) }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $workspace->description }}</p>
                    <div class="flex items-center gap-6 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            {{ $tasks->count() }} Tasks
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Filter -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button onclick="filterTasks('all')" id="tab-all" 
                        class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                    All Tasks
                </button>
                <button onclick="filterTasks('unfinished')" id="tab-unfinished" 
                        class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Unfinished
                </button>
                <button onclick="filterTasks('overdue')" id="tab-overdue" 
                        class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Overdue
                </button>
                <button onclick="filterTasks('done')" id="tab-done" 
                        class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Done
                </button>
            </nav>
        </div>
    </div>

    <!-- Tasks Table -->
    <div>
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            @if($tasks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Task
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priority
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tasks as $task)
                                @php
                                    // Check if user has submitted this task
                                    $hasSubmission = $task->submissions->isNotEmpty();
                                    $latestSubmission = $task->submissions->first();
                                    
                                    // Check if task is overdue
                                    $isOverdue = false;
                                    if ($task->due_date && !$hasSubmission) {
                                        $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast();
                                    }
                                    
                                    // Determine status
                                    $isDone = $hasSubmission;
                                    $isUnfinished = !$hasSubmission && !$isOverdue;
                                    
                                    // Status for filtering
                                    if ($isDone) {
                                        $statusFilter = 'done';
                                    } elseif ($isOverdue) {
                                        $statusFilter = 'overdue';
                                    } else {
                                        $statusFilter = 'unfinished';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 task-row" 
                                    data-status="{{ $statusFilter }}">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                        @if($task->description)
                                            <div class="text-sm text-gray-500 line-clamp-1">{{ $task->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($isDone)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Done
                                            </span>
                                        @elseif($isOverdue)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Overdue
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Unfinished
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($task->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($task->due_date)
                                            <div class="flex flex-col">
                                                <span class="{{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                                </span>
                                                @if($isOverdue)
                                                    <span class="text-xs text-red-500">
                                                        {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('my-workspaces.task.show', [$workspace, $task]) }}" 
                                           class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium">
                                            View Detail
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Empty State for Filtered Results -->
                <div id="empty-state" class="hidden text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500" id="empty-message">No tasks found</p>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Belum ada task di workspace ini</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function filterTasks(filter) {
    const rows = document.querySelectorAll('.task-row');
    const emptyState = document.getElementById('empty-state');
    const emptyMessage = document.getElementById('empty-message');
    const table = document.querySelector('table');
    let visibleCount = 0;
    
    // Update tab styles
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeTab = document.getElementById('tab-' + filter);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-500', 'text-blue-600');
    
    // Filter rows
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        
        if (filter === 'all') {
            row.style.display = '';
            visibleCount++;
        } else if (filter === status) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    if (visibleCount === 0) {
        if (table) table.style.display = 'none';
        emptyState.classList.remove('hidden');
        
        if (filter === 'done') {
            emptyMessage.textContent = 'Belum ada task yang selesai';
        } else if (filter === 'unfinished') {
            emptyMessage.textContent = 'Semua task sudah selesai atau overdue!';
        } else if (filter === 'overdue') {
            emptyMessage.textContent = 'Tidak ada task yang overdue';
        } else {
            emptyMessage.textContent = 'Tidak ada task';
        }
    } else {
        if (table) table.style.display = '';
        emptyState.classList.add('hidden');
    }
}
</script>
</x-app-layout>