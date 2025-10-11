<x-app-layout>
<div class="max-w-5xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center text-sm text-gray-600 space-x-2">
            <a href="{{ route('my-workspaces.index') }}" class="hover:text-gray-900">My Workspaces</a>
            <span>/</span>
            <a href="{{ route('my-workspaces.show', $workspace) }}" class="hover:text-gray-900">{{ $workspace->name }}</a>
            <span>/</span>
            <span class="text-gray-900">{{ $task->title }}</span>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Detail Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="flex items-start justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @if($task->priority === 'urgent') bg-red-100 text-red-800
                        @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($task->priority) }} Priority
                    </span>
                </div>

                @if($task->description)
                    <div class="prose prose-sm max-w-none mb-6">
                        <p class="text-gray-600 whitespace-pre-wrap">{{ $task->description }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Your Status</p>
                        @php
                            // Check if current user has submitted
                            $userSubmission = $task->submissions->where('user_id', auth()->id())->first();
                            $hasUserSubmitted = $userSubmission !== null;
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full
                            @if($hasUserSubmitted) bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
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
                        <p class="text-sm text-gray-500 mb-1">Due Date</p>
                        <p class="text-sm font-medium text-gray-900">
                            @if($task->due_date)
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                @if(\Carbon\Carbon::parse($task->due_date)->isPast() && !$hasUserSubmitted)
                                    <span class="text-red-600 text-xs">(Overdue)</span>
                                @endif
                            @else
                                <span class="text-gray-400">No due date</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- My Submissions -->
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">My Submissions</h2>

                @if($submissions->count() > 0)
                    <div class="space-y-4">
                        @foreach($submissions as $submission)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">
                                            Submitted {{ $submission->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if($submission->status)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($submission->status === 'approved') bg-green-100 text-green-800
                                            @elseif($submission->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    @endif
                                </div>

                                @if($submission->notes)
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700 mb-1">Notes:</p>
                                        <p class="text-sm text-gray-600">{{ $submission->notes }}</p>
                                    </div>
                                @endif

                                <div class="flex flex-wrap gap-3">
                                    @if($submission->file_path)
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 px-3 py-2 text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            View File
                                        </a>
                                    @endif

                                    @if($submission->link)
                                        <a href="{{ $submission->link }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 px-3 py-2 text-sm bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                            View Link
                                        </a>
                                    @endif
                                </div>

                                @if($submission->admin_notes)
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs font-medium text-gray-700 mb-1">Admin Feedback:</p>
                                        <p class="text-sm text-gray-600">{{ $submission->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No submissions yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Submit Task Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-6">
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

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload File
                        </label>
                        <input type="file" 
                               name="file"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none {{ $hasSubmitted ? 'opacity-50' : '' }}">
                        <p class="mt-1 text-xs text-gray-500">Max 10MB</p>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Link (Optional)
                        </label>
                        <input type="url" 
                               name="link" 
                               placeholder="https://example.com"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}">
                        @error('link')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" 
                                  rows="4" 
                                  placeholder="Add any additional notes..."
                                  {{ $hasSubmitted ? 'disabled' : '' }}
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}"></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            {{ $hasSubmitted ? 'disabled' : '' }}
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition {{ $hasSubmitted ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700' }}">
                        {{ $hasSubmitted ? 'Already Submitted' : 'Submit Task' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>