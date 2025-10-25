@extends('superadmin.layouts.superadmin')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Buat Kategori Baru</h1>
                <p class="text-sm sm:text-base text-gray-600">Tambahkan kategori baru untuk mengelompokkan pengguna</p>
            </div>
        </div>
        {{-- Info Global --}}
        <div class="mb-6 p-4 bg-purple-50 border-l-4 border-purple-500 rounded-lg mt-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-purple-900">Tampilan Admin</p>
                    <p class="text-xs text-purple-700 mt-0.5">Kategori yang Anda buat akan tersedia untuk semua pengguna</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm sm:text-base text-rose-800 font-medium">Terjadi kesalahan saat memvalidasi form:</p>
                </div>
                <ul class="mt-3 ml-8 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-rose-700">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <!-- Nama Kategori -->
            <div class="p-6 border-b border-gray-100">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required
                           value="{{ old('name') }}"
                           class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all bg-gray-50 hover:bg-white"
                           placeholder="Contoh: Programmer, Designer, Manager">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <p class="mt-2 text-sm text-gray-500">Nama kategori akan digunakan untuk mengelompokkan pengguna</p>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-3 p-6 bg-gray-50 border-t border-gray-100 rounded-b-2xl">
                <a href="{{ route('categories.index') }}" 
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-medium text-sm">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all font-medium text-sm shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Custom Animations --}}
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
@endsection