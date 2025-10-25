<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">⚙️ Pengaturan Profil</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola informasi akun dan preferensi Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            {{-- Alert Sukses --}}
            @if(session('status') || session('success'))
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm sm:text-base text-green-800 font-medium">
                        @if(session('status'))
                            {{ session('status') }}
                        @else
                            {{ session('success') }}
                        @endif
                    </span>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                {{-- Sidebar --}}
                <div class="space-y-4">
                    {{-- Kartu Profil Pengguna --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-blue-100 flex items-center justify-center mb-3 sm:mb-4">
                                <span class="text-2xl sm:text-3xl font-bold text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">{{ $user->name }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-3 break-all">{{ $user->email }}</p>
                            
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail())
                                <div class="inline-flex items-center gap-1.5 bg-green-50 px-3 py-1.5 rounded-full border border-green-200">
                                    <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-green-700">Terverifikasi</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Kartu Kategori --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-50 p-2.5 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-600 font-medium mb-0.5">Kategori</p>
                                <h3 class="text-sm font-bold text-gray-900 truncate">
                                    {{ $user->category->name ?? 'Tidak ada kategori' }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Area Konten Utama --}}
                <div class="lg:col-span-2 space-y-4">
                    {{-- Kartu Informasi Profil --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="border-b border-gray-200 px-4 sm:px-6 py-3 sm:py-4">
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Informasi Profil</span>
                            </h2>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                                @csrf
                                @method('patch')
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="John Doe" required>
                                    @error('name')
                                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                                    <input type="email" id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="john@example.com" required>
                                    @error('email')
                                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    
                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <p class="text-xs sm:text-sm text-yellow-800">
                                                Alamat email Anda belum diverifikasi.
                                                <button form="send-verification" class="underline font-medium hover:text-yellow-900">
                                                    Klik di sini untuk mengirim ulang email verifikasi.
                                                </button>
                                            </p>
                                            @if (session('status') === 'verification-link-sent')
                                                <p class="mt-2 text-xs sm:text-sm font-medium text-green-600">
                                                    Tautan verifikasi baru telah dikirim ke alamat email Anda.
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-between pt-3">
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 sm:py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                    
                                    @if (session('status') === 'profile-updated')
                                        <div class="flex items-center gap-2 text-green-600 bg-green-50 px-3 py-1.5 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm font-medium">Tersimpan!</span>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Kartu Perbarui Kata Sandi --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="border-b border-gray-200 px-4 sm:px-6 py-3 sm:py-4">
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Perbarui Kata Sandi</span>
                            </h2>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                                @csrf
                                @method('put')
                                
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi Saat Ini</label>
                                    <input type="password" id="current_password" name="current_password" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="Masukkan kata sandi saat ini" required>
                                    @error('current_password')
                                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi Baru</label>
                                    <input type="password" id="password" name="password" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="Masukkan kata sandi baru" required>
                                    @error('password')
                                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="Konfirmasi kata sandi baru" required>
                                    @error('password_confirmation')
                                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="flex items-center justify-between pt-3">
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 sm:py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Perbarui Kata Sandi
                                    </button>
                                    
                                    @if (session('status') === 'password-updated')
                                        <div class="flex items-center gap-2 text-green-600 bg-green-50 px-3 py-1.5 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm font-medium">Diperbarui!</span>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Kartu Hapus Akun --}}
                    <div class="bg-white rounded-lg shadow-sm border border-red-200 overflow-hidden">
                        <div class="border-b border-red-200 bg-red-50 px-4 sm:px-6 py-3 sm:py-4">
                            <h2 class="text-base sm:text-lg font-bold text-red-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Zona Bahaya</span>
                            </h2>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <p class="text-xs sm:text-sm text-gray-600 mb-4">
                                Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                            </p>
                            
                            <button onclick="openDeleteModal()" 
                                    class="inline-flex items-center gap-2 px-4 py-2 sm:py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-4 sm:p-6 transform transition-all scale-95 opacity-0" id="deleteModalContent">
            <div class="text-center mb-4 sm:mb-6">
                <div class="mx-auto flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-red-100 mb-3 sm:mb-4">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Hapus Akun</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            
            <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirmDelete()">
                @csrf
                @method('delete')
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Masukkan kata sandi Anda untuk mengonfirmasi</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                           placeholder="Masukkan kata sandi" required>
                    @error('password')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex gap-2 sm:gap-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        Hapus Akun
                    </button>
                </div>
            </form>
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
            return confirm('Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.');
        }
        
        // Auto-dismiss pesan sukses setelah 5 detik
        setTimeout(function() {
            const alert = document.querySelector('.bg-green-50');
            if (alert && alert.parentElement) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.3s';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
        
        // Tutup modal saat klik di luar
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>

    <style>
    /* Smooth transitions */
    * {
        transition-property: background-color, border-color, color, fill, stroke, opacity, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    
    /* Focus states */
    input:focus, button:focus {
        outline: none;
    }
    
    /* Hover effects */
    .hover\:shadow-md:hover {
        transform: translateY(-1px);
    }
    
    /* Mobile optimization */
    @media (max-width: 640px) {
        .break-all {
            word-break: break-word;
        }
    }
    </style>
</x-app-layout>