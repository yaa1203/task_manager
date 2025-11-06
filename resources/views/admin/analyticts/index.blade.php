@extends('admin.layouts.admin')

@section('page-title', 'Dashboard Analitik')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Bagian Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Dashboard Analitik</h1>
                <p class="text-sm sm:text-base text-gray-600">Tinjauan global tentang semua pengguna dan aktivitas</p>
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
                    <span id="completion-rate">-</span>% tingkat penyelesaian
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
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Semua Pengguna</span>
                </div>
                <div class="h-48 sm:h-64">
                    <canvas id="taskChart"></canvas>
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
                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Semua Pengguna</span>
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
                        <div id="system-completion-bar" style="width:0%" class="h-full bg-gradient-to-r from-green-500 to-green-600"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Di semua pengguna</p>
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

    document.addEventListener("DOMContentLoaded", loadAnalytics);

    function loadAnalytics() {
        fetch(`${window.location.origin}/analytict/data`)
            .then(res => res.json())
            .then(data => {
                updateUI(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateUI(data) {
        const { tasks, users, summary } = data;
        const total = (tasks.overdue || 0) + (tasks.unfinished || 0) + (tasks.done || 0);
        
        // Update kartu
        const updates = {
            'total-users': users?.total || 0,
            'total-tasks': total,
            'completed-tasks': tasks.done || 0,
            'unfinished-tasks': tasks.overdue || 0,
            'overdue-count': tasks.overdue || 0,
            'unfinished-count': tasks.unfinished || 0,
            'done-count': tasks.done || 0,
            'completion-rate': summary?.completion_rate || 0,
            'system-completion-percentage': (summary?.completion_rate || 0) + '%',
            'overdue-workload-value': summary?.overdue_workload || 0,
            'overdue-overview': tasks.overdue || 0,
            'unfinished-overview': tasks.unfinished || 0,
            'done-overview': tasks.done || 0,
            'total-tasks-overview': total
        };

        Object.entries(updates).forEach(([id, value]) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        });

        // Update progress bar
        document.getElementById('system-completion-bar').style.width = (summary?.completion_rate || 0) + '%';

        updateCharts(data);
    }

    function updateCharts(data) {
        const taskData = [data.tasks.overdue || 0, data.tasks.unfinished || 0, data.tasks.done || 0];

        if (charts.task) charts.task.destroy();
        charts.task = new Chart(document.getElementById('taskChart'), {
            type: 'doughnut',
            data: {
                labels: ['Terlambat', 'Belum Selesai', 'Selesai'],
                datasets: [{
                    data: taskData,
                    backgroundColor: ['#ef4444', '#6b7280', '#10b981'],
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
    }
</script>
@endsection