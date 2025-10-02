@extends('admin.layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Users -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-700">Total Users</h2>
            <p class="mt-2 text-3xl font-bold text-indigo-600">
                {{ $totalUsers }}
            </p>
        </div>

        <!-- Active Projects -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-700">Active Projects</h2>
            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $totalProjects }}
            </p>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-700">Total Tasks</h2>
            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ $totalTasks }}
            </p>
        </div>
    </div>

    <!-- Optional: daftar admin dan user terbaru -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Admins -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Admins</h2>
            <ul class="space-y-2">
                @foreach ($admins as $admin)
                    <li class="flex items-center justify-between border-b pb-2">
                        <span>{{ $admin->name }}</span>
                        <span class="text-sm text-gray-500">{{ $admin->email }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Latest Users -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Latest Users</h2>
            <ul class="space-y-2">
                @foreach ($users as $user)
                    <li class="flex items-center justify-between border-b pb-2">
                        <span>{{ $user->name }}</span>
                        <span class="text-sm text-gray-500">{{ $user->email }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
