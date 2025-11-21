@extends('admin.layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
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
                Detail Tugas
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Deskripsi</label>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $task->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>

                    <!-- Task Attachments -->
                    @if($task->file_path || $task->link)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 block">Materi Tugas</label>
                            <div class="space-y-3">
                                @if($task->file_path)
                                    @php
                                        // Tentukan ekstensi file
                                        $fileExtension = strtolower(pathinfo($task->file_path, PATHINFO_EXTENSION));
                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
                                        $isImage = in_array($fileExtension, $imageExtensions);
                                        $pdfExtensions = ['pdf'];
                                        $isPdf = in_array($fileExtension, $pdfExtensions);
                                        $textExtensions = ['txt', 'md', 'csv'];
                                        $isText = in_array($fileExtension, $textExtensions);
                                        $storagePath = storage_path('app/public/' . $task->file_path);
                                        $fileExists = file_exists($storagePath);
                                        
                                        // Tentukan ikon file berdasarkan ekstensi
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
                                        
                                        // File yang bisa dilihat (preview)
                                        $previewableExtensions = ['pdf', 'txt', 'md', 'csv', 'html', 'htm'];
                                        $isPreviewable = in_array($fileExtension, $previewableExtensions);
                                        
                                        // Gunakan nama asli jika ada, jika tidak gunakan nama dari path
                                        $displayName = $task->original_filename;
                                        if (!$displayName && $task->file_path) {
                                            $displayName = basename($task->file_path);
                                        }
                                    @endphp

                                    @if($fileExists)
                                        @if($isImage)
                                            <!-- Preview Gambar -->
                                            <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl border border-indigo-200 p-4 shadow-sm">
                                                <div class="flex items-center gap-3 mb-3">
                                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $displayName }}</p>
                                                        <p class="text-xs text-gray-500">Gambar</p>
                                                    </div>
                                                </div>
                                                <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                                    <button type="button" 
                                                            onclick="openFileModal('{{ asset('storage/' . $task->file_path) }}', 'image', '{{ $displayName }}')" 
                                                            class="block w-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg overflow-hidden">
                                                        <img src="{{ asset('storage/' . $task->file_path) }}" 
                                                            alt="{{ $displayName }}"
                                                            class="w-full h-auto max-h-[300px] object-contain hover:opacity-95 transition-opacity"
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
                                            </div>
                                        @else
                                            <!-- Kartu File -->
                                            <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl border border-indigo-200 p-4 shadow-sm">
                                                <div class="flex items-center gap-3 mb-3">
                                                    <div class="w-10 h-10 rounded-lg bg-{{ $iconColor }}-100 flex items-center justify-center flex-shrink-0">
                                                        @if($fileIcon === 'pdf')
                                                            <svg class="w-5 h-5 text-{{ $iconColor }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h10l4 4v16H2v-1z"/>
                                                                <text x="10" y="14" font-size="6" text-anchor="middle" fill="currentColor">PDF</text>
                                                            </svg>
                                                        @elseif($fileIcon === 'word')
                                                            <svg class="w-5 h-5 text-{{ $iconColor }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M4 2h12l4 4v12H4V2zm1 1v14h10V7h-4V3H5z"/>
                                                                <text x="10" y="14" font-size="5" text-anchor="middle" fill="currentColor">DOC</text>
                                                            </svg>
                                                        @elseif($fileIcon === 'excel')
                                                            <svg class="w-5 h-5 text-{{ $iconColor }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M4 2h12l4 4v12H4V2zm1 1v14h10V7h-4V3H5z"/>
                                                                <text x="10" y="14" font-size="5" text-anchor="middle" fill="currentColor">XLS</text>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5 text-{{ $iconColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $displayName }}</p>
                                                        <p class="text-xs text-gray-500">{{ strtoupper($fileExtension) }} File</p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Tombol Lihat dan Unduh -->
                                                <div class="flex gap-2">
                                                    <!-- Untuk task file -->
                                                    @if($isPreviewable)
                                                        <button onclick="openFileModal('{{ route('workspace.tasks.view-file', [$workspace, $task]) }}', '{{ $fileExtension }}', '{{ $displayName }}')"
                                                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all text-sm font-medium shadow-sm hover:shadow">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            Lihat
                                                        </button>
                                                    @endif

                                                    <a href="{{ route('workspace.tasks.download', [$workspace, $task]) }}" 
                                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all text-sm font-medium shadow-sm hover:shadow">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                        Unduh
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-gradient-to-br from-red-50 to-white rounded-xl border border-red-200 p-4 shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-red-800">File tidak ditemukan</p>
                                                    <p class="text-xs text-red-600">{{ $task->file_path }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                            <p class="text-xs font-medium text-blue-600 mb-0.5">Tautan Eksternal</p>
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
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Prioritas</label>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold {{ 
                                $task->priority === 'urgent' ? 'bg-red-100 text-red-800 border border-red-200' : 
                                ($task->priority === 'high' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 
                                ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 
                                'bg-blue-100 text-blue-800 border border-blue-200'))
                            }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Prioritas {{ $task->priority === 'urgent' ? 'Mendesak' : ($task->priority === 'high' ? 'Tinggi' : ($task->priority === 'medium' ? 'Sedang' : 'Rendah')) }}
                            </span>
                        </div>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-lg p-4 border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Status Keseluruhan</label>
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
                                    Semua Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Sedang Berlangsung
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-lg p-4 border border-gray-200">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 block">Garis Waktu</label>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Dibuat</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($task->created_at)->locale('id')->translatedFormat('d F Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Batas Waktu</p>
                                    @if($task->due_date)
                                        <p class="text-sm font-medium text-gray-900">
                                          {{ \Carbon\Carbon::parse($task->due_date)->locale('id')->translatedFormat('d F Y H:i') }}

                                        </p>
                                    @else
                                        <p class="text-sm text-gray-400">Tidak ada batas waktu</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-lg p-4 border border-indigo-200">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 block">Pelacakan Progres</label>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700 font-medium">{{ $submittedCount }} dari {{ $totalUsers }} selesai</span>
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
                    Pengguna
                </h2>
                <span class="px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-700 rounded-full">
                    {{ $task->assignedUsers->count() }} {{ $task->assignedUsers->count() === 1 ? 'pengguna' : 'pengguna' }}
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
                                Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200 flex-shrink-0 ml-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                                </svg>
                                Belum Selesai
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
                    Pengumpulan
                </h2>
                <span class="px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-700 rounded-full">
                    {{ $task->submissions->count() }} {{ $task->submissions->count() === 1 ? 'pengumpulan' : 'pengumpulan' }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            @if($task->submissions->count() > 0)
                <div class="space-y-4">
                    @foreach($task->submissions as $submission)
                        @php
                            // Gunakan metode dari model
                            $fileExtension = $submission->file_extension;
                            $isImage = $submission->isImage();
                            $isPreviewable = $submission->isPreviewable();
                            $fileIcon = $submission->file_icon;
                            $iconColor = $submission->file_icon_color;
                            $fileExists = $submission->file_path && file_exists(storage_path('app/public/' . $submission->file_path));
                            $displayName = $submission->display_name;
                        @endphp
                        
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
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-semibold border border-green-200 mb-2">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Dikumpulkan
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                      {{ \Carbon\Carbon::parse($submission->created_at)->locale('id')->translatedFormat('d F Y H:i') }}
                                    </p>
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
                                            Catatan
                                        </h4>
                                        <p class="text-gray-700 leading-relaxed bg-white rounded-lg p-4 border border-gray-200 shadow-sm whitespace-pre-line">{{ $submission->notes }}</p>
                                    </div>
                                @endif
                                
                                @if($submission->hasFile())
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            File Terlampir
                                        </h4>
                                        @if($fileExists)
                                            @if($isImage)
                                                <!-- Preview Gambar -->
                                                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                                                    <div class="flex items-center gap-3 mb-3">
                                                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="font-medium text-gray-900 text-sm truncate">{{ $displayName }}</p>
                                                            <p class="text-xs text-gray-500 mt-0.5">Gambar</p>
                                                        </div>
                                                    </div>
                                                    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                                        <button type="button" 
                                                                onclick="openFileModal('{{ $submission->file_url }}', 'image', '{{ $displayName }}')" 
                                                                class="block w-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg overflow-hidden">
                                                            <img src="{{ $submission->file_url }}" 
                                                                alt="{{ $displayName }}"
                                                                class="w-full h-auto max-h-[300px] object-contain hover:opacity-95 transition-opacity"
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
                                                </div>
                                            @else
                                                <!-- Kartu File -->
                                                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                                                    <div class="flex items-center gap-3 mb-3">
                                                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                                                            @if($fileIcon === 'pdf')
                                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h10l4 4v16H2v-1z"/>
                                                                    <text x="10" y="14" font-size="6" text-anchor="middle" fill="currentColor">PDF</text>
                                                                </svg>
                                                            @elseif($fileIcon === 'word')
                                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M4 2h12l4 4v12H4V2zm1 1v14h10V7h-4V3H5z"/>
                                                                    <text x="10" y="14" font-size="5" text-anchor="middle" fill="currentColor">DOC</text>
                                                                </svg>
                                                            @elseif($fileIcon === 'excel')
                                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M4 2h12l4 4v12H4V2zm1 1v14h10V7h-4V3H5z"/>
                                                                    <text x="10" y="14" font-size="5" text-anchor="middle" fill="currentColor">XLS</text>
                                                                </svg>
                                                            @else
                                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="font-medium text-gray-900 text-sm truncate">{{ $displayName }}</p>
                                                            <p class="text-xs text-gray-500 mt-0.5">{{ strtoupper($fileExtension) }} File</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Tombol Lihat dan Unduh -->
                                                    <div class="flex gap-2">
                                                        <!-- Untuk submission file -->
                                                        @if($isPreviewable)
                                                            <button onclick="openFileModal('{{ route('workspace.submissions.view', [$workspace, $task, $submission]) }}', '{{ $fileExtension }}', '{{ $displayName }}')"
                                                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all text-sm font-medium shadow-sm hover:shadow">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                                Lihat
                                                            </button>
                                                        @endif

                                                        <a href="{{ route('workspace.submissions.download', [$workspace, $task, $submission]) }}" 
                                                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all text-sm font-medium shadow-sm hover:shadow">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                            </svg>
                                                            Unduh
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="bg-gradient-to-br from-red-50 to-white rounded-xl border border-red-200 p-4 shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-red-800">File tidak ditemukan</p>
                                                        <p class="text-xs text-red-600">{{ $submission->file_path }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($submission->link)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                            Tautan Pengumpulan
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
                                                <p class="text-xs font-medium text-blue-600 mb-0.5">Tautan Eksternal</p>
                                                <p class="text-sm text-gray-700 truncate font-medium">{{ $submission->link }}</p>
                                            </div>
                                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                
                                @if($submission->admin_notes)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            Feedback Admin
                                        </h4>
                                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                            <p class="text-sm text-amber-800 leading-relaxed whitespace-pre-line">{{ $submission->admin_notes }}</p>
                                        </div>
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada pengumpulan</h3>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto">Pengumpulan akan muncul di sini setelah pengguna mengumpulkan tugas mereka</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Universal File Viewer - DIPERBAIKI -->
<div id="fileModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 p-4 overflow-auto" onclick="closeFileModal(event)">
    <div class="relative mx-auto" style="width: fit-content; max-width: 95vw;">
        <!-- Header Modal -->
        <div class="sticky top-0 bg-black bg-opacity-90 flex items-center justify-between p-4 z-10 rounded-t-lg">
            <h3 id="modalFileName" class="text-white font-semibold text-lg truncate mr-4 flex-1"></h3>
            <button onclick="closeFileModal()" 
                    class="text-white hover:text-gray-300 transition-colors focus:outline-none flex-shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Content Modal -->
        <div class="bg-white flex items-center justify-center rounded-b-lg" style="max-height: 85vh; display: flex; align-items: center; overflow: hidden;">
            <!-- Loading State -->
            <div id="fileLoading" class="text-center py-12 px-6">
                <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600">Memuat file...</p>
            </div>
            
            <!-- Image Container - UKURAN ASLI -->
            <div id="imageContainer" class="hidden w-full h-full overflow-auto flex items-center justify-center">
                <img id="modalImage" src="" alt="Gambar ukuran penuh" class="h-auto w-auto max-w-full">
            </div>
            
            <!-- PDF/Document Viewer - FULL VIEW -->
            <iframe id="modalIframe" class="hidden w-full" style="height: 85vh; border: none;"></iframe>
            
            <!-- Unsupported File State -->
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
        
        <!-- Footer Modal -->
        <div class="bg-black bg-opacity-90 text-center p-4 rounded-b-lg" id="modalDownloadBtn">
            <a href="#" id="modalDownload"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
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

// File modal functions - DIPERBAIKI UNTUK NGROK
function openFileModal(fileUrl, fileType, fileName) {
    const modal = document.getElementById('fileModal');
    const modalImage = document.getElementById('modalImage');
    const imageContainer = document.getElementById('imageContainer');
    const modalIframe = document.getElementById('modalIframe');
    const fileLoading = document.getElementById('fileLoading');
    const unsupportedFile = document.getElementById('unsupportedFile');
    const modalFileName = document.getElementById('modalFileName');
    const modalDownload = document.getElementById('modalDownload');
    const downloadLink = document.getElementById('downloadLink');
    const modalDownloadBtn = document.getElementById('modalDownloadBtn');
    
    // Reset all states
    modalImage.classList.add('hidden');
    imageContainer.classList.add('hidden');
    modalIframe.classList.add('hidden');
    unsupportedFile.classList.add('hidden');
    fileLoading.classList.remove('hidden');
    modalDownloadBtn.classList.remove('hidden');
    
    modalFileName.textContent = fileName;
    
    // Set download URL dengan mempertimbangkan ngrok
    let downloadUrl = fileUrl;
    
    // Jika menggunakan ngrok, pastikan URL menggunakan domain yang sama
    if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
        // Untuk file storage, gunakan URL asli
        if (fileUrl.includes('/storage/')) {
            // Jika URL storage mengandung localhost, ganti dengan domain ngrok
            if (fileUrl.includes('localhost') || fileUrl.includes('127.0.0.1')) {
                const urlParts = fileUrl.split('/');
                urlParts[2] = window.location.hostname;
                downloadUrl = urlParts.join('/');
            }
        } else if (fileUrl.includes('/view-file/')) {
            // Untuk route view, konversi ke download
            downloadUrl = fileUrl.replace('/view-file/', '/download/');
            
            // Jika URL mengandung localhost, ganti dengan domain ngrok
            if (downloadUrl.includes('localhost') || downloadUrl.includes('127.0.0.1')) {
                const urlParts = downloadUrl.split('/');
                urlParts[2] = window.location.hostname;
                downloadUrl = urlParts.join('/');
            }
        }
    } else {
        // Untuk localhost, gunakan URL asli
        if (fileUrl.includes('/view-file/')) {
            downloadUrl = fileUrl.replace('/view-file/', '/download/');
        }
    }
    
    modalDownload.href = downloadUrl;
    downloadLink.href = downloadUrl;
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        fileLoading.classList.add('hidden');
        
        if (fileType === 'image') {
            // Load image dengan ukuran asli
            const img = new Image();
            img.onload = function() {
                modalImage.src = fileUrl;
                imageContainer.classList.remove('hidden');
                fileLoading.classList.add('hidden');
            };
            img.onerror = function() {
                unsupportedFile.classList.remove('hidden');
                modalDownloadBtn.classList.add('hidden');
                fileLoading.classList.add('hidden');
            };
            img.src = fileUrl;
        } 
        else if (fileType === 'pdf') {
            // PDF dengan full toolbar dan scrollbar
            modalIframe.src = fileUrl + '#toolbar=1&navpanes=0&scrollbar=1&view=FitBH&page=1';
            modalIframe.classList.remove('hidden');
            fileLoading.classList.add('hidden');
        } 
        else if (fileType === 'txt' || fileType === 'md' || fileType === 'csv' || fileType === 'html' || fileType === 'htm') {
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
                    fileLoading.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error loading text file:', error);
                    unsupportedFile.classList.remove('hidden');
                    modalDownloadBtn.classList.add('hidden');
                    fileLoading.classList.add('hidden');
                });
        } 
        else {
            unsupportedFile.classList.remove('hidden');
            modalDownloadBtn.classList.add('hidden');
            fileLoading.classList.add('hidden');
        }
    }, 300);
}

function closeFileModal(event) {
    // Pastikan hanya close saat klik di luar modal atau tombol close
    if (event && event.target.id !== 'fileModal' && event.currentTarget.tagName !== 'BUTTON') {
        return;
    }
    
    const modal = document.getElementById('fileModal');
    const modalIframe = document.getElementById('modalIframe');
    const modalImage = document.getElementById('modalImage');
    const imageContainer = document.getElementById('imageContainer');
    
    modalIframe.src = '';
    modalImage.src = '';
    imageContainer.classList.add('hidden');
    
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close modal dengan ESC key
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

#modalImage {
    animation: fadeIn 0.2s ease-in-out;
    display: block;
    margin: 0 auto;
}

#modalIframe {
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

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Scrollbar styling untuk image container */
#imageContainer::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

#imageContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#imageContainer::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 5px;
}

#imageContainer::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

#imageContainer {
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
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
</style>
@endsection