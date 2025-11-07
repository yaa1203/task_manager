@extends('admin.layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('workspaces.show', $workspace) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Tugas</h1>
                <p class="text-sm text-gray-600">di {{ $workspace->name }}</p>
            </div>
        </div>
    </div>

    <!-- Kartu Form -->
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

        <form action="{{ route('workspace.tasks.update', [$workspace, $task]) }}" 
              method="POST" enctype="multipart/form-data" id="taskForm">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Tugas <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       required
                       value="{{ old('title', $task->title) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Deskripsi -->
            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $task->description) }}</textarea>
            </div>

            <!-- Unggah File -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Lampiran (Opsional)
                </label>

                @if ($task->file_path)
                <div class="mb-3 flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <div>
                        <p class="text-sm text-gray-700">File saat ini:</p>
                        <a href="{{ asset('storage/' . $task->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">
                            {{ $task->original_filename ?? basename($task->file_path) }}
                        </a>
                    </div>
                    <label class="flex items-center text-sm text-red-600 cursor-pointer">
                        <input type="checkbox" name="remove_file" value="1" class="mr-2">
                        Hapus file
                    </label>
                </div>
                @endif

                <div class="flex items-center justify-center w-full">
                    <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk mengunggah</span> atau seret dan lepas</p>
                            <p class="text-xs text-gray-500">Ukuran maksimal file: 10MB</p>
                        </div>
                        <input id="file" name="file" type="file" class="hidden" onchange="displayFileName(this)" />
                    </label>
                </div>
                <p id="file-name" class="mt-2 text-sm text-gray-600 hidden"></p>
            </div>

            <!-- Tautan -->
            <div class="mb-5">
                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">
                    Tautan (Opsional)
                </label>
                <input type="url" 
                       name="link" 
                       id="link"
                       value="{{ old('link', $task->link) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="https://contoh.com">
            </div>

            <!-- Berikan Kepada -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Berikan Kepada <span class="text-red-500">*</span>
                </label>
                
                <!-- Info Badge Kategori -->
                <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-blue-800">
                            Menampilkan pengguna kategori: <span class="font-bold">{{ $categoryName }}</span>
                        </span>
                    </div>
                </div>
                
                <!-- Peringatan tentang pengguna yang diblokir -->
                <div class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <span class="text-amber-800">
                            Pengguna yang diblokir tidak akan ditampilkan dalam daftar pilihan.
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="flex items-center gap-2 cursor-pointer p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                        <input type="checkbox" id="assign_to_all" onchange="toggleUserSelection(this)"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <div class="flex-1">
                            <span class="text-sm font-medium text-gray-900">Berikan ke semua tim {{ $categoryName }}</span>
                            <p class="text-xs text-gray-600 mt-0.5">Tugaskan ke {{ $users->count() }} pengguna yang terdaftar</p>
                        </div>
                    </label>
                </div>

                @if($users->count() > 0)
                <div id="user-selection" class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto">
                    <div class="mb-2 pb-2 border-b border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase">{{ $users->count() }} Pengguna Tersedia</p>
                    </div>
                    @foreach($users as $user)
                    <label class="flex items-center gap-2 py-2 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" 
                            name="user_ids[]" 
                            value="{{ $user->id }}"
                            {{ $task->assignedUsers->contains($user->id) ? 'checked' : '' }}
                            class="user-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <div class="flex items-center gap-2 flex-1">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-semibold text-indigo-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                            </div>
                            <!-- Status User -->
                            <div class="flex items-center gap-1">
                                @if($user->is_blocked)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Diblokir
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Aktif
                                </span>
                                @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @else
                <div class="border border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-900 mb-1">Tidak ada pengguna tersedia</p>
                    <p class="text-xs text-gray-600">Belum ada pengguna dalam kategori {{ $categoryName }}</p>
                </div>
                @endif
            </div>

            <!-- Status Tersembunyi -->
            <input type="hidden" name="status" value="{{ old('status', $task->status ?? 'todo') }}">

            <!-- Prioritas -->
            <div class="mb-5">
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                    Prioritas <span class="text-red-500">*</span>
                </label>
                <select name="priority" id="priority" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                    <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Segera</option>
                </select>
            </div>

            <!-- Tanggal dan Waktu Batas -->
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
                               value="{{ old('due_date_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label for="due_date_time" class="block text-xs font-medium text-gray-500 mb-1">Waktu</label>
                        <input type="time" 
                               name="due_date_time" 
                               id="due_date_time"
                               value="{{ old('due_date_time', $task->due_date ? $task->due_date->format('H:i') : '23:59') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <input type="hidden" 
                       name="due_date" 
                       id="due_date_hidden"
                       value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d H:i:s') : '') }}">
            </div>

            <!-- Aksi -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('workspaces.show', $workspace) }}" 
                   class="flex-1 px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-1 px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Perbarui Tugas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Script untuk form EDIT TASK
// Letakkan di bagian bawah file edit.blade.php

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

function toggleUserSelection(checkbox) {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const userSelection = document.getElementById('user-selection');
    if (checkbox.checked) {
        userCheckboxes.forEach(cb => { 
            cb.checked = true; 
            cb.disabled = true; 
        });
        userSelection.classList.add('opacity-50', 'pointer-events-none');
    } else {
        userCheckboxes.forEach(cb => { 
            cb.checked = false; 
            cb.disabled = false; 
        });
        userSelection.classList.remove('opacity-50', 'pointer-events-none');
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
        console.log('Combined DateTime:', hiddenInput.value); // Debug
    } else if (dateInput.value && !timeInput.value) {
        // Jika hanya tanggal yang diisi, set waktu default 23:59:00
        hiddenInput.value = dateInput.value + ' 23:59:00';
        console.log('Date only, set default time:', hiddenInput.value); // Debug
    } else {
        hiddenInput.value = '';
    }
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('due_date_date');
    const timeInput = document.getElementById('due_date_time');
    const hiddenInput = document.getElementById('due_date_hidden');
    
    console.log('Initial hidden value:', hiddenInput.value); // Debug
    
    // Jika ada nilai hidden yang sudah ada, coba pisahkan ke date dan time
    if (hiddenInput.value) {
        const dateTime = hiddenInput.value.split(' ');
        if (dateTime.length === 2) {
            dateInput.value = dateTime[0];
            timeInput.value = dateTime[1].substring(0, 5); // Ambil HH:mm
            console.log('Parsed date:', dateInput.value, 'time:', timeInput.value); // Debug
        }
    }
    
    // Tambahkan event listener untuk input tanggal dan waktu
    dateInput.addEventListener('change', combineDateTime);
    timeInput.addEventListener('change', combineDateTime);
});

// Form submit handler
document.getElementById('taskForm').addEventListener('submit', function(e) {
    const assignToAllCheckbox = document.getElementById('assign_to_all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const dateInput = document.getElementById('due_date_date');
    const timeInput = document.getElementById('due_date_time');
    const hiddenInput = document.getElementById('due_date_hidden');
    
    // PENTING: Gabungkan tanggal dan waktu sebelum submit
    if (dateInput.value) {
        if (timeInput.value) {
            hiddenInput.value = dateInput.value + ' ' + timeInput.value + ':00';
        } else {
            // Jika waktu tidak diisi, gunakan waktu default
            hiddenInput.value = dateInput.value + ' 23:59:00';
        }
        console.log('Final DateTime before submit:', hiddenInput.value); // Debug
    }
    
    // Handle assign to all
    if (assignToAllCheckbox.checked) {
        userCheckboxes.forEach(cb => {
            cb.disabled = false; // Enable agar value terkirim
            cb.checked = false;
        });
    }
    
    // Validasi minimal satu user dipilih
    if (!assignToAllCheckbox.checked) {
        let anyChecked = false;
        userCheckboxes.forEach(cb => {
            if (cb.checked) anyChecked = true;
        });
        
        if (!anyChecked) {
            e.preventDefault();
            alert('Silakan pilih setidaknya satu pengguna untuk diberi tugas.');
            return false;
        }
    }
    
    // Validasi tanggal batas tidak di masa lalu
    if (dateInput.value) {
        const selectedDate = new Date(dateInput.value + ' ' + (timeInput.value || '23:59'));
        const now = new Date();
        
        if (selectedDate < now) {
            e.preventDefault();
            alert('Tanggal batas tidak boleh di masa lalu.');
            return false;
        }
    }
});
</script>
@endsection