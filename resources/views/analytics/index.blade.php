<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800">📊 Analytics Dashboard</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Your productivity insights and statistics</p>
            </div>
            <button onclick="refreshData()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg flex items-center gap-2 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span class="hidden sm:inline">Refresh</span>
            </button>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            {{-- Error Alert --}}
            <div id="error-alert" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">Failed to load analytics</p>
                        <p id="error-message" class="text-xs text-red-600 mt-1"></p>
                    </div>
                    <button onclick="hideError()" class="ml-auto text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
                @php
                $cards = [
                    ['color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Total Tasks', 'id' => 'total-tasks'],
                    ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Completed', 'id' => 'completed-tasks'],
                    ['color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Unfinished', 'id' => 'unfinished-tasks'],
                    ['color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Overdue', 'id' => 'overdue-tasks']
                ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-gradient-to-br from-{{ $card['color'] }}-500 to-{{ $card['color'] }}-600 rounded-lg shadow-lg p-3 sm:p-5 text-white animate-fade-in">
                    <div class="flex items-center justify-between mb-2">
                        <div class="bg-white bg-opacity-20 p-1.5 sm:p-2 rounded-lg">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-xs opacity-90">{{ $card['label'] }}</p>
                    <p id="{{ $card['id'] }}" class="text-xl sm:text-2xl lg:text-3xl font-bold mt-1">
                        <span class="animate-pulse">-</span>
                    </p>
                </div>
                @endforeach
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                
                <!-- Task Distribution -->
                <div class="bg-white shadow-lg rounded-lg p-3 sm:p-6">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-800">Task Distribution</h3>
                        <span id="chart-update-time" class="text-[10px] sm:text-xs text-gray-500">Loading...</span>
                    </div>
                    <div class="h-40 sm:h-48 lg:h-64 mb-3 relative">
                        <canvas id="taskChart"></canvas>
                        <div id="task-chart-loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
                            <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-1.5 sm:gap-2 text-center">
                        @foreach([
                            ['id' => 'done-count', 'label' => 'Done', 'color' => 'green'], 
                            ['id' => 'unfinished-count', 'label' => 'Unfinished', 'color' => 'gray'], 
                            ['id' => 'overdue-count', 'label' => 'Overdue', 'color' => 'red']
                        ] as $stat)
                        <div class="bg-{{ $stat['color'] }}-50 rounded-lg p-1.5 sm:p-2">
                            <p class="text-[10px] sm:text-xs text-gray-600">{{ $stat['label'] }}</p>
                            <p id="{{ $stat['id'] }}" class="text-base sm:text-lg font-bold text-{{ $stat['color'] }}-600">-</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Workspace Overview -->
                <div class="bg-white shadow-lg rounded-lg p-3 sm:p-6">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-800">Workspace Overview</h3>
                        <span class="text-[10px] sm:text-xs text-gray-500">Updated now</span>
                    </div>
                    <div class="h-40 sm:h-48 lg:h-64 mb-3 relative">
                        <canvas id="workspaceChart"></canvas>
                        <div id="workspace-chart-loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
                            <div class="animate-spin h-8 w-8 border-4 border-purple-600 border-t-transparent rounded-full"></div>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="bg-purple-50 rounded-lg p-2 sm:p-3 text-center min-w-[100px] sm:min-w-[120px]">
                            <p class="text-[10px] sm:text-xs text-gray-600">Total Workspaces</p>
                            <p id="total-workspaces" class="text-xl sm:text-2xl font-bold text-purple-600">-</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metrics --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                @php
                $metrics = [
                    ['title' => 'Completion Rate', 'color' => 'green', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'id' => 'completion', 'type' => 'progress', 'desc' => 'Tasks completed'],
                    ['title' => 'Average Tasks', 'color' => 'blue', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'id' => 'avg-tasks', 'type' => 'decimal', 'desc' => 'Per workspace'],
                    ['title' => 'Overdue Rate', 'color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'id' => 'overdue-rate', 'type' => 'progress', 'desc' => 'Tasks overdue']
                ];
                @endphp

                @foreach($metrics as $metric)
                <div class="bg-white shadow-lg rounded-lg p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-800">{{ $metric['title'] }}</h3>
                        <div class="bg-{{ $metric['color'] }}-100 p-1.5 sm:p-2 rounded-full">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-{{ $metric['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $metric['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    @if($metric['type'] === 'progress')
                    <div>
                        <span id="{{ $metric['id'] }}-percentage" class="text-xl sm:text-2xl lg:text-3xl font-bold text-{{ $metric['color'] }}-600">0%</span>
                        <div class="mt-2 overflow-hidden h-2 rounded-full bg-{{ $metric['color'] }}-100">
                            <div id="{{ $metric['id'] }}-bar" style="width:0%" class="h-full bg-gradient-to-r from-{{ $metric['color'] }}-500 to-{{ $metric['color'] }}-600 transition-all duration-500"></div>
                        </div>
                    </div>
                    @elseif($metric['type'] === 'decimal')
                    <div class="flex items-baseline gap-1.5 sm:gap-2">
                        <span id="{{ $metric['id'] }}" class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">-</span>
                        <span class="text-xs sm:text-sm text-gray-500">tasks</span>
                    </div>
                    @else
                    <div class="flex items-baseline gap-1.5 sm:gap-2">
                        <span id="{{ $metric['id'] }}" class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">-</span>
                        <span class="text-xs sm:text-sm text-gray-500">tasks</span>
                    </div>
                    @endif
                    <p class="text-[10px] sm:text-xs text-gray-500 mt-1 sm:mt-2">{{ $metric['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="loading" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-4 sm:p-6 flex flex-col items-center gap-2 sm:gap-3 mx-4">
            <svg class="animate-spin h-8 w-8 sm:h-10 sm:w-10 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-700 font-medium text-sm sm:text-base">Loading analytics...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let charts = { task: null, workspace: null };
        let retryCount = 0;
        const MAX_RETRIES = 3;

        document.addEventListener("DOMContentLoaded", function() {
            console.log('Analytics page loaded');
            loadAnalytics();
        });

        function toggleLoading(show = true) {
            const loading = document.getElementById('loading');
            if (loading) {
                loading.classList.toggle('hidden', !show);
            }
        }

        function showError(message) {
            const errorAlert = document.getElementById('error-alert');
            const errorMessage = document.getElementById('error-message');
            if (errorAlert && errorMessage) {
                errorMessage.textContent = message;
                errorAlert.classList.remove('hidden');
            }
            console.error('Analytics Error:', message);
        }

        function hideError() {
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                errorAlert.classList.add('hidden');
            }
        }

        async function loadAnalytics() {
            toggleLoading(true);
            hideError();
            
            const url = "{{ route('analytics.data') }}";
            console.log('Fetching analytics from:', url);

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    cache: 'no-cache' // Force fresh data
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Response is not JSON');
                }

                const data = await response.json();
                console.log('Analytics data received:', data);

                // Validate data structure
                if (!data || typeof data !== 'object') {
                    throw new Error('Invalid data structure');
                }

                updateUI(data);
                retryCount = 0; // Reset retry count on success
                toggleLoading(false);

            } catch (error) {
                console.error('Error loading analytics:', error);
                toggleLoading(false);
                
                if (retryCount < MAX_RETRIES) {
                    retryCount++;
                    showError(`Failed to load data. Retrying... (${retryCount}/${MAX_RETRIES})`);
                    setTimeout(() => loadAnalytics(), 2000 * retryCount);
                } else {
                    showError('Failed to load analytics data. Please check your connection and try again.');
                    // Show default/empty state
                    updateUI(getDefaultData());
                }
            }
        }

        function getDefaultData() {
            return {
                tasks: { done: 0, unfinished: 0, overdue: 0 },
                workspaces: { total: 0, breakdown: [] },
                weekly_trend: [],
                summary: { total_tasks: 0, completion_rate: 0 }
            };
        }

        function updateUI(data) {
            try {
                const { tasks, workspaces, weekly_trend, summary } = data;
                
                // Safely get task counts
                const done = parseInt(tasks?.done) || 0;
                const unfinished = parseInt(tasks?.unfinished) || 0;
                const overdue = parseInt(tasks?.overdue) || 0;
                const total = done + unfinished + overdue;
                
                // Calculate rates
                const completionRate = parseFloat(summary?.completion_rate) || 0;
                const overdueRate = total > 0 ? Math.round((overdue / total) * 100) : 0;
                const totalWorkspaces = parseInt(workspaces?.total) || 0;
                const avgTasks = totalWorkspaces > 0 ? (total / totalWorkspaces).toFixed(1) : '0.0';
                
                // Update all elements safely
                const updates = {
                    'total-tasks': total,
                    'completed-tasks': done,
                    'unfinished-tasks': unfinished,
                    'overdue-tasks': overdue,
                    'done-count': done,
                    'unfinished-count': unfinished,
                    'overdue-count': overdue,
                    'total-workspaces': totalWorkspaces,
                    'completion-percentage': completionRate + '%',
                    'overdue-rate-percentage': overdueRate + '%',
                    'avg-tasks': avgTasks
                };

                Object.entries(updates).forEach(([id, value]) => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.textContent = value;
                    }
                });

                // Update progress bars
                const completionBar = document.getElementById('completion-bar');
                const overdueBar = document.getElementById('overdue-rate-bar');
                
                if (completionBar) completionBar.style.width = completionRate + '%';
                if (overdueBar) overdueBar.style.width = overdueRate + '%';
                
                // Update timestamp
                const timestamp = document.getElementById('chart-update-time');
                if (timestamp) {
                    timestamp.textContent = 'Updated ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                }
                
                // Update charts
                updateCharts(data);
                
                console.log('UI updated successfully');
            } catch (error) {
                console.error('Error updating UI:', error);
                showError('Error displaying data: ' + error.message);
            }
        }

        function updateCharts(data) {
            try {
                const taskData = [
                    parseInt(data.tasks?.done) || 0, 
                    parseInt(data.tasks?.unfinished) || 0, 
                    parseInt(data.tasks?.overdue) || 0
                ];
                const workspaceData = Array.isArray(data.workspaces?.breakdown) ? data.workspaces.breakdown : [];

                // Responsive settings
                const isMobile = window.innerWidth < 640;
                const fontSize = isMobile ? 9 : 11;
                const legendPadding = isMobile ? 5 : 10;

                // Hide loading spinners
                const taskLoading = document.getElementById('task-chart-loading');
                const workspaceLoading = document.getElementById('workspace-chart-loading');
                if (taskLoading) taskLoading.style.display = 'none';
                if (workspaceLoading) workspaceLoading.style.display = 'none';

                // Task Distribution Chart
                const taskCanvas = document.getElementById('taskChart');
                if (taskCanvas) {
                    if (charts.task) charts.task.destroy();
                    
                    const ctx = taskCanvas.getContext('2d');
                    charts.task = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Done', 'Unfinished', 'Overdue'],
                            datasets: [{
                                data: taskData,
                                backgroundColor: ['#10b981', '#6b7280', '#ef4444'],
                                borderWidth: isMobile ? 1 : 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { 
                                    position: 'bottom', 
                                    labels: { 
                                        padding: legendPadding, 
                                        font: { size: fontSize },
                                        boxWidth: isMobile ? 10 : 12
                                    } 
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                            const pct = total > 0 ? Math.round((ctx.parsed / total) * 100) : 0;
                                            return `${ctx.label}: ${ctx.parsed} (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Workspace Chart
                const workspaceCanvas = document.getElementById('workspaceChart');
                if (workspaceCanvas) {
                    if (charts.workspace) charts.workspace.destroy();
                    
                    const ctx = workspaceCanvas.getContext('2d');
                    
                    if (workspaceData.length > 0) {
                        charts.workspace = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: workspaceData.map(w => {
                                    const name = w.name || 'Unknown';
                                    const maxLen = isMobile ? 10 : 15;
                                    return name.length > maxLen ? name.substring(0, maxLen) + '...' : name;
                                }),
                                datasets: [{
                                    label: 'Tasks',
                                    data: workspaceData.map(w => parseInt(w.tasks) || 0),
                                    backgroundColor: '#8b5cf6',
                                    borderRadius: isMobile ? 4 : 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { 
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(ctx) { return `${ctx.parsed.y} tasks`; }
                                        }
                                    }
                                },
                                scales: {
                                    y: { 
                                        beginAtZero: true, 
                                        ticks: { 
                                            stepSize: 1,
                                            font: { size: fontSize }
                                        }, 
                                        grid: { color: '#f3f4f6' } 
                                    },
                                    x: { 
                                        grid: { display: false },
                                        ticks: {
                                            font: { size: fontSize }
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        // Show empty state
                        ctx.clearRect(0, 0, workspaceCanvas.width, workspaceCanvas.height);
                        ctx.font = `${isMobile ? 12 : 14}px sans-serif`;
                        ctx.fillStyle = '#9ca3af';
                        ctx.textAlign = 'center';
                        ctx.fillText('No workspace data', workspaceCanvas.width / 2, workspaceCanvas.height / 2);
                    }
                }
                
                console.log('Charts updated successfully');
            } catch (error) {
                console.error('Error updating charts:', error);
            }
        }

        function refreshData() { 
            console.log('Manual refresh triggered');
            retryCount = 0;
            loadAnalytics(); 
        }

        // Re-render charts on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (charts.task || charts.workspace) {
                    console.log('Window resized, re-rendering charts');
                    loadAnalytics();
                }
            }, 250);
        });

        // Handle visibility change (when user returns to tab)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                console.log('Page became visible, refreshing data');
                loadAnalytics();
            }
        });
    </script>

    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.5s ease-out; }

        canvas {
            max-width: 100%;
            height: auto !important;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</x-app-layout>