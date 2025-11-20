<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('my-workspaces.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-all duration-200 p-2 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1.5">Buat Ruang Kerja Pribadi</h2>
                <p class="text-xs sm:text-sm lg:text-base text-gray-600">Kelola tugas pribadi Anda di satu tempat</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <form action="{{ route('my-workspaces.store-personal') }}" method="POST" id="personalWorkspaceForm">
                            @csrf
                            
                            <div class="p-6 sm:p-8 space-y-6">
                                
                                <!-- Nama Workspace -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Nama Ruang Kerja <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base transition-all duration-200 @error('name') border-red-500 @enderror"
                                           placeholder="Contoh: Tugas Pribadi Saya"
                                           required>
                                    @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pilih Ikon -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                                        Ikon <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 max-w-md">
                                        @php
                                        $icons = [
                                            'folder' => '<svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                            'briefcase' => '<svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                            'chart' => '<svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                            'target' => '<svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                            'cog' => '<svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                            'clipboard' => '<svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                                        ];
                                        @endphp

                                        @foreach($icons as $iconName => $iconSvg)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" 
                                                   name="icon" 
                                                   value="{{ $iconName }}" 
                                                   class="peer sr-only icon-radio"
                                                   {{ old('icon', 'folder') === $iconName ? 'checked' : '' }}
                                                   required>
                                            <div class="w-full aspect-square flex items-center justify-center border-2 border-gray-300 rounded-lg peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-gray-400 transition-all duration-200 bg-white p-3 text-gray-600 peer-checked:text-indigo-600">
                                                {!! $iconSvg !!}
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('icon')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pilih Warna -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                                        Warna Tema <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex flex-wrap gap-3 max-w-md">
                                        @php
                                        $professionalColors = [
                                            '#6366f1' => 'Indigo',
                                            '#3b82f6' => 'Biru',
                                            '#8b5cf6' => 'Ungu',
                                            '#ec4899' => 'Merah Muda',
                                            '#ef4444' => 'Merah',
                                            '#f59e0b' => 'Kuning',
                                            '#10b981' => 'Hijau',
                                            '#06b6d4' => 'Cyan',
                                            '#64748b' => 'Abu-abu',
                                            '#78716c' => 'Abu-abu Tua'
                                        ];
                                        @endphp
                                        @foreach($professionalColors as $colorValue => $colorName)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" 
                                                   name="color" 
                                                   value="{{ $colorValue }}" 
                                                   class="peer sr-only color-radio"
                                                   {{ old('color', '#6366f1') === $colorValue ? 'checked' : '' }}
                                                   required>
                                            <div class="w-12 h-12 rounded-lg transition-all duration-200 peer-checked:ring-2 peer-checked:ring-offset-2 border-2 border-transparent peer-checked:border-white hover:scale-110"
                                                 style="background-color: {{ $colorValue }}; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                            </div>
                                            <div class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                                <span class="text-xs bg-gray-900 text-white px-2 py-1 rounded">{{ $colorName }}</span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('color')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            <!-- Aksi Footer -->
                            <div class="border-t border-gray-200 px-6 py-4 sm:px-8 bg-gray-50">
                                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                    <a href="{{ route('my-workspaces.index') }}" 
                                       class="w-full sm:w-auto text-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
                                        Batal
                                    </a>
                                    <button type="submit"
                                            class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-all duration-200 font-medium shadow-md hover:shadow-lg">
                                        Buat Ruang Kerja
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="lg:col-span-1">
                    <div class="sticky top-6">
                        <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border border-gray-200">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Pratinjau
                            </h3>
                            
                            <!-- Preview Card -->
                            <div id="previewCard" class="group block bg-white rounded-lg sm:rounded-xl border border-gray-200 hover:border-indigo-400 transition-all duration-200 overflow-hidden hover:shadow-lg">
                                
                                <!-- Header Preview -->
                                <div class="p-4 sm:p-5 border-l-4" id="previewHeader" style="border-color: #6366f1; background-color: #6366f108;">
                                    <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center flex-shrink-0 bg-white border border-gray-200 text-gray-700 group-hover:border-indigo-300 transition-all duration-200" id="previewIconContainer">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="previewIcon">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                <h4 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition break-words" id="previewName">
                                                    Nama Ruang Kerja
                                                </h4>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 flex-shrink-0">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Pribadi
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Statistik Preview -->
                                    <div class="flex items-center gap-2 sm:gap-3 text-xs flex-wrap">
                                        <div class="flex items-center gap-1.5 text-gray-700 bg-white/80 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg border border-gray-200">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <span class="font-semibold">0</span>
                                            <span class="hidden sm:inline">Tugas</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Preview -->
                                <div class="px-4 sm:px-5 py-2.5 sm:py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs text-gray-500 flex items-center gap-1.5">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="hidden sm:inline">Baru saja</span>
                                        <span class="sm:hidden">Baru</span>
                                    </span>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 mt-4 text-center">
                                Pratinjau ruang kerja pribadi Anda
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const iconRadios = document.querySelectorAll('.icon-radio');
        const colorRadios = document.querySelectorAll('.color-radio');
        
        const previewHeader = document.getElementById('previewHeader');
        const previewIcon = document.getElementById('previewIcon');
        const previewName = document.getElementById('previewName');

        const iconSvgs = {
            'folder': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>',
            'briefcase': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            'chart': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
            'target': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'cog': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
            'clipboard': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'
        };
        // Update preview saat nama berubah
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Nama Ruang Kerja';
        });

        // Update preview saat icon dipilih
        iconRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    previewIcon.innerHTML = iconSvgs[this.value];
                }
            });
        });

        // Update preview saat warna dipilih
        colorRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    previewHeader.style.borderColor = this.value;
                    previewHeader.style.backgroundColor = this.value + '08';
                }
            });
        });

        // Set default jika ada old input
        const checkedIcon = document.querySelector('.icon-radio:checked');
        const checkedColor = document.querySelector('.color-radio:checked');
        
        if (checkedIcon) {
            previewIcon.innerHTML = iconSvgs[checkedIcon.value];
        }
        
        if (checkedColor) {
            previewHeader.style.borderColor = checkedColor.value;
            previewHeader.style.backgroundColor = checkedColor.value + '08';
        }

        // Update nama jika ada old input
        if (nameInput.value) {
            previewName.textContent = nameInput.value;
        }
    });
    </script>

    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Better touch feedback on mobile */
    @media (max-width: 640px) {
        button:active, a:active {
            transform: scale(0.97);
            transition: transform 0.1s;
        }
    }

    /* Smooth transitions */
    * {
        -webkit-tap-highlight-color: transparent;
    }

    a, button {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    </style>
</x-app-layout>