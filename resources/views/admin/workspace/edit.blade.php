@extends('admin.layouts.admin')

@section('content')
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('workspaces.show', $workspace) }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl sm:text-3xl text-gray-900">Edit Workspace</h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui pengaturan workspace</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <form action="{{ route('workspaces.update', $workspace) }}" method="POST" id="workspaceForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 sm:p-8 space-y-6">
                        <!-- Nama Workspace -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Nama Workspace <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required
                                   value="{{ old('name', $workspace->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                                   placeholder="Contoh: Tugas Pengembangan Web">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                                      placeholder="Deskripsi singkat workspace ini...">{{ old('description', $workspace->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Workspace (Tersembunyi - Default ke Task) -->
                        <input type="hidden" name="type" value="task">

                        <!-- Pilihan Ikon - Ikon SVG Profesional -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Ikon <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 max-w-md">
                                @php
                                $icons = [
                                    'folder' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                                    'briefcase' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                    'chart' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                                    'target' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                    'cog' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                    'clipboard' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                                ];
                                @endphp
                                @foreach($icons as $iconName => $iconSvg)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="icon" value="{{ $iconName }}" 
                                           class="peer sr-only icon-radio"
                                           {{ old('icon', $workspace->icon) === $iconName ? 'checked' : '' }}>
                                    <div class="w-full aspect-square flex items-center justify-center border-2 border-gray-300 rounded-lg peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-gray-400 transition bg-white p-3 text-gray-600 peer-checked:text-indigo-600">
                                        {!! $iconSvg !!}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('icon')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pilihan Warna - Palet Profesional -->
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
                                    <input type="radio" name="color" value="{{ $colorValue }}" 
                                           class="peer sr-only color-radio"
                                           {{ old('color', $workspace->color) === $colorValue ? 'checked' : '' }}>
                                    <div class="w-12 h-12 rounded-lg transition-all peer-checked:ring-2 peer-checked:ring-offset-2 border-2 border-transparent peer-checked:border-white"
                                         style="background-color: {{ $colorValue }}; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    </div>
                                    <div class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                        <span class="text-xs bg-gray-900 text-white px-2 py-1 rounded">{{ $colorName }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('color')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kartu Pratinjau -->
                        <div class="border-t pt-6">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Pratinjau
                            </label>
                            <div class="bg-white rounded-lg border border-gray-300 overflow-hidden max-w-sm">
                                <div id="previewCard" class="p-5 border-l-4" style="border-color: {{ $workspace->color }}; background-color: {{ $workspace->color }}08;">
                                    <div class="flex items-start gap-3">
                                        <div id="previewIcon" class="w-10 h-10 flex items-center justify-center text-gray-600">
                                            @php
                                            $currentIconSvg = $icons[$workspace->icon] ?? $icons['folder'];
                                            @endphp
                                            {!! $currentIconSvg !!}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 id="previewName" class="font-semibold text-gray-900 mb-1">
                                                {{ $workspace->name }}
                                            </h4>
                                            <p id="previewDesc" class="text-sm text-gray-600 line-clamp-2">
                                                {{ $workspace->description ?: 'Deskripsi workspace Anda akan muncul di sini' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aksi Footer -->
                    <div class="border-t border-gray-200 px-6 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <a href="{{ route('workspaces.show', $workspace) }}" 
                               class="w-full sm:w-auto text-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Batal
                            </a>
                            <button type="submit"
                                    class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">
                                Perbarui Workspace
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const descInput = document.getElementById('description');
            const iconRadios = document.querySelectorAll('.icon-radio');
            const colorRadios = document.querySelectorAll('.color-radio');
            
            const previewCard = document.getElementById('previewCard');
            const previewIcon = document.getElementById('previewIcon');
            const previewName = document.getElementById('previewName');
            const previewDesc = document.getElementById('previewDesc');

            let selectedColor = '{{ $workspace->color }}';
            let selectedIcon = '{{ $workspace->icon }}';

            const iconSvgs = {
                'folder': '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                'briefcase': '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                'chart': '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                'target': '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'cog': '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                'clipboard': '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'
            };

            // Update pratinjau nama
            nameInput.addEventListener('input', function() {
                previewName.textContent = this.value || 'Nama Workspace';
            });

            // Update pratinjau deskripsi
            descInput.addEventListener('input', function() {
                previewDesc.textContent = this.value || 'Deskripsi workspace Anda akan muncul di sini';
            });

            // Update pratinjau ikon
            iconRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedIcon = this.value;
                    previewIcon.innerHTML = iconSvgs[selectedIcon];
                });
            });

            // Update pratinjau warna
            colorRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedColor = this.value;
                    updatePreviewColor();
                });
            });

            function updatePreviewColor() {
                previewCard.style.borderColor = selectedColor;
                previewCard.style.backgroundColor = selectedColor + '08';
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
    </style>
@endsection