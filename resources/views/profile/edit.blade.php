<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Profil Saya</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola informasi dan pengaturan akun Anda dengan aman</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if(session('status') || session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 shadow-sm flex items-center gap-3 animate-fade-in">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('status') ?? session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="mb-8 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 shadow-sm flex items-center gap-3 animate-fade-in">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="space-y-8">
                {{-- Profile Header Card with Avatar --}}
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-6 sm:p-8 text-white">
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            {{-- Avatar Section --}}
                            <div class="relative group">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="Avatar {{ $user->name }}"
                                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-white/40 shadow-xl">
                                @else
                                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-white/25 backdrop-blur-md flex items-center justify-center text-3xl sm:text-4xl font-bold border-4 border-white/40 shadow-xl">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 rounded-full bg-white/20 animate-pulse"></div>
                                
                                {{-- Edit Avatar Button --}}
                                <button onclick="openAvatarModal()" 
                                        class="absolute bottom-0 right-0 w-9 h-9 bg-white text-blue-600 rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-blue-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-2xl sm:text-3xl font-bold">{{ $user->name }}</h3>
                                <p class="text-sm sm:text-base opacity-90 break-all mt-1">{{ $user->email }}</p>
                                <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail())
                                        <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur px-3 py-1.5 rounded-full text-xs font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Terverifikasi
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur px-3 py-1.5 rounded-full text-xs font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $user->category->name ?? 'Tidak ada kategori' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Section: Two Columns --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                    {{-- Update Profile --}}
                    <div class="bg-white rounded-3xl shadow-md border border-gray-100 p-6 sm:p-7">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Informasi Profil</h3>
                            <p class="text-sm text-gray-600 mt-1">Perbarui nama dan email akun Anda</p>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                            @csrf @method('patch')

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="Masukkan nama lengkap" required>
                                @error('name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="user@example.com" required>
                                @error('email') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-amber-800">Email belum diverifikasi.</p>
                                                <button form="send-verification" class="text-sm text-amber-700 underline font-semibold hover:text-amber-900 mt-1">
                                                    Kirim ulang verifikasi
                                                </button>
                                                @if (session('status') === 'verification-link-sent')
                                                    <p class="mt-1 text-xs font-medium text-green-700">Tautan verifikasi telah dikirim!</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-3">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm">
                                    Simpan Perubahan
                                </button>
                                @if (session('status') === 'profile-updated')
                                    <div class="flex items-center gap-2 text-green-700 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Tersimpan
                                    </div>
                                @endif
                            </div>
                        </form>

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">@csrf</form>
                        @endif
                    </div>

                    {{-- Update Password --}}
                    <div class="bg-white rounded-3xl shadow-md border border-gray-100 p-6 sm:p-7">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Keamanan Akun</h3>
                            <p class="text-sm text-gray-600 mt-1">Ganti kata sandi secara berkala</p>
                        </div>

                        <form method="post" action="{{ route('profile.password.update') }}" class="space-y-5">
                            @csrf @method('put')

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" required autocomplete="current-password"
                                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="••••••••">
                                @error('current_password') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Baru</label>
                                <input type="password" name="password" required autocomplete="new-password"
                                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="••••••••">
                                @error('password') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                                <input type="password" name="password_confirmation" required autocomplete="new-password"
                                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="••••••••">
                            </div>

                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-xs font-bold text-blue-900 mb-2">Tips Keamanan:</p>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li>• Gunakan minimal 8 karakter</li>
                                    <li>• Kombinasikan huruf besar & kecil</li>
                                    <li>• Tambahkan angka dan simbol (!@#)</li>
                                    <li>• Jangan gunakan data pribadi</li>
                                </ul>
                            </div>

                            <div class="flex items-center justify-between pt-3">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm">
                                    Perbarui Kata Sandi
                                </button>
                                @if (session('status') === 'password-updated')
                                    <div class="flex items-center gap-2 text-green-700 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Tersimpan
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Danger Zone --}}
                <div class="bg-white rounded-3xl shadow-lg border-2 border-red-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 px-6 py-5 border-b border-red-200">
                        <h3 class="text-xl font-bold text-red-900">Zona Bahaya</h3>
                        <p class="text-sm text-red-700 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                    <div class="p-6 sm:p-7">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                            <div class="flex-1">
                                <p class="text-base font-semibold text-red-800 mb-2">Hapus Akun Secara Permanen</p>
                                <ul class="text-sm text-red-700 space-y-1">
                                    <li>• Semua data profil akan dihapus selamanya</li>
                                    <li>• Riwayat login & aktivitas hilang permanen</li>
                                    <li>• Akses ke sistem dicabut total</li>
                                </ul>
                            </div>
                            <button onclick="openDeleteModal()"
                                    class="w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md whitespace-nowrap">
                                Hapus Akun Saya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Avatar Modal --}}
    <div id="avatarModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4" onclick="if(event.target===this) closeAvatarModal()">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-gray-900">Edit Foto Profil</h3>
                <button onclick="closeAvatarModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Current Avatar Preview --}}
            <div class="flex flex-col items-center mb-6">
                <div id="avatarPreviewContainer" class="relative">
                    @if($user->avatar)
                        <img id="currentAvatar" src="{{ asset('storage/' . $user->avatar) }}" 
                             alt="Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                    @else
                        <div id="currentAvatar" class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center border-4 border-gray-200">
                            <span class="text-4xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-3">JPG, PNG, atau GIF (Max. 2MB)</p>
            </div>

            {{-- Upload Form --}}
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="avatarForm">
                @csrf @method('patch')
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">
                
                <div class="mb-5">
                    <label class="block w-full">
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                        <div class="w-full px-6 py-3 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-500 transition-colors cursor-pointer text-center">
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
                    @if($user->avatar)
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
            @if($user->avatar)
                <form id="removeAvatarForm" method="post" action="{{ route('profile.avatar.remove') }}" class="hidden">
                    @csrf @method('delete')
                </form>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Yakin ingin menghapus akun?</h3>
                <p class="text-sm text-gray-600 mt-2">Tindakan ini <strong>tidak dapat dibatalkan</strong>. Semua data akan hilang permanen.</p>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf @method('delete')
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi dengan kata sandi</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                           placeholder="Masukkan kata sandi Anda">
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md">
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        // Avatar Modal Functions
        function openAvatarModal() {
            document.getElementById('avatarModal').classList.remove('hidden');
            document.getElementById('avatarModal').classList.add('flex');
        }
        
        function closeAvatarModal() {
            document.getElementById('avatarModal').classList.add('hidden');
            document.getElementById('avatarModal').classList.remove('flex');
            document.getElementById('avatarInput').value = '';
        }
        
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const fileSize = input.files[0].size / 1024 / 1024;
                
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

        // Close on backdrop click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('deleteModal').classList.contains('hidden')) {
                    closeDeleteModal();
                } if (!document.getElementById('avatarModal').classList.contains('hidden')) {
                    closeAvatarModal();
                }
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

        // Auto dismiss alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.animate-fade-in');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }
    </style>
</x-app-layout>