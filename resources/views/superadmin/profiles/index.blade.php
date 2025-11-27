@extends('superadmin.layouts.superadmin')
@section('title', 'Profil Super Admin')
@section('content')    
    <div class="max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1.5 sm:mb-2">Profil Super Admin</h1>
                    <p class="text-xs sm:text-sm lg:text-base text-gray-600">Kelola informasi dan pengaturan akun Anda</p>
                </div>
            </div>
        </div>

        {{-- Success Alert --}}
        @if(session('status') || session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 shadow-sm flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('status') ?? session('success') }}</p>
                <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-600 hover:text-emerald-800">
                    <svg class="w-5 h-5" fill="none"-stroke" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 quanh rounded-xl p-4 shadow-sm flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sticky Sidebar --}}
            <div class="w-full lg:w-80 lg:sticky lg:top-6 lg:self-start" style="max-height: calc(100vh - 6rem);">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-purple-500 via-purple-600 to-pink-600 p-6 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                        
                        <div class="flex flex-col items-center relative z-10">
                            {{-- Avatar Section --}}
                            <div class="relative group mb-4">
                                @if($admin->avatar)
                                    <img src="{{ asset('storage/' . $admin->avatar) }}" 
                                         alt="Avatar {{ $admin->name }}"
                                         class="w-24 h-24 rounded-full object-cover border-4 border-white/30 shadow-xl">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/30 shadow-xl">
                                        <span class="text-3xl font-bold">{{ strtoupper(substr($admin->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <button onclick="openAvatarModal()"
                                    class="absolute bottom-0 right-0 w-9 h-9 bg-white text-purple-600 rounded-full shadow-lg flex items-center justify-center hover:bg-purple-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                            </div>
                            
                            <h3 class="text-xl font-bold mb-1">{{ $admin->name }}</h3>
                            <p class="text-sm opacity-90 break-all mb-3">{{ $admin->email }}</p>
                            
                            <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold border border-white/30">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Super Administrator
                            </span>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex flex-wrap gap-2 justify-center">
                            @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $admin->hasVerifiedEmail())
                                <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Terverifikasi
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1.5 bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Akses Penuh
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Status</p>
                                <p class="font-semibold text-gray-900">Super Admin</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Bergabung Sejak</p>
                                <p class="font-semibold text-gray-900">{{ $admin->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Terakhir Update</p>
                                <p class="font-semibold text-gray-900">{{ $admin->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="flex-1 space-y-6">
                {{-- Update Profile --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                    <div class="px-6 py-5 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Informasi Profil</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui detail akun Anda</p>
                    </div>
                    <div class="p-6">
                        <form method="post" action="{{ route('superadmin.profile.update') }}" class="space-y-5">
                            @csrf @method('patch')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" required>
                                @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" required>
                                @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                                @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $admin->hasVerifiedEmail())
                                    <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-amber-800">Email belum diverifikasi.</p>
                                                <button form="send-verification" class="text-sm text-amber-700 underline font-semibold hover:text-amber-900">Kirim ulang email verifikasi</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-3">
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                        @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $admin->hasVerifiedEmail())
                            <form id="send-verification" method="post" action="{{ route('superadmin.verification.send') }}" class="hidden">@csrf</form>
                        @endif
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Keamanan Akun</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui kata sandi Anda</p>
                    </div>
                    <div class="p-6">
                        <form method="post" action="{{ route('superadmin.password.update') }}" class="space-y-5">
                            @csrf @method('put')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" required class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="••••••••">
                                @error('current_password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Baru</label>
                                <input type="password" name="password" required class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="••••••••">
                                @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                                <input type="password" name="password_confirmation" required class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="••••••••">
                            </div>
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-sm font-semibold text-blue-900 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Tips Kata Sandi Kuat:
                                </p>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li>• Minimal 8 karakter</li>
                                    <li>• Gunakan huruf besar & kecil</li>
                                    <li>• Sertakan angka dan simbol</li>
                                </ul>
                            </div>
                            <div class="flex items-center justify-between pt-3">
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
                                    Perbarui Kata Sandi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Danger Zone --}}
                <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200">
                    <div class="px-6 py-5 bg-gradient-to-r from-red-50 to-rose-50 border-b border-red-200">
                        <h2 class="text-lg font-bold text-red-900">Zona Bahaya</h2>
                        <p class="text-sm text-red-700 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                    <div class="p-6">
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl p-4 mb-5">
                            <p class="text-sm font-bold text-red-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Peringatan Penting:
                            </p>
                            <ul class="text-xs text-red-700 space-y-1.5">
                                <li class="flex items-start gap-2"><span class="text-red-500 mt-0.5">•</span> Semua data profil akan dihapus permanen</li>
                                <li class="flex items-start gap-2"><span class="text-red-500 mt-0.5">•</span> Riwayat login & aktivitas akan hilang</li>
                                <li class="flex items-start gap-2"><span class="text-red-500 mt-0.5">•</span> Akses sistem dicabut selamanya</li>
                            </ul>
                        </div>
                        <button onclick="openDeleteModal()" class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:shadow-md">
                            Hapus Akun Permanen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Avatar Modal --}}
    <div id="avatarModal" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center z-50 p-4" onclick="if(event.target===this) closeAvatarModal()">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">Edit Foto Profil</h3>
                <button onclick="closeAvatarModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex flex-col items-center mb-6">
                <div id="avatarPreviewContainer" class="relative">
                    @if($admin->avatar)
                        <img id="currentAvatar" src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                    @else
                        <div id="currentAvatar" class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center border-4 border-gray-200">
                            <span class="text-4xl font-bold text-white">{{ strtoupper(substr($admin->name, 0, 2)) }}</span>
                        </div>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-3">JPG, PNG, atau GIF (Max. 2MB)</p>
            </div>

            <form method="post" action="{{ route('superadmin.profile.update') }}" enctype="multipart/form-data" id="avatarForm">
                @csrf @method('patch')
                <input type="hidden" name="name" value="{{ $admin->name }}">
                <input type="hidden" name="email" value="{{ $admin->email }}">

                <div class="mb-5">
                    <label class="block w-full">
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                        <div class="w-full px-6 py-3 border-2 border-dashed border-gray-300 rounded-xl hover:border-purple-500 transition-colors cursor-pointer text-center">
                            <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-700">Klik untuk upload foto</p>
                            <p class="text-xs text-gray-500 mt-1">atau drag & drop</p>
                        </div>
                    </label>
                    @error('avatar') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    @if($admin->avatar)
                        <button type="button" onclick="removeAvatar()" class="flex-1 px-5 py-2.5 border border-red-300 text-red-700 text-sm font-semibold rounded-xl hover:bg-red-50 transition-colors">
                            Hapus Foto
                        </button>
                    @endif
                    <button type="submit" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                        Simpan Foto
                    </button>
                </div>
            </form>

            @if($admin->avatar)
                <form id="removeAvatarForm" method="post" action="{{ route('superadmin.avatar.remove') }}" class="hidden">
                    @csrf @method('delete')
                </form>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center z-50 p-4" onclick="if(event.target===this) closeDeleteModal()">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" onclick="event.stopPropagation()">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <div class="absolute inset-0 bg-red-500 rounded-full animate-ping opacity-20"></div>
                    <svg class="w-8 h-8 text-red-600 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Akun Super Admin?</h3>
                <p class="text-sm text-gray-600">
                    Akun <strong class="text-gray-900">{{ $admin->email }}</strong> akan dihapus <span class="text-red-600 font-bold">selamanya</span>.
                </p>
            </div>

            <form method="post" action="{{ route('superadmin.profile.destroy') }}" onsubmit="return confirmDelete()">
                @csrf @method('delete')
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Masukkan kata sandi untuk konfirmasi</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Kata sandi Anda">
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-5 py-2.5 border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:shadow-md">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Avatar Modal
        function openAvatarModal() {
            document.getElementById('avatarModal').classList.remove('hidden');
            document.getElementById('avatarModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeAvatarModal() {
            document.getElementById('avatarModal').classList.add('hidden');
            document.getElementById('avatarModal').classList.remove('flex');
            document.body.style.overflow = '';
            document.getElementById('avatarInput').value = '';
        }
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const fileSize = input.files[0].size / 1024 / 1024;
                if (fileSize > 2) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('currentAvatar').outerHTML = `<img id="currentAvatar" src="${e.target.result}" alt="Preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function removeAvatar() {
            if (confirm('Yakin ingin menghapus foto profil?')) {
                document.getElementById('removeAvatarForm').submit();
            }
        }

        // Delete Modal
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            document.body.style.overflow = '';
        }
        function confirmDelete() {
            return confirm('PERINGATAN TERAKHIR!\n\nAkun Super Admin akan dihapus PERMANEN.\nSemua data dan akses akan hilang selamanya.\n\nApakah Anda benar-benar yakin?');
        }

        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.animate-fade-in').forEach(el => {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            });
        }, 5000);

        // ESC to close modals
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeAvatarModal();
                closeDeleteModal();
            }
        });

        // Drag & drop untuk avatar
        const dropZone = document.querySelector('label[for="avatarInput"] div');
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
                dropZone.addEventListener(event, e => { e.preventDefault(); e.stopPropagation(); });
            });
            ['dragenter', 'dragover'].forEach(event => {
                dropZone.addEventListener(event, () => dropZone.classList.add('border-purple-500', 'bg-purple-50'));
            });
            ['dragleave', 'drop'].forEach(event => {
                dropZone.addEventListener(event, () => dropZone.classList.remove('border-purple-500', 'bg-purple-50'));
            });
            dropZone.addEventListener('drop', e => {
                const files = e.dataTransfer.files;
                if (files.length) {
                    document.getElementById('avatarInput').files = files;
                    previewAvatar(document.getElementById('avatarInput'));
                }
            });
        }
    </script>

    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }
        .animate-ping { animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite; }
        @keyframes ping { 75%, 100% { transform: scale(2); opacity: 0; } }
        input:focus, button:focus-visible { outline: none; }
        .rounded-xl { border-radius: 1rem; }
        .rounded-2xl { border-radius: 1.25rem; }
    </style>
@endsection