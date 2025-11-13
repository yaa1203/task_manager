@extends('superadmin.layouts.superadmin')

@section('page-title', 'Detail Workspace')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header Section dengan Breadcrumb --}}
    <div class="mb-6 sm:mb-8">
        {{-- Breadcrumb --}}
        <nav class="flex mb-3 sm:mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('space.index') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-700 hover:text-purple-600 transition">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Workspaces
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-xs sm:text-sm font-medium text-gray-500 md:ml-2">Detail</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- Header Card --}}
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl sm:rounded-2xl shadow-md overflow-hidden border border-purple-200">
            <div class="p-4 sm:p-6 lg:p-8">
                <div class="flex flex-col gap-4">
                    {{-- Back Button & Icon - Mobile Optimized --}}
                    <div class="flex items-start gap-3">
                        <a href="{{ route('space.index') }}" 
                           class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mt-1 sm:mt-3 rounded-lg sm:rounded-xl bg-purple-100 hover:bg-purple-200 transition-all group flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md bg-white border border-purple-200"> 
                                @php
                                    $iconSvgs = [
                                        'folder' => '<svg class="w-7 h-7 sm:w-9 sm:h-9 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                        'briefcase' => '<svg class="w-7 h-7 sm:w-9 sm:h-9 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                        'chart' => '<svg class="w-7 h-7 sm:w-9 sm:h-9 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                        'target' => '<svg class="w-7 h-7 sm:w-9 sm:h-9 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                        'cog' => '<svg class="w-7 h-7 sm:w-9 sm:h-9 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                        'clipboard' => '<svg class="w-7 h-7 sm:w-9 sm:h-9 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                                    ];
                                @endphp
                                {!! $iconSvgs[$workspace->icon] ?? $iconSvgs['folder'] !!}
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h1 class="text-xl sm:text-2xl lg:text-4xl font-bold text-gray-900 mb-2 break-words">{{ $workspace->name }}</h1>
                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                    <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 rounded-md sm:rounded-lg text-xs font-medium bg-purple-100 text-purple-700 border border-purple-200">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="hidden sm:inline">Admin: </span>{{ $workspace->admin->name }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 rounded-md sm:rounded-lg text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $workspace->admin->category->name ?? 'Tidak Ada Kategori' }}
                                    </span>
                                    @if($workspace->is_archived)
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 rounded-md sm:rounded-lg text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                            </svg>
                                            Terarsip
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 rounded-md sm:rounded-lg text-xs font-medium bg-green-100 text-green-700 border border-green-200">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-lg sm:rounded-xl shadow-sm animate-fade-in">
        <div class="flex items-center gap-2 sm:gap-3">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs sm:text-sm lg:text-base text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <span class="px-2 sm:px-3 py-0.5 sm:py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-full w-fit">Total</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Tugas</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $workspace->tasks->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-2 sm:px-3 py-0.5 sm:py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full w-fit">Selesai</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Selesai</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">
                    {{ $workspace->tasks->filter(function($task) { return $task->getProgressPercentage() == 100; })->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-2 sm:px-3 py-0.5 sm:py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full w-fit">Progress</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Dalam Progress</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">
                    {{ $workspace->tasks->filter(function($task) { 
                        $progress = $task->getProgressPercentage();
                        return $progress > 0 && $progress < 100; 
                    })->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-3 sm:p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-2 sm:mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="px-2 sm:px-3 py-0.5 sm:py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full w-fit">Pengguna</span>
                </div>
                <h3 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Pengguna</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">
                    {{ $workspace->tasks->flatMap->assignedUsers->unique('id')->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Tasks Section --}}
    <div>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                Daftar Tugas
                <span class="text-sm sm:text-lg font-normal text-gray-500">({{ $workspace->tasks->count() }})</span>
            </h2>
        </div>

        @if($workspace->tasks->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
            @foreach($workspace->tasks as $task)
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300 group">
                {{-- Task Header --}}
                <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-start justify-between mb-3 sm:mb-4">
                        <div class="flex-1 min-w-0 pr-2">
                            <h3 class="font-bold text-gray-900 mb-1.5 sm:mb-2 text-base sm:text-lg group-hover:text-purple-600 transition-colors break-words">
                                {{ $task->title }}
                            </h3>
                            @if($task->description)
                            <p class="text-xs sm:text-sm text-gray-600 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
                            @endif
                        </div>
                        <span class="text-xs font-bold px-2 sm:px-3 py-1 sm:py-1.5 rounded-md sm:rounded-lg shadow-sm flex-shrink-0
                            @php
                                $priorityConfig = [
                                    'urgent' => 'bg-red-100 text-red-700 border border-red-200',
                                    'high' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                    'medium' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                    'low' => 'bg-gray-100 text-gray-700 border border-gray-200'
                                ];
                                $pConfig = $priorityConfig[$task->priority] ?? $priorityConfig['low'];

                                $priorityText = match($task->priority) {
                                    'low' => 'RENDAH',
                                    'medium' => 'SEDANG',
                                    'high' => 'TINGGI',
                                    'urgent' => 'SEGERA',
                                    default => strtoupper($task->priority),
                                };
                            @endphp
                            {{ $pConfig }}">
                            {{ $priorityText }}
                        </span>
                    </div>

                    {{-- Progress Section --}}
                    <div class="space-y-1.5 sm:space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-gray-700">Kemajuan Tugas</span>
                            <span class="text-xs sm:text-sm font-bold text-purple-600">
                                {{ $task->getProgressPercentage() }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 sm:h-3 shadow-inner overflow-hidden">
                            <div class="h-2.5 sm:h-3 rounded-full transition-all duration-500 bg-gradient-to-r from-purple-500 to-purple-600 shadow-sm" 
                                 style="width: {{ $task->getProgressPercentage() }}%">
                                @if($task->getProgressPercentage() > 0)
                                <div class="w-full h-full bg-white/20"></div>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0 text-xs">
                            <span class="text-gray-600 font-medium">
                                <span class="font-bold text-purple-600">{{ $task->submissions->count() }}</span> / 
                                <span class="font-bold text-gray-900">{{ $task->assignedUsers->count() }}</span> selesai
                            </span>
                            @if($task->due_date)
                                <span class="text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Task Details --}}
                <div class="p-4 sm:p-6">
                    {{-- Assigned Users with Status --}}
                    <div class="mb-3 sm:mb-4">
                        <div class="flex items-center justify-between mb-2 sm:mb-3">
                            <h4 class="text-xs sm:text-sm font-bold text-gray-900 flex items-center gap-1.5 sm:gap-2">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Pengguna Ditugaskan
                            </h4>
                            <span class="text-xs font-semibold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-purple-100 text-purple-700">
                                {{ $task->assignedUsers->count() }} orang
                            </span>
                        </div>
                        <div class="space-y-1.5 sm:space-y-2 max-h-[200px] sm:max-h-[240px] overflow-y-auto custom-scrollbar pr-0.5 sm:pr-1">
                            @foreach($task->assignedUsers as $user)
                                @php
                                    $userSubmission = $task->submissions->where('user_id', $user->id)->first();
                                    $hasSubmitted = $userSubmission !== null;
                                    $isLate = false;
                                    
                                    // Check if user is late (deadline passed and no submission)
                                    if ($task->due_date && !$hasSubmitted) {
                                        $isLate = \Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($task->due_date));
                                    }
                                @endphp
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-3 border border-gray-200 hover:border-purple-300 transition-all group/user">
                                    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1 pr-2">
                                        <div class="relative flex-shrink-0">
                                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-sm group-hover/user:shadow-md transition-shadow">
                                                <span class="text-xs sm:text-sm font-bold text-white">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            @if($hasSubmitted)
                                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 sm:w-4 sm:h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                                <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            @elseif($isLate)
                                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 sm:w-4 sm:h-4 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
                                                <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs sm:text-sm font-semibold text-gray-900 block truncate">{{ $user->name }}</span>
                                            <span class="text-xs text-gray-500 truncate block">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                    @if($hasSubmitted)
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 rounded-md sm:rounded-lg text-xs font-bold bg-green-100 text-green-700 border border-green-200 flex-shrink-0">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="hidden sm:inline">Selesai</span>
                                        </span>
                                    @elseif($isLate)
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 rounded-md sm:rounded-lg text-xs font-bold bg-red-100 text-red-700 border border-red-200 flex-shrink-0">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="hidden sm:inline">Terlambat</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 rounded-md sm:rounded-lg text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 flex-shrink-0">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="hidden sm:inline">Belum</span>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Task Metadata --}}
                    <div class="grid grid-cols-2 gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-1.5 sm:gap-2 text-xs">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-gray-500 font-medium">Dibuat</p>
                                <p class="text-gray-900 font-semibold truncate">{{ $task->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-xs">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-gray-500 font-medium">Pengumpulan</p>
                                <p class="text-gray-900 font-semibold">{{ $task->submissions->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        {{-- Empty State --}}
        <div class="bg-white rounded-xl sm:rounded-2xl border-2 border-dashed border-gray-300 p-8 sm:p-12 lg:p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gray-100 rounded-2xl sm:rounded-3xl flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">Belum Ada Tugas</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    Workspace ini belum memiliki tugas. Tugas yang dibuat oleh admin akan muncul di sini.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Custom Styles --}}
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

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

@media (min-width: 640px) {
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #9333ea;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #7e22ce;
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #9333ea #f1f5f9;
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.group\/user:hover {
    transform: translateX(2px);
}

@media (min-width: 640px) {
    .group\/user:hover {
        transform: translateX(4px);
    }
}

/* Mobile touch optimization */
@media (max-width: 639px) {
    /* Reduce motion for better mobile performance */
    * {
        transition-duration: 100ms;
    }
    
    /* Better touch targets */
    button, a {
        min-height: 44px;
        min-width: 44px;
    }
}
</style>
@endsection