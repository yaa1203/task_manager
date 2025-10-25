<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Tombol Kembali -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('my-workspaces.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Ruang Kerja
        </a>
    </div>
    
    <!-- Header Ruang Kerja -->
    <div class="mb-6 sm:mb-8">
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <!-- Header Gradien -->
            <div class="p-4 sm:p-6 lg:p-8 border-b border-gray-100" style="background: linear-gradient(135deg, {{ $workspace->color }}15 0%, {{ $workspace->color }}05 100%);">
                <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                    
                    <div class="flex-1 w-full">
                        <!-- Judul & Badge -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-3">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $workspace->name }}</h1>
                        </div>
                        
                        @php
                            $myTasks = $tasks;
                            $totalTasks = $myTasks->count();
                            $doneTasks = $myTasks->filter(function($task) {
                                return $task->submissions->isNotEmpty();
                            })->count();
                            
                            $overdueTasks = $myTasks->filter(function($task) {
                                $hasSubmission = $task->submissions->isNotEmpty();
                                if (!$hasSubmission && $task->due_date) {
                                    return \Carbon\Carbon::parse($task->due_date)->isPast();
                                }
                                return false;
                            })->count();
                            
                            $unfinishedTasks = $totalTasks - $doneTasks - $overdueTasks;
                            $progress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
                        @endphp

                        <!-- Kartu Statistik - Grid Responsif -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                            <!-- Total Tugas -->
                            <div class="bg-white rounded-lg p-3 sm:p-4 shadow-sm border border-gray-200">
                                <div class="flex items-center gap-1.5 sm:gap-2 mb-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalTasks }}</span>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-600 font-medium">Total</p>
                            </div>

                            <!-- Selesai -->
                            <div class="bg-green-50 rounded-lg p-3 sm:p-4 shadow-sm border border-green-200">
                                <div class="flex items-center gap-1.5 sm:gap-2 mb-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-xl sm:text-2xl font-bold text-green-700">{{ $doneTasks }}</span>
                                </div>
                                <p class="text-xs sm:text-sm text-green-700 font-medium">Selesai</p>
                            </div>

                            <!-- Belum Selesai -->
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 shadow-sm border border-gray-200">
                                <div class="flex items-center gap-1.5 sm:gap-2 mb-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xl sm:text-2xl font-bold text-gray-700">{{ $unfinishedTasks }}</span>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-600 font-medium">Belum Selesai</p>
                            </div>

                            <!-- Terlambat -->
                            <div class="bg-red-50 rounded-lg p-3 sm:p-4 shadow-sm border border-red-200">
                                <div class="flex items-center gap-1.5 sm:gap-2 mb-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xl sm:text-2xl font-bold text-red-700">{{ $overdueTasks }}</span>
                                </div>
                                <p class="text-xs sm:text-sm text-red-700 font-medium">Terlambat</p>
                            </div>
                        </div>

                        <!-- Bar Kemajuan -->
                        <div class="mt-4 sm:mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs sm:text-sm font-semibold text-gray-700">Kemajuan Keseluruhan</span>
                                <span class="text-xs sm:text-sm font-bold text-gray-900">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 sm:h-3 shadow-inner">
                                <div class="h-2.5 sm:h-3 rounded-full transition-all duration-500 shadow-sm" 
                                     style="width: {{ $progress }}%; background: linear-gradient(90deg, {{ $workspace->color }}, {{ $workspace->color }}dd);">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tab - Dioptimalkan untuk Mobile -->
    <div class="mb-4 sm:mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-1.5 sm:p-2">
            <nav class="grid grid-cols-2 sm:flex sm:space-x-2 gap-1.5 sm:gap-0">
                <button onclick="filterTasks('all')" id="tab-all" 
                        class="tab-button px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold rounded-lg transition-all bg-blue-600 text-white shadow-sm">
                    <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="hidden xs:inline">Semua</span>
                        <span class="xs:hidden">Semua</span>
                    </span>
                </button>
                <button onclick="filterTasks('unfinished')" id="tab-unfinished" 
                        class="tab-button px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold rounded-lg transition-all text-gray-600 hover:bg-gray-50">
                    <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="hidden xs:inline">Belum Selesai</span>
                        <span class="xs:hidden">Todo</span>
                    </span>
                </button>
                <button onclick="filterTasks('overdue')" id="tab-overdue" 
                        class="tab-button px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold rounded-lg transition-all text-gray-600 hover:bg-gray-50">
                    <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Terlambat</span>
                    </span>
                </button>
                <button onclick="filterTasks('done')" id="tab-done" 
                        class="tab-button px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold rounded-lg transition-all text-gray-600 hover:bg-gray-50">
                    <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Selesai</span>
                    </span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Kontainer Tugas -->
    <div>
        @if($tasks->count() > 0)
            <!-- Tampilan Tabel Desktop (tersembunyi di mobile) -->
            <div class="hidden lg:block bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Tugas
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Prioritas
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Tanggal Jatuh Tempo
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tasks as $task)
                                @php
                                    $hasSubmission = $task->submissions->isNotEmpty();
                                    $isOverdue = false;
                                    if ($task->due_date && !$hasSubmission) {
                                        $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast();
                                    }
                                    $isDone = $hasSubmission;
                                    $statusFilter = $isDone ? 'done' : ($isOverdue ? 'overdue' : 'unfinished');
                                @endphp
                                <tr class="hover:bg-gray-50 task-row transition-colors" data-status="{{ $statusFilter }}">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900 mb-1">{{ $task->title }}</div>
                                        @if($task->description)
                                            <div class="text-sm text-gray-500 line-clamp-2">{{ $task->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($isDone)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Selesai
                                            </span>
                                        @elseif($isOverdue)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Belum Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1.5 inline-flex text-xs font-bold rounded-full border
                                            @if($task->priority === 'urgent') bg-red-100 text-red-800 border-red-200
                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-800 border-orange-200
                                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800 border-yellow-200
                                            @else bg-gray-100 text-gray-800 border-gray-200
                                            @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($task->due_date)
                                            <div class="flex flex-col">
                                                <span class="{{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-700 font-medium' }}">
                                                    {{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('d F Y H:i') }}
                                                </span>
                                                @if($isOverdue)
                                                    <span class="text-xs text-red-500 font-medium">
                                                        {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('my-workspaces.task.show', [$workspace, $task]) }}" 
                                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                                            Lihat Detail
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tampilan Kartu Mobile (terlihat di mobile) -->
            <div class="lg:hidden space-y-3 sm:space-y-4">
                @foreach($tasks as $task)
                    @php
                        $hasSubmission = $task->submissions->isNotEmpty();
                        $isOverdue = false;
                        if ($task->due_date && !$hasSubmission) {
                            $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast();
                        }
                        $isDone = $hasSubmission;
                        $statusFilter = $isDone ? 'done' : ($isOverdue ? 'overdue' : 'unfinished');
                    @endphp
                    <div class="task-row bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm" data-status="{{ $statusFilter }}">
                        <div class="p-4">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <h3 class="font-semibold text-gray-900 text-base flex-1">{{ $task->title }}</h3>
                                <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-full border flex-shrink-0
                                    @if($task->priority === 'urgent') bg-red-100 text-red-800 border-red-200
                                    @elseif($task->priority === 'high') bg-orange-100 text-orange-800 border-orange-200
                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800 border-yellow-200
                                    @else bg-gray-100 text-gray-800 border-gray-200
                                    @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            @if($task->description)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>
                            @endif

                            <!-- Meta Info -->
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                                <!-- Status -->
                                <div class="flex-1">
                                    @if($isDone)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Selesai
                                        </span>
                                    @elseif($isOverdue)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Belum Selesai
                                        </span>
                                    @endif
                                </div>

                                <!-- Tanggal Jatuh Tempo -->
                                @if($task->due_date)
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Tanggal Jatuh Tempo</div>
                                        <div class="{{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-900 font-medium' }} text-sm">
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y H:i') }}
                                        </div>
                                        @if($isOverdue)
                                            <div class="text-xs text-red-500 font-medium">
                                                {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Tombol Aksi -->
                            <a href="{{ route('my-workspaces.task.show', [$workspace, $task]) }}" 
                               class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm text-sm">
                                Lihat Detail
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- State Kosong untuk Hasil Filter -->
            <div id="empty-state" class="hidden bg-white border border-gray-200 rounded-xl p-8 sm:p-12 text-center">
                <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2" id="empty-message">Tidak ada tugas ditemukan</h3>
                <p class="text-xs sm:text-sm text-gray-500">Coba pilih filter yang berbeda</p>
            </div>
        @else
            <div class="bg-white border border-gray-200 rounded-xl p-8 sm:p-12 lg:p-16 text-center">
                <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Belum Ada Tugas</h3>
                <p class="text-xs sm:text-sm text-gray-500">Belum ada tugas di ruang kerja ini</p>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Breakpoint responsif untuk perangkat ekstra kecil */
@media (min-width: 475px) {
    .xs\:inline {
        display: inline;
    }
    .xs\:hidden {
        display: none;
    }
}
</style>

<script>
function filterTasks(filter) {
    const rows = document.querySelectorAll('.task-row');
    const emptyState = document.getElementById('empty-state');
    const emptyMessage = document.getElementById('empty-message');
    const desktopTable = document.querySelector('.lg\\:block');
    const mobileCards = document.querySelector('.lg\\:hidden');
    let visibleCount = 0;
    
    // Perbarui gaya tab
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        button.classList.add('text-gray-600', 'hover:bg-gray-50');
    });
    
    const activeTab = document.getElementById('tab-' + filter);
    activeTab.classList.remove('text-gray-600', 'hover:bg-gray-50');
    activeTab.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    
    // Filter baris
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        
        if (filter === 'all') {
            row.style.display = '';
            visibleCount++;
        } else if (filter === status) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Tampilkan/sembunyikan state kosong
    if (visibleCount === 0) {
        if (desktopTable) desktopTable.style.display = 'none';
        if (mobileCards) mobileCards.style.display = 'none';
        emptyState.classList.remove('hidden');
        
        if (filter === 'done') {
            emptyMessage.textContent = 'Belum ada tugas yang selesai';
        } else if (filter === 'unfinished') {
            emptyMessage.textContent = 'Semua tugas sudah selesai atau terlambat!';
        } else if (filter === 'overdue') {
            emptyMessage.textContent = 'Tidak ada tugas yang terlambat';
        } else {
            emptyMessage.textContent = 'Tidak ada tugas';
        }
    } else {
        if (desktopTable) desktopTable.style.display = '';
        if (mobileCards) mobileCards.style.display = '';
        emptyState.classList.add('hidden');
    }
}
</script>
</x-app-layout>