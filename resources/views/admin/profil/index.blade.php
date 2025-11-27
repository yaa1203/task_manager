@extends('admin.layouts.admin')
@section('title', 'Profil Admin')
@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1.5 sm:mb-2">Profil Admin</h1>
                <p class="text-xs sm:text-sm lg:text-base text-gray-600">Kelola informasi dan pengaturan akun Anda</p>
            </div>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('status') || session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 shadow-sm flex items-center gap-3 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm font-medium">{{ session('status') ?? session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    {{-- Error Alert --}}
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 shadow-sm flex items-center gap-3 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm font-medium">{{ session('error') }}</p>
        <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sticky Sidebar --}}
        <div class="w-full lg:w-80 lg:sticky lg:top-6 lg:self-start" style="max-height: calc(100vh - 6rem);">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white">
                    <div class="flex flex-col items-center">
                        {{-- Avatar Section --}}
                        <div class="relative group mb-3">
                            @if($admin->avatar)
                            <img src="{{ asset('storage/' . $admin->avatar) }}"
                                alt="Avatar {{ $admin->name }}"
                                class="w-20 h-20 rounded-full object-cover border-4 border-white/30 shadow-lg">
                            @else
                            <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/30">
                                <span class="text-2xl font-bold">{{ strtoupper(substr($admin->name, 0, 2)) }}</span>
                            </div>
                            @endif

                            {{-- Edit Avatar Button --}}

                            <button onclick="openAvatarModal()"
                                class="absolute bottom-0 right-0 w-9 h-9 bg-white text-blue-600 rounded-full shadow-lg flex items-center justify-center hover:bg-blue-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>

                        <h3 class="text-xl font-bold">{{ $admin->name }}</h3>
                        <p class="text-sm opacity-90 break-all">{{ $admin->email }}</p>
                    </div>
                </div>
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex flex-wrap gap-2 justify-center">
                        @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $admin->hasVerifiedEmail())
                        <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Terverifikasi
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Administrator
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kategori</p>
                            <p class="font-semibold text-gray-900">{{ $admin->category->name ?? 'Super Admin' }}</p>
                        </div>
                    </div>

                    {{-- WhatsApp (TAMBAHAN BARU) --}}
                    @if($admin->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $admin->whatsapp) }}"
                        target="_blank"
                        class="flex items-center gap-4 hover:bg-green-50 p-2 -mx-2 rounded-xl transition-colors group">
                        <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 transition-colors">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500">WhatsApp</p>
                            <p class="font-semibold text-gray-900 truncate group-hover:text-green-600 transition-colors">{{ $admin->whatsapp }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                    @endif

                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Bergabung Sejak</p>
                            <p class="font-semibold text-gray-900">{{ $admin->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Profil</h2>
                    <p class="text-sm text-gray-600 mt-1">Perbarui detail akun Anda</p>
                </div>
                <div class="p-6">
                    <form method="post" action="{{ route('admin.profile.update') }}" class="space-y-5">
                        @csrf @method('patch')

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="Masukkan nama lengkap" required>
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="admin@example.com" required>
                            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $admin->hasVerifiedEmail())
                            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-amber-800">Email belum diverifikasi.</p>
                                        <button form="send-verification" class="text-sm text-amber-700 underline font-semibold hover:text-amber-900">Kirim ulang email verifikasi</button>
                                        @if (session('status') === 'verification-link-sent')
                                        <p class="mt-1 text-sm font-medium text-green-700">Tautan verifikasi dikirim!</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- WhatsApp (TAMBAHAN BARU) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp</label>
                            <div class="relative">
                                <!-- Icon WhatsApp Hijau Resmi -->
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-[#25D366]" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                        <path d="M20.52 3.48A11.962 11.962 0 0012 0C5.373 0 .8 5.373.8 12c0 2.13.56 4.118 1.54 5.84L.8 24l6.38-1.68c1.67.92 3.54 1.44 5.46 1.44 6.627 0 12-5.373 12-12 0-3.19-1.24-6.19-3.48-8.44zM12 21.6c-1.78 0-3.51-.49-5.01-1.41l-.36-.21-3.72.98.99-3.62-.23-.37a9.58 9.58 0 01-1.46-5.14c0-5.3 4.31-9.6 9.62-9.6 2.57 0 4.99 1 6.8 2.82A9.577 9.577 0 0121.2 12c0 5.3-4.31 9.6-9.62 9.6z" />
                                    </svg>
                                </div>

                                <!-- Prefix +62 tetap -->
                                <div class="absolute inset-y-0 left-10 pl-3 flex items-center pointer-events-none text-gray-700 font-medium z-10">
                                    +62
                                </div>

                                <!-- Input nomor saja (user isi di sini) -->
                                <input
                                    type="tel"
                                    id="whatsapp_input"
                                    value="{{ old('whatsapp', $admin->whatsapp ?? '') ? substr(preg_replace('/\D/', '', old('whatsapp', $admin->whatsapp ?? '')), (str_starts_with(preg_replace('/\D/', '', old('whatsapp', $admin->whatsapp ?? '')), '62') ? 2 : (str_starts_with(preg_replace('/\D/', '', old('whatsapp', $admin->whatsapp ?? '')), '0') ? 1 : 0))) : '' }}"
                                    class="block w-full pl-24 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                                    placeholder="81234567890"
                                    required
                                    inputmode="numeric"
                                    maxlength="13">

                                <!-- Hidden field: ini yang benar-benar dikirim -->
                                <input type="hidden" name="whatsapp" id="whatsapp_full" value="">
                            </div>

                            <p class="mt-1 text-xs text-gray-500">Tanpa 0 di depan → contoh: 81234567890</p>

                            @error('whatsapp')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-3">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                                Simpan Perubahan
                            </button>
                            @if (session('status') === 'profile-updated')
                            <div class="flex items-center gap-2 text-green-700 text-sm font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Profil diperbarui!
                            </div>
                            @endif
                        </div>
                    </form>
                    @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $admin->hasVerifiedEmail())
                    <form id="send-verification" method="post" action="{{ route('admin.verification.send') }}" class="hidden">@csrf</form>
                    @endif
                </div>
            </div>

            {{-- Update Password --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Keamanan Akun</h2>
                    <p class="text-sm text-gray-600 mt-1">Perbarui kata sandi Anda</p>
                </div>
                <div class="p-6">
                    <form method="post" action="{{ route('admin.password.update') }}" class="space-y-5">
                        @csrf @method('put')
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" required autocomplete="current-password" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="••••••••">
                            @error('current_password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Baru</label>
                            <input type="password" name="password" required autocomplete="new-password" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="••••••••">
                            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" required autocomplete="new-password" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="••••••••">
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-sm font-semibold text-blue-900 mb-2">Tips Kata Sandi Kuat:</p>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• Minimal 8 karakter</li>
                                <li>• Gunakan huruf besar & kecil</li>
                                <li>• Sertakan angka dan simbol</li>
                            </ul>
                        </div>
                        <div class="flex items-center justify-between pt-3">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">Perbarui Kata Sandi</button>
                            @if (session('status') === 'password-updated')
                            <div class="flex items-center gap-2 text-green-700 text-sm font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Kata sandi diperbarui!
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200">
                <div class="px-6 py-5 bg-red-50 border-b border-red-200">
                    <h2 class="text-lg font-semibold text-red-900">Zona Bahaya</h2>
                    <p class="text-sm text-red-700 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                </div>
                <div class="p-6">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
                        <p class="text-sm font-medium text-red-800 mb-2">Peringatan:</p>
                        <ul class="text-xs text-red-700 space-y-1">
                            <li>• Semua data profil akan dihapus permanen</li>
                            <li>• Riwayat login & aktivitas hilang</li>
                            <li>• Akses sistem dicabut selamanya</li>
                        </ul>
                    </div>
                    <button onclick="openDeleteModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">Hapus Akun Permanen</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Avatar Modal --}}
<div id="avatarModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4" onclick="if(event.target===this) closeAvatarModal()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900">Edit Foto Profil</h3>
            <button onclick="closeAvatarModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Current Avatar Preview --}}
        <div class="flex flex-col items-center mb-6">
            <div id="avatarPreviewContainer" class="relative">
                @if($admin->avatar)
                <img id="currentAvatar" src="{{ asset('storage/' . $admin->avatar) }}"
                    alt="Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                @else
                <div id="currentAvatar" class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center border-4 border-gray-200">
                    <span class="text-4xl font-bold text-white">{{ strtoupper(substr($admin->name, 0, 2)) }}</span>
                </div>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-3">JPG, PNG, atau GIF (Max. 2MB)</p>
        </div>

        {{-- Upload Form --}}
        <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" id="avatarForm">
            @csrf @method('patch')
            <input type="hidden" name="name" value="{{ $admin->name }}">
            <input type="hidden" name="email" value="{{ $admin->email }}">
            <input type="hidden" name="whatsapp" value="{{ $admin->whatsapp }}"> {{-- TAMBAHKAN INI --}}

            <div class="mb-5">
                <label class="block w-full">
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                    <div class="w-full px-6 py-3 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-500 transition-colors cursor-pointer text-center">
                        <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
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
                <button type="submit" class="flex-1 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    Simpan Foto
                </button>
            </div>
        </form>

        {{-- Remove Avatar Form --}}
        @if($admin->avatar)
        <form id="removeAvatarForm" method="post" action="{{ route('admin.avatar.remove') }}" class="hidden">
            @csrf @method('delete')
        </form>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4" onclick="if(event.target===this) closeDeleteModal()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" onclick="event.stopPropagation()">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Hapus Akun?</h3>
            <p class="text-sm text-gray-600 mt-2">
                Akun <strong class="text-gray-900">{{ $admin->email }}</strong> akan dihapus <span class="text-red-600 font-semibold">selamanya</span>.
            </p>
        </div>

        <form method="post" action="{{ route('admin.profile.destroy') }}" onsubmit="return confirmDelete()">
            @csrf @method('delete')
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Masukkan kata sandi untuk konfirmasi</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Kata sandi Anda">
                @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    Hapus Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('whatsapp_input');
        const hidden = document.getElementById('whatsapp_full');
        const currentWhatsapp = @json(old('whatsapp', $admin -> whatsapp ?? ''));

        const updateWhatsapp = () => {
            if (!input || !hidden) return;
            let num = input.value.replace(/\D/g, '');
            if (num.startsWith('0')) num = num.slice(1);
            hidden.value = num ? '+62' + num : '';
        };

        if (input && hidden) {
            input.addEventListener('input', updateWhatsapp);
            document.querySelector('form')?.addEventListener('submit', updateWhatsapp);

            // Isi otomatis dari database atau old input
            if (currentWhatsapp) {
                let num = currentWhatsapp.toString().replace(/\D/g, '');
                if (num.startsWith('62')) num = num.substring(2);
                else if (num.startsWith('0')) num = num.substring(1);
                input.value = num;
                updateWhatsapp();
            }
        }
    });
    // Avatar Modal Functions
    function openAvatarModal() {
        document.getElementById('avatarModal').classList.remove('hidden');
        document.getElementById('avatarModal').classList.add('flex');
    }

    function closeAvatarModal() {
        document.getElementById('avatarModal').classList.add('hidden');
        document.getElementById('avatarModal').classList.remove('flex');
        // Reset preview
        document.getElementById('avatarInput').value = '';
    }

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const fileSize = input.files[0].size / 1024 / 1024; // in MB

            if (fileSize > 2) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                input.value = '';
                return;
            }

            reader.onload = function(e) {
                const preview = document.getElementById('currentAvatar');
                preview.outerHTML = `<img id="currentAvatar" src="${e.target.result}" alt="Preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">`;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeAvatar() {
        if (confirm('Yakin ingin menghapus foto profil?')) {
            document.getElementById('removeAvatarForm').submit();
        }
    }

    // Delete Modal Functions
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }

    function confirmDelete() {
        return confirm('PERINGATAN!\n\nAkun akan dihapus PERMANEN.\nTindakan ini TIDAK DAPAT dibatalkan!');
    }

    // Auto dismiss alert
    setTimeout(() => {
        const alert = document.querySelector('.animate-fade-in');
        if (alert) {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }
    }, 5000);

    // ESC to close modals
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeAvatarModal();
        }
    });

    // Drag and drop support
    const dropZone = document.querySelector('label[for="avatarInput"] div');
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('avatarInput').files = files;
            previewAvatar(document.getElementById('avatarInput'));
        }, false);
    }
</script>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    input:focus {
        outline: none;
    }

    .rounded-xl {
        border-radius: 1rem;
    }

    .rounded-2xl {
        border-radius: 1.25rem;
    }
</style>
@endsection