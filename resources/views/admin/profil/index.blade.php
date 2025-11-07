@extends('admin.layouts.admin')
@section('title', 'Profil Admin')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header Section with Breadcrumb --}}
            <div class="mb-8">
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                    <span>/</span>
                    <span class="text-blue-600 font-medium">Profil</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Profil Administrator</h1>
                        <p class="text-gray-600">Kelola informasi akun dan preferensi sistem Anda</p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-6 py-3">
                            <div class="text-xs text-gray-500 mb-1">Status Akun</div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-sm font-semibold text-gray-900">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alert Sukses --}}
            @if(session('status') || session('success'))
            <div class="mb-6 overflow-hidden rounded-2xl shadow-lg animate-slideDown">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-semibold text-sm">
                                {{ session('status') ?? session('success') }}
                            </p>
                        </div>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-6">
                {{-- Sidebar Profile Card --}}
                <div class="w-full lg:w-1/3">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden sticky top-6">
                        {{-- Header Gradient --}}
                        <div class="h-32 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 relative overflow-hidden">
                            <div class="absolute inset-0 bg-black/10"></div>
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
                        </div>

                        <div class="px-6 pb-6">
                            {{-- Avatar --}}
                            <div class="relative -mt-16 mb-4 flex justify-center">
                                <div class="relative">
                                    <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center ring-4 ring-white shadow-2xl transform hover:scale-105 transition-transform duration-300">
                                        <span class="text-5xl font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                                         
                                    </div>
                                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-green-500 rounded-xl border-4 border-white flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Admin Info --}}
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $admin->name }}</h3>
                                <p class="text-sm text-gray-600 mb-4 break-all">{{ $admin->email }}</p>

                                {{-- Badges --}}
                                <div class="flex flex-wrap gap-2 justify-center mb-4">
                                    @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $admin->hasVerifiedEmail())
                                    <div class="inline-flex items-center gap-1.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 py-1.5 rounded-lg shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xs font-semibold">Terverifikasi</span>
                                    </div>
                                    @endif
                                    
                                    <div class="inline-flex items-center gap-1.5 bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-3 py-1.5 rounded-lg shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-xs font-semibold">Administrator</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Stats Grid --}}
                       

                            {{-- Divider --}}
                            <div class="border-t border-gray-200 my-6"></div>

                            {{-- Account Info --}}
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 mb-0.5">Kategori</div>
                                        <div class="font-semibold text-gray-900 truncate">{{ $admin->category->name ?? 'Super Admin' }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 mb-0.5">Bergabung Sejak</div>
                                        <div class="font-semibold text-gray-900">{{ $admin->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 mb-0.5">Terakhir Login</div>
                                        <div class="font-semibold text-gray-900">{{ $admin->updated_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content Area --}}
                <div class="w-full lg:w-2/3 space-y-6">
                    {{-- Update Profile Card --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:shadow-2xl transition-shadow duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Informasi Profil</h2>
                                    <p class="text-blue-100 text-sm">Perbarui detail akun Anda</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <form method="post" action="{{ route('admin.profile.update') }}" class="space-y-5">
                                @csrf
                                @method('patch')
                                
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Nama Lengkap
                                        </span>
                                    </label>
                                    <input type="text" id="name" name="name" 
                                           value="{{ old('name', $admin->name) }}" 
                                           class="w-full px-4 py-3 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-300"
                                           placeholder="Masukkan nama lengkap" required>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Alamat Email
                                        </span>
                                    </label>
                                    <input type="email" id="email" name="email" 
                                           value="{{ old('email', $admin->email) }}" 
                                           class="w-full px-4 py-3 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-300"
                                           placeholder="admin@example.com" required>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    
                                    @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $admin->hasVerifiedEmail())
                                        <div class="mt-3 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-lg">
                                            <div class="flex items-start gap-3">
                                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm text-yellow-800 font-medium mb-2">
                                                        Alamat email Anda belum diverifikasi.
                                                    </p>
                                                    <button form="send-verification" class="text-sm text-yellow-700 underline font-semibold hover:text-yellow-900 transition-colors">
                                                        Kirim ulang email verifikasi →
                                                    </button>
                                                    @if (session('status') === 'verification-link-sent')
                                                        <p class="mt-2 text-sm font-medium text-green-700 flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            Tautan verifikasi baru telah dikirim!
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-between pt-2">
                                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                    
                                    @if (session('status') === 'profile-updated')
                                        <div class="flex items-center gap-2 text-green-700 bg-green-50 px-4 py-2.5 rounded-xl border border-green-200 animate-slideIn">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm font-semibold">Berhasil disimpan!</span>
                                        </div>
                                    @endif
                                </div>
                            </form>

                            @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $admin->hasVerifiedEmail())
                            <form id="send-verification" method="post" action="{{ route('admin.verification.send') }}" class="hidden">
                                @csrf
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- Update Password Card --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:shadow-2xl transition-shadow duration-300">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Keamanan Akun</h2>
                                    <p class="text-purple-100 text-sm">Perbarui kata sandi Anda</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <form method="post" action="{{ route('admin.password.update') }}" class="space-y-5">
                                @csrf
                                @method('put')
                                
                                <div class="form-group">
                                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                            </svg>
                                            Kata Sandi Saat Ini
                                        </span>
                                    </label>
                                    <input type="password" id="current_password" name="current_password" 
                                           class="w-full px-4 py-3 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-300"
                                           placeholder="Masukkan kata sandi saat ini" required autocomplete="current-password">
                                    @error('current_password')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Kata Sandi Baru
                                        </span>
                                    </label>
                                    <input type="password" id="password" name="password" 
                                           class="w-full px-4 py-3 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-300"
                                           placeholder="Masukkan kata sandi baru" required autocomplete="new-password">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Konfirmasi Kata Sandi Baru
                                        </span>
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="w-full px-4 py-3 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-300"
                                           placeholder="Konfirmasi kata sandi baru" required autocomplete="new-password">
                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Password Strength Indicator --}}
                                <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-100 rounded-xl p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="text-sm text-gray-700">
                                            <p class="font-semibold mb-2 text-gray-900">Tips Kata Sandi Kuat:</p>
                                            <ul class="space-y-1 text-xs">
                                                <li class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                                    Minimal 8 karakter
                                                </li>
                                                <li class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                                    Kombinasi huruf besar dan kecil
                                                </li>
                                                <li class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                                    Mengandung angka dan simbol
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between pt-2">
                                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Perbarui Kata Sandi
                                    </button>
                                    
                                    @if (session('status') === 'password-updated')
                                        <div class="flex items-center gap-2 text-green-700 bg-green-50 px-4 py-2.5 rounded-xl border border-green-200 animate-slideIn">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm font-semibold">Berhasil diperbarui!</span>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Danger Zone Card --}}
                    <div class="bg-white rounded-2xl shadow-xl border-2 border-red-200 overflow-hidden transform hover:shadow-2xl transition-all duration-300">
                        <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Zona Bahaya</h2>
                                    <p class="text-red-100 text-sm">Tindakan permanen yang tidak dapat dibatalkan</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-xl p-5 mb-5">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-red-900 mb-2">
                                            ⚠️ Peringatan Penting!
                                        </p>
                                        <p class="text-sm text-red-800">
                                            Menghapus akun administrator akan menghapus semua data dan tidak dapat dibatalkan. Semua sumber daya dan informasi yang terkait dengan akun Anda akan dihapus secara permanen.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 mb-5">
                                <p class="text-sm text-gray-700 font-medium">Yang akan dihapus:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Informasi profil</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Riwayat aktivitas</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Hak akses sistem</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Preferensi & pengaturan</span>
                                    </div>
                                </div>
                            </div>
                            
                            <button onclick="openDeleteModal()" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Akun Permanen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full transform transition-all scale-95 opacity-0" id="deleteModalContent">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-5 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Konfirmasi Penghapusan</h3>
                            <p class="text-red-100 text-sm">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <button onclick="closeDeleteModal()" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-2xl mb-4">
                        <svg class="w-9 h-9 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <p class="text-gray-700 mb-4">
                        Apakah Anda yakin ingin menghapus akun <strong class="text-gray-900">{{ $admin->email }}</strong>? 
                    </p>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        <p class="text-sm text-red-800 font-medium">
                            Semua data akan dihapus secara permanen dan tidak dapat dipulihkan kembali.
                        </p>
                    </div>
                </div>
                
                <form method="post" action="{{ route('admin.profile.destroy') }}" onsubmit="return confirmDelete()">
                    @csrf
                    @method('delete')
                    
                    <div class="mb-6">
                        <label for="delete_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Masukkan kata sandi untuk konfirmasi
                            </span>
                        </label>
                        <input type="password" id="delete_password" name="password" 
                               class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                               placeholder="Kata sandi Anda" required>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()" 
                                class="flex-1 px-5 py-3 border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-5 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                            Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
        
        function confirmDelete() {
            return confirm('PERINGATAN TERAKHIR!\n\nApakah Anda benar-benar yakin ingin menghapus akun ini?\n\nTindakan ini TIDAK DAPAT DIBATALKAN dan akan menghapus SEMUA DATA secara PERMANEN!');
        }
        
        // Auto-dismiss success alert after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.animate-slideDown');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                alert.style.transition = 'all 0.3s ease-out';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
        
        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>

    <style>
    /* Smooth transitions */
    * {
        transition-property: background-color, border-color, color, fill, stroke, opacity, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Focus states */
    input:focus, 
    button:focus {
        outline: none;
    }
    
    /* Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.4s ease-out;
    }
    
    .animate-slideIn {
        animation: slideIn 0.3s ease-out;
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Sticky sidebar on desktop */
    @media (min-width: 1024px) {
        .sticky {
            position: sticky;
            top: 1.5rem;
        }
    }
    
    /* Mobile optimization */
    @media (max-width: 640px) {
        .break-all {
            word-break: break-word;
        }
    }
    
    /* Gradient backgrounds */
    .bg-gradient-to-br,
    .bg-gradient-to-r {
        background-size: 100% 100%;
    }
    
    /* Hover effects with smooth elevation */
    button:not(:disabled):hover,
    a:hover {
        transform: translateY(-2px);
    }
    
    button:not(:disabled):active,
    a:active {
        transform: translateY(0);
    }

    /* Backdrop blur support */
    @supports (backdrop-filter: blur(10px)) {
        .backdrop-blur-sm {
            backdrop-filter: blur(10px);
        }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #2563eb, #7c3aed);
    }

    /* Form focus rings */
    input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Disabled state */
    button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    </style>
@endsection