@extends('admin.layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('categories.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Kategori</h1>
                <p class="text-sm text-gray-600">Perbarui informasi kategori pengguna</p>
            </div>
        </div>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-800 mb-2">Ada beberapa kesalahan pada input Anda:</p>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Kategori -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       required
                       value="{{ old('name', $category->name) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Contoh: Programmer, Designer, Manager, dsb.">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('categories.index') }}" 
                   class="flex-1 px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg 
                          hover:bg-gray-200 transition text-center font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-1 px-6 py-2.5 bg-indigo-600 text-white rounded-lg 
                               hover:bg-indigo-700 transition font-medium">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
