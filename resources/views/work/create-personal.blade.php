<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('my-workspaces.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Buat Ruang Kerja Pribadi</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola tugas pribadi Anda di satu tempat</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('my-workspaces.store-personal') }}" method="POST" id="personalWorkspaceForm">
                        @csrf

                        {{-- Nama Workspace --}}
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Ruang Kerja <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                                   placeholder="Contoh: Tugas Pribadi Saya"
                                   required>
                            @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror"
                                      placeholder="Deskripsi singkat tentang ruang kerja ini...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pilih Ikon --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Pilih Ikon <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                                @php
                                $icons = [
                                    'folder' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                    'briefcase' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                    'chart' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                    'target' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                    'cog' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                    'clipboard' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                                ];
                                @endphp

                                @foreach($icons as $iconName => $iconSvg)
                                <label class="icon-option cursor-pointer">
                                    <input type="radio" 
                                           name="icon" 
                                           value="{{ $iconName }}" 
                                           class="hidden icon-radio"
                                           {{ old('icon') == $iconName ? 'checked' : '' }}
                                           required>
                                    <div class="icon-box flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-lg transition-all hover:border-indigo-300 hover:bg-indigo-50">
                                        <div class="text-gray-600">
                                            {!! $iconSvg !!}
                                        </div>
                                        <span class="text-xs mt-2 text-gray-600 capitalize">{{ $iconName }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('icon')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pilih Warna --}}
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Pilih Warna <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-5 sm:grid-cols-10 gap-3">
                                @php
                                $colors = [
                                    '#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#ef4444',
                                    '#f97316', '#f59e0b', '#eab308', '#84cc16', '#22c55e',
                                    '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6',
                                    '#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#ec4899'
                                ];
                                @endphp

                                @foreach($colors as $color)
                                <label class="color-option cursor-pointer">
                                    <input type="radio" 
                                           name="color" 
                                           value="{{ $color }}" 
                                           class="hidden color-radio"
                                           {{ old('color') == $color ? 'checked' : '' }}
                                           required>
                                    <div class="color-box w-10 h-10 rounded-lg border-2 border-gray-200 transition-all hover:scale-110" 
                                         style="background-color: {{ $color }};">
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('color')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Preview --}}
                        <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Preview</h4>
                            <div id="preview" class="bg-white rounded-lg shadow-sm p-5 border border-gray-200">
                                <div class="flex items-start gap-3">
                                    <div id="previewIcon" class="w-12 h-12 rounded-lg flex items-center justify-center bg-gray-100 text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 id="previewName" class="font-bold text-gray-900">Tugas Pribadi Saya</h3>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                                Pribadi
                                            </span>
                                        </div>
                                        <p id="previewDesc" class="text-sm text-gray-600">Deskripsi ruang kerja akan muncul di sini</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-3">
                            <button type="submit" 
                                    class="flex-1 sm:flex-none px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-all">
                                Buat Ruang Kerja
                            </button>
                            <a href="{{ route('my-workspaces.index') }}" 
                               class="flex-1 sm:flex-none px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
    .icon-radio:checked + .icon-box {
        border-color: #6366f1;
        background-color: #eef2ff;
    }

    .icon-radio:checked + .icon-box svg {
        color: #6366f1;
    }

    .icon-radio:checked + .icon-box span {
        color: #6366f1;
        font-weight: 600;
    }

    .color-radio:checked + .color-box {
        border-color: #1f2937;
        border-width: 3px;
        transform: scale(1.1);
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const descInput = document.getElementById('description');
        const iconRadios = document.querySelectorAll('.icon-radio');
        const colorRadios = document.querySelectorAll('.color-radio');
        
        const previewName = document.getElementById('previewName');
        const previewDesc = document.getElementById('previewDesc');
        const previewIcon = document.getElementById('previewIcon');
        const preview = document.getElementById('preview');

        const iconSvgs = {
            'folder': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
            'briefcase': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
            'chart': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
            'target': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'cog': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            'clipboard': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
        };

        let selectedColor = '#6366f1';

        // Update preview saat nama berubah
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Tugas Pribadi Saya';
        });

        // Update preview saat deskripsi berubah
        descInput.addEventListener('input', function() {
            previewDesc.textContent = this.value || 'Deskripsi ruang kerja akan muncul di sini';
        });

        // Update preview saat icon dipilih
        iconRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    previewIcon.innerHTML = iconSvgs[this.value];
                    updateIconColor();
                }
            });
        });

        // Update preview saat warna dipilih
        colorRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    selectedColor = this.value;
                    updateIconColor();
                    updatePreviewGradient();
                }
            });
        });

        function updateIconColor() {
            const svgElement = previewIcon.querySelector('svg');
            if (svgElement) {
                svgElement.style.color = selectedColor;
            }
            previewIcon.style.backgroundColor = selectedColor + '15';
        }

        function updatePreviewGradient() {
            preview.style.background = `linear-gradient(135deg, ${selectedColor}10 0%, ${selectedColor}05 100%)`;
        }

        // Set default jika ada old input
        const checkedIcon = document.querySelector('.icon-radio:checked');
        const checkedColor = document.querySelector('.color-radio:checked');
        
        if (checkedIcon) {
            previewIcon.innerHTML = iconSvgs[checkedIcon.value];
        }
        
        if (checkedColor) {
            selectedColor = checkedColor.value;
            updateIconColor();
            updatePreviewGradient();
        }

        // Update nama dan deskripsi jika ada old input
        if (nameInput.value) {
            previewName.textContent = nameInput.value;
        }
        if (descInput.value) {
            previewDesc.textContent = descInput.value;
        }
    });
    </script>
</x-app-layout>