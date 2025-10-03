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

            {{-- Stats Section --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @php
                $stats = [
                    [
                        'title' => 'Total Projects',
                        'value' => $projectsCount,
                        'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
                        'color' => 'blue',
                        'bg' => 'from-blue-500 to-blue-600'
                    ],
                    [
                        'title' => 'Total Tasks',
                        'value' => $tasksCount,
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        'color' => 'indigo',
                        'bg' => 'from-indigo-500 to-indigo-600',
                        'breakdown' => [
                            ['label' => 'Done', 'value' => $tasksDone, 'icon' => '✓'],
                            ['label' => 'Progress', 'value' => $tasksInProgress, 'icon' => '⏳'],
                            ['label' => 'Todo', 'value' => $tasksTodo, 'icon' => '📝']
                        ]
                    ],
                    [
                        'title' => 'Completion Rate',
                        'value' => $tasksCount > 0 ? round(($tasksDone / $tasksCount) * 100) . '%' : '0%',
                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'color' => 'green',
                        'bg' => 'from-green-500 to-green-600'
                    ]
                ];
                @endphp

                @foreach($stats as $stat)
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br {{ $stat['bg'] }} p-2 sm:p-3 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-sm text-gray-600 mb-1">{{ $stat['title'] }}</h4>
                    <p class="text-2xl sm:text-3xl font-bold text-{{ $stat['color'] }}-600">{{ $stat['value'] }}</p>
                    
                    @if(isset($stat['breakdown']))
                    <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-2 text-xs">
                        @foreach($stat['breakdown'] as $item)
                        <span class="bg-gray-50 px-2 py-1 rounded">
                            {{ $item['icon'] }} {{ $item['value'] }} {{ $item['label'] }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Quick Access</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    @php
                    $quickLinks = [
                        ['url' => 'tasks', 'label' => 'Tasks', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'color' => 'blue'],
                        ['url' => 'projects', 'label' => 'Projects', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', 'color' => 'indigo'],
                        ['url' => 'calendar', 'label' => 'Calendar', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'purple'],
                        ['url' => 'analytics', 'label' => 'Analytics', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color' => 'green']
                    ];
                    @endphp

                    @foreach($quickLinks as $link)
                    <a href="{{ url($link['url']) }}" 
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

            {{-- Recent Activity Section (Optional) --}}
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recent Activity</h3>
                    <a href="{{ url('tasks') }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View All →
                    </a>
                </div>
                <div class="space-y-3">
                    @if($tasksCount > 0)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="bg-blue-100 p-2 rounded">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">You have {{ $tasksTodo + $tasksInProgress }} active tasks</p>
                            <p class="text-xs text-gray-500">{{ $tasksTodo }} pending, {{ $tasksInProgress }} in progress</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($projectsCount > 0)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="bg-indigo-100 p-2 rounded">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $projectsCount }} active {{ Str::plural('project', $projectsCount) }}</p>
                            <p class="text-xs text-gray-500">Keep up the great work!</p>
                        </div>
                    </div>
                    @endif

                    @if($tasksCount === 0 && $projectsCount === 0)
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-500">No recent activity. Start by creating a task or project!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>