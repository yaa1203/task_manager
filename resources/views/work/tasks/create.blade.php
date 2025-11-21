<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('my-workspaces.show', $workspace) }}" 
               class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Buat Tugas Baru</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $workspace->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                
                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800 mb-2">Ada beberapa kesalahan pada pengisian Anda:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('my-workspaces.tasks.store', $workspace) }}" method="POST" enctype="multipart/form-data" id="taskForm">
                    @csrf

                    {{-- Judul Tugas --}}
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Tugas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               required
                               value="{{ old('title') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="Masukkan judul tugas">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-5">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Masukkan deskripsi tugas (opsional)">{{ old('description') }}</textarea>
                    </div>

                    {{-- Unggah File --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lampiran
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk mengunggah</span> atau seret dan lepas</p>
                                    <p class="text-xs text-gray-500">Ukuran maksimal: 10MB</p>
                                </div>
                                <input id="file" name="file" type="file" class="hidden" onchange="displayFileName(this)" />
                            </label>
                        </div>
                        <p id="file-name" class="mt-2 text-sm text-gray-600 hidden"></p>
                    </div>

                    {{-- Tautan --}}
                    <div class="mb-5">
                        <label for="link" class="block text-sm font-medium text-gray-700 mb-2">
                            Tautan
                        </label>
                        <input type="url" 
                               name="link" 
                               id="link"
                               value="{{ old('link') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="https://contoh.com">
                    </div>

                    {{-- Hidden Status --}}
                    <input type="hidden" name="status" value="todo">

                    {{-- Prioritas --}}
                    <div class="mb-5">
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Prioritas <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" 
                                id="priority" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Segera</option>
                        </select>
                    </div>

                    {{-- Tanggal dan Waktu Batas --}}
                    <div class="mb-6">
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal dan Waktu Batas <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="due_date_date" class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                                <input type="date" 
                                       name="due_date_date" 
                                       id="due_date_date"
                                       required
                                       value="{{ old('due_date_date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="due_date_time" class="block text-xs font-medium text-gray-500 mb-1">Waktu</label>
                                <input type="time" 
                                       name="due_date_time" 
                                       id="due_date_time"
                                       value="{{ old('due_date_time', '23:59') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <input type="hidden" 
                               name="due_date" 
                               id="due_date_hidden"
                               value="{{ old('due_date') }}">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('my-workspaces.show', $workspace) }}" 
                           class="flex-1 px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center font-medium">
                            Batal
                        </a>
                        <button type="submit" 
                                class="flex-1 px-6 py-2.5 text-white rounded-lg transition font-medium"
                                style="background-color: {{ $workspace->color }}; hover:opacity-90;">
                            Buat Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Menampilkan nama file yang dipilih
    function displayFileName(input) {
        const fileNameDisplay = document.getElementById('file-name');
        if (input.files && input.files[0]) {
            const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
            fileNameDisplay.textContent = '📎 ' + input.files[0].name + ' (' + fileSize + ' MB)';
            fileNameDisplay.classList.remove('hidden');
        } else {
            fileNameDisplay.classList.add('hidden');
        }
    }

    // Fungsi untuk menggabungkan tanggal dan waktu
    function combineDateTime() {
        const dateInput = document.getElementById('due_date_date');
        const timeInput = document.getElementById('due_date_time');
        const hiddenInput = document.getElementById('due_date_hidden');
        
        if (dateInput.value && timeInput.value) {
            // Format: YYYY-MM-DD HH:mm:00
            hiddenInput.value = dateInput.value + ' ' + timeInput.value + ':00';
        } else if (dateInput.value && !timeInput.value) {
            // Jika hanya tanggal yang diisi, set waktu default 23:59:00
            hiddenInput.value = dateInput.value + ' 23:59:00';
        } else {
            hiddenInput.value = '';
        }
    }

    // Event listener untuk input tanggal dan waktu
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('due_date_date');
        const timeInput = document.getElementById('due_date_time');
        const hiddenInput = document.getElementById('due_date_hidden');
        
        // Jika ada nilai hidden yang sudah ada (dari old input), pisahkan ke date dan time
        if (hiddenInput.value) {
            const dateTime = hiddenInput.value.split(' ');
            if (dateTime.length === 2) {
                dateInput.value = dateTime[0];
                timeInput.value = dateTime[1].substring(0, 5); // Ambil HH:mm
            }
        }
        
        // Tambahkan event listener
        dateInput.addEventListener('change', combineDateTime);
        timeInput.addEventListener('change', combineDateTime);
    });

    // Form submit handler
    document.getElementById('taskForm').addEventListener('submit', function(e) {
        const dateInput = document.getElementById('due_date_date');
        const timeInput = document.getElementById('due_date_time');
        const hiddenInput = document.getElementById('due_date_hidden');
        
        // Gabungkan tanggal dan waktu sebelum submit
        if (dateInput.value) {
            if (timeInput.value) {
                hiddenInput.value = dateInput.value + ' ' + timeInput.value + ':00';
            } else {
                // Jika waktu tidak diisi, gunakan waktu default
                hiddenInput.value = dateInput.value + ' 23:59:00';
            }
        } else {
            e.preventDefault();
            alert('Silakan pilih tanggal batas tugas.');
            return false;
        }
        
        // Validasi tanggal batas tidak di masa lalu
        const selectedDate = new Date(dateInput.value + ' ' + (timeInput.value || '23:59'));
        const now = new Date();
        
        if (selectedDate < now) {
            e.preventDefault();
            alert('Tanggal batas tidak boleh di masa lalu.');
            return false;
        }
    });
    </script>
</x-app-layout>