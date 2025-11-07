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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('status') ?? session('success') }}</p>
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
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
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mb-3 border-4 border-white/30">
                                <span class="text-2xl font-bold">{{ strtoupper(substr($admin->name, 0, 2)) }}</span>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Terverifikasi
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Administrator
                            </span>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Kategori</p>
                                <p class="font-semibold text-gray-900">{{ $admin->category->name ?? 'Super Admin' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
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
                            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
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
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Profil</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui detail akun Anda</p>
                    </div>
                    <div class="p-6">
                        <form method="post" action="{{ route('admin.profile.update') }}" class="space-y-5">
                            @csrf @method('patch')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Masukkan nama lengkap" required>
                                @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="admin@example.com" required>
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
                                                @if (session('status') === 'verification-link-sent')
                                                    <p class="mt-1 text-sm font-medium text-green-700">Tautan verifikasi dikirim!</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center justify-between pt-3">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">Simpan Perubahan</button>
                                @if (session('status') === 'profile-updated')
                                    <div class="flex items-center gap-2 text-green-700 text-sm font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4" onclick="if(event.target===this) closeDeleteModal()">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" onclick="event.stopPropagation()">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
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

        // ESC to close
        document.addEventListener('keydown', e => e.key === 'Escape' && closeDeleteModal());
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }
        input:focus { outline: none; }
        .rounded-xl { border-radius: 1rem; }
        .rounded-2xl { border-radius: 1.25rem; }
    </style>
@endsection