@extends('admin.layouts.admin')

@section('content')
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-bold text-2xl sm:text-3xl text-gray-900">My Workspaces</h1>
                <p class="text-sm text-gray-600 mt-1">Organize your projects and tasks into workspaces</p>
            </div>
            <a href="{{ route('workspaces.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Create Workspace</span>
                <span class="sm:hidden">Create</span>
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm sm:text-base text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Active Workspaces -->
    @if($workspaces->count() > 0)
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-5">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900">Active Workspaces</h3>
            <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">
                {{ $workspaces->count() }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
            @foreach($workspaces as $workspace)
            <a href="{{ route('workspaces.show', $workspace) }}" 
               class="group block bg-white rounded-xl border-2 border-gray-200 hover:border-indigo-300 transition-all duration-200 overflow-hidden hover:shadow-xl hover:-translate-y-1">
                
                <!-- Workspace Header with Color -->
                <div class="p-5" style="background: linear-gradient(135deg, {{ $workspace->color }}20 0%, {{ $workspace->color }}08 100%);">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shadow-sm flex-shrink-0 group-hover:scale-110 transition-transform duration-200" 
                                 style="background-color: {{ $workspace->color }}30;">
                                {{ $workspace->icon }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition text-base">
                                    {{ $workspace->name }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-0.5 capitalize flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $workspace->color }};"></span>
                                    {{ $workspace->type }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($workspace->description)
                    <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                        {{ $workspace->description }}
                    </p>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center gap-4 text-xs">
                        @if($workspace->type === 'task' || $workspace->type === 'mixed')
                        <div class="flex items-center gap-1.5 text-gray-700 bg-white/60 px-2.5 py-1.5 rounded-lg">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-semibold">{{ $workspace->tasks_count }}</span>
                            <span>Task{{ $workspace->tasks_count !== 1 ? 's' : '' }}</span>
                        </div>
                        @endif

                        @if($workspace->type === 'project' || $workspace->type === 'mixed')
                        <div class="flex items-center gap-1.5 text-gray-700 bg-white/60 px-2.5 py-1.5 rounded-lg">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <span class="font-semibold">{{ $workspace->projects_count }}</span>
                            <span>Project{{ $workspace->projects_count !== 1 ? 's' : '' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-500 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $workspace->updated_at->diffForHumans() }}
                    </span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl border-2 border-dashed border-gray-300 p-12 sm:p-16 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">No workspaces yet</h3>
            <p class="text-base text-gray-600 mb-8 leading-relaxed">
                Get started by creating your first workspace to organize<br class="hidden sm:inline"> your projects and tasks efficiently
            </p>
            <a href="{{ route('workspaces.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Your First Workspace
            </a>
        </div>
    </div>
    @endif

    <!-- Archived Workspaces -->
    @if($archivedWorkspaces->count() > 0)
    <div class="mt-10">
        <div class="flex items-center gap-2 mb-5">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900">Archived Workspaces</h3>
            <span class="px-2.5 py-0.5 bg-gray-200 text-gray-700 rounded-full text-xs font-medium">
                {{ $archivedWorkspaces->count() }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
            @foreach($archivedWorkspaces as $workspace)
            <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden opacity-70 hover:opacity-100 transition-all duration-200 hover:shadow-lg">
                <div class="p-5" style="background: linear-gradient(135deg, {{ $workspace->color }}15 0%, {{ $workspace->color }}05 100%);">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0" 
                                 style="background-color: {{ $workspace->color }}20;">
                                {{ $workspace->icon }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate">
                                    {{ $workspace->name }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    Archived
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <a href="{{ route('workspaces.show', $workspace) }}" 
                           class="flex-1 text-center px-3 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 text-sm font-medium">
                            View
                        </a>
                        <form action="{{ route('workspaces.toggle-archive', $workspace) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-3 py-2.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-all duration-200 text-sm font-medium">
                                Restore
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth hover transitions */
        a, button {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
@endsection