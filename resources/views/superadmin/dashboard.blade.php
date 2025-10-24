@extends('superadmin.layouts.superadmin')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard Super Admin</h1>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-sm font-medium text-gray-500">Total Users</h2>
            <p class="mt-2 text-xl font-semibold text-gray-900">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-sm font-medium text-gray-500">Total Admins</h2>
            <p class="mt-2 text-xl font-semibold text-gray-900">{{ $totalAdmins }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-sm font-medium text-gray-500">Total Super Admins</h2>
            <p class="mt-2 text-xl font-semibold text-gray-900">{{ $totalSuperAdmins }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-sm font-medium text-gray-500">Total Workspaces</h2>
            <p class="mt-2 text-xl font-semibold text-gray-900">{{ $totalWorkspaces }}</p>
        </div>
    </div>

    {{-- Completed Tasks Chart --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Completed Tasks by Role</h2>
        <canvas id="completedTasksChart" height="100"></canvas>
    </div>

    {{-- Recent Users --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Users</h2>
        <ul class="divide-y divide-gray-200">
            @foreach($recentUsers as $user)
            <li class="py-2 flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                </div>
                <span class="text-xs px-2 py-1 bg-gray-100 rounded-full text-gray-800">{{ ucfirst($user->role) }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('completedTasksChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Users', 'Admins', 'Super Admins'],
            datasets: [{
                label: 'Completed Tasks',
                data: [
                    {{ $completedTasks['users'] }},
                    {{ $completedTasks['admins'] }},
                    {{ $completedTasks['superadmins'] }}
                ],
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endsection
