@extends('admin.layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('workspaces.show', $workspace) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-5 h-5 rounded flex items-center justify-center text-gray-600 border" style="border-color: {{ $workspace->color }};">
                            @php
                            $iconSvgs = [
                                'folder' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                'briefcase' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                'chart' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                'target' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'cog' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                'clipboard' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                            ];
                            @endphp
                            {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                        </div>
                        <span class="text-sm text-gray-600">{{ $workspace->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <!-- Header with colored accent -->
        <div class="border-l-4 px-6 py-5 bg-gray-50" style="border-color: {{ $workspace->color }};">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Task Details
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Description</label>
                        <p class="text-gray-700 leading-relaxed">{{ $task->description ?? 'No description provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Priority</label>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium {{ 
                            $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                            ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                            ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-blue-100 text-blue-800'))
                        }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Overall Status</label>
                        @php
                            $totalUsers = $task->assignedUsers->count();
                            $submittedCount = $task->submissions->count();
                            $isAllDone = $totalUsers > 0 && $submittedCount === $totalUsers;
                        @endphp
                        @if($isAllDone)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Completed
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-amber-100 text-amber-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                In Progress
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Created Date</label>
                        <div class="flex items-center gap-2 text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $task->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Due Date</label>
                        <div class="flex items-center gap-2 text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @if($task->due_date)
                                {{ date('M d, Y', strtotime($task->due_date)) }}
                            @else
                                <span class="text-gray-400">No due date set</span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Progress</label>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">{{ $submittedCount }} of {{ $totalUsers }} completed</span>
                                <span class="font-semibold text-gray-900">{{ $totalUsers > 0 ? round(($submittedCount / $totalUsers) * 100) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full transition-all duration-500" 
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
        <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Assigned Users
                <span class="ml-auto text-sm font-normal text-gray-500">{{ $task->assignedUsers->count() }} users</span>
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($task->assignedUsers as $user)
                    @php
                        $userSubmission = $task->submissions->where('user_id', $user->id)->first();
                        $hasSubmitted = $userSubmission !== null;
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-gray-300 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-indigo-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        @if($hasSubmitted)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Done
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                                </svg>
                                Pending
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Submissions Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Submissions
                <span class="ml-auto text-sm font-normal text-gray-500">{{ $task->submissions->count() }} submission(s)</span>
            </h2>
        </div>
        
        <div class="p-6">
            @if($task->submissions->count() > 0)
                <div class="space-y-4">
                    @foreach($task->submissions as $submission)
                        <div class="border border-gray-200 rounded-lg p-5 hover:border-gray-300 transition">
                            <!-- Student Header -->
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-sm font-semibold text-indigo-600">
                                            {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $submission->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $submission->user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Submitted</p>
                                    <p class="text-sm font-medium text-gray-700">{{ $submission->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <!-- Submission Content -->
                            <div class="space-y-4">
                                @if($submission->notes)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Notes</h4>
                                        <p class="text-gray-700 leading-relaxed bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $submission->notes }}</p>
                                    </div>
                                @endif
                                
                                @if($submission->file_path)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Attached File</h4>
                                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3 border border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                                       target="_blank"
                                                       class="font-medium text-gray-900 hover:text-indigo-600 transition">
                                                        {{ basename($submission->file_path) }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">Click to view</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                               download
                                               class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
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
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Submission Link</h4>
                                        <a href="{{ $submission->link }}" 
                                           target="_blank"
                                           class="flex items-center gap-2 bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition group">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            <span class="text-sm text-indigo-600 font-medium truncate">{{ $submission->link }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No submissions yet</h3>
                    <p class="text-sm text-gray-500">Submissions will appear here once users submit their work</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection