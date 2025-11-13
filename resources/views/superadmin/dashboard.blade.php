@extends('superadmin.layouts.superadmin')

@section('page-title', 'Super Admin Dashboard')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Bagian Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Dashboard Super Administrator</h1>
                <p class="text-sm sm:text-base text-gray-600">Selamat datang kembali {{ Auth::user()->name }} - Kontrol penuh sistem Anda.</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg shadow-sm">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-medium text-purple-900">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Aksi Cepat Super Admin --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Aksi Cepat Super Admin
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <a href="{{ route('pengguna.admin') }}" 
               class="group flex flex-col items-center justify-center gap-2 p-4 sm:p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-purple-500 hover:shadow-lg transition-all duration-200">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-purple-600 text-center">Kelola Admin</span>
            </a>

            <a href="{{ route('pengguna.user') }}" 
               class="group flex flex-col items-center justify-center gap-2 p-4 sm:p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-lg transition-all duration-200">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600 text-center">Kelola User</span>
            </a>

            <a href="{{ route('categories.index') }}" 
               class="group flex flex-col items-center justify-center gap-2 p-4 sm:p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:shadow-lg transition-all duration-200">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 text-center">Kelola Kategori</span>
            </a>

            <a href="{{ route('space.index') }}" 
               class="group flex flex-col items-center justify-center gap-2 p-4 sm:p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-lg transition-all duration-200">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600 text-center">Semua Workspace</span>
            </a>
        </div>
    </div>

    {{-- Kartu Statistik Super Admin --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        {{-- Kartu Total Admins --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-full">Admins</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Admins</h3>
                <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalAdmins }}</p>
                <p class="text-sm text-gray-500">
                    Administrator biasa
                </p>
            </div>
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('pengguna.admin') }}" class="text-sm font-medium text-purple-600 hover:text-purple-700 flex items-center gap-1">
                    Kelola admins
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Kartu Total Users --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full">Users</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Users</h3>
                <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalUsers }}</p>
                <p class="text-sm text-gray-500">
                    Pengguna biasa
                </p>
            </div>
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('pengguna.user') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    Lihat semua users
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Kartu Total Kategori --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">Kategori</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Kategori</h3>
                <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalCategories }}</p>
                <p class="text-sm text-gray-500">
                    Di seluruh sistem
                </p>
            </div>
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('categories.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    Kelola kategori
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Kartu Total Workspaces --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full">Active</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Workspaces</h3>
                <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalWorkspaces }}</p>
                <p class="text-sm text-gray-500">
                    Di seluruh sistem
                </p>
            </div>
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('space.index') }}" class="text-sm font-medium text-green-600 hover:text-green-700 flex items-center gap-1">
                    Lihat workspace
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Chart & Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Task Status Chart untuk Super Admin --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Status Tugas Global</h2>
                            <p class="text-xs sm:text-sm text-gray-600">Selesai, Belum Selesai, dan Terlambat</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <canvas id="taskStatusChart" class="w-full" style="max-height: 300px;"></canvas>
                
                {{-- Update bagian legend statistics dengan warna abu-abu untuk belum selesai --}}
                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600" id="completedCount">{{ $completedTasks ?? 0 }}</div>
                        <div class="text-xs text-gray-600 mt-1">Selesai</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-600" id="pendingCount">{{ $pendingTasks ?? 0 }}</div>
                        <div class="text-xs text-gray-600 mt-1">Belum Selesai</div>
                    </div>
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600" id="overdueCount">{{ $overdueTasks ?? 0 }}</div>
                        <div class="text-xs text-gray-600 mt-1">Terlambat</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Aktivitas Sistem</h2>
                            <p class="text-xs sm:text-sm text-gray-600">Aktivitas terbaru di sistem</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentUsers->take(5) as $user)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-{{ $user->role === 'admin' ? 'purple' : 'green' }}-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                <span class="font-semibold">{{ $user->name }}</span> 
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $user->role === 'admin' ? 'purple' : 'green' }}-100 text-{{ $user->role === 'admin' ? 'purple' : 'green' }}-800">
                                    {{ ucfirst($user->role) }}
                                </span>
                                bergabung dengan sistem
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Tidak ada aktivitas terkini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Users Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Pengguna Terbaru</h2>
                        <p class="text-xs sm:text-sm text-gray-600">Daftar admin & user yang baru bergabung</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pengguna
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bergabung
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentUsers->take(5) as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-{{ $user->role === 'admin' ? 'purple' : 'green' }}-500 to-{{ $user->role === 'admin' ? 'purple' : 'green' }}-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-semibold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $user->role === 'admin' ? 'purple' : 'green' }}-100 text-{{ $user->role === 'admin' ? 'purple' : 'green' }}-800">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            {{-- Link detail yang disesuaikan dengan role --}}
                            @if($user->role === 'admin')
                                <a href="{{ route('pengguna.admin', ['id' => $user->id]) }}" class="text-purple-600 hover:text-purple-900">
                                    Detail
                                </a>
                            @else
                                <a href="{{ route('pengguna.user', ['id' => $user->id]) }}" class="text-green-600 hover:text-green-900">
                                    Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm text-gray-500">Tidak ada pengguna ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Task Status Chart for Super Admin
    const ctx = document.getElementById('taskStatusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Terlambat', 'Belum Selesai', 'Selesai'],
                datasets: [{
                    data: [
                        {{ $overdueTasks ?? 0 }},
                        {{ $pendingTasks ?? 0 }},
                        {{ $completedTasks ?? 0 }}
                    ],
                    backgroundColor: [
                        '#ef4444',  // Red for overdue
                        '#9ca3af', // Gray for pending/unfinished
                        '#10b981' // Green for completed
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 12,
                        cornerRadius: 8,
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
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
});
</script>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Custom scrollbar for tables */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #9333ea;
        border-radius: 10px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #7e22ce;
    }
</style>
@endsection