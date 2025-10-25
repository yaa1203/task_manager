<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">📂 Ruang Kerja Saya</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola dan lacak semua tugas Anda di berbagai ruang kerja</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            @if($workspaces->count() > 0)
                @php
                    // Hitung total statistik dari semua workspace
                    $totalTasks = 0;
                    $totalDone = 0;
                    $totalOverdue = 0;
                    $totalUnfinished = 0;
                    
                    foreach($workspaces as $workspace) {
                        $myTasks = $workspace->tasks->filter(function($task) {
                            return $task->assignedUsers->contains(auth()->id());
                        });
                        
                        $totalTasks += $myTasks->count();
                        
                        $doneTasks = $myTasks->filter(function($task) {
                            return $task->submissions->where('user_id', auth()->id())->isNotEmpty();
                        })->count();
                        $totalDone += $doneTasks;
                        
                        $overdueTasks = $myTasks->filter(function($task) {
                            $hasSubmission = $task->submissions->where('user_id', auth()->id())->isNotEmpty();
                            if (!$hasSubmission && $task->due_date) {
                                return \Carbon\Carbon::parse($task->due_date)->isPast();
                            }
                            return false;
                        })->count();
                        $totalOverdue += $overdueTasks;
                    }
                    
                    $totalUnfinished = $totalTasks - $totalDone - $totalOverdue;
                @endphp

                {{-- Kartu Statistik --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    @php
                    $statCards = [
                        ['label' => 'Total Tugas', 'value' => $totalTasks, 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['label' => 'Selesai', 'value' => $totalDone, 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Terlambat', 'value' => $totalOverdue, 'color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Belum Selesai', 'value' => $totalUnfinished, 'color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']
                    ];
                    @endphp

                    @foreach($statCards as $card)
                    <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 border-l-4 border-{{ $card['color'] }}-500 transition-all hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 font-medium">{{ $card['label'] }}</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ $card['value'] }}</p>
                            </div>
                            <div class="bg-{{ $card['color'] }}-100 p-2 sm:p-3 rounded-full">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-{{ $card['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Grid Ruang Kerja --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    @foreach($workspaces as $workspace)
                        @php
                            // Hitung statistik task untuk user ini
                            $myTasks = $workspace->tasks->filter(function($task) {
                                return $task->assignedUsers->contains(auth()->id());
                            });
                            
                            $totalTasks = $myTasks->count();
                            $doneTasks = $myTasks->filter(function($task) {
                                return $task->submissions->where('user_id', auth()->id())->isNotEmpty();
                            })->count();
                            
                            $overdueTasks = $myTasks->filter(function($task) {
                                $hasSubmission = $task->submissions->where('user_id', auth()->id())->isNotEmpty();
                                if (!$hasSubmission && $task->due_date) {
                                    return \Carbon\Carbon::parse($task->due_date)->isPast();
                                }
                                return false;
                            })->count();
                            
                            $unfinishedTasks = $totalTasks - $doneTasks - $overdueTasks;
                            $progress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

                            // Icon mapping dengan SVG lengkap
                            $iconSvgs = [
                                'folder' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                'briefcase' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                'chart' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                'target' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'cog' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                'clipboard' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                            ];
                        @endphp
                        
                        <a href="{{ route('my-workspaces.show', $workspace) }}" 
                           class="block bg-white rounded-lg shadow-sm hover:shadow-lg transition-all group border border-gray-200">
                            
                            {{-- Header dengan Ikon dan Info Admin --}}
                            <div class="p-4 sm:p-5 border-b border-gray-100" 
                                 style="background: linear-gradient(135deg, {{ $workspace->color }}15 0%, {{ $workspace->color }}05 100%);">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-14 h-14 mt-3 rounded-lg flex items-center justify-center flex-shrink-0 bg-white border border-gray-200 text-gray-700 group-hover:border-indigo-300 transition-all duration-200">
                                        @php
                                        $iconSvgs = [
                                            'folder' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                            'briefcase' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                            'chart' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                            'target' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                            'cog' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                            'clipboard' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                                        ];
                                        @endphp
                                        {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2 line-clamp-2">
                                            {{ $workspace->name }}
                                        </h3>
                                        
                                        {{-- Info PIC/Admin --}}
                                        <div class="flex items-center gap-2 text-xs">
                                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-white/80 backdrop-blur-sm rounded-lg border border-gray-200/50 shadow-sm">
                                                <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0"
                                                     style="background-color: {{ $workspace->color }}20;">
                                                    <span class="text-[10px] font-bold" style="color: {{ $workspace->color }};">
                                                        {{ strtoupper(substr($workspace->admin->name ?? 'A', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-gray-900 leading-tight truncate max-w-[150px]">
                                                        {{ $workspace->admin->name ?? 'Admin' }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($workspace->admin && $workspace->admin->category)
                                            <div class="flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-medium"
                                                 style="background-color: {{ $workspace->color }}15; color: {{ $workspace->color }};">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <span>{{ $workspace->admin->category->name }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bagian Statistik --}}
                            <div class="p-4 sm:p-5">
                                {{-- Bar Kemajuan --}}
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-700">Kemajuan</span>
                                        <span class="text-sm font-bold" style="color: {{ $workspace->color }};">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full transition-all duration-500" 
                                             style="width: {{ $progress }}%; background-color: {{ $workspace->color }};">
                                        </div>
                                    </div>
                                </div>

                                {{-- Grid Statistik Tugas --}}
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    {{-- Tugas Selesai --}}
                                    <div class="text-center p-2.5 bg-green-50 rounded-lg border border-green-100">
                                        <div class="flex items-center justify-center gap-1 mb-1">
                                            <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-bold text-green-700">{{ $doneTasks }}</p>
                                        <p class="text-xs text-green-600 font-medium">Selesai</p>
                                    </div>

                                    {{-- Tugas Belum Selesai --}}
                                    <div class="text-center p-2.5 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="flex items-center justify-center gap-1 mb-1">
                                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-bold text-gray-700">{{ $unfinishedTasks }}</p>
                                        <p class="text-xs text-gray-600 font-medium">Belum Selesai</p>
                                    </div>

                                    {{-- Tugas Terlambat --}}
                                    <div class="text-center p-2.5 {{ $overdueTasks > 0 ? 'bg-red-50 border border-red-100' : 'bg-gray-50 border border-gray-100' }} rounded-lg">
                                        <div class="flex items-center justify-center gap-1 mb-1">
                                            <svg class="w-3.5 h-3.5 {{ $overdueTasks > 0 ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-bold {{ $overdueTasks > 0 ? 'text-red-700' : 'text-gray-400' }}">{{ $overdueTasks }}</p>
                                        <p class="text-xs {{ $overdueTasks > 0 ? 'text-red-600' : 'text-gray-400' }} font-medium">Terlambat</p>
                                    </div>
                                </div>

                                {{-- Total Tugas --}}
                                <div class="pt-3 border-t border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Total Tugas</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $totalTasks }} tugas</span>
                                    </div>
                                </div>

                                {{-- Tombol Lihat Detail --}}
                                <div class="mt-4 pt-3 border-t border-gray-100">
                                    <div class="flex items-center justify-center gap-2 text-sm font-semibold group-hover:gap-3 transition-all"
                                         style="color: {{ $workspace->color }};">
                                        <span>Lihat Detail</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- State Kosong --}}
                <div class="bg-white rounded-lg shadow-sm p-8 sm:p-12 text-center border border-gray-200">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gray-100 w-20 h-20 sm:w-24 sm:h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Belum Ada Ruang Kerja</h3>
                        <p class="text-sm sm:text-base text-gray-500 mb-4">Anda belum ditugaskan ke ruang kerja mana pun. Hubungi administrator Anda untuk memulai.</p>
                        <div class="flex items-center justify-center gap-2 text-sm text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Periksa nanti untuk pembaruan</span>
                        </div>
                    </div>
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

    /* Transisi hover yang halus */
    .group:hover {
        transform: translateY(-2px);
    }

    /* Memastikan pemotongan teks berfungsi dengan baik */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Optimasi mobile */
    @media (max-width: 640px) {
        .truncate {
            max-width: 100%;
        }
    }

    /* Smooth backdrop blur effect */
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
    </style>
</x-app-layout>