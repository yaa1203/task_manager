@php
use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Tombol Kembali -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('my-workspaces.show', $workspace) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors mb-3">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke {{ $workspace->name }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Konten Utama -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Kartu Detail Tugas -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex-1">{{ $task->title }}</h1>
                    <span class="px-3 py-1.5 text-xs sm:text-sm font-semibold rounded-full w-fit border
                        @if($task->priority === 'urgent') bg-red-100 text-red-800 border-red-200
                        @elseif($task->priority === 'high') bg-orange-100 text-orange-800 border-orange-200
                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800 border-yellow-200
                        @else bg-gray-100 text-gray-800 border-gray-200
                        @endif">
                        {{ ucfirst($task->priority) }} Prioritas
                    </span>
                </div>

                @if($task->description)
                    <div class="prose prose-sm max-w-none mb-4 sm:mb-6">
                        <p class="text-sm sm:text-base text-gray-600 whitespace-pre-wrap leading-relaxed">{{ $task->description }}</p>
                    </div>
                @endif

                <!-- Materi Tugas dari Admin -->
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-blue-900 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            Materi Tugas
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
                                    $fileExists = file_exists($storagePath);
                                    
                                    // Determine file icon based on extension
                                    $fileIcon = 'document';
                                    $iconColor = 'gray';
                                    if (in_array($fileExtension, ['pdf'])) {
                                        $fileIcon = 'pdf';
                                        $iconColor = 'red';
                                    } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                                        $fileIcon = 'word';
                                        $iconColor = 'blue';
                                    } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                                        $fileIcon = 'excel';
                                        $iconColor = 'green';
                                    } elseif (in_array($fileExtension, ['ppt', 'pptx'])) {
                                        $fileIcon = 'powerpoint';
                                        $iconColor = 'orange';
                                    } elseif (in_array($fileExtension, ['zip', 'rar', '7z'])) {
                                        $fileIcon = 'archive';
                                        $iconColor = 'yellow';
                                    }
                                @endphp
                                
                                @if($fileExists)
                                    @if($isImage)
                                        <!-- Image Preview -->
                                        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                            <button type="button" 
                                                    onclick="openFileModal('{{ asset('storage/' . $task->file_path) }}', 'image', '{{ basename($task->file_path) }}')" 
                                                    class="block w-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg overflow-hidden">
                                                <img src="{{ asset('storage/' . $task->file_path) }}" 
                                                    alt="Lampiran tugas"
                                                    class="w-full h-auto max-h-[500px] object-contain hover:opacity-95 transition-opacity"
                                                    loading="lazy">
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-600 text-center mt-2">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Klik gambar untuk melihat ukuran penuh
                                        </p>
                                    @else
                                        <!-- File Preview Card -->
                                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-all">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-{{ $iconColor }}-100 flex items-center justify-center">
                                                    @if($fileIcon === 'pdf')
                                                        <svg class="w-6 h-6 text-{{ $iconColor }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h10l4 4v16H2v-1z"/>
                                                            <text x="10" y="14" font-size="6" text-anchor="middle" fill="currentColor">PDF</text>
                                                        </svg>
                                                    @elseif($fileIcon === 'word')
                                                        <svg class="w-6 h-6 text-{{ $iconColor }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 2h12l4 4v12H4V2zm1 1v14h10V7h-4V3H5z"/>
                                                            <text x="10" y="14" font-size="5" text-anchor="middle" fill="currentColor">DOC</text>
                                                        </svg>
                                                    @elseif($fileIcon === 'excel')
                                                        <svg class="w-6 h-6 text-{{ $iconColor }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 2h12l4 4v12H4V2zm1 1v14h10V7h-4V3H5z"/>
                                                            <text x="10" y="14" font-size="5" text-anchor="middle" fill="currentColor">XLS</text>
                                                        </svg>
                                                    @else
                                                        <svg class="w-6 h-6 text-{{ $iconColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ basename($task->file_path) }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 uppercase">{{ $fileExtension }} file</p>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                @if(in_array($fileExtension, ['pdf', 'txt']))
                                                    <button onclick="openFileModal('{{ route('my-workspaces.task.view-file', [$workspace, $task]) }}', '{{ $fileExtension }}', '{{ basename($task->file_path) }}')"
                                                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm font-medium">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Lihat
                                                    </button>
                                                @endif
                                                <a href="{{ route('my-workspaces.task.download', [$workspace, $task]) }}" 
                                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Unduh
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                                        <svg class="w-12 h-12 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <p class="text-sm font-medium text-red-800 mb-1">File tidak ditemukan di penyimpanan</p>
                                        <p class="text-xs text-red-600 mb-2">{{ $task->file_path }}</p>
                                        <p class="text-xs text-red-500">Pastikan: php artisan storage:link telah dijalankan</p>
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
                                                Tautan Referensi
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
                            <p class="text-sm text-gray-600 font-medium">Tidak ada materi terlampir</p>
                            <p class="text-xs text-gray-500 mt-1">Admin belum mengunggah file atau tautan apa pun</p>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4 pt-4 border-t border-gray-200">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1.5">Status Anda</p>
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
                                Selesai
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Belum Selesai
                            @endif
                        </span>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1.5">Tanggal Jatuh Tempo</p>
                        <div class="text-xs sm:text-sm font-medium text-gray-900">
                            @if($task->due_date)
                                <span class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() && !$hasUserSubmitted ? 'text-red-600' : '' }}">
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y H:i') }}
                                </span>
                                @if(\Carbon\Carbon::parse($task->due_date)->isPast() && !$hasUserSubmitted)
                                    <div class="text-xs text-red-600 mt-0.5">(Terlambat)</div>
                                @endif
                            @else
                                <span class="text-gray-400">Tidak ada tanggal jatuh tempo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Submit Tugas (Mobile) -->
            <div class="lg:hidden bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Kirim Tugas</h3>

                @if($hasSubmitted)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 mb-4">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-xs sm:text-sm text-green-800">
                                Anda sudah mengirimkan submission untuk tugas ini
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
                            Unggah File
                        </label>
                        <input type="file" 
                               name="file"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full text-xs sm:text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none {{ $hasSubmitted ? 'opacity-50' : '' }}">
                        <p class="mt-1 text-xs text-gray-500">Maks 10MB</p>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                            Tautan (Opsional)
                        </label>
                        <input type="url" 
                               name="link" 
                               placeholder="https://contoh.com"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="notes" 
                                  rows="4" 
                                  placeholder="Tambahkan catatan tambahan..."
                                  {{ $hasSubmitted ? 'disabled' : '' }}
                                  class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}"></textarea>
                    </div>

                    <button type="submit" 
                            {{ $hasSubmitted ? 'disabled' : '' }}
                            class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg font-semibold text-sm sm:text-base transition shadow-sm {{ $hasSubmitted ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 active:bg-blue-800' }}">
                        {{ $hasSubmitted ? 'Sudah Dikirim' : 'Kirim Tugas' }}
                    </button>
                </form>
            </div>

            <!-- Submission Saya -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Submission Saya</h2>

                @if($submissions->count() > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($submissions as $submission)
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:border-gray-300 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 sm:gap-0 mb-3">
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">
                                            Dikirim {{ $submission->created_at->format('d M Y H:i') }}
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
                                        <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-1">Catatan:</p>
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
                                            Unduh File
                                        </a>
                                    @endif

                                    @if($submission->link)
                                        <a href="{{ $submission->link }}" 
                                           target="_blank"
                                           class="inline-flex items-center justify-center gap-2 px-3 py-2 text-xs sm:text-sm bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 border border-blue-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Buka Tautan
                                        </a>
                                    @endif
                                </div>

                                @if($submission->admin_notes)
                                    <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                        <p class="text-xs font-semibold text-amber-900 mb-1 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            Feedback Admin:
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
                        <p class="mt-2 text-xs sm:text-sm text-gray-500">Belum ada submission</p>
                        <p class="text-xs text-gray-400 mt-1">Kirim pekerjaan Anda menggunakan form {{ $hasSubmitted ? 'di atas' : 'di samping' }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar (Hanya desktop) -->
        <div class="hidden lg:block lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kirim Tugas</h3>

                @if($hasSubmitted)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-green-800">
                                Anda sudah mengirimkan submission untuk tugas ini
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
                            Unggah File
                        </label>
                        <input type="file" 
                               name="file"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none {{ $hasSubmitted ? 'opacity-50' : '' }}">
                        <p class="mt-1 text-xs text-gray-500">Maks 10MB</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tautan (Opsional)
                        </label>
                        <input type="url" 
                               name="link" 
                               placeholder="https://contoh.com"
                               {{ $hasSubmitted ? 'disabled' : '' }}
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="notes" 
                                  rows="4" 
                                  placeholder="Tambahkan catatan tambahan..."
                                  {{ $hasSubmitted ? 'disabled' : '' }}
                                  class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $hasSubmitted ? 'opacity-50 bg-gray-100' : '' }}"></textarea>
                    </div>

                    <button type="submit" 
                            {{ $hasSubmitted ? 'disabled' : '' }}
                            class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold text-base transition shadow-sm {{ $hasSubmitted ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 active:bg-blue-800' }}">
                        {{ $hasSubmitted ? 'Sudah Dikirim' : 'Kirim Tugas' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Universal File Viewer -->
<div id="fileModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75 p-4" onclick="closeFileModal(event)">
    <div class="relative w-full max-w-7xl max-h-full flex flex-col">
        <!-- Header Modal -->
        <div class="flex items-center justify-between mb-4">
            <h3 id="modalFileName" class="text-white font-semibold text-lg truncate mr-4"></h3>
            <button onclick="closeFileModal()" 
                    class="text-white hover:text-gray-300 transition-colors focus:outline-none flex-shrink-0">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Kontainer File -->
        <div class="bg-white rounded-lg overflow-hidden shadow-2xl flex-1 flex items-center justify-center" style="max-height: 85vh;">
            <!-- Loading Indicator -->
            <div id="fileLoading" class="text-center py-12">
                <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600">Memuat file...</p>
            </div>
            
            <!-- Image Container -->
            <img id="modalImage" src="" alt="Gambar ukuran penuh" class="hidden max-w-full max-h-full w-auto h-auto object-contain">
            
            <!-- PDF/Document Container -->
            <iframe id="modalIframe" class="hidden w-full h-full" frameborder="0"></iframe>
            
            <!-- Unsupported File Message -->
            <div id="unsupportedFile" class="hidden text-center py-12 px-6">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pratinjau Tidak Tersedia</h3>
                <p class="text-gray-600 mb-4">Tipe file ini tidak dapat ditampilkan di browser</p>
                <a id="downloadLink" href="#" download 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh File
                </a>
            </div>
        </div>
        
        <!-- Tombol Unduh -->
        <div class="text-center mt-4" id="modalDownloadBtn">
            <a href="#" id="modalDownload"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
               download>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh File
            </a>
        </div>
    </div>
</div>

<script>
let currentDownloadUrl = '';

function openFileModal(fileUrl, fileType, fileName) {
    const modal = document.getElementById('fileModal');
    const modalImage = document.getElementById('modalImage');
    const modalIframe = document.getElementById('modalIframe');
    const fileLoading = document.getElementById('fileLoading');
    const unsupportedFile = document.getElementById('unsupportedFile');
    const modalFileName = document.getElementById('modalFileName');
    const modalDownload = document.getElementById('modalDownload');
    const downloadLink = document.getElementById('downloadLink');
    const modalDownloadBtn = document.getElementById('modalDownloadBtn');
    
    // Reset semua elemen
    modalImage.classList.add('hidden');
    modalIframe.classList.add('hidden');
    unsupportedFile.classList.add('hidden');
    fileLoading.classList.remove('hidden');
    modalDownloadBtn.classList.remove('hidden');
    
    // Set nama file
    modalFileName.textContent = fileName;
    
    // Set download URL
    const downloadUrl = fileUrl.replace('/view-file/', '/download/');
    currentDownloadUrl = downloadUrl;
    modalDownload.href = downloadUrl;
    downloadLink.href = downloadUrl;
    
    // Tampilkan modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Handle berdasarkan tipe file
    setTimeout(() => {
        fileLoading.classList.add('hidden');
        
        if (fileType === 'image') {
            // Untuk gambar
            modalImage.src = fileUrl;
            modalImage.classList.remove('hidden');
            modalImage.onload = () => {
                fileLoading.classList.add('hidden');
            };
        } else if (fileType === 'pdf') {
            // Untuk PDF - langsung embed
            modalIframe.src = fileUrl + '#toolbar=0&navpanes=0&scrollbar=1';
            modalIframe.classList.remove('hidden');
            
            // Handle jika PDF gagal dimuat
            modalIframe.onerror = () => {
                modalIframe.classList.add('hidden');
                unsupportedFile.classList.remove('hidden');
                modalDownloadBtn.classList.add('hidden');
            };
        } else if (fileType === 'txt') {
            // Untuk text files
            modalIframe.src = fileUrl;
            modalIframe.classList.remove('hidden');
        } else {
            // File tidak didukung untuk preview (Office files: doc, docx, xls, xlsx, ppt, pptx)
            unsupportedFile.classList.remove('hidden');
            modalDownloadBtn.classList.add('hidden');
        }
    }, 300);
}

function closeFileModal(event) {
    // Tutup hanya jika diklik di backdrop atau tombol tutup
    if (!event || event.target.id === 'fileModal' || event.currentTarget.tagName === 'BUTTON') {
        const modal = document.getElementById('fileModal');
        const modalIframe = document.getElementById('modalIframe');
        const modalImage = document.getElementById('modalImage');
        
        // Reset iframe dan image
        modalIframe.src = '';
        modalImage.src = '';
        
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFileModal();
    }
});
</script>

<style>
#fileModal {
    backdrop-filter: blur(4px);
}

#modalImage, #modalIframe {
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

/* Styling untuk iframe */
#modalIframe {
    min-height: 600px;
}

/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
</x-app-layout>