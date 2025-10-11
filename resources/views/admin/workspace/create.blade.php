@extends('admin.layouts.admin')

@section('content')
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('workspaces.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl sm:text-3xl text-gray-900">Create New Workspace</h2>
                <p class="text-sm text-gray-600 mt-1">Set up a new workspace to organize your tasks</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <form action="{{ route('workspaces.store') }}" method="POST" id="workspaceForm">
                    @csrf
                    
                    <div class="p-6 sm:p-8 space-y-6">
                        <!-- Workspace Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Workspace Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                                   placeholder="e.g., Web Development Tasks">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                                      placeholder="Brief description of this workspace...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Workspace Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Workspace Type <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="type" value="task" 
                                           class="peer sr-only" 
                                           checked>
                                    <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-gray-400 transition">
                                        <div class="text-2xl mb-2">✅</div>
                                        <div class="font-semibold text-gray-900">Tasks</div>
                                        <div class="text-xs text-gray-600 mt-1">Tasks only</div>
                                    </div>
                                </label>
                            </div>
                            @error('type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Icon Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Choose Icon <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-8 sm:grid-cols-12 gap-2">
                                @foreach($icons as $icon)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="icon" value="{{ $icon }}" 
                                           class="peer sr-only icon-radio"
                                           {{ old('icon', '📁') === $icon ? 'checked' : '' }}>
                                    <div class="w-full aspect-square flex items-center justify-center text-2xl border-2 border-gray-300 rounded-lg peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-gray-400 transition">
                                        {{ $icon }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('icon')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Color Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Choose Color <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-5 sm:grid-cols-10 lg:grid-cols-15 gap-3">
                                @foreach($colors as $colorValue => $colorName)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="color" value="{{ $colorValue }}" 
                                           class="peer sr-only color-radio"
                                           {{ old('color', '#6366f1') === $colorValue ? 'checked' : '' }}>
                                    <div class="w-full aspect-square rounded-lg transition-all peer-checked:ring-4 peer-checked:ring-offset-2 peer-checked:scale-110"
                                         style="background-color: {{ $colorValue }}; ring-color: {{ $colorValue }}40;">
                                    </div>
                                    <div class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        <span class="text-xs bg-gray-900 text-white px-2 py-1 rounded">{{ $colorName }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('color')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview Card -->
                        <div class="border-t pt-6">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Preview
                            </label>
                            <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden max-w-sm">
                                <div id="previewCard" class="p-5" style="background: linear-gradient(135deg, #6366f115 0%, #6366f105 100%);">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div id="previewIcon" class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shadow-sm" 
                                             style="background-color: #6366f120;">
                                            📁
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 id="previewName" class="font-semibold text-gray-900 truncate">
                                                Workspace Name
                                            </h4>
                                            <p id="previewType" class="text-xs text-gray-500 mt-0.5 capitalize">
                                                Task Workspace
                                            </p>
                                        </div>
                                    </div>
                                    <p id="previewDesc" class="text-sm text-gray-600 line-clamp-2">
                                        Your workspace description will appear here
                                    </p>
                                </div>
                                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Preview</span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="border-t border-gray-200 px-6 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <a href="{{ route('workspaces.index') }}" 
                               class="w-full sm:w-auto text-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">
                                Create Workspace
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
            const typeRadios = document.querySelectorAll('input[name="type"]');
            const iconRadios = document.querySelectorAll('.icon-radio');
            const colorRadios = document.querySelectorAll('.color-radio');
            
            const previewCard = document.getElementById('previewCard');
            const previewIcon = document.getElementById('previewIcon');
            const previewName = document.getElementById('previewName');
            const previewType = document.getElementById('previewType');
            const previewDesc = document.getElementById('previewDesc');

            let selectedColor = '#6366f1';
            let selectedIcon = '📁';

            // Update preview name
            nameInput.addEventListener('input', function() {
                previewName.textContent = this.value || 'Workspace Name';
            });

            // Update preview description
            descInput.addEventListener('input', function() {
                previewDesc.textContent = this.value || 'Your workspace description will appear here';
            });

            // Update preview type - always show "Task Workspace"
            typeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    previewType.textContent = 'Task Workspace';
                });
            });

            // Update preview icon
            iconRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedIcon = this.value;
                    previewIcon.textContent = selectedIcon;
                });
            });

            // Update preview color
            colorRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedColor = this.value;
                    updatePreviewColor();
                });
            });

            function updatePreviewColor() {
                previewCard.style.background = `linear-gradient(135deg, ${selectedColor}15 0%, ${selectedColor}05 100%)`;
                previewIcon.style.backgroundColor = `${selectedColor}20`;
            }

            // Initialize preview with selected values
            const selectedColorRadio = document.querySelector('.color-radio:checked');
            if (selectedColorRadio) {
                selectedColor = selectedColorRadio.value;
                updatePreviewColor();
            }

            const selectedIconRadio = document.querySelector('.icon-radio:checked');
            if (selectedIconRadio) {
                selectedIcon = selectedIconRadio.value;
                previewIcon.textContent = selectedIcon;
            }

            // Set type to "Task Workspace" as it's the only option
            previewType.textContent = 'Task Workspace';

            if (nameInput.value) {
                previewName.textContent = nameInput.value;
            }

            if (descInput.value) {
                previewDesc.textContent = descInput.value;
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