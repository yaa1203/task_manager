@extends('admin.layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Task Details</h1>
                <p class="text-sm sm:text-base text-gray-600">View detailed information about the task</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tugas.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Tasks
                </a>
            </div>
        </div>
    </div>

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

    <!-- Task Detail Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Task Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white">
                            #{{ $task->id }}
                        </span>
                        @php
                            $priorityConfig = [
                                'high' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                'medium' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                                'low' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800']
                            ];
                            $pConfig = $priorityConfig[$task->priority] ?? $priorityConfig['low'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $pConfig['bg'] }} {{ $pConfig['text'] }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-white">{{ $task->title }}</h2>
                </div>
                <div class="flex items-center gap-3">
                    @php
                        $statusConfig = [
                            'done' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'M5 13l4 4L19 7'],
                            'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z']
                        ];
                        $config = $statusConfig[$task->status] ?? $statusConfig['pending'];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                        </svg>
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Task Body -->
        <div class="p-6">
            <!-- Description -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Description
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 text-gray-700">
                    {{ nl2br($task->description) ?: '<p class="text-gray-400 italic">No description provided</p>' }}
                </div>
            </div>

            <!-- Task Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Assigned To -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Assigned To
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($task->user)
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-lg font-semibold text-indigo-600">
                                        {{ strtoupper(substr($task->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $task->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $task->user->email }}</div>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-400 italic">Unassigned</p>
                        @endif
                    </div>
                </div>

                <!-- Created By -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Created By
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($task->creator)
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-lg font-semibold text-purple-600">
                                        {{ strtoupper(substr($task->creator->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $task->creator->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $task->creator->email }}</div>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-400 italic">System Generated</p>
                        @endif
                    </div>
                </div>

                <!-- Due Date -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Due Date
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($task->due_date)
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">{{ date('F j, Y', strtotime($task->due_date)) }}</span>
                                <span class="text-sm text-gray-500">({{ date('l', strtotime($task->due_date)) }})</span>
                            </div>
                            <div class="mt-2">
                                @php
                                    $now = time();
                                    $due = strtotime($task->due_date);
                                    $diff = $due - $now;
                                    $days = floor($diff / (60 * 60 * 24));
                                @endphp
                                @if($days < 0)
                                    <span class="text-red-600 text-sm font-medium">Overdue by {{ abs($days) }} day(s)</span>
                                @elseif($days == 0)
                                    <span class="text-yellow-600 text-sm font-medium">Due today</span>
                                @elseif($days <= 3)
                                    <span class="text-orange-600 text-sm font-medium">Due in {{ $days }} day(s)</span>
                                @else
                                    <span class="text-green-600 text-sm font-medium">Due in {{ $days }} day(s)</span>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-400 italic">No due date set</p>
                        @endif
                    </div>
                </div>

                <!-- Created At -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Created At
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="font-medium text-gray-900">{{ date('F j, Y \a\t g:i A', strtotime($task->created_at)) }}</div>
                        <div class="text-sm text-gray-500">
                            {{ $task->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <form action="{{ route('tugas.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Task
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom Animation -->
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
</style>
@endsection