@extends('admin.layouts.admin')

@section('page-title', 'Dashboard Analitik')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Alert Error --}}
    <div id="error-alert" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
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

    {{-- Bagian Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Dashboard Analitik</h1>
                <p class="text-sm sm:text-base text-gray-600">Tinjauan global tentang semua pengguna dan aktivitas workspace aktif</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="last-update">Memuat...</span>
            </div>
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-4 sm:mb-6 lg:mb-8">
        {{-- Kartu Total Pengguna --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3 lg:mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">Total</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Semua Pengguna</h3>
                <p id="total-users" class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">-</p>
            </div>
        </div>

        {{-- Kartu Total Tugas --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3 lg:mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full">Semua</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Total Tugas</h3>
                <p id="total-tasks" class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">-</p>
            </div>
        </div>

        {{-- Kartu Tugas Selesai --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3 lg:mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full whitespace-nowrap">Selesai</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Selesai</h3>
                <p id="completed-tasks" class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">-</p>
                <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">
                    <span id="completion-rate">0</span>% tingkat penyelesaian
                </p>
            </div>
        </div>

        {{-- Kartu Tugas Terlambat --}}
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-3 lg:mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline-flex px-2 sm:px-3 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full whitespace-nowrap">Peringatan</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1 line-clamp-1">Tugas Terlambat</h3>
                <p id="unfinished-tasks" class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">-</p>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-8">
        
        <!-- Distribusi Tugas -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Distribusi Tugas</h3>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Workspace Aktif</span>
                </div>
                <div class="h-48 sm:h-64 relative">
                    <canvas id="taskChart"></canvas>
                    <div id="task-chart-empty" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-50 rounded-lg">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-gray-500 font-medium text-sm sm:text-base">Belum ada data tugas</p>
                    </div>
                    <div id="task-chart-loading" class="absolute inset-0 flex items-center justify-center bg-white">
                        <div class="text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                            <div class="text-gray-500 text-sm">Memuat data...</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                    <div class="bg-red-50 rounded-lg p-2">
                        <p class="text-xs text-gray-600">Terlambat</p>
                        <p id="overdue-count" class="text-lg font-bold text-red-600">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="text-xs text-gray-600">Belum Selesai</p>
                        <p id="unfinished-count" class="text-lg font-bold text-gray-600">-</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-2">
                        <p class="text-xs text-gray-600">Selesai</p>
                        <p id="done-count" class="text-lg font-bold text-green-600">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Status Tugas -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Ringkasan Status Tugas</h3>
                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Workspace Aktif</span>
                </div>
                <div class="h-48 sm:h-64 flex items-center justify-center">
                    <div class="grid grid-cols-3 gap-6 w-full max-w-md">
                        <!-- Terlambat -->
                        <div class="text-center">
                            <div class="relative inline-flex items-center justify-center w-20 h-20 mb-2">
                                <svg class="w-20 h-20">
                                    <circle cx="40" cy="40" r="36" fill="#fef2f2" />
                                </svg>
                                <div class="absolute">
                                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <p id="overdue-overview" class="text-2xl font-bold text-red-600">-</p>
                            <p class="text-xs text-gray-600 mt-1">Terlambat</p>
                        </div>
                        
                        <!-- Belum Selesai -->
                        <div class="text-center">
                            <div class="relative inline-flex items-center justify-center w-20 h-20 mb-2">
                                <svg class="w-20 h-20">
                                    <circle cx="40" cy="40" r="36" fill="#f9fafb" />
                                </svg>
                                <div class="absolute">
                                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                            </div>
                            <p id="unfinished-overview" class="text-2xl font-bold text-gray-600">-</p>
                            <p class="text-xs text-gray-600 mt-1">Belum Selesai</p>
                        </div>
                        
                        <!-- Selesai -->
                        <div class="text-center">
                            <div class="relative inline-flex items-center justify-center w-20 h-20 mb-2">
                                <svg class="w-20 h-20">
                                    <circle cx="40" cy="40" r="36" fill="#f0fdf4" />
                                </svg>
                                <div class="absolute">
                                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <p id="done-overview" class="text-2xl font-bold text-green-600">-</p>
                            <p class="text-xs text-gray-600 mt-1">Selesai</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 bg-gray-50 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-600">Total Tugas</p>
                    <p id="total-tasks-overview" class="text-2xl font-bold text-gray-800">-</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Metrik Kinerja --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
        {{-- Tingkat Penyelesaian --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-800">Tingkat Penyelesaian</h3>
                    <div class="bg-green-100 p-2 rounded-full">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <span id="system-completion-percentage" class="text-2xl sm:text-3xl font-bold text-green-600">0%</span>
                    <div class="mt-2 overflow-hidden h-2 rounded-full bg-green-100">
                        <div id="system-completion-bar" style="width:0%" class="h-full bg-gradient-to-r from-green-500 to-green-600 transition-all duration-500"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Di workspace aktif</p>
            </div>
        </div>

        {{-- Beban Tugas Terlambat --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-800">Tugas Terlambat</h3>
                    <div class="bg-red-100 p-2 rounded-full">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="overdue-workload-value" class="text-2xl sm:text-3xl font-bold text-red-600">-</span>
                    <span class="text-sm text-gray-500">tugas</span>
                </div>
                <p class="text-xs text-gray-500 mt-2">Memerlukan perhatian</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let charts = { task: null };
    let isLoading = false;
    let isInitialized = false;

    document.addEventListener("DOMContentLoaded", function() {
        if (isInitialized) {
            console.log('Already initialized, skipping...');
            return;
        }
        isInitialized = true;
        
        console.log('Admin Analytics loaded');
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
        const canvas = document.getElementById('taskChart');
        
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
        const canvas = document.getElementById('taskChart');
        
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
        if (isLoading) {
            console.log('Already loading, skipping...');
            return;
        }

        isLoading = true;
        hideError();

        // Cache busting dengan timestamp
        const timestamp = new Date().getTime();
        const url = `${window.location.origin}/analytict/data?_=${timestamp}`;
        console.log('Fetching admin analytics from:', url);

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                },
                cache: 'no-store'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Admin analytics data received:', data);

            if (!data || typeof data !== 'object') {
                throw new Error('Invalid data structure');
            }

            updateUI(data);

        } catch (error) {
            console.error('Error loading admin analytics:', error);
            showError('Gagal memuat data analitik. Silakan periksa koneksi Anda.');
            updateUI(getDefaultData());
        } finally {
            isLoading = false;
        }
    }

    function getDefaultData() {
        return {
            tasks: { done: 0, unfinished: 0, overdue: 0 },
            users: { total: 0 },
            summary: { 
                total_tasks: 0, 
                completion_rate: 0,
                overdue_workload: 0 
            }
        };
    }

    function updateUI(data) {
        try {
            const { tasks, users, summary } = data;
            
            const done = parseInt(tasks?.done) || 0;
            const unfinished = parseInt(tasks?.unfinished) || 0;
            const overdue = parseInt(tasks?.overdue) || 0;
            const total = done + unfinished + overdue;
            
            const completionRate = parseFloat(summary?.completion_rate) || 0;
            const totalUsers = parseInt(users?.total) || 0;
            const overdueWorkload = parseInt(summary?.overdue_workload) || overdue;
            
            const updates = {
                'total-users': totalUsers,
                'total-tasks': total,
                'completed-tasks': done,
                'unfinished-tasks': overdue,
                'completion-rate': completionRate.toFixed(1),
                'overdue-count': overdue,
                'unfinished-count': unfinished,
                'done-count': done,
                'overdue-overview': overdue,
                'unfinished-overview': unfinished,
                'done-overview': done,
                'total-tasks-overview': total,
                'system-completion-percentage': completionRate.toFixed(1) + '%',
                'overdue-workload-value': overdueWorkload
            };

            Object.entries(updates).forEach(([id, value]) => {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = value;
                }
            });

            const progressBar = document.getElementById('system-completion-bar');
            if (progressBar) {
                setTimeout(() => {
                    progressBar.style.width = completionRate + '%';
                }, 100);
            }
            
            const lastUpdate = document.getElementById('last-update');
            if (lastUpdate) {
                lastUpdate.textContent = 'Diperbarui ' + new Date().toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
            }
            
            updateCharts(data);
            
            console.log('Admin UI successfully updated');
        } catch (error) {
            console.error('Error updating admin UI:', error);
            showError('Error menampilkan data: ' + error.message);
        }
    }

    function updateCharts(data) {
        try {
            console.log('=== UPDATE ADMIN CHARTS ===');
            
            const done = parseInt(data.tasks?.done) || 0;
            const unfinished = parseInt(data.tasks?.unfinished) || 0;
            const overdue = parseInt(data.tasks?.overdue) || 0;
            
            const taskData = [overdue, unfinished, done];
            const taskTotal = done + unfinished + overdue;
            
            console.log('Admin task data:', {
                done, unfinished, overdue, total: taskTotal
            });

            const taskCanvas = document.getElementById('taskChart');
            
            if (taskCanvas) {
                if (charts.task) {
                    console.log('Destroying existing admin chart');
                    charts.task.destroy();
                    charts.task = null;
                }
                
                if (taskTotal === 0) {
                    console.log('No tasks, showing empty state');
                    showEmptyState('task-chart');
                } else {
                    console.log('Creating admin task chart with data:', taskData);
                    hideEmptyState('task-chart');
                    
                    taskCanvas.style.display = 'block';
                    taskCanvas.style.visibility = 'visible';
                    
                    try {
                        const ctx = taskCanvas.getContext('2d');
                        const isMobile = window.innerWidth < 640;
                        
                        charts.task = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Terlambat', 'Belum Selesai', 'Selesai'],
                                datasets: [{
                                    data: taskData,
                                    backgroundColor: ['#ef4444', '#6b7280', '#10b981'],
                                    borderWidth: isMobile ? 1 : 2,
                                    borderColor: '#ffffff',
                                    hoverOffset: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { 
                                        position: 'bottom', 
                                        labels: { 
                                            padding: isMobile ? 10 : 15, 
                                            font: { size: isMobile ? 10 : 11 },
                                            usePointStyle: true,
                                            pointStyle: 'circle',
                                            boxWidth: isMobile ? 10 : 12
                                        } 
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const label = context.label || '';
                                                const value = context.parsed || 0;
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                                return `${label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                },
                                animation: {
                                    animateRotate: true,
                                    animateScale: true,
                                    duration: 1000
                                }
                            }
                        });
                        console.log('✅ Admin chart created successfully');
                    } catch (chartError) {
                        console.error('❌ Error creating admin chart:', chartError);
                        showError('Gagal membuat chart: ' + chartError.message);
                    }
                }
            }
            
            console.log('=== ADMIN CHARTS UPDATE COMPLETE ===');
        } catch (error) {
            console.error('❌ Fatal error in admin updateCharts:', error);
            showError('Gagal membuat diagram: ' + error.message);
        }
    }

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (charts.task) {
                console.log('Window resized, re-rendering admin chart');
                charts.task.resize();
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

    /* Smooth transitions for progress bars */
    [id$="-bar"] {
        transition: width 0.5s ease-in-out;
    }

    /* Loading spinner animation */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Ensure empty states are centered and sharp */
    [id$="-empty"] {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        transform: translateZ(0);
        will-change: auto;
    }

    /* Remove blur effects */
    body, div, canvas, svg {
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        transform: translateZ(0);
    }

    /* Simple scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #555; }
</style>
@endsection