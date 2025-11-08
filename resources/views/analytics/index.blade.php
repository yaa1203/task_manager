<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Dasbor Analitik</h2>
                <p class="text-sm text-gray-600 mt-1">Wawasan produktivitas dan statistik Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            {{-- Alert Error --}}
            <div id="error-alert" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">Gagal memuat analitik</p>
                        <p id="error-message" class="text-xs text-red-600 mt-1"></p>
                    </div>
                    <button onclick="hideError()" class="ml-auto text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            {{-- Kartu Ringkasan --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @php
                $cards = [
                    ['color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Total Tugas', 'id' => 'total-tasks'],
                    ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Selesai', 'id' => 'completed-tasks'],
                    ['color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Belum Selesai', 'id' => 'unfinished-tasks'],
                    ['color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Terlambat', 'id' => 'overdue-tasks']
                ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 border-l-4 border-{{ $card['color'] }}-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">{{ $card['label'] }}</p>
                            <p id="{{ $card['id'] }}" class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">
                                <span class="loading-dash">-</span>
                            </p>
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

            {{-- Grafik --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                
                <!-- Distribusi Tugas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 sm:p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base sm:text-lg font-semibold text-white">Distribusi Tugas</h3>
                            <span id="chart-update-time" class="text-xs text-blue-100">Memuat...</span>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        <div class="h-40 sm:h-48 lg:h-64 mb-4 relative">
                            <canvas id="taskChart"></canvas>
                            <div id="task-chart-empty" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-50 rounded-lg">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <p class="text-gray-500 font-medium text-sm sm:text-base">Belum ada data tugas</p>
                                <p class="text-gray-400 text-xs sm:text-sm mt-1">Mulai tambahkan tugas untuk melihat statistik</p>
                            </div>
                            <div id="task-chart-loading" class="absolute inset-0 flex items-center justify-center bg-white">
                                <div class="text-center">
                                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                                    <div class="text-gray-500 text-sm">Memuat data...</div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 sm:gap-3 text-center">
                            @foreach([
                                ['id' => 'done-count', 'label' => 'Selesai', 'color' => 'green'], 
                                ['id' => 'unfinished-count', 'label' => 'Belum Selesai', 'color' => 'gray'], 
                                ['id' => 'overdue-count', 'label' => 'Terlambat', 'color' => 'red']
                            ] as $stat)
                            <div class="bg-{{ $stat['color'] }}-50 rounded-lg p-2 sm:p-3 border border-{{ $stat['color'] }}-100">
                                <p class="text-xs text-gray-600">{{ $stat['label'] }}</p>
                                <p id="{{ $stat['id'] }}" class="text-lg sm:text-xl font-bold text-{{ $stat['color'] }}-600">-</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Tinjauan Ruang Kerja -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-4 sm:p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base sm:text-lg font-semibold text-white">Tinjauan Ruang Kerja</h3>
                            <span class="text-xs text-purple-100">Diperbarui sekarang</span>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        <div class="h-40 sm:h-48 lg:h-64 mb-4 relative">
                            <canvas id="workspaceChart"></canvas>
                            <div id="workspace-chart-empty" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-50 rounded-lg">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-gray-500 font-medium text-sm sm:text-base">Belum ada ruang kerja</p>
                                <p class="text-gray-400 text-xs sm:text-sm mt-1">Buat ruang kerja untuk mengelola tugas Anda</p>
                            </div>
                            <div id="workspace-chart-loading" class="absolute inset-0 flex items-center justify-center bg-white">
                                <div class="text-center">
                                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600 mb-2"></div>
                                    <div class="text-gray-500 text-sm">Memuat data...</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <div class="bg-purple-50 rounded-lg p-3 sm:p-4 text-center min-w-[120px] sm:min-w-[140px] border border-purple-100">
                                <p class="text-xs text-gray-600">Total Ruang Kerja</p>
                                <p id="total-workspaces" class="text-xl sm:text-2xl font-bold text-purple-600">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metrik --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                @php
                $metrics = [
                    ['title' => 'Tingkat Penyelesaian', 'color' => 'green', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'id' => 'completion', 'type' => 'progress', 'desc' => 'Tugas selesai'],
                    ['title' => 'Rata-rata Tugas', 'color' => 'blue', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'id' => 'avg-tasks', 'type' => 'decimal', 'desc' => 'Per ruang kerja'],
                    ['title' => 'Tingkat Keterlambatan', 'color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'id' => 'overdue-rate', 'type' => 'progress', 'desc' => 'Tugas terlambat']
                ];
                @endphp

                @foreach($metrics as $metric)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-800">{{ $metric['title'] }}</h3>
                        <div class="bg-{{ $metric['color'] }}-100 p-2 rounded-full">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-{{ $metric['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $metric['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    @if($metric['type'] === 'progress')
                    <div>
                        <span id="{{ $metric['id'] }}-percentage" class="text-2xl sm:text-3xl font-bold text-{{ $metric['color'] }}-600">0%</span>
                        <div class="mt-3 overflow-hidden h-2 rounded-full bg-{{ $metric['color'] }}-100">
                            <div id="{{ $metric['id'] }}-bar" style="width:0%" class="h-full bg-gradient-to-r from-{{ $metric['color'] }}-500 to-{{ $metric['color'] }}-600 transition-all duration-500"></div>
                        </div>
                    </div>
                    @elseif($metric['type'] === 'decimal')
                    <div class="flex items-baseline gap-2">
                        <span id="{{ $metric['id'] }}" class="text-2xl sm:text-3xl font-bold text-gray-800">-</span>
                        <span class="text-sm text-gray-500">tugas</span>
                    </div>
                    @else
                    <div class="flex items-baseline gap-2">
                        <span id="{{ $metric['id'] }}" class="text-2xl sm:text-3xl font-bold text-gray-800">-</span>
                        <span class="text-sm text-gray-500">tugas</span>
                    </div>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $metric['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Simplified Analytics Script - No Auto Refresh
        let charts = { task: null, workspace: null };
        let isLoading = false;

        // Prevent multiple initializations
        let isInitialized = false;

        document.addEventListener("DOMContentLoaded", function() {
            if (isInitialized) {
                console.log('Already initialized, skipping...');
                return;
            }
            isInitialized = true;
            
            console.log('Analytics page loaded');
            loadAnalytics();
        });

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

        function showEmptyState(chartId) {
            const emptyEl = document.getElementById(chartId + '-empty');
            const loadingEl = document.getElementById(chartId + '-loading');
            const canvas = document.getElementById(chartId === 'task-chart' ? 'taskChart' : 'workspaceChart');
            
            console.log(`Showing empty state for ${chartId}`);
            if (emptyEl) {
                emptyEl.classList.remove('hidden');
                emptyEl.style.display = 'flex';
            }
            if (loadingEl) loadingEl.style.display = 'none';
            if (canvas) {
                canvas.style.display = 'none';
                canvas.style.visibility = 'hidden';
            }
        }

        function hideEmptyState(chartId) {
            const emptyEl = document.getElementById(chartId + '-empty');
            const loadingEl = document.getElementById(chartId + '-loading');
            const canvas = document.getElementById(chartId === 'task-chart' ? 'taskChart' : 'workspaceChart');
            
            console.log(`Hiding empty state for ${chartId}, showing chart`);
            if (emptyEl) {
                emptyEl.classList.add('hidden');
                emptyEl.style.display = 'none';
            }
            if (loadingEl) loadingEl.style.display = 'none';
            if (canvas) {
                canvas.style.display = 'block';
                canvas.style.visibility = 'visible';
            }
        }

        async function loadAnalytics() {
            // Prevent concurrent loads
            if (isLoading) {
                console.log('Already loading, skipping...');
                return;
            }

            isLoading = true;
            hideError();
            
            const url = `${window.location.origin}/analytics/data`;
            console.log('Fetching analytics data from:', url);

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Analytics data received:', data);

                if (!data || typeof data !== 'object') {
                    throw new Error('Invalid data structure');
                }

                updateUI(data);

            } catch (error) {
                console.error('Error loading analytics:', error);
                showError('Gagal memuat data analitik. Silakan periksa koneksi Anda.');
                updateUI(getDefaultData());
            } finally {
                isLoading = false;
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
                
                const done = parseInt(tasks?.done) || 0;
                const unfinished = parseInt(tasks?.unfinished) || 0;
                const overdue = parseInt(tasks?.overdue) || 0;
                const total = done + unfinished + overdue;
                
                const completionRate = parseFloat(summary?.completion_rate) || 0;
                const overdueRate = total > 0 ? Math.round((overdue / total) * 100) : 0;
                const totalWorkspaces = parseInt(workspaces?.total) || 0;
                const avgTasks = totalWorkspaces > 0 ? (total / totalWorkspaces).toFixed(1) : '0.0';
                
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

                const completionBar = document.getElementById('completion-bar');
                const overdueBar = document.getElementById('overdue-rate-bar');
                
                if (completionBar) completionBar.style.width = completionRate + '%';
                if (overdueBar) overdueBar.style.width = overdueRate + '%';
                
                const timestamp = document.getElementById('chart-update-time');
                if (timestamp) {
                    timestamp.textContent = 'Diperbarui ' + new Date().toLocaleTimeString('id-ID', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                }
                
                updateCharts(data);
                
                console.log('UI successfully updated');
            } catch (error) {
                console.error('Error updating UI:', error);
                showError('Error displaying data: ' + error.message);
            }
        }

        function updateCharts(data) {
            try {
                console.log('=== UPDATE CHARTS CALLED ===');
                console.log('Raw data received:', data);
                
                // Parse task data dengan validasi ketat
                const done = parseInt(data.tasks?.done) || 0;
                const unfinished = parseInt(data.tasks?.unfinished) || 0;
                const overdue = parseInt(data.tasks?.overdue) || 0;
                
                const taskData = [done, unfinished, overdue];
                const taskTotal = done + unfinished + overdue;
                
                console.log('Task data parsed:', {
                    done: done,
                    unfinished: unfinished,
                    overdue: overdue,
                    total: taskTotal,
                    array: taskData
                });

                const workspaceData = Array.isArray(data.workspaces?.breakdown) ? data.workspaces.breakdown : [];
                console.log('Workspace data parsed:', {
                    count: workspaceData.length,
                    data: workspaceData
                });

                const isMobile = window.innerWidth < 640;
                const fontSize = isMobile ? 9 : 11;
                const legendPadding = isMobile ? 5 : 10;

                // === TASK CHART ===
                const taskCanvas = document.getElementById('taskChart');
                console.log('Task canvas element:', taskCanvas);
                
                if (taskCanvas) {
                    // Destroy existing chart
                    if (charts.task) {
                        console.log('Destroying existing task chart');
                        charts.task.destroy();
                        charts.task = null;
                    }
                    
                    if (taskTotal === 0) {
                        console.log('Task total is 0, showing empty state');
                        showEmptyState('task-chart');
                    } else {
                        console.log('Task total is', taskTotal, ', creating chart');
                        hideEmptyState('task-chart');
                        
                        // Pastikan canvas terlihat
                        taskCanvas.style.display = 'block';
                        taskCanvas.style.visibility = 'visible';
                        
                        try {
                            const ctx = taskCanvas.getContext('2d');
                            console.log('Got canvas context:', ctx);
                            
                            charts.task = new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Selesai', 'Belum Selesai', 'Terlambat'],
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
                            console.log('✅ Task chart created successfully:', charts.task);
                        } catch (chartError) {
                            console.error('❌ Error creating task chart:', chartError);
                            showError('Gagal membuat chart distribusi tugas: ' + chartError.message);
                        }
                    }
                }

                // === WORKSPACE CHART ===
                const workspaceCanvas = document.getElementById('workspaceChart');
                console.log('Workspace canvas element:', workspaceCanvas);
                
                if (workspaceCanvas) {
                    // Destroy existing chart
                    if (charts.workspace) {
                        console.log('Destroying existing workspace chart');
                        charts.workspace.destroy();
                        charts.workspace = null;
                    }
                    
                    if (workspaceData.length === 0) {
                        console.log('No workspace data, showing empty state');
                        showEmptyState('workspace-chart');
                    } else {
                        console.log('Workspace data exists, creating chart');
                        hideEmptyState('workspace-chart');
                        
                        // Pastikan canvas terlihat
                        workspaceCanvas.style.display = 'block';
                        workspaceCanvas.style.visibility = 'visible';
                        
                        try {
                            const ctx = workspaceCanvas.getContext('2d');
                            console.log('Got workspace canvas context:', ctx);
                            
                            charts.workspace = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: workspaceData.map(w => {
                                        const name = w.name || 'Unknown';
                                        const maxLen = isMobile ? 10 : 15;
                                        return name.length > maxLen ? name.substring(0, maxLen) + '...' : name;
                                    }),
                                    datasets: [{
                                        label: 'Tugas',
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
                                                label: function(ctx) { return `${ctx.parsed.y} tugas`; }
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
                            console.log('✅ Workspace chart created successfully:', charts.workspace);
                        } catch (chartError) {
                            console.error('❌ Error creating workspace chart:', chartError);
                            showError('Gagal membuat chart workspace: ' + chartError.message);
                        }
                    }
                }
                
                console.log('=== CHARTS UPDATE COMPLETE ===');
            } catch (error) {
                console.error('❌ FATAL ERROR in updateCharts:', error);
                console.error('Error stack:', error.stack);
                showError('Gagal membuat diagram: ' + error.message);
            }
        }

        // Handle window resize for responsive charts
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (charts.task || charts.workspace) {
                    console.log('Window resized, re-rendering charts');
                    const taskCanvas = document.getElementById('taskChart');
                    const workspaceCanvas = document.getElementById('workspaceChart');
                    if (taskCanvas && charts.task) {
                        charts.task.resize();
                    }
                    if (workspaceCanvas && charts.workspace) {
                        charts.workspace.resize();
                    }
                }
            }, 250);
        });
    </script>

    <style>
        /* Sharp rendering */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        /* Canvas sharp rendering */
        canvas {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }

        /* Remove loading dash animation */
        .loading-dash {
            display: inline-block;
        }

        /* Smooth transitions for progress bars */
        [id$="-bar"] {
            transition: width 0.5s ease-in-out;
        }

        /* Simple scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }

        /* Loading spinner animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Ensure empty states are perfectly centered and sharp */
        [id$="-empty"] {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            transform: translateZ(0);
            will-change: auto;
        }

        /* Remove any blur effects */
        body, div, canvas, svg {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0);
        }
    </style>
</x-app-layout>