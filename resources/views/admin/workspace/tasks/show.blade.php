@extends('admin.layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start gap-3">
                <a href="{{ route('workspaces.show', $workspace) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm flex-shrink-0 mt-1">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">{{ $task->title }}</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="w-5 h-5 rounded flex items-center justify-center text-gray-600 border flex-shrink-0" style="border-color: {{ $workspace->color }};">
                            @php
                            $iconSvgs = [
                                'folder' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                'briefcase' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                            ];
                            @endphp
                            {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                        </div>
                        <span class="text-sm text-gray-600 truncate">{{ $workspace->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="border-l-4 px-6 py-4 bg-gradient-to-r from-gray-50 to-white" style="border-color: {{ $workspace->color }};">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Task Details
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Description</label>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $task->description ?? 'No description provided' }}</p>
                        </div>
                    </div>

                    <!-- Task Attachments -->
                    @if($task->file_path || $task->link)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 block">Task Materials</label>
                            <div class="space-y-3">
                                @if($task->file_path)
                                    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl border border-indigo-200 p-4 shadow-sm">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ basename($task->file_path) }}</p>
                                                <p class="text-xs text-gray-500">Task attachment</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('workspace.tasks.view-file', [$workspace, $task]) }}" 
                                               target="_blank"
                                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all text-sm font-medium shadow-sm hover:shadow">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </a>
                                            <a href="{{ route('workspace.tasks.download', [$workspace, $task]) }}" 
                                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all text-sm font-medium shadow-sm hover:shadow">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if($task->link)
                                    <a href="{{ $task->link }}" 
                                       target="_blank"
                                       class="flex items-center gap-3 p-4 bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-200 hover:border-blue-300 hover:shadow-md transition-all group">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-blue-600 mb-0.5">External Link</p>
                                            <p class="text-sm text-gray-700 truncate">{{ $task->link }}</p>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-lg p-4 border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Priority</label>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold {{ 
                                $task->priority === 'urgent' ? 'bg-red-100 text-red-800 border border-red-200' : 
                                ($task->priority === 'high' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 
                                ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 
                                'bg-blue-100 text-blue-800 border border-blue-200'))
                            }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ ucfirst($task->priority) }} Priority
                            </span>
                        </div>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-lg p-4 border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Overall Status</label>
                            @php
                                $totalUsers = $task->assignedUsers->count();
                                $submittedCount = $task->submissions->count();
                                $isAllDone = $totalUsers > 0 && $submittedCount === $totalUsers;
                            @endphp
                            @if($isAllDone)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    All Completed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    In Progress
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-lg p-4 border border-gray-200">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 block">Timeline</label>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Created</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $task->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Due Date</p>
                                    @if($task->due_date)
                                        <p class="text-sm font-medium text-gray-900">{{ date('M d, Y', strtotime($task->due_date)) }}</p>
                                    @else
                                        <p class="text-sm text-gray-400">No due date</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-lg p-4 border border-indigo-200">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 block">Progress Tracker</label>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700 font-medium">{{ $submittedCount }} of {{ $totalUsers }} completed</span>
                                <span class="text-xl font-bold text-indigo-600">{{ $totalUsers > 0 ? round(($submittedCount / $totalUsers) * 100) : 0 }}%</span>
                            </div>
                            <div class="relative w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-700 ease-out shadow-sm" 
                                     style="width: {{ $totalUsers > 0 ? round(($submittedCount / $totalUsers) * 100) : 0 }}%; background-color: {{ $workspace->color }};"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Users Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Assigned Users
                </h2>
                <span class="px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-700 rounded-full">
                    {{ $task->assignedUsers->count() }} {{ $task->assignedUsers->count() === 1 ? 'user' : 'users' }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($task->assignedUsers as $user)
                    @php
                        $userSubmission = $task->submissions->where('user_id', $user->id)->first();
                        $hasSubmitted = $userSubmission !== null;
                    @endphp
                    <div class="flex items-center justify-between p-4 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-md transition-all">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <span class="text-sm font-bold text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 text-sm truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        @if($hasSubmitted)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200 flex-shrink-0 ml-2">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Done
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200 flex-shrink-0 ml-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                                </svg>
                                Unfinished
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Submissions Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Submissions
                </h2>
                <span class="px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-700 rounded-full">
                    {{ $task->submissions->count() }} {{ $task->submissions->count() === 1 ? 'submission' : 'submissions' }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            @if($task->submissions->count() > 0)
                <div class="space-y-4">
                    @foreach($task->submissions as $submission)
                        <div class="border border-gray-200 rounded-xl p-5 hover:border-indigo-200 hover:shadow-md transition-all bg-gradient-to-br from-white to-gray-50">
                            <!-- Student Header -->
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md">
                                        <span class="text-sm font-bold text-white">
                                            {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900">{{ $submission->user->name }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $submission->user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-semibold border border-green-200">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Submitted
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $submission->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <!-- Submission Content -->
                            <div class="space-y-4">
                                @if($submission->notes)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            Notes
                                        </h4>
                                        <p class="text-gray-700 leading-relaxed bg-white rounded-lg p-4 border border-gray-200 shadow-sm whitespace-pre-line">{{ $submission->notes }}</p>
                                    </div>
                                @endif
                                
                                @if($submission->file_path)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            Attached File
                                        </h4>
                                        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-gray-900 text-sm truncate">
                                                        {{ basename($submission->file_path) }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-0.5">Submission file</p>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('workspace.submissions.view', [$workspace, $task, $submission]) }}" 
                                                   target="_blank"
                                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all text-sm font-medium shadow-sm hover:shadow">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View
                                                </a>
                                                <a href="{{ route('workspace.submissions.download', [$workspace, $task, $submission]) }}" 
                                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all text-sm font-medium shadow-sm hover:shadow">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($submission->link)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                            Submission Link
                                        </h4>
                                        <a href="{{ $submission->link }}" 
                                           target="_blank"
                                           class="flex items-center gap-3 bg-gradient-to-br from-blue-50 to-white rounded-xl p-4 border border-blue-200 hover:border-blue-400 hover:shadow-md transition-all group">
                                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-blue-600 mb-0.5">External Link</p>
                                                <p class="text-sm text-gray-700 truncate font-medium">{{ $submission->link }}</p>
                                            </div>
                                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No submissions yet</h3>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto">Submissions will appear here once users submit their work for this task</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection