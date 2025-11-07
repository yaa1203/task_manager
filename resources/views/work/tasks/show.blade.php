@php
use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Tombol Kembali -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('my-workspaces.show', $workspace) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors mb-3">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke {{ $workspace->name }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 sm:gap-6">
        <!-- Konten Utama -->
        <div class="lg:col-span-3 space-y-4 sm:space-y-6">
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
                                    } elseif (in_array($fileExtension, ['txt', 'md', 'csv'])) {
                                        $fileIcon = 'text';
                                        $iconColor = 'gray';
                                    }
                                    
                                    $previewableExtensions = ['pdf', 'txt', 'md', 'csv', 'html', 'htm', 'doc', 'docx'];
                                    $isPreviewable = in_array($fileExtension, $previewableExtensions);
                                    
                                    $displayName = $task->original_filename ?? basename($task->file_path);
                                @endphp

                                @if($fileExists)
                                    @if($isImage)
                                        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                            <button type="button" 
                                                    onclick="openFileModal('{{ asset('storage/' . $task->file_path) }}', 'image', '{{ addslashes($displayName) }}')" 
                                                    class="block w-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg overflow-hidden">
                                                <img src="{{ asset('storage/' . $task->file_path) }}" 
                                                    alt="{{ $displayName }}"
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
                                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-all">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-{{ $iconColor }}-100 flex items-center justify-center">
                                                    @if($fileIcon === 'pdf')
                                                        <svg class="w-6 h-6 text-{{ $iconColor }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h10l4 4v16H2v-1z"/>
                                                            <text x="10" y="14" font-size="6" text-anchor="middle" fill="currentColor">PDF</text>
                                                        </svg>
                                                    @else
                                                        <svg class="w-6 h-6 text-{{ $iconColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $displayName }}</p>
                                                    <p class="text-xs text-gray-500 uppercase">{{ $fileExtension }} file</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex gap-2">
                                                @if($isPreviewable)
                                                    <button onclick="openFileModal('{{ route('my-workspaces.task.view-file', [$workspace, $task]) }}', '{{ $fileExtension }}', '{{ addslashes($displayName) }}')"
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
                                        <p class="text-sm font-medium text-red-800 mb-1">File tidak ditemukan</p>
                                        <p class="text-xs text-red-600">{{ $task->file_path }}</p>
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
                                            <p class="text-sm font-medium text-blue-900 group-hover:text-blue-700 truncate">Tautan Referensi</p>
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
                                  {{ \Carbon\Carbon::parse($task->due_date)->locale('id')->translatedFormat('d F Y H:i') }}
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

            <!-- Pengumpulan Saya -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Pengumpulan Saya</h2>

                @if($submissions->count() > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($submissions as $submission)
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:border-gray-300 transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-xs sm:text-sm text-gray-500">
                                        Dikirim {{ $submission->created_at->locale('id')->translatedFormat('d F Y H:i') }}
                                    </p>
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

                                {{-- Tampilkan File yang Disubmit --}}
                                @if($submission->file_path)
                                    @php
                                        $subFileExt = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION));
                                        $subImageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
                                        $subIsImage = in_array($subFileExt, $subImageExts);
                                        $subStoragePath = storage_path('app/public/' . $submission->file_path);
                                        $subFileExists = file_exists($subStoragePath);
                                        $subDisplayName = $submission->original_filename ?? basename($submission->file_path);
                                        
                                        // Tentukan apakah file bisa di-preview
                                        $subPreviewableExts = ['pdf', 'txt', 'md', 'csv', 'html', 'htm', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', '7z'];
                                        $subIsPreviewable = in_array($subFileExt, $subPreviewableExts);
                                        
                                        // Tentukan ikon dan warna
                                        $subFileIcon = 'document';
                                        $subIconColor = 'gray';
                                        if (in_array($subFileExt, ['pdf'])) {
                                            $subFileIcon = 'pdf';
                                            $subIconColor = 'red';
                                        } elseif (in_array($subFileExt, ['doc', 'docx'])) {
                                            $subFileIcon = 'word';
                                            $subIconColor = 'blue';
                                        } elseif (in_array($subFileExt, ['xls', 'xlsx'])) {
                                            $subFileIcon = 'excel';
                                            $subIconColor = 'green';
                                        } elseif (in_array($subFileExt, ['ppt', 'pptx'])) {
                                            $subFileIcon = 'powerpoint';
                                            $subIconColor = 'orange';
                                        } elseif (in_array($subFileExt, ['zip', 'rar', '7z'])) {
                                            $subFileIcon = 'archive';
                                            $subIconColor = 'yellow';
                                        }
                                    @endphp

                                    @if($subFileExists)
                                        <div class="mt-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            <p class="text-xs font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                File Terlampir:
                                            </p>
                                            
                                            @if($subIsImage)
                                                <div class="bg-white rounded-lg overflow-hidden border border-gray-200 mb-3">
                                                    <button type="button" 
                                                            onclick="openFileModal('{{ asset('storage/' . $submission->file_path) }}', 'image', '{{ addslashes($subDisplayName) }}')" 
                                                            class="block w-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg overflow-hidden">
                                                        <img src="{{ asset('storage/' . $submission->file_path) }}" 
                                                            alt="{{ $subDisplayName }}"
                                                            class="w-full h-auto max-h-[200px] object-contain hover:opacity-95 transition-opacity"
                                                            loading="lazy">
                                                    </button>
                                                </div>
                                                <p class="text-xs text-gray-600 text-center mb-2">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Klik gambar untuk melihat ukuran penuh
                                                </p>
                                                <!-- Tombol untuk gambar -->
                                                <div class="flex gap-2">
                                                    <button onclick="openFileModal('{{ asset('storage/' . $submission->file_path) }}', 'image', '{{ addslashes($subDisplayName) }}')"
                                                            class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs font-medium shadow-sm">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Lihat
                                                    </button>
                                                    <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                                    download="{{ $subDisplayName }}"
                                                    class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all text-xs font-medium shadow-sm">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                        Unduh
                                                    </a>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200 mb-3">
                                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-{{ $subIconColor }}-100 flex items-center justify-center">
                                                        @if($subFileIcon === 'pdf')
                                                            <svg class="w-5 h-5 text-{{ $subIconColor }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h10l4 4v16H2v-1z"/>
                                                                <text x="10" y="14" font-size="6" text-anchor="middle" fill="currentColor">PDF</text>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5 text-{{ $subIconColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $subDisplayName }}</p>
                                                        <p class="text-xs text-gray-500 uppercase">{{ $subFileExt }} file</p>
                                                    </div>
                                                </div>
                                                <!-- Tombol Lihat dan Unduh untuk file non-gambar -->
                                                <div class="flex gap-2">
                                                    @if($subIsPreviewable)
                                                        <button onclick="openFileModal('{{ asset('storage/' . $submission->file_path) }}', '{{ $subFileExt }}', '{{ addslashes($subDisplayName) }}')"
                                                                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs font-medium shadow-sm">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            Lihat
                                                        </button>
                                                    @endif
                                                    
                                                    <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                                    download="{{ $subDisplayName }}"
                                                    class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all text-xs font-medium shadow-sm">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                        Unduh
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endif

                                {{-- Tampilkan Link yang Disubmit --}}
                                @if($submission->link)
                                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-xs font-semibold text-blue-900 mb-2 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                            Link Terlampir:
                                        </p>
                                        <a href="{{ $submission->link }}" 
                                           target="_blank"
                                           class="text-sm text-blue-600 hover:text-blue-800 hover:underline break-all">
                                            {{ $submission->link }}
                                        </a>
                                    </div>
                                @endif

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
                        <p class="mt-2 text-xs sm:text-sm text-gray-500">Belum ada pengumpulan</p>
                        <p class="text-xs text-gray-400 mt-1">Kirim pekerjaan Anda menggunakan form di samping</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Submit Form (Desktop & Mobile) -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg sticky top-6">
                <!-- Header dengan Icon -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-blue-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Kirim Tugas</h3>
                            <p class="text-sm text-gray-600">Upload pekerjaan Anda</p>
                        </div>
                    </div>
                    @if($hasUserSubmitted)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-500 text-white shadow-md">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Terkirim
                        </span>
                    @endif
                </div>

                @if($hasUserSubmitted)
                    <div class="bg-white border-2 border-green-300 rounded-xl p-5 mb-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-bold text-green-900 mb-1">Pengumpulan Berhasil!</h4>
                                <p class="text-sm text-green-700">Tugas Anda telah diterima dan sedang ditinjau oleh admin.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Peringatan Form Kosong -->
                <div id="emptyFormWarning" class="hidden bg-amber-50 border-2 border-amber-300 rounded-xl p-4 mb-6 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-amber-900 mb-1">Form Tidak Boleh Kosong!</h4>
                            <p class="text-xs text-amber-700">Silakan isi minimal salah satu: upload file, tautan, atau catatan tambahan.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('my-workspaces.task.submit', [$workspace, $task]) }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                    class="space-y-6"
                    id="submitForm">
                    @csrf

                    <!-- Upload File Section -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Unggah File Tugas
                            </span>
                        </label>
                        
                        <!-- File Preview Area -->
                        <div id="filePreviewArea" class="hidden mb-3">
                            <div class="bg-white border-2 border-green-300 rounded-xl p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate" id="selectedFileName">File dipilih</p>
                                        <p class="text-xs text-gray-600" id="selectedFileSize"></p>
                                    </div>
                                    <button type="button" 
                                            onclick="removeSelectedFile()"
                                            class="flex-shrink-0 text-red-600 hover:text-red-800 transition-colors"
                                            {{ $hasUserSubmitted ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Image Preview -->
                                <div id="imagePreview" class="hidden mt-3">
                                    <img src="" alt="Preview" class="w-full h-auto max-h-[200px] object-contain rounded-lg border border-gray-200">
                                </div>
                            </div>
                        </div>

                        <div id="uploadArea" class="mt-2 flex justify-center px-6 pt-8 pb-8 border-3 border-dashed rounded-xl transition-all {{ $hasUserSubmitted ? 'bg-gray-100 border-gray-300' : 'bg-white border-blue-300 hover:border-blue-500 hover:bg-blue-50' }}">
                            <div class="space-y-3 text-center">
                                @if(!$hasUserSubmitted)
                                    <svg class="mx-auto h-16 w-16 text-blue-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @else
                                    <svg class="mx-auto h-16 w-16 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <div class="flex flex-col items-center">
                                    <label for="file" class="relative cursor-pointer bg-transparent rounded-lg font-bold text-base {{ $hasUserSubmitted ? 'text-gray-500 pointer-events-none' : 'text-blue-600 hover:text-blue-700' }}">
                                        <span class="block mb-1">{{ $hasUserSubmitted ? '✓ File Sudah Diunggah' : 'Klik untuk pilih file' }}</span>
                                        <input id="file" name="file" type="file" class="sr-only" {{ $hasUserSubmitted ? 'disabled' : '' }} onchange="handleFileSelect(this)">
                                    </label>
                                    <p class="text-sm text-gray-600 mt-1">atau seret dan letakkan di sini</p>
                                </div>
                                <p class="text-xs text-gray-500 px-4">
                                    <span class="font-semibold">Maksimal 10MB.</span><br>
                                    Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, 7Z, JPG, PNG, GIF
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Link Section -->
                    <div>
                        <label for="link" class="block text-sm font-bold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                Tautan (Opsional)
                            </span>
                        </label>
                        <div class="relative">
                            <input type="url" 
                                id="link"
                                name="link" 
                                placeholder="https://drive.google.com/..."
                                {{ $hasUserSubmitted ? 'disabled' : '' }}
                                oninput="validateForm()"
                                class="block w-full pl-4 pr-12 py-3.5 text-base border-2 rounded-xl transition-all focus:ring-4 focus:ring-blue-100 {{ $hasUserSubmitted ? 'bg-gray-100 border-gray-300 text-gray-500' : 'bg-white border-gray-300 focus:border-blue-500' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-600">Tambahkan link Google Drive, OneDrive, atau platform lainnya</p>
                    </div>

                    <!-- Notes Section -->
                    <div>
                        <label for="notes" class="block text-sm font-bold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Catatan Tambahan
                            </span>
                        </label>
                        <textarea id="notes" 
                                name="notes" 
                                rows="5" 
                                placeholder="Tambahkan catatan, komentar, atau penjelasan tentang tugas Anda..."
                                {{ $hasUserSubmitted ? 'disabled' : '' }}
                                oninput="validateForm()"
                                class="block w-full px-4 py-3.5 text-base border-2 rounded-xl transition-all focus:ring-4 focus:ring-blue-100 resize-none {{ $hasUserSubmitted ? 'bg-gray-100 border-gray-300 text-gray-500' : 'bg-white border-gray-300 focus:border-blue-500' }}"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                id="submitButton"
                                {{ $hasUserSubmitted ? 'disabled' : '' }}
                                class="w-full flex justify-center items-center gap-3 px-6 py-4 border-2 border-transparent rounded-xl shadow-lg text-lg font-bold text-white transition-all transform focus:outline-none focus:ring-4 focus:ring-blue-300 {{ $hasUserSubmitted ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-400 cursor-not-allowed' }}"
                                disabled>
                            @if($hasUserSubmitted)
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Tugas Sudah Dikirim
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <span id="submitButtonText">Isi Minimal 1 Form</span>
                            @endif
                        </button>
                        @if(!$hasUserSubmitted)
                            <p class="text-center text-xs text-gray-600 mt-3">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Isi minimal salah satu form sebelum mengirim
                            </p>
                        @endif
                    </div>
                </form>

                <!-- Info Box -->
                @if(!$hasUserSubmitted)
                    <div class="mt-6 bg-white border-2 border-blue-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-gray-900 mb-1">Tips Pengumpulan</h5>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• <strong>Wajib mengisi minimal 1 form</strong></li>
                                    <li>• Upload file dengan format yang sesuai</li>
                                    <li>• Pastikan file tidak melebihi 10MB</li>
                                    <li>• Periksa kembali sebelum mengirim</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Universal File Viewer -->
<div id="fileModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75 p-4" onclick="closeFileModal(event)">
    <div class="relative w-full max-w-7xl max-h-full flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modalFileName" class="text-white font-semibold text-lg truncate mr-4"></h3>
            <button onclick="closeFileModal()" 
                    class="text-white hover:text-gray-300 transition-colors focus:outline-none flex-shrink-0">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="bg-white rounded-lg overflow-hidden shadow-2xl flex-1 flex items-center justify-center" style="max-height: 85vh;">
            <div id="fileLoading" class="text-center py-12">
                <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600">Memuat file...</p>
            </div>
            
            <img id="modalImage" src="" alt="Gambar ukuran penuh" class="hidden max-w-full max-h-full w-auto h-auto object-contain">
            
            <iframe id="modalIframe" class="hidden w-full h-full" frameborder="0"></iframe>
            
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
// Handle file selection and preview
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;
    
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
        alert('Ukuran file terlalu besar! Maksimal 10MB.');
        input.value = '';
        return;
    }
    
    // Show file preview area
    const uploadArea = document.getElementById('uploadArea');
    const filePreviewArea = document.getElementById('filePreviewArea');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileSize = document.getElementById('selectedFileSize');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewImg = imagePreview.querySelector('img');
    
    uploadArea.classList.add('hidden');
    filePreviewArea.classList.remove('hidden');
    
    // Set file name and size
    selectedFileName.textContent = file.name;
    selectedFileSize.textContent = formatFileSize(file.size);
    
    // Show image preview if it's an image
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    
    if (imageExtensions.includes(fileExtension)) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreviewImg.src = e.target.result;
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.classList.add('hidden');
    }
}

// Remove selected file
function removeSelectedFile() {
    const fileInput = document.getElementById('file');
    const uploadArea = document.getElementById('uploadArea');
    const filePreviewArea = document.getElementById('filePreviewArea');
    const imagePreview = document.getElementById('imagePreview');
    
    fileInput.value = '';
    uploadArea.classList.remove('hidden');
    filePreviewArea.classList.add('hidden');
    imagePreview.classList.add('hidden');
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Drag and drop functionality
(function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('file');
    
    if (!uploadArea || !fileInput || fileInput.disabled) return;
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.add('border-blue-500', 'bg-blue-100');
        }, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.remove('border-blue-500', 'bg-blue-100');
        }, false);
    });
    
    uploadArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(fileInput);
        }
    }, false);
})();

// File modal functions
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
    
    modalImage.classList.add('hidden');
    modalIframe.classList.add('hidden');
    unsupportedFile.classList.add('hidden');
    fileLoading.classList.remove('hidden');
    modalDownloadBtn.classList.remove('hidden');
    
    modalFileName.textContent = fileName;
    
    let downloadUrl = fileUrl;
    if (fileUrl.includes('/storage/')) {
        downloadUrl = fileUrl;
    } else if (fileUrl.includes('/view-file/')) {
        downloadUrl = fileUrl.replace('/view-file/', '/download/');
    }
    
    modalDownload.href = downloadUrl;
    downloadLink.href = downloadUrl;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        fileLoading.classList.add('hidden');
        
        if (fileType === 'image') {
            modalImage.src = fileUrl;
            modalImage.classList.remove('hidden');
            modalImage.onload = () => {
                fileLoading.classList.add('hidden');
            };
            modalImage.onerror = () => {
                modalImage.classList.add('hidden');
                unsupportedFile.classList.remove('hidden');
                modalDownloadBtn.classList.add('hidden');
            };
        } else if (fileType === 'pdf') {
            modalIframe.src = fileUrl + '#toolbar=0&navpanes=0&scrollbar=0&view=FitH';
            modalIframe.classList.remove('hidden');
        } else if (fileType === 'txt' || fileType === 'md' || fileType === 'csv' || fileType === 'html' || fileType === 'htm') {
            fetch(fileUrl)
                .then(response => response.text())
                .then(text => {
                    const blob = new Blob([`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="UTF-8">
                            <style>
                                body {
                                    font-family: 'Courier New', monospace;
                                    padding: 20px;
                                    margin: 0;
                                    background: white;
                                    color: #1f2937;
                                    line-height: 1.6;
                                }
                                pre {
                                    white-space: pre-wrap;
                                    word-wrap: break-word;
                                    margin: 0;
                                }
                            </style>
                        </head>
                        <body>
                            <pre>${text.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</pre>
                        </body>
                        </html>
                    `], { type: 'text/html' });
                    modalIframe.src = URL.createObjectURL(blob);
                    modalIframe.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading text file:', error);
                    unsupportedFile.classList.remove('hidden');
                    modalDownloadBtn.classList.add('hidden');
                });
        } else {
            unsupportedFile.classList.remove('hidden');
            modalDownloadBtn.classList.add('hidden');
        }
    }, 300);
}

function closeFileModal(event) {
    if (!event || event.target.id === 'fileModal' || event.currentTarget.tagName === 'BUTTON') {
        const modal = document.getElementById('fileModal');
        const modalIframe = document.getElementById('modalIframe');
        const modalImage = document.getElementById('modalImage');
        
        modalIframe.src = '';
        modalImage.src = '';
        
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFileModal();
    }
});

function validateForm() {
    @if($hasUserSubmitted)
        return; // Skip validation if already submitted
    @endif

    const fileInput = document.getElementById('file');
    const linkInput = document.getElementById('link');
    const notesInput = document.getElementById('notes');
    const submitButton = document.getElementById('submitButton');
    const submitButtonText = document.getElementById('submitButtonText');
    const emptyFormWarning = document.getElementById('emptyFormWarning');
    
    const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
    const hasLink = linkInput && linkInput.value.trim() !== '';
    const hasNotes = notesInput && notesInput.value.trim() !== '';
    
    const isFormValid = hasFile || hasLink || hasNotes;
    
    if (isFormValid) {
        submitButton.disabled = false;
        submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
        submitButton.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600', 'hover:from-blue-700', 'hover:to-indigo-700', 'hover:scale-[1.02]', 'active:scale-[0.98]');
        submitButtonText.textContent = 'Kirim Tugas Sekarang';
        emptyFormWarning.classList.add('hidden');
    } else {
        submitButton.disabled = true;
        submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
        submitButton.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600', 'hover:from-blue-700', 'hover:to-indigo-700', 'hover:scale-[1.02]', 'active:scale-[0.98]');
        submitButtonText.textContent = 'Isi Minimal 1 Form';
        emptyFormWarning.classList.remove('hidden');
    }
}

// Handle file selection and preview
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) {
        validateForm();
        return;
    }
    
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
        alert('Ukuran file terlalu besar! Maksimal 10MB.');
        input.value = '';
        validateForm();
        return;
    }
    
    // Show file preview area
    const uploadArea = document.getElementById('uploadArea');
    const filePreviewArea = document.getElementById('filePreviewArea');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileSize = document.getElementById('selectedFileSize');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewImg = imagePreview.querySelector('img');
    
    uploadArea.classList.add('hidden');
    filePreviewArea.classList.remove('hidden');
    
    // Set file name and size
    selectedFileName.textContent = file.name;
    selectedFileSize.textContent = formatFileSize(file.size);
    
    // Show image preview if it's an image
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    
    if (imageExtensions.includes(fileExtension)) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreviewImg.src = e.target.result;
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.classList.add('hidden');
    }
    
    // Validate form after file selection
    validateForm();
}

// Remove selected file
function removeSelectedFile() {
    const fileInput = document.getElementById('file');
    const uploadArea = document.getElementById('uploadArea');
    const filePreviewArea = document.getElementById('filePreviewArea');
    const imagePreview = document.getElementById('imagePreview');
    
    fileInput.value = '';
    uploadArea.classList.remove('hidden');
    filePreviewArea.classList.add('hidden');
    imagePreview.classList.add('hidden');
    
    // Validate form after removing file
    validateForm();
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Form submission validation
document.getElementById('submitForm').addEventListener('submit', function(e) {
    @if(!$hasUserSubmitted)
        const fileInput = document.getElementById('file');
        const linkInput = document.getElementById('link');
        const notesInput = document.getElementById('notes');
        
        const hasFile = fileInput.files.length > 0;
        const hasLink = linkInput.value.trim() !== '';
        const hasNotes = notesInput.value.trim() !== '';
        
        if (!hasFile && !hasLink && !hasNotes) {
            e.preventDefault();
            alert('Mohon isi minimal salah satu form: file, tautan, atau catatan!');
            document.getElementById('emptyFormWarning').classList.remove('hidden');
            return false;
        }
    @endif
});

// Drag and drop functionality
(function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('file');
    
    if (!uploadArea || !fileInput || fileInput.disabled) return;
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.add('border-blue-500', 'bg-blue-100');
        }, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.remove('border-blue-500', 'bg-blue-100');
        }, false);
    });
    
    uploadArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(fileInput);
        }
    }, false);
})();

// Initialize validation on page load
document.addEventListener('DOMContentLoaded', function() {
    validateForm();
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

#modalIframe {
    min-height: 600px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Custom scrollbar untuk textarea */
textarea::-webkit-scrollbar {
    width: 8px;
}

textarea::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

textarea::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

textarea::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* File preview animations */
#filePreviewArea {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Drag and drop hover effect */
#uploadArea.border-blue-500 {
    transform: scale(1.02);
    transition: all 0.2s ease;
}

/* Smooth transitions for button state changes */
#submitButton {
    transition: all 0.3s ease;
}

#submitButton:not(:disabled):hover {
    transform: scale(1.02);
}

#submitButton:not(:disabled):active {
    transform: scale(0.98);
}

#emptyFormWarning {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
</x-app-layout>