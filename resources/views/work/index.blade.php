<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Workspace Saya</h1>
        <p class="text-sm sm:text-base text-gray-600">Kelola dan pantau semua tugas Anda di sini</p>
    </div>

    @if($workspaces->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
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
                @endphp
                
                <a href="{{ route('my-workspaces.show', $workspace) }}" 
                   class="group block bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                    
                    <!-- Header Card dengan Icon -->
                    <div class="p-4 sm:p-6 border-b border-gray-100" style="background: linear-gradient(135deg, {{ $workspace->color }}15 0%, {{ $workspace->color }}05 100%);">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-xl sm:text-2xl rounded-xl shadow-sm flex-shrink-0" 
                                     style="background-color: {{ $workspace->color }};">
                                    {{ $workspace->icon }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-gray-900 text-base sm:text-lg truncate">{{ $workspace->name }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-white text-gray-600 shadow-sm mt-1">
                                        {{ ucfirst($workspace->type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if($workspace->description)
                            <p class="text-xs sm:text-sm text-gray-600 line-clamp-2">{{ $workspace->description }}</p>
                        @endif
                    </div>

                    <!-- Progress Section -->
                    <div class="p-4 sm:p-6">
                        <!-- Progress Bar -->
                        <div class="mb-3 sm:mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-700">Progress</span>
                                <span class="text-xs font-bold text-gray-900">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $progress }}%; background-color: {{ $workspace->color }};">
                                </div>
                            </div>
                        </div>

                        <!-- Task Statistics -->
                        <div class="grid grid-cols-3 gap-2 sm:gap-3">
                            <!-- Done -->
                            <div class="text-center p-2 sm:p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center justify-center gap-0.5 sm:gap-1 mb-1">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-base sm:text-lg font-bold text-green-700">{{ $doneTasks }}</span>
                                </div>
                                <p class="text-xs text-green-600 font-medium">Done</p>
                            </div>

                            <!-- Unfinished -->
                            <div class="text-center p-2 sm:p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-center gap-0.5 sm:gap-1 mb-1">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-base sm:text-lg font-bold text-gray-700">{{ $unfinishedTasks }}</span>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Todo</p>
                            </div>

                            <!-- Overdue -->
                            <div class="text-center p-2 sm:p-3 bg-red-50 rounded-lg">
                                <div class="flex items-center justify-center gap-0.5 sm:gap-1 mb-1">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-base sm:text-lg font-bold text-red-700">{{ $overdueTasks }}</span>
                                </div>
                                <p class="text-xs text-red-600 font-medium">Late</p>
                            </div>
                        </div>

                        <!-- Total Tasks -->
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <span class="text-gray-600">Total Tasks</span>
                                <span class="font-semibold text-gray-900">{{ $totalTasks }} task{{ $totalTasks !== 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer dengan CTA -->
                    <div class="px-4 sm:px-6 pb-4 sm:pb-6">
                        <div class="flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">
                            <span>Lihat Detail</span>
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center bg-white border-2 border-dashed border-gray-300 rounded-xl py-12 sm:py-16 px-4">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Belum Ada Workspace</h3>
                <p class="text-xs sm:text-sm text-gray-500">Anda belum memiliki workspace yang berisi tugas. Hubungi admin untuk menambahkan Anda ke workspace.</p>
            </div>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions for hover effects */
.group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
}

.group:hover .group-hover\:text-indigo-600 {
    color: #4f46e5;
}

/* Ensure proper spacing on mobile */
@media (max-width: 640px) {
    .truncate {
        max-width: 100%;
    }
}
</style>
</x-app-layout>