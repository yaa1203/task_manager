<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold">Welcome back, {{ auth()->user()->name }} 🎉</h3>
                <p class="text-gray-600">Here’s an overview of your activity.</p>
            </div>

            {{-- Stats Section --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h4 class="font-semibold">Total Projects</h4>
                    <p class="text-2xl font-bold text-indigo-600">{{ $projectsCount }}</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-6">
                    <h4 class="font-semibold">Total Tasks</h4>
                    <p class="text-2xl font-bold text-indigo-600">{{ $tasksCount }}</p>
                    <div class="mt-2 text-sm text-gray-500">
                        ✅ {{ $tasksDone }} Done | ⏳ {{ $tasksInProgress }} In Progress | 📝 {{ $tasksTodo }} Todo
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <a href="{{ url('tasks') }}" class="bg-indigo-50 hover:bg-indigo-100 p-6 rounded-lg shadow text-center">
                    <h5 class="font-semibold">Tasks</h5>
                </a>
                <a href="{{ url('projects') }}" class="bg-indigo-50 hover:bg-indigo-100 p-6 rounded-lg shadow text-center">
                    <h5 class="font-semibold">Projects</h5>
                </a>
                <a href="{{ url('calendar') }}" class="bg-indigo-50 hover:bg-indigo-100 p-6 rounded-lg shadow text-center">
                    <h5 class="font-semibold">Calendar</h5>
                </a>
                <a href="{{ url('analytics') }}" class="bg-indigo-50 hover:bg-indigo-100 p-6 rounded-lg shadow text-center">
                    <h5 class="font-semibold">Analytics</h5>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
