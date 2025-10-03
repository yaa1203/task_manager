@extends('admin.layouts.admin')

@section('content')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">🎯 Admin Analytics Dashboard</h2>
                <p class="text-sm text-gray-600 mt-1">Global overview of all users and activities</p>
            </div>
            <div class="flex gap-2">
                <button onclick="exportReport()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg flex items-center gap-2 transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="hidden sm:inline">Export</span>
                </button>
                <button onclick="refreshData()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg flex items-center gap-2 transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="hidden sm:inline">Refresh</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                @php
                $cards = [
                    ['color' => 'blue', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Total Users', 'id' => 'total-users', 'sub' => 'active-users'],
                    ['color' => 'indigo', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Total Tasks', 'id' => 'total-tasks'],
                    ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Completed', 'id' => 'completed-tasks', 'sub' => 'completion-rate'],
                    ['color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'In Progress', 'id' => 'progress-tasks'],
                    ['color' => 'purple', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', 'label' => 'Projects', 'id' => 'total-projects', 'sub' => 'active-projects-count']
                ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-gradient-to-br from-{{ $card['color'] }}-500 to-{{ $card['color'] }}-600 rounded-lg shadow-lg p-3 sm:p-5 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs opacity-90">{{ $card['label'] }}</p>
                    <p id="{{ $card['id'] }}" class="text-2xl sm:text-3xl font-bold mt-1">-</p>
                    @if(isset($card['sub']))
                    <p class="text-xs opacity-75 mt-1">
                        <span id="{{ $card['sub'] }}">-</span> {{ $card['sub'] === 'completion-rate' ? '% rate' : 'active' }}
                    </p>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                
                <!-- Task Distribution -->
                <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Task Distribution</h3>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">All Users</span>
                    </div>
                    <div class="h-48 sm:h-64">
                        <canvas id="taskChart"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                        @foreach([['id' => 'pending-count', 'label' => 'Pending', 'color' => 'red'], ['id' => 'inprogress-count', 'label' => 'Progress', 'color' => 'yellow'], ['id' => 'done-count', 'label' => 'Done', 'color' => 'green']] as $stat)
                        <div class="bg-{{ $stat['color'] }}-50 rounded-lg p-2">
                            <p class="text-xs text-gray-600">{{ $stat['label'] }}</p>
                            <p id="{{ $stat['id'] }}" class="text-lg font-bold text-{{ $stat['color'] }}-600">-</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Project Status -->
                <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Projects Status</h3>
                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">All Users</span>
                    </div>
                    <div class="h-48 sm:h-64">
                        <canvas id="projectChart"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2 text-center">
                        <div class="bg-blue-50 rounded-lg p-2">
                            <p class="text-xs text-gray-600">Active</p>
                            <p id="active-projects" class="text-lg font-bold text-blue-600">-</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-2">
                            <p class="text-xs text-gray-600">Finished</p>
                            <p id="finished-projects" class="text-lg font-bold text-purple-600">-</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Performance Metrics --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @php
                $metrics = [
                    ['title' => 'Completion Rate', 'color' => 'green', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'id' => 'system-completion', 'desc' => 'Across all users'],
                    ['title' => 'User Activity', 'color' => 'blue', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'id' => 'user-activity', 'desc' => 'Active in last 7 days'],
                    ['title' => 'Pending Work', 'color' => 'orange', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'id' => 'pending-workload', 'desc' => 'Awaiting completion']
                ];
                @endphp

                @foreach($metrics as $metric)
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-800">{{ $metric['title'] }}</h3>
                        <div class="bg-{{ $metric['color'] }}-100 p-2 rounded-full">
                            <svg class="w-4 h-4 text-{{ $metric['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $metric['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    @if($metric['id'] === 'system-completion')
                    <div>
                        <span id="system-completion-percentage" class="text-2xl sm:text-3xl font-bold text-green-600">0%</span>
                        <div class="mt-2 overflow-hidden h-2 rounded-full bg-green-100">
                            <div id="system-completion-bar" style="width:0%" class="h-full bg-gradient-to-r from-green-500 to-green-600 transition-all duration-500"></div>
                        </div>
                    </div>
                    @else
                    <div class="flex items-baseline gap-2">
                        <span id="{{ $metric['id'] }}-percentage" class="text-2xl sm:text-3xl font-bold text-gray-800">-</span>
                        <span class="text-sm text-gray-500">{{ $metric['id'] === 'user-activity' ? '% active' : 'tasks' }}</span>
                    </div>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $metric['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="loading" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex flex-col items-center gap-3">
            <svg class="animate-spin h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-700 font-medium">Loading analytics...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let charts = { task: null, project: null };

        document.addEventListener("DOMContentLoaded", loadAnalytics);

        function toggleLoading(show = true) {
            document.getElementById('loading').classList.toggle('hidden', !show);
        }

        function loadAnalytics() {
            toggleLoading(true);
            fetch("{{ route('analytict.data') }}")
                .then(res => res.json())
                .then(data => {
                    updateUI(data);
                    toggleLoading(false);
                })
                .catch(error => {
                    console.error('Error:', error);
                    toggleLoading(false);
                });
        }

        function updateUI(data) {
            const { tasks, projects, users, summary } = data;
            const total = (tasks.pending || 0) + (tasks.in_progress || 0) + (tasks.done || 0);
            
            // Update cards
            const updates = {
                'total-users': users?.total || 0,
                'active-users': users?.active || 0,
                'total-tasks': total,
                'completed-tasks': tasks.done || 0,
                'progress-tasks': tasks.in_progress || 0,
                'total-projects': (projects.active || 0) + (projects.finished || 0),
                'active-projects-count': projects.active || 0,
                'pending-count': tasks.pending || 0,
                'inprogress-count': tasks.in_progress || 0,
                'done-count': tasks.done || 0,
                'active-projects': projects.active || 0,
                'finished-projects': projects.finished || 0,
                'completion-rate': summary?.completion_rate || 0,
                'system-completion-percentage': (summary?.completion_rate || 0) + '%',
                'user-activity-percentage': users?.total > 0 ? Math.round((users.active / users.total) * 100) : 0,
                'pending-workload-percentage': (tasks.pending || 0) + (tasks.in_progress || 0)
            };

            Object.entries(updates).forEach(([id, value]) => {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            });

            document.getElementById('system-completion-bar').style.width = (summary?.completion_rate || 0) + '%';

            updateCharts(data);
        }

        function updateCharts(data) {
            const taskData = [data.tasks.pending || 0, data.tasks.in_progress || 0, data.tasks.done || 0];
            const projectData = [data.projects.active || 0, data.projects.finished || 0];

            if (charts.task) charts.task.destroy();
            charts.task = new Chart(document.getElementById('taskChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Done'],
                    datasets: [{
                        data: taskData,
                        backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 10, font: { size: 11 } } }
                    }
                }
            });

            if (charts.project) charts.project.destroy();
            charts.project = new Chart(document.getElementById('projectChart'), {
                type: 'bar',
                data: {
                    labels: ['Active', 'Finished'],
                    datasets: [{
                        data: projectData,
                        backgroundColor: ['#3b82f6', '#8b5cf6'],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        function refreshData() { loadAnalytics(); }

        function exportReport() {
            alert('Export feature will be implemented.');
        }
    </script>
@endsection