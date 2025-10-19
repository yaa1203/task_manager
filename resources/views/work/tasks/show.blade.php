<x-app-layout>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Back Button -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('my-workspaces.show', $workspace) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors mb-3">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke {{ $workspace->name }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Task Detail Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex-1">{{ $task->title }}</h1>
                    <span class="px-3 py-1.5 text-xs sm:text-sm font-semibold rounded-full w-fit border
                        @if($task->priority === 'urgent') bg-red-100 text-red-800 border-red-200
                        @elseif($task->priority === 'high') bg-orange-100 text-orange-800 border-orange-200
                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800 border-yellow-200
                        @else bg-gray-100 text-gray-800 border-gray-200
                        @endif">
                        {{ ucfirst($task->priority) }} Priority
                    </span>
                </div>

                @if($task->description)
                    <div class="prose prose-sm max-w-none mb-4 sm:mb-6">
                        <p class="text-sm sm:text-base text-gray-600 whitespace-pre-wrap leading-relaxed">{{ $task->description }}</p>
                    </div>
                @endif

                <!-- Task Attachments from Admin - ALWAYS SHOW THIS SECTION -->
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        Task Materials
                    </h4>
                    
                    @if($task->file_path || $task->link)
                        <div class="flex flex-col sm:flex-row flex-wrap gap-2">
                            @if($task->file_path)
                                <div class="flex gap-2">
                                    <a href="{{ route('my-workspaces.task.download', [$workspace, $task]) }}" 
                                       class="inline-flex items-center gap-2 px-3 py-2 text-xs sm:text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-100 border border-gray-300 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        View/Download File
                                    </a>
                                </div>
                            @endif

                            @if($task->link)
                                <a href="{{ $task->link }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-3 py-2 text-xs sm:text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 border border-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open Link
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-600">No materials attached</p>
                            <p class="text-xs text-gray-500">Admin hasn't uploaded any files or links yet</p>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4 pt-4 border-t border-gray-200">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1.5">Your Status</p>
                        @php
                            $userSubmission = $task->submissions->where('user_id', auth()->id())->first();
                            $hasUserSubmitted = $userSubmission !== null;
                        @endphp
                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-2.5 py-1 sm:py-1.5 text-xs font-bold rounded-full border
                            @if($hasUserSubmitted) bg-green-100 text-green-800 border-green-200
                            @else bg-gray-100 text-gray-800 border-gray-200
                            @endif">
                            @if($hasUserSubmitted)
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Done
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Unfinished
                            @endif
                        </span>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1.5">Due Date</p>
                        <div class="text-xs sm:text-sm font-medium text-gray-900">
                            @if($task->due_date)
                                <span class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() && !$hasUserSubmitted ? 'text-red-600' : '' }}">
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                </span>
                                @if(\Carbon\Carbon::parse($task->due_date)->isPast() && !$hasUserSubmitted)
                                    <div class="text-xs text-red-600 mt-0.5">(Overdue)</div>
                                @endif
                            @else
                                <span class="text-gray-400">No due date</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Task Card (Mobile) -->
            <div class="lg:hidden bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Submit Task</h3>

                @if($hasSubmitted)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 mb-4">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-xs sm:text-sm text-green-800">
                                Anda sudah mengirimkan submission untuk task ini
                            </p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('my-workspaces.task.submit', [$workspace, $task]) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="space-y-3 sm:space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                            Upload File
                        </label>
                        <input type="file" 
                               name="file"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full text-xs sm:text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none {{ $hasSubmitted ? 'opacity-50' : '' }}">
                        <p class="mt-1 text-xs text-gray-500">Max 10MB</p>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                            Link (Optional)
                        </label>
                        <input type="url" 
                               name="link" 
                               placeholder="https://example.com"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" 
                                  rows="4" 
                                  placeholder="Add any additional notes..."
                                  {{ $hasSubmitted ? 'disabled' : '' }}
                                  class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}"></textarea>
                    </div>

                    <button type="submit" 
                            {{ $hasSubmitted ? 'disabled' : '' }}
                            class="w-full bg-blue-600 text-white px-4 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition shadow-sm {{ $hasSubmitted ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 active:bg-blue-800' }}">
                        {{ $hasSubmitted ? 'Already Submitted' : 'Submit Task' }}
                    </button>
                </form>
            </div>

            <!-- My Submissions -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">My Submissions</h2>

                @if($submissions->count() > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($submissions as $submission)
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:border-gray-300 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 sm:gap-0 mb-3">
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">
                                            Submitted {{ $submission->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if($submission->status)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full border w-fit
                                            @if($submission->status === 'approved') bg-green-100 text-green-800 border-green-200
                                            @elseif($submission->status === 'rejected') bg-red-100 text-red-800 border-red-200
                                            @else bg-yellow-100 text-yellow-800 border-yellow-200
                                            @endif">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    @endif
                                </div>

                                @if($submission->notes)
                                    <div class="mb-3">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-1">Notes:</p>
                                        <p class="text-xs sm:text-sm text-gray-600 bg-gray-50 p-2 rounded">{{ $submission->notes }}</p>
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3">
                                    @if($submission->file_path)
                                        <a href="{{ route('my-workspaces.submission.download', [$workspace, $task, $submission]) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-3 py-2 text-xs sm:text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 border border-gray-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download File
                                        </a>
                                    @endif

                                    @if($submission->link)
                                        <a href="{{ $submission->link }}" 
                                           target="_blank"
                                           class="inline-flex items-center justify-center gap-2 px-3 py-2 text-xs sm:text-sm bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 border border-blue-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Open Link
                                        </a>
                                    @endif
                                </div>

                                @if($submission->admin_notes)
                                    <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                        <p class="text-xs font-semibold text-amber-900 mb-1 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            Admin Feedback:
                                        </p>
                                        <p class="text-xs sm:text-sm text-amber-800">{{ $submission->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 sm:py-12">
                        <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-xs sm:text-sm text-gray-500">No submissions yet</p>
                        <p class="text-xs text-gray-400 mt-1">Submit your work using the form {{ $hasSubmitted ? 'above' : 'on the side' }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar (Desktop only) -->
        <div class="hidden lg:block lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit Task</h3>

                @if($hasSubmitted)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-green-800">
                                Anda sudah mengirimkan submission untuk task ini
                            </p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('my-workspaces.task.submit', [$workspace, $task]) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload File
                        </label>
                        <input type="file" 
                               name="file"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none {{ $hasSubmitted ? 'opacity-50' : '' }}">
                        <p class="mt-1 text-xs text-gray-500">Max 10MB</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Link (Optional)
                        </label>
                        <input type="url" 
                               name="link" 
                               placeholder="https://example.com"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" 
                                  rows="4" 
                                  placeholder="Add any additional notes..."
                                  {{ $hasSubmitted ? 'disabled' : '' }}
                                  class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}"></textarea>
                    </div>

                    <button type="submit" 
                            {{ $hasSubmitted ? 'disabled' : '' }}
                            class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg font-semibold transition shadow-sm {{ $hasSubmitted ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 active:bg-blue-800' }}">
                        {{ $hasSubmitted ? 'Already Submitted' : 'Submit Task' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>