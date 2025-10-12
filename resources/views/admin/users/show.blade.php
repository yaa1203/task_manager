@extends('admin.layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('users.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">User Details</h1>
                <p class="text-sm text-gray-600 mt-1">View user information and assigned tasks</p>
            </div>
        </div>
    </div>

    {{-- User Profile Card --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <span class="text-white font-bold text-4xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->name }}</h2>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="10" r="4"/>
                            </svg>
                            Active User
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            ID: {{ $user->id }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $totalTasks = $tasks->count();
            $completedTasks = $tasks->filter(function($task) use ($user) {
                return $task->submissions->where('user_id', $user->id)->isNotEmpty();
            })->count();
            
            // Overdue tasks: tasks with due_date in the past and not submitted
            $overdueTasks = $tasks->filter(function($task) use ($user) {
                $hasSubmitted = $task->submissions->where('user_id', $user->id)->isNotEmpty();
                return !$hasSubmitted && $task->due_date && now()->gt($task->due_date);
            })->count();
            
            // Unfinished tasks: tasks not submitted yet (including overdue)
            $unfinishedTasks = $totalTasks - $completedTasks;
        @endphp

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Total Tasks</p>
            <p class="text-3xl font-bold">{{ $totalTasks }}</p>
            <p class="text-xs opacity-75 mt-2">Assigned tasks</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Completed</p>
            <p class="text-3xl font-bold">{{ $completedTasks }}</p>
            <p class="text-xs opacity-75 mt-2">Submitted tasks</p>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Overdue</p>
            <p class="text-3xl font-bold">{{ $overdueTasks }}</p>
            <p class="text-xs opacity-75 mt-2">Past deadline</p>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Unfinished</p>
            <p class="text-3xl font-bold">{{ $unfinishedTasks }}</p>
            <p class="text-xs opacity-75 mt-2">Not submitted yet</p>
        </div>
    </div>

    {{-- Tasks Section --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Task Details</h2>
                        <p class="text-sm text-gray-600">All tasks with submission status</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if($tasks->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($tasks as $task)
                        @php
                            $userSubmission = $task->submissions->where('user_id', $user->id)->first();
                            $hasSubmitted = $userSubmission !== null;
                            $workspace = $task->workspace;
                        @endphp
                        <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200 hover:border-indigo-300">
                            <!-- Task Header -->
                            <div class="p-5 border-b border-gray-100" style="background: linear-gradient(135deg, {{ $workspace->color }}15 0%, {{ $workspace->color }}05 100%);">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-base" 
                                                 style="background-color: {{ $workspace->color }}30;">
                                                {{ $workspace->icon }}
                                            </div>
                                            <span class="text-xs text-gray-600 font-medium">{{ $workspace->name }}</span>
                                        </div>
                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $task->title }}</h3>
                                        @if($task->description)
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $task->description }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap ml-2
                                        @php
                                            $priorityConfig = [
                                                'urgent' => 'bg-red-100 text-red-800',
                                                'high' => 'bg-orange-100 text-orange-800',
                                                'medium' => 'bg-yellow-100 text-yellow-800',
                                                'low' => 'bg-blue-100 text-blue-800'
                                            ];
                                            echo $priorityConfig[$task->priority] ?? $priorityConfig['low'];
                                        @endphp">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>

                                <!-- Task Info -->
                                <div class="flex items-center gap-4 text-xs text-gray-600">
                                    @if($task->due_date)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Due: {{ date('M d, Y', strtotime($task->due_date)) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Task Details -->
                            <div class="p-5">
                                <!-- Status -->
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Submission Status</h4>
                                    @if($hasSubmitted)
                                        <div class="flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-green-800">Submitted</p>
                                                <p class="text-xs text-green-600">
                                                    {{ $userSubmission->submitted_at ? $userSubmission->submitted_at->diffForHumans() : 'Recently' }}
                                                </p>
                                            </div>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                                @php
                                                    $statusConfig = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'approved' => 'bg-green-100 text-green-800',
                                                        'rejected' => 'bg-red-100 text-red-800'
                                                    ];
                                                    echo $statusConfig[$userSubmission->status] ?? $statusConfig['pending'];
                                                @endphp">
                                                {{ ucfirst($userSubmission->status) }}
                                            </span>
                                        </div>

                                        @if($userSubmission->notes)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs font-medium text-gray-700 mb-1">Notes:</p>
                                            <p class="text-sm text-gray-600">{{ $userSubmission->notes }}</p>
                                        </div>
                                        @endif
                                    @else
                                        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-800">Not Submitted</p>
                                                <p class="text-xs text-gray-600">Waiting for submission</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Button -->
                                <div class="pt-3 border-t border-gray-100">
                                    <a href="{{ route('workspace.tasks.show', [$workspace, $task]) }}" 
                                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="text-sm font-medium">View Workspace</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No Tasks Assigned</h3>
                    <p class="text-sm text-gray-500">This user has no tasks assigned yet</p>
                </div>
            @endif
        </div>
    </div>
</div>

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
@endsection