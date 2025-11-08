@extends('admin.layouts.admin')

@section('page-title', 'Detail Pengguna')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Bagian Header --}}
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('users.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Detail Pengguna</h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Lihat informasi pengguna dan tugas yang diberikan</p>
            </div>
        </div>
    </div>

    {{-- Kartu Profil Pengguna --}}
    <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6 sm:mb-8">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-lg ring-4 ring-blue-100">
                    <span class="text-white font-bold text-3xl sm:text-4xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 w-full sm:w-auto">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">{{ $user->name }}</h2>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium text-sm sm:text-base break-all">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-start sm:items-center gap-2 text-gray-600 text-xs sm:text-sm flex-wrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Bergabung {{ $user->created_at->format('d M, Y') }}</span>
                            </div>
                            <span class="text-gray-400 hidden sm:inline">•</span>
                            <span class="text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4 flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="10" r="4"/>
                            </svg>
                            Pengguna Aktif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            ID: {{ $user->id }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Statistik (Hanya dari workspace aktif) --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between mb-3 sm:mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full sm:block hidden">Total</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Tugas</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalTasks }}</p>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Tugas aktif</p>
            </div>
        </div>

        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between mb-3 sm:mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full sm:block hidden">Selesai</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Selesai</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $completedTasks }}</p>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Tugas yang telah dikirim</p>
            </div>
        </div>

        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between mb-3 sm:mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full sm:block hidden">Terlambat</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Terlambat</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $overdueTasks }}</p>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Melewati batas waktu</p>
            </div>
        </div>

        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between mb-3 sm:mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 bg-orange-50 text-orange-700 text-xs font-semibold rounded-full sm:block hidden">Pending</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Belum Selesai</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $unfinishedTasks }}</p>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Belum dikirim</p>
            </div>
        </div>
    </div>

    {{-- Info Workspace Archived --}}
    @if($archivedTasks->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="ml-3">
                <p class="text-sm font-medium text-yellow-800">
                    Terdapat {{ $archivedTasks->count() }} tugas dari workspace yang diarsipkan
                </p>
                <p class="text-xs text-yellow-700 mt-1">
                    Tugas dari workspace yang diarsipkan tidak dapat diakses dan tidak dihitung dalam statistik.
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Bagian Tugas --}}
    <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Tugas yang Diberikan</h2>
                        <p class="text-xs sm:text-sm text-gray-600">Semua tugas dengan status pengiriman</p>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs sm:text-sm font-semibold rounded-full">
                        {{ $activeTasks->count() }} Aktif
                    </span>
                    @if($archivedTasks->count() > 0)
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold rounded-full">
                        {{ $archivedTasks->count() }} Diarsipkan
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6">
            @if($tasks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 lg:gap-4">
                    @foreach($tasks as $task)
                        @php
                            $userSubmission = $task->submissions->where('user_id', $user->id)->first();
                            $hasSubmitted = $userSubmission !== null;
                            $workspace = $task->workspace;
                            $isArchived = $workspace->is_archived;
                        @endphp
                        <div class="bg-white rounded-lg border-2 overflow-hidden hover:shadow-lg transition-all duration-200 
                            {{ $isArchived ? 'border-gray-300 opacity-75' : 'border-gray-200 hover:border-blue-300' }}">
                            
                            {{-- Archived Badge --}}
                            @if($isArchived)
                            <div class="bg-gray-100 border-b border-gray-200 px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700">Workspace Diarsipkan</span>
                                </div>
                            </div>
                            @endif

                            <!-- Header Tugas -->
                            <div class="p-3 lg:p-4 border-b border-gray-100" 
                                 style="background: linear-gradient(135deg, {{ $workspace->color }}15 0%, {{ $workspace->color }}05 100%);">
                                <div class="flex items-start justify-between mb-2 gap-2">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <div class="w-6 h-6 lg:w-7 lg:h-7 rounded-lg flex items-center justify-center flex-shrink-0 text-gray-700" 
                                                 style="background-color: {{ $workspace->color }}30;">
                                                <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs text-gray-600 font-medium truncate">{{ $workspace->name }}</span>
                                        </div>
                                        <h3 class="font-semibold text-gray-900 leading-tight text-sm line-clamp-1 mb-1">{{ $task->title }}</h3>
                                        @if($task->description)
                                        <p class="text-xs text-gray-600 line-clamp-1">{{ $task->description }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap border flex-shrink-0
                                        @php
                                            $priorityConfig = [
                                                'urgent' => 'bg-red-100 text-red-800 border-red-200',
                                                'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                'medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'low' => 'bg-blue-100 text-blue-800 border-blue-200'
                                            ];
                                            echo $priorityConfig[$task->priority] ?? $priorityConfig['low'];
                                        @endphp">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>

                                @if($task->due_date)
                                <div class="flex items-center gap-1 text-xs text-gray-600">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">{{ date('d M, Y', strtotime($task->due_date)) }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Detail Tugas -->
                            <div class="p-3 lg:p-4">
                                <!-- Status -->
                                <div class="mb-3">
                                    @if($hasSubmitted)
                                        <div class="flex items-center gap-2 p-2.5 bg-green-50 rounded-lg border border-green-200">
                                            <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-green-800">Telah Dikirim</p>
                                                <p class="text-xs text-green-600">
                                                    {{ $userSubmission->submitted_at ? $userSubmission->submitted_at->diffForHumans() : 'Baru saja' }}
                                                </p>
                                            </div>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border flex-shrink-0
                                                @php
                                                    $statusConfig = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                        'approved' => 'bg-green-100 text-green-800 border-green-200',
                                                        'rejected' => 'bg-red-100 text-red-800 border-red-200'
                                                    ];
                                                    echo $statusConfig[$userSubmission->status] ?? $statusConfig['pending'];
                                                @endphp">
                                                {{ ucfirst($userSubmission->status) }}
                                            </span>
                                        </div>

                                        @if($userSubmission->notes)
                                        <div class="mt-2 p-2.5 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-xs font-medium text-gray-700 mb-1">Catatan:</p>
                                            <p class="text-xs text-gray-600 break-words line-clamp-2">{{ $userSubmission->notes }}</p>
                                        </div>
                                        @endif
                                    @else
                                        <div class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs font-semibold text-gray-800">Belum Dikirim</p>
                                                <p class="text-xs text-gray-600">Menunggu pengiriman</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="pt-2 border-t border-gray-100">
                                    @if($isArchived)
                                        <button disabled 
                                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed text-xs font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            <span>Workspace Diarsipkan</span>
                                        </button>
                                    @else
                                        <a href="{{ route('workspace.tasks.show', [$workspace, $task]) }}" 
                                           class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm text-xs font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Lihat Detail</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 sm:py-16">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada tugas</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Pengguna ini belum memiliki tugas yang ditugaskan.</p>
                    <div class="mt-6">
                        <a href="{{ route('workspaces.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Tugas Baru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Custom styles untuk memastikan tampilan tetap tajam dan tidak blur */
* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}

/* Memastikan tidak ada efek blur pada transisi */
.transition-all {
    transition: all 0.2s ease;
}

/* Style untuk line-clamp */
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

/* Memastikan gambar tetap tajam */
img {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
}

/* Animasi hover yang halus */
.hover\:shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Memastikan konten tetap di tengah */
.max-w-6xl {
    margin-left: auto;
    margin-right: auto;
}

/* Scrollbar yang lebih baik */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection