<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
        <p class="text-sm text-gray-600 mt-1">Tetap update dengan penugasan tugas Anda</p>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <!-- Alert Section - Stacked alerts with better design -->
            <div class="space-y-3">
                {{-- ALERT: Tugas Terlambat Kritikal --}}
                @if($overdueTasks->count() > 0)
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                    <div class="p-4 sm:p-5 relative">
                        <div class="flex items-start gap-4">
                            <!-- Ikon dengan animasi pulse -->
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-white rounded-full animate-ping opacity-30"></div>
                                    <div class="relative bg-white rounded-full p-2.5 sm:p-3">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Konten -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg sm:text-xl font-bold text-white mb-1 flex flex-wrap items-center gap-2">
                                            <span>Peringatan Kritis</span>
                                            <span class="px-2.5 py-0.5 bg-white text-red-600 text-xs font-bold rounded-full">
                                                {{ $overdueTasks->count() }}
                                            </span>
                                        </h3>
                                        <p class="text-sm text-red-100 font-medium">
                                            {{ $overdueTasks->count() }} {{ Str::plural('tugas', $overdueTasks->count()) }} terlambat dan memerlukan perhatian segera
                                        </p>
                                    </div>
                                    <button onclick="this.closest('.bg-gradient-to-r').remove()" 
                                            class="flex-shrink-0 text-red-100 hover:text-white transition-colors p-1 rounded hover:bg-red-700">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Daftar Tugas -->
                                <div class="space-y-2.5">
                                    @foreach($overdueTasks->take(3) as $task)
                                    <div class="bg-white/90 backdrop-blur rounded-lg p-3 sm:p-4 shadow-sm hover:shadow-md transition-all">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <div class="flex-shrink-0 mt-0.5">
                                                        <div class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-semibold text-gray-900 text-sm leading-tight break-words mb-1.5">
                                                            {{ $task->title }}
                                                        </h4>
                                                        @if($task->description)
                                                        <p class="text-xs text-gray-600 line-clamp-2 mb-2">{{ Str::limit($task->description, 80) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <!-- Badge Prioritas -->
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-full
                                                        @if($task->priority === 'urgent') bg-red-100 text-red-800 border border-red-300
                                                        @elseif($task->priority === 'high') bg-orange-100 text-orange-800 border border-orange-300
                                                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800 border border-yellow-300
                                                        @else bg-gray-100 text-gray-800 border border-gray-300
                                                        @endif">
                                                        @if($task->priority === 'urgent')
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                        {{ ucfirst($task->priority) }}
                                                    </span>

                                                    <!-- Tanggal Jatuh Tempo -->
                                                    <span class="inline-flex items-center gap-1.5 text-xs text-red-700 font-semibold">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="whitespace-nowrap">{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y H:i') }}</span>
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Tombol Aksi -->
                                            <a href="{{ route('my-workspaces.task.show', [$task->workspace_id, $task->id]) }}" 
                                            class="flex-shrink-0 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm hover:shadow w-full sm:w-auto">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                                <span>Kirim Sekarang</span>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Tautan Lihat Semua -->
                                @if($overdueTasks->count() > 3)
                                <div class="mt-3 pt-3 border-t border-red-400/30">
                                    <a href="{{ route('my-workspaces.index') }}" 
                                    class="inline-flex items-center gap-2 text-sm text-white hover:text-red-100 font-semibold group">
                                        <span>Lihat semua {{ $overdueTasks->count() }} tugas terlambat</span>
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ALERT: Peringatan Batas Waktu Mendekati --}}
                @if($upcomingDeadlineTasks->count() > 0)
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                    <div class="p-4 sm:p-5 relative">
                        <div class="flex items-start gap-4">
                            <!-- Ikon -->
                            <div class="flex-shrink-0">
                                <div class="bg-white rounded-full p-2.5 sm:p-3">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Konten -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg sm:text-xl font-bold text-white mb-1 flex flex-wrap items-center gap-2">
                                            <span>Batas Waktu Mendekati</span>
                                            <span class="px-2.5 py-0.5 bg-white text-amber-600 text-xs font-bold rounded-full">
                                                {{ $upcomingDeadlineTasks->count() }}
                                            </span>
                                        </h3>
                                        <p class="text-sm text-amber-100">
                                            {{ $upcomingDeadlineTasks->count() }} {{ Str::plural('tugas', $upcomingDeadlineTasks->count()) }} jatuh tempo dalam 24 jam
                                        </p>
                                    </div>
                                    <button onclick="this.closest('.bg-gradient-to-r').remove()" 
                                            class="flex-shrink-0 text-amber-100 hover:text-white transition-colors p-1 rounded hover:bg-amber-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @foreach($upcomingDeadlineTasks->take(5) as $task)
                                    <a href="{{ route('my-workspaces.task.show', [$task->workspace_id, $task->id]) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/90 backdrop-blur hover:bg-white border border-amber-200 rounded-lg text-xs font-medium text-gray-700 hover:text-amber-900 transition-all">
                                        <svg class="w-3.5 h-3.5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="truncate max-w-[120px] sm:max-w-[150px] md:max-w-[200px]">{{ $task->title }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Welcome Section with Enhanced Design - Mobile Optimized -->
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 rounded-2xl shadow-xl p-5 sm:p-8 text-white relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mt-6 -mr-6 w-24 h-24 sm:w-32 sm:h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 sm:w-40 sm:h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute top-1/2 right-10 w-16 h-16 bg-white/5 rounded-full blur-xl"></div>
                
                <div class="relative z-10">
                    <!-- Mobile Layout: Stack -->
                    <div class="flex flex-col gap-4">
                        <!-- Welcome Text -->
                        <div class="flex items-start gap-3">
                            <!-- Avatar/Icon -->
                            <div class="flex-shrink-0 mt-1">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                        alt="Avatar" 
                                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover ring-4 ring-white/30 shadow-lg">
                                @else
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center ring-4 ring-white/30 shadow-lg">
                                        <span class="text-2xl">👋</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Text Content -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg sm:text-2xl font-bold mb-1.5 leading-tight">
                                    Selamat datang kembali!
                                </h4>
                                <p class="text-sm sm:text-base text-white/90 font-medium mb-1">
                                    {{ auth()->user()->name }}
                                </p>
                                <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-sm rounded-lg px-2.5 py-1 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $category }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date & Time Card -->
                        <div class="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-lg">
                            <div class="flex items-center justify-between">
                                <!-- Time -->
                                <div class="flex items-center gap-3">
                                    <div class="bg-white/20 p-2.5 rounded-lg">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl sm:text-3xl font-bold tracking-tight">{{ date('H:i') }}</p>
                                        <p class="text-xs sm:text-sm text-white/80 font-medium">
                                            @php
                                                $days = [
                                                    'Sunday' => 'Minggu',
                                                    'Monday' => 'Senin',
                                                    'Tuesday' => 'Selasa',
                                                    'Wednesday' => 'Rabu',
                                                    'Thursday' => 'Kamis',
                                                    'Friday' => 'Jumat',
                                                    'Saturday' => 'Sabtu'
                                                ];
                                                echo $days[date('l')];
                                            @endphp
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Date -->
                                <div class="text-right">
                                    <p class="text-lg sm:text-xl font-bold">{{ date('d') }}</p>
                                    <p class="text-xs sm:text-sm text-white/80 font-medium">
                                        @php
                                            $months = [
                                                'Jan' => 'Januari',
                                                'Feb' => 'Februari',
                                                'Mar' => 'Maret',
                                                'Apr' => 'April',
                                                'May' => 'Mei',
                                                'Jun' => 'Juni',
                                                'Jul' => 'Juli',
                                                'Aug' => 'Agustus',
                                                'Sep' => 'September',
                                                'Oct' => 'Oktober',
                                                'Nov' => 'November',
                                                'Dec' => 'Desember'
                                            ];
                                            echo $months[date('M')] . ' ' . date('Y');
                                        @endphp
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alternative Version: Compact Mobile --}}
            {{-- Uncomment if you want more compact design --}}
            {{-- 
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 rounded-2xl shadow-xl p-4 sm:p-8 text-white relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent"></div>
                
                <div class="relative z-10">
                    <!-- Header with Avatar -->
                    <div class="flex items-center gap-3 mb-4">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                alt="Avatar" 
                                class="w-14 h-14 rounded-2xl object-cover ring-4 ring-white/30 shadow-lg">
                        @else
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center ring-4 ring-white/30 shadow-lg text-3xl">
                                👋
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-lg font-bold">Halo, {{ auth()->user()->name }}!</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center gap-1 bg-white/20 rounded-full px-2 py-0.5 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                                    {{ $category }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Time & Date Row -->
                    <div class="bg-white/15 backdrop-blur-md rounded-xl p-3 border border-white/20">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-semibold">{{ date('H:i') }}</span>
                            </div>
                            <span class="text-white/80">{{ date('l, d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            --}}

            <!-- Statistics Cards with Enhanced Design -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Total Workspaces Card -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="h-1 bg-gradient-to-r from-purple-500 to-purple-600"></div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-xl shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium">Total</p>
                                <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $workspacesCount }}</p>
                            </div>
                        </div>
                        <h4 class="text-sm text-gray-600 font-medium">Ruang Kerja Saya</h4>
                        <div class="mt-2 flex items-center text-xs text-gray-500">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span>Diakses hari ini</span>
                        </div>
                    </div>
                </div>

                <!-- Total Tasks Card -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="h-1 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-xl shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium">Total</p>
                                <p class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ $totalTasks }}</p>
                            </div>
                        </div>
                        <h4 class="text-sm text-gray-600 font-medium">Total Tugas</h4>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2 py-1 rounded-md text-xs font-semibold">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ $doneTasks }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-700 px-2 py-1 rounded-md text-xs font-semibold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $unfinishedTasks }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-2 py-1 rounded-md text-xs font-semibold">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $overdueCount }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Completion Rate Card -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="h-1 bg-gradient-to-r from-green-500 to-green-600"></div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-br from-green-500 to-green-600 p-3 rounded-xl shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium">Rate</p>
                                <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $completionRate }}%</p>
                            </div>
                        </div>
                        <h4 class="text-sm text-gray-600 font-medium">Tingkat Penyelesaian</h4>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 shadow-inner">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2.5 rounded-full transition-all duration-500 shadow-sm" 
                                     style="width: {{ $completionRate }}%">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $doneTasks }} dari {{ $totalTasks }} tugas selesai</p>
                        </div>
                    </div>
                </div>

                <!-- Overdue Tasks Card -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border {{ $overdueCount > 0 ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-100' }}">
                    <div class="h-1 {{ $overdueCount > 0 ? 'bg-gradient-to-r from-red-500 to-red-600' : 'bg-gradient-to-r from-gray-400 to-gray-500' }}"></div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-br {{ $overdueCount > 0 ? 'from-red-500 to-red-600' : 'from-gray-400 to-gray-500' }} p-3 rounded-xl shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium">Alert</p>
                                <p class="text-2xl sm:text-3xl font-bold {{ $overdueCount > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $overdueCount }}</p>
                            </div>
                        </div>
                        <h4 class="text-sm text-gray-600 font-medium">Tugas Terlambat</h4>
                        <div class="mt-3">
                            @if($overdueCount > 0)
                            <p class="text-xs text-red-600 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Memerlukan perhatian segera!
                            </p>
                            @else
                            <p class="text-xs text-gray-500 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Semua tugas sesuai jadwal
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links with Enhanced Design -->
            <div class="bg-white rounded-xl shadow-md p-5 sm:p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Akses Cepat</h3>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                    $quickLinks = [
                        ['url' => route('my-workspaces.index'), 'label' => 'Ruang Kerja Saya', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4', 'color' => 'blue', 'badge' => $workspacesCount],
                        ['url' => route('notifikasi.index'), 'label' => 'Notifikasi', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'color' => 'purple', 'badge' => 5],
                        ['url' => route('profile.edit'), 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'green', 'badge' => null]
                    ];
                    @endphp

                    @foreach($quickLinks as $link)
                    <a href="{{ $link['url'] }}" 
                       class="group relative overflow-hidden rounded-xl border-2 border-{{ $link['color'] }}-100 hover:border-{{ $link['color'] }}-300 bg-white hover:bg-{{ $link['color'] }}-50 transition-all duration-300 p-5">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-{{ $link['color'] }}-100 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="relative">
                                    <div class="bg-{{ $link['color'] }}-100 p-3 rounded-xl group-hover:bg-{{ $link['color'] }}-200 transition-colors">
                                        <svg class="w-6 h-6 text-{{ $link['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                                        </svg>
                                    </div>
                                </div>
                                <h5 class="font-semibold text-gray-800 text-sm">{{ $link['label'] }}</h5>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity with Enhanced Design -->
            <div class="bg-white rounded-xl shadow-md p-5 sm:p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    <a href="{{ route('my-workspaces.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold group inline-flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="space-y-3">
                    @if($totalTasks > 0)
                        @if($unfinishedTasks > 0)
                        <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl border border-blue-200 hover:border-blue-300 transition-colors">
                            <div class="bg-blue-100 p-2.5 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $unfinishedTasks }} Tugas {{ Str::plural('Belum Selesai', $unfinishedTasks) }}</p>
                                <p class="text-xs text-gray-600">Terus berusaha baik!</p>
                            </div>
                            <a href="{{ route('my-workspaces.index') }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                Lihat
                            </a>
                        </div>
                        @endif

                        @if($overdueCount > 0)
                        <div class="flex items-center gap-3 p-4 bg-red-50 rounded-xl border border-red-200 hover:border-red-300 transition-colors">
                            <div class="bg-red-100 p-2.5 rounded-lg">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-red-500 rounded-full animate-ping opacity-40"></div>
                                    <svg class="w-5 h-5 text-red-600 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-900">{{ $overdueCount }} Tugas {{ Str::plural('Terlambat', $overdueCount) }}</p>
                                <p class="text-xs text-red-700 font-medium">Harap selesaikan sesegera mungkin</p>
                            </div>
                            <a href="{{ route('my-workspaces.index') }}" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                Lihat
                            </a>
                        </div>
                        @endif

                        @if($doneTasks > 0)
                        <div class="flex items-center gap-3 p-4 bg-green-50 rounded-xl border border-green-200 hover:border-green-300 transition-colors">
                            <div class="bg-green-100 p-2.5 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $doneTasks }} Tugas {{ Str::plural('Selesai', $doneTasks) }}</p>
                                <p class="text-xs text-gray-600">Bagus kerja! 🎉</p>
                            </div>
                            <span class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-lg">
                                ✓ Selesai
                            </span>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-10">
                            <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Belum Ada Tugas</h4>
                            <p class="text-xs text-gray-500 mb-4">Anda belum ditugaskan tugas apa pun</p>
                            <p class="text-xs text-gray-400">Periksa nanti untuk tugas baru</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Active Workspaces with Enhanced Design -->
            @if($workspacesCount > 0)
            <div class="bg-white rounded-xl shadow-md p-5 sm:p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Ruang Kerja Aktif</h3>
                    <a href="{{ route('my-workspaces.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold group inline-flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($workspaces->take(3) as $workspace)
                        @php
                            $myTasks = $workspace->tasks;
                            $wsTotalTasks = $myTasks->count();
                            $wsDoneTasks = $myTasks->filter(function($task) {
                                return $task->submissions->isNotEmpty();
                            })->count();
                            $wsProgress = $wsTotalTasks > 0 ? round(($wsDoneTasks / $wsTotalTasks) * 100) : 0;

                            $iconSvgs = [
                                'folder' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                'briefcase' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                'chart' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                'target' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'cog' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                'clipboard' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                            ];
                        @endphp
                        <a href="{{ route('my-workspaces.show', $workspace) }}" 
                           class="group block p-4 border-2 border-gray-200 rounded-xl hover:shadow-lg hover:border-gray-300 transition-all relative overflow-hidden"
                           style="background: linear-gradient(135deg, {{ $workspace->color }}10 0%, {{ $workspace->color }}05 100%);">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-white rounded-full -mr-10 -mt-10 opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 bg-white border border-gray-200 text-gray-700 group-hover:border-indigo-300 transition-all duration-200">
                                        {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-900 text-sm truncate group-hover:text-gray-700">{{ $workspace->name }}</h4>
                                        <p class="text-xs text-gray-600 font-medium">{{ $wsTotalTasks }} {{ Str::plural('tugas', $wsTotalTasks) }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 shadow-inner">
                                        <div class="h-2.5 rounded-full transition-all duration-500 shadow-sm" 
                                             style="width: {{ $wsProgress }}%; background-color: {{ $workspace->color }};">
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-700 font-semibold">{{ $wsProgress }}% Selesai</span>
                                        <span class="text-gray-600 font-medium">{{ $wsDoneTasks }}/{{ $wsTotalTasks }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <style>
    /* Utility line clamp */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Animasi halus */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bg-gradient-to-r {
        animation: fadeIn 0.5s ease-out;
    }

    /* Hover effects */
    .group:hover .group-hover\:scale-150 {
        transform: scale(1.5);
    }

    .group:hover .group-hover\:translate-x-0\.5 {
        transform: translateX(0.125rem);
    }

    /* Smooth transitions */
    * {
        transition-property: color, background-color, border-color, fill, stroke, opacity, box-shadow, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    </style>
</x-app-layout>