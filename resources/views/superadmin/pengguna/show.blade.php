@extends('superadmin.layouts.superadmin')

@section('page-title', 'Detail Admin & Anggota Tim')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('pengguna.admin') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all text-sm font-medium text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Admin Profile Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-8 py-6">
            <div class="flex items-start gap-6">
                <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0 shadow-xl border-4 border-white/30">
                    <span class="text-white font-bold text-3xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $user->name }}</h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30">
                            Admin
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-white/90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span class="text-sm">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-white/90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-sm">{{ $user->category->name ?? 'Tidak ada kategori' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-white/90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm">Bergabung {{ $user->created_at->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Team Members Section --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Anggota Tim</h2>
                    <p class="text-sm text-gray-600 mt-1">Daftar user yang pernah ditugaskan oleh admin ini</p>
                </div>
                <span class="inline-flex items-center px-4 py-2 bg-purple-50 text-purple-700 rounded-xl text-sm font-semibold border border-purple-200">
                    {{ $teamMembers->count() }} Anggota
                </span>
            </div>
        </div>

        @if($teamMembers->isEmpty())
        {{-- Empty State --}}
        <div class="p-16 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Anggota Tim</h3>
            <p class="text-sm text-gray-600 max-w-md mx-auto">Admin ini belum menugaskan task kepada user manapun</p>
        </div>
        @else
        {{-- Desktop Table --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tugas Diterima</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bergabung Tim</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($teamMembers as $member)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $member->name }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        User
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                {{ $member->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    {{ $member->tasks_count }} Tugas
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $member->first_assigned_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $member->first_assigned_at->diffForHumans() }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="lg:hidden divide-y divide-gray-200">
            @foreach($teamMembers as $member)
            <div class="p-5 hover:bg-gray-50 transition-colors">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-white font-bold text-base">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-gray-900 mb-1">{{ $member->name }}</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span class="truncate">{{ $member->email }}</span>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                User
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                Aktif
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $member->tasks_count }} Tugas
                            </span>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-3 border border-gray-200">
                    <div class="flex items-center gap-2 text-xs text-gray-700">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600">Bergabung tim:</span>
                        <span class="font-semibold text-gray-900">{{ $member->first_assigned_at->format('d F Y') }}</span>
                        <span class="text-gray-500">({{ $member->first_assigned_at->diffForHumans() }})</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Custom Animations --}}
<style>
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection