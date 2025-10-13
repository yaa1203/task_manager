<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            {{-- Welcome Section --}}
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-4 sm:p-6 text-white">
                <h3 class="text-lg sm:text-xl font-bold mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h3>
                <p class="text-blue-100 text-sm">Here's an overview of your activity</p>
            </div>

            @php
                // Hitung statistik dari workspace dan tasks
                $allMyTasks = collect();
                $workspaces = \App\Models\Workspace::whereHas('tasks.assignedUsers', function ($q) {
                    $q->where('user_id', auth()->id());
                })->with(['tasks' => function($q) {
                    $q->whereHas('assignedUsers', function($query) {
                        $query->where('user_id', auth()->id());
                    })->with(['submissions' => function($query) {
                        $query->where('user_id', auth()->id());
                    }]);
                }])->get();

                foreach ($workspaces as $workspace) {
                    $allMyTasks = $allMyTasks->merge($workspace->tasks);
                }

                $totalTasks = $allMyTasks->count();
                
                $doneTasks = $allMyTasks->filter(function($task) {
                    return $task->submissions->isNotEmpty();
                })->count();
                
                $overdueTasks = $allMyTasks->filter(function($task) {
                    $hasSubmission = $task->submissions->isNotEmpty();
                    if (!$hasSubmission && $task->due_date) {
                        return \Carbon\Carbon::parse($task->due_date)->isPast();
                    }
                    return false;
                })->count();
                
                $unfinishedTasks = $totalTasks - $doneTasks - $overdueTasks;
                
                $completionRate = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
                $workspacesCount = $workspaces->count();

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

            {{-- Stats Section --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                {{-- Total Workspaces --}}
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-2 sm:p-3 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-sm text-gray-600 mb-1">My Workspaces</h4>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $workspacesCount }}</p>
                </div>

                {{-- Total Tasks --}}
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-2 sm:p-3 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-sm text-gray-600 mb-1">Total Tasks</h4>
                    <p class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ $totalTasks }}</p>
                    
                    <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-2 text-xs">
                        <span class="bg-green-50 text-green-700 px-2 py-1 rounded font-medium">
                            ✓ {{ $doneTasks }} Done
                        </span>
                        <span class="bg-gray-50 text-gray-700 px-2 py-1 rounded font-medium">
                            ⏳ {{ $unfinishedTasks }} Todo
                        </span>
                        <span class="bg-red-50 text-red-700 px-2 py-1 rounded font-medium">
                            ⚠️ {{ $overdueTasks }} Late
                        </span>
                    </div>
                </div>

                {{-- Completion Rate --}}
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-green-500 to-green-600 p-2 sm:p-3 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-sm text-gray-600 mb-1">Completion Rate</h4>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $completionRate }}%</p>
                    
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $completionRate }}%">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Overdue Tasks Alert --}}
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition {{ $overdueTasks > 0 ? 'ring-2 ring-red-200' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br {{ $overdueTasks > 0 ? 'from-red-500 to-red-600' : 'from-gray-400 to-gray-500' }} p-2 sm:p-3 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-sm text-gray-600 mb-1">Overdue Tasks</h4>
                    <p class="text-2xl sm:text-3xl font-bold {{ $overdueTasks > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $overdueTasks }}</p>
                    @if($overdueTasks > 0)
                    <p class="text-xs text-red-500 mt-2 font-medium">⚠️ Needs immediate attention!</p>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Quick Access</h3>
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    @php
                    $quickLinks = [
                        ['url' => route('my-workspaces.index'), 'label' => 'My Workspaces', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4', 'color' => 'blue'],
                        ['url' => route('notifikasi.index'), 'label' => 'Notifications', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'color' => 'purple'],
                        ['url' => route('profile.edit'), 'label' => 'Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'green']
                    ];
                    @endphp

                    @foreach($quickLinks as $link)
                    <a href="{{ $link['url'] }}" 
                       class="bg-white hover:bg-{{ $link['color'] }}-50 border-2 border-{{ $link['color'] }}-100 hover:border-{{ $link['color'] }}-300 p-4 sm:p-6 rounded-lg shadow-sm hover:shadow-md transition group">
                        <div class="flex flex-col items-center text-center gap-2 sm:gap-3">
                            <div class="bg-{{ $link['color'] }}-100 p-3 rounded-full group-hover:bg-{{ $link['color'] }}-200 transition">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-{{ $link['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                                </svg>
                            </div>
                            <h5 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $link['label'] }}</h5>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Recent Activity Section --}}
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recent Activity</h3>
                    <a href="{{ route('my-workspaces.index') }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View All →
                    </a>
                </div>
                <div class="space-y-3">
                    @if($totalTasks > 0)
                        @if($unfinishedTasks > 0)
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="bg-blue-100 p-2 rounded">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">You have {{ $unfinishedTasks }} unfinished task{{ $unfinishedTasks !== 1 ? 's' : '' }}</p>
                                <p class="text-xs text-gray-500">Keep up the good work!</p>
                            </div>
                        </div>
                        @endif

                        @if($overdueTasks > 0)
                        <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg border border-red-100">
                            <div class="bg-red-100 p-2 rounded animate-pulse">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-red-900">⚠️ {{ $overdueTasks }} overdue task{{ $overdueTasks !== 1 ? 's' : '' }}</p>
                                <p class="text-xs text-red-600">Please complete as soon as possible</p>
                            </div>
                        </div>
                        @endif

                        @if($doneTasks > 0)
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="bg-green-100 p-2 rounded">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">🎉 {{ $doneTasks }} completed task{{ $doneTasks !== 1 ? 's' : '' }}</p>
                                <p class="text-xs text-gray-500">Great job!</p>
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-sm text-gray-500">No tasks assigned yet</p>
                            <p class="text-xs text-gray-400 mt-1">Check back later for new assignments</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Workspace Overview --}}
            @if($workspacesCount > 0)
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Active Workspaces</h3>
                    <a href="{{ route('my-workspaces.index') }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View All →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($workspaces->take(3) as $workspace)
                        @php
                            $myTasks = $workspace->tasks;
                            $wsTotalTasks = $myTasks->count();
                            $wsDoneTasks = $myTasks->filter(function($task) {
                                return $task->submissions->isNotEmpty();
                            })->count();
                            $wsProgress = $wsTotalTasks > 0 ? round(($wsDoneTasks / $wsTotalTasks) * 100) : 0;
                        @endphp
                        <a href="{{ route('my-workspaces.show', $workspace) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:shadow-md transition group"
                           style="background: linear-gradient(135deg, {{ $workspace->color }}10 0%, {{ $workspace->color }}05 100%);">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg text-white" 
                                     style="background-color: {{ $workspace->color }};">
                                    {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $workspace->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $wsTotalTasks }} task{{ $wsTotalTasks !== 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $wsProgress }}%; background-color: {{ $workspace->color }};">
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-600">
                                <span>{{ $wsProgress }}% Complete</span>
                                <span>{{ $wsDoneTasks }}/{{ $wsTotalTasks }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>