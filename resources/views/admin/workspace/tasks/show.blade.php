@extends('admin.layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('workspaces.show', $workspace) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                <p class="text-sm text-gray-600">Workspace: {{ $workspace->name }}</p>
            </div>
        </div>
    </div>

    <!-- Task Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Task Information -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Task Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Description</p>
                        <p class="text-gray-700">{{ $task->description ?? 'No description provided' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status</p>
                        @php
                            $totalUsers = $task->assignedUsers->count();
                            $submittedCount = $task->submissions->count();
                            $isAllDone = $totalUsers > 0 && $submittedCount === $totalUsers;
                        @endphp
                        @if($isAllDone)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Done
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Unfinished
                            </span>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Priority</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ 
                            $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                            ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                            ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-blue-100 text-blue-800'))
                        }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Assignment Information -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Assignment</h2>
                
                <div class="space-y-4">
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Workspace</p>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xl shadow-sm" 
                                 style="background-color: {{ $workspace->color }}30;">
                                {{ $workspace->icon }}
                            </div>
                            <p class="font-medium text-gray-900">{{ $workspace->name }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Created</p>
                        <p class="text-gray-700">{{ $task->created_at->format('M d, Y') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Due Date</p>
                        <p class="text-gray-700">
                            @if($task->due_date)
                                {{ date('M d, Y', strtotime($task->due_date)) }}
                            @else
                                <span class="text-gray-400">No due date</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Student Submissions</h2>
            <span class="text-sm text-gray-500">{{ $task->submissions->count() }} submission(s)</span>
        </div>
        
        @if($task->submissions->count() > 0)
            <div class="space-y-6">
                @foreach($task->submissions as $submission)
                    <div class="border border-gray-200 rounded-lg p-5">
                        <!-- Student Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-indigo-600">
                                        {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $submission->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $submission->user->email }}</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                Submitted {{ $submission->created_at->diffForHumans() }}
                            </div>
                        </div>
                        
                        <!-- Submission Content -->
                        <div class="space-y-4">
                            @if($submission->notes)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">Notes</h3>
                                    <p class="text-gray-700">{{ $submission->notes }}</p>
                                </div>
                            @endif
                            
                            @if($submission->file_path)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">File</h3>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                           target="_blank"
                                           class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            {{ basename($submission->file_path) }}
                                        </a>
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                           download
                                           class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            @if($submission->link)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">Link</h3>
                                    <a href="{{ $submission->link }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        {{ $submission->link }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500">No submissions yet</p>
            </div>
        @endif
    </div>
</div>

<style>
/* Custom styles for better presentation */
.border-gray-200 {
    border-color: #e5e7eb;
}
.rounded-xl {
    border-radius: 0.75rem;
}
.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
</style>
@endsection