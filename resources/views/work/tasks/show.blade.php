@php
use Illuminate\Support\Facades\Storage;
@endphp

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

                <!-- Task Materials from Admin -->
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-blue-900 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            Task Materials
                        </h4>
                    </div>
                    
                    @if($task->file_path || $task->link)
                        <div class="space-y-3">
                            @if($task->file_path)
                                @php
                                    $fileExtension = strtolower(pathinfo($task->file_path, PATHINFO_EXTENSION));
                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
                                    $isImage = in_array($fileExtension, $imageExtensions);
                                    $storagePath = storage_path('app/public/' . $task->file_path);
                                    $imageExists = file_exists($storagePath);
                                @endphp
                                
                                @if($isImage)
                                    @if($imageExists)
                                        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                            <button type="button" 
                                                    onclick="openImageModal('{{ asset('storage/' . $task->file_path) }}')" 
                                                    class="block w-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg overflow-hidden">
                                                <img src="{{ asset('storage/' . $task->file_path) }}" 
                                                    alt="Task attachment"
                                                    class="w-full h-auto max-h-[500px] object-contain hover:opacity-95 transition-opacity"
                                                    loading="lazy">
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-600 text-center mt-2">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Click image to view full size
                                        </p>
                                    @else
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                                            <svg class="w-12 h-12 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <p class="text-sm font-medium text-red-800 mb-1">Image file not found in storage</p>
                                            <p class="text-xs text-red-600 mb-2">{{ $task->file_path }}</p>
                                            <p class="text-xs text-red-500">Please ensure: php artisan storage:link has been run</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ basename($task->file_path) }}
                                                </p>
                                                <p class="text-xs text-gray-500 uppercase">{{ $fileExtension }} file</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if($task->link)
                                <a href="{{ $task->link }}" 
                                target="_blank"
                                class="block bg-white rounded-lg p-4 border border-blue-200 hover:border-blue-400 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-blue-900 group-hover:text-blue-700 truncate">
                                                Reference Link
                                            </p>
                                            <p class="text-xs text-gray-600 truncate">{{ $task->link }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </div>
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-600 font-medium">No materials attached</p>
                            <p class="text-xs text-gray-500 mt-1">Admin hasn't uploaded any files or links yet</p>
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
                            @endif>
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
                            @endif>
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
                            class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg font-semibold text-sm sm:text-base transition shadow-sm {{ $hasSubmitted ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 active:bg-blue-800' }}">
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
                            class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold text-base transition shadow-sm {{ $hasSubmitted ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 active:bg-blue-800' }}">
                        {{ $hasSubmitted ? 'Already Submitted' : 'Submit Task' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal Pop-up -->
<div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75 p-4" onclick="closeImageModal(event)">
    <div class="relative max-w-7xl max-h-full">
        <!-- Close Button -->
        <button onclick="closeImageModal()" 
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors focus:outline-none">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Image Container -->
        <div class="bg-white rounded-lg overflow-hidden shadow-2xl">
            <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-[85vh] w-auto h-auto object-contain">
        </div>
        
        <!-- Download Button -->
        <div class="text-center mt-4">
            <a href="{{ route('my-workspaces.task.download', [$workspace, $task]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
               download>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download Image
            </a>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageSrc;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeImageModal(event) {
    // Close only if clicked on backdrop or close button
    if (!event || event.target.id === 'imageModal' || event.currentTarget.tagName === 'BUTTON') {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Restore body scroll
        document.body.style.overflow = 'auto';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<style>
#imageModal {
    backdrop-filter: blur(4px);
}

#imageModal img {
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
</x-app-layout>