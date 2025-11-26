{{-- resources/views/superadmin/password-reset/index.blade.php --}}

@extends('superadmin.layouts.superadmin')

@section('page-title', 'Reset Password - Super Admin')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Permintaan Reset Password</h1>
        <p class="mt-2 text-sm text-gray-600">
            Super Admin dapat mereset password user yang lupa password.
        </p>
    </div>

    <!-- Notifikasi Password Berhasil Direset -->
    @if(session('new_password') && session('reset_request_id'))
        @php
            $resetReq = App\Models\PasswordResetRequest::with(['user', 'admin'])->find(session('reset_request_id'));
        @endphp

        <div class="mb-8 p-6 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-green-900">Password Berhasil Direset!</h3>
                    <div class="mt-3 p-4 bg-white rounded-lg border border-green-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Email:</span><br>
                                <span class="font-mono text-gray-900">{{ session('user_email') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Password Baru:</span><br>
                                <code class="inline-block px-3 py-1 bg-gray-100 rounded font-mono text-green-700">
                                    {{ session('new_password') }}
                                </code>
                            </div>
                        </div>
                    </div>

                    @if($resetReq?->user?->whatsapp)
                        <div class="mt-4">
                            <a href="https://wa.me/{{ $resetReq->user->whatsapp }}?text=Halo%20{{ urlencode($resetReq->user->name) }}%2C%0A%0APassword%20Anda%20telah%20direset%20oleh%20Super%20Admin.%0A%0AEmail%3A%20{{ urlencode($resetReq->user->email) }}%0APassword%20Baru%3A%20{{ urlencode(session('new_password')) }}%0A%0ASilakan%20login%20dan%20segera%20ganti%20password%20Anda.%0A%0ATerima%20kasih!"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-5 py-3 bg-[#25D366] hover:bg-[#1da851] text-white font-medium rounded-lg transition shadow-md">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.265c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C6.06 0 1.05 5.01 1.05 11.192c0 1.917.547 3.793 1.585 5.415L1 23l6.516-1.71c1.698.956 3.638 1.475 5.584 1.475 5.99 0 10.88-4.89 10.88-10.882 0-2.928-1.173-5.712-3.257-7.783"/>
                            </svg>
                            Kirim ke {{ $resetReq->user->role === 'admin' ? 'Admin' : 'User' }} ({{ $resetReq->user->name }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Notifikasi Umum -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel Permintaan Reset -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Permintaan Reset Password</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    {{-- Avatar dengan warna berbeda berdasarkan role --}}
                                    @if($req->user->role === 'admin')
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($req->user->name, 0, 2)) }}
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($req->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $req->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $req->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Role --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($req->user->role === 'admin')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                        </svg>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        User
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($req->user->whatsapp)
                                    <a href="https://wa.me/{{ $req->user->whatsapp }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-green-600 hover:text-green-700 font-medium">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.265c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C6.06 0 1.05 5.01 1.05 11.192c0 1.917.547 3.793 1.585 5.415L1 23l6.516-1.71c1.698.956 3.638 1.475 5.584 1.475 5.99 0 10.88-4.89 10.88-10.882 0-2.928-1.173-5.712-3.257-7.783"/></svg>
                                        {{ $req->user->whatsapp }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($req->status === 'pending')
                                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $req->created_at->format('d M Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($req->status === 'pending')
                                    <form method="POST" action="{{ route('superadmin.reset.perform', $req->id) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Yakin reset password untuk {{ $req->user->email }}?')"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-lg transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v4m-3-9V6a2 2 0 012-2h2a2 2 0 012 2v4m-7 9h10a2 2 0 002-2v-6a2 2 0 00-2-2H7a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                            </svg>
                                            Reset Password
                                        </button>
                                    </form>
                                @else
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-green-600 font-medium text-sm">✓ Selesai</span>
                                        @if($req->admin)
                                            <span class="text-xs text-gray-500">
                                                oleh {{ $req->admin->name }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>Belum ada permintaan reset password</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection