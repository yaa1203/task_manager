@extends('admin.layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Bagian Header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col gap-3 sm:gap-4">
            <!-- Baris 1: Tombol Kembali dan Info Workspace -->
            <div class="flex items-start gap-2 sm:gap-4">
                <a href="{{ route('workspaces.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gray-100 hover:bg-gray-200 active:bg-gray-300 transition flex-shrink-0 touch-manipulation">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex items-start gap-2 sm:gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center bg-white border-2 text-gray-700 flex-shrink-0" 
                         style="border-color: {{ $workspace->color }};">
                        @php
                        $iconSvgs = [
                            'folder' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                            'briefcase' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                            'chart' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                            'target' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                            'cog' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                            'clipboard' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                        ];
                        @endphp
                        {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 truncate leading-tight">{{ $workspace->name }}</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Workspace Tugas</p>
                    </div>
                </div>
            </div>

            <!-- Baris 2: Status dan Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <!-- Status Workspace -->
                <div class="flex items-center">
                    @if($workspace->is_archived)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            Terarsip
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium bg-green-100 text-green-700 border border-green-200">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            Aktif
                        </span>
                    @endif
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex gap-2 sm:ml-auto">
                    <a href="{{ route('workspaces.edit', $workspace) }}" 
                       class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition text-sm font-medium touch-manipulation min-h-[44px]">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    
                    <!-- Tombol Arsip/Pulihkan berdasarkan status -->
                    <form action="{{ route('workspaces.toggle-archive', $workspace) }}" method="POST" class="flex-1 sm:flex-initial">
                        @csrf
                        @if($workspace->is_archived)
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition text-sm font-medium touch-manipulation min-h-[44px]">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Pulihkan
                            </button>
                        @else
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition text-sm font-medium touch-manipulation min-h-[44px]">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                Arsip
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesan Sukses -->
    @if(session('success'))
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
        <div class="flex items-center gap-2 sm:gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Bagian Tugas -->
    <div>
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Tugas ({{ $workspace->tasks->count() }})</span>
            </h2>
            
            <!-- Tampilkan tombol Buat Tugas hanya jika workspace tidak diarsipkan -->
            @if(!$workspace->is_archived)
            <a href="{{ route('workspace.tasks.create', $workspace) }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition text-sm font-medium touch-manipulation min-h-[44px] w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Tugas
            </a>
            @endif
        </div>

        @if($workspace->tasks->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
            @foreach($workspace->tasks as $task)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <!-- Header Tugas -->
                <div class="p-4 sm:p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-base sm:text-lg text-gray-900 mb-1.5 line-clamp-2">{{ $task->title }}</h3>
                            @if($task->description)
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $task->description }}</p>
                            @endif
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap flex-shrink-0
                            @php
                                $priorityConfig = [
                                    'urgent' => 'bg-red-100 text-red-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'low' => 'bg-blue-100 text-blue-800'
                                ];
                                $pConfig = $priorityConfig[$task->priority] ?? $priorityConfig['low'];
                            @endphp
                            {{ $pConfig }}">
                            @switch($task->priority)
                                @case('low') Rendah @break
                                @case('medium') Sedang @break
                                @case('high') Tinggi @break
                                @case('urgent') Segera @break
                                @default {{ ucfirst($task->priority) }}
                            @endswitch
                        </span>
                    </div>

                    <!-- Bagian Kemajuan -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-700">Kemajuan</span>
                            <span class="text-sm font-bold text-gray-900">
                                {{ $task->getProgressPercentage() }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $task->getProgressPercentage() }}%"></div>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-500 pt-1">
                            <span>{{ $task->submissions->count() }}/{{ $task->assignedUsers->count() }} selesai</span>
                            @if($task->due_date)
                                <span class="truncate ml-2">
                                    {{ \Carbon\Carbon::parse($task->due_date)->locale('id')->translatedFormat('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Detail Tugas -->
                <div class="p-4 sm:p-5">
                    <!-- Pengguna yang Diberi Tugas dengan Status dan Scroll -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2.5">
                            Pengguna ({{ $task->assignedUsers->count() }})
                        </h4>
                        <div class="space-y-2 max-h-[180px] sm:max-h-[200px] overflow-y-auto custom-scrollbar pr-1">
                            @foreach($task->assignedUsers as $user)
                                @php
                                    $userSubmission = $task->submissions->where('user_id', $user->id)->first();
                                    $hasSubmitted = $userSubmission !== null;
                                    $isLate = false;
                                    
                                    // Check if user is late (deadline passed and no submission)
                                    if ($task->due_date && !$hasSubmitted) {
                                        $isLate = \Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($task->due_date));
                                    }
                                @endphp
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2.5 min-h-[44px]">
                                    <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                        <div class="relative flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-xs font-bold text-indigo-600">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            @if($hasSubmitted)
                                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                                <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            @elseif($isLate)
                                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
                                                <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            @endif
                                        </div>
                                        <span class="text-sm text-gray-700 truncate font-medium">{{ $user->name }}</span>
                                    </div>
                                    @if($hasSubmitted)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Selesai
                                        </span>
                                    @elseif($isLate)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Belum
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Keseluruhan -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2.5">Status</h4>
                        <div class="flex items-center">
                            @php
                                $totalUsers = $task->assignedUsers->count();
                                $submittedCount = $task->submissions->count();
                                $isAllDone = $totalUsers > 0 && $submittedCount === $totalUsers;
                            @endphp
                            @if($isAllDone)
                                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold bg-green-100 text-green-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold bg-gray-100 text-gray-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Dalam Progress
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Aksi - Tampilkan hanya jika workspace tidak diarsipkan -->
                    @if(!$workspace->is_archived)
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('workspace.tasks.show', [$workspace, $task]) }}" 
                           class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 active:bg-indigo-200 transition text-sm font-medium touch-manipulation min-h-[44px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span class="hidden sm:inline">Lihat</span>
                        </a>
                        <a href="{{ route('workspace.tasks.edit', [$workspace, $task]) }}" 
                           class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 active:bg-blue-700 transition text-sm font-medium touch-manipulation min-h-[44px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                        <form action="{{ route('workspace.tasks.destroy', [$workspace, $task]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 active:bg-red-700 transition text-sm font-medium touch-manipulation min-h-[44px]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span class="hidden sm:inline">Hapus</span>
                            </button>
                        </form>
                    </div>
                    @else
                    <!-- Tampilkan pesan bahwa workspace diarsipkan -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center">
                        <p class="text-sm text-gray-600">Workspace diarsipkan. Aksi dinonaktifkan.</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-300 p-8 sm:p-12 text-center">
            <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Belum ada tugas</h3>
            <p class="text-sm text-gray-500 mb-6">
                @if($workspace->is_archived)
                    Workspace ini diarsipkan. Pulihkan workspace untuk membuat tugas.
                @else
                    Buat tugas pertama Anda untuk memulai
                @endif
            </p>
            @if(!$workspace->is_archived)
            <a href="{{ route('workspace.tasks.create', $workspace) }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition text-sm font-medium touch-manipulation min-h-[44px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Tugas
            </a>
            @endif
        </div>
        @endif
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

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

/* Touch manipulation untuk performa lebih baik */
.touch-manipulation {
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
}

/* Prevent text selection pada tombol */
button, a {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    /* Pastikan tidak ada horizontal scroll */
    body {
        overflow-x: hidden;
    }
    
    /* Optimasi spacing untuk mobile */
    .max-w-7xl {
        max-width: 100%;
    }
    
    /* Better touch targets */
    button, a, input, select, textarea {
        min-height: 44px;
    }
    
    /* Prevent zoom on input focus (iOS) */
    input, select, textarea {
        font-size: 16px;
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
}

/* Tablet optimization */
@media (min-width: 641px) and (max-width: 1024px) {
    .grid-cols-1 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Loading skeleton animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Improved active states for better touch feedback */
button:active, a:active {
    transform: scale(0.98);
    transition: transform 0.1s ease;
}

/* Card hover effects - disabled on touch devices */
@media (hover: hover) and (pointer: fine) {
    .hover\:shadow-md:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
}

/* Safe area for notched devices */
@supports (padding: max(0px)) {
    .max-w-7xl {
        padding-left: max(0.75rem, env(safe-area-inset-left));
        padding-right: max(0.75rem, env(safe-area-inset-right));
    }
}

/* Prevent content shift during loading */
.min-h-\[44px\] {
    min-height: 44px;
}

/* Better focus states for accessibility */
button:focus-visible, a:focus-visible {
    outline: 2px solid #6366f1;
    outline-offset: 2px;
}

/* Optimized text rendering */
body {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}

/* Prevent layout shift */
img, svg {
    max-width: 100%;
    height: auto;
}

/* Smooth transitions */
* {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Dark mode support (optional) */
@media (prefers-color-scheme: dark) {
    /* Add dark mode styles if needed */
}

/* Reduce motion for users who prefer it */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endsection