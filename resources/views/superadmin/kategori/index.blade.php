@extends('superadmin.layouts.superadmin')

@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Manajemen Kategori</h1>
                <p class="text-sm sm:text-base text-gray-600">
                    Kelola kategori yang dapat dipilih oleh pengguna dan admin
                </p>
            </div>
            <div>
                <a href="{{ route('categories.create') }}"
                   class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium text-sm">
                    Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-xl shadow-sm animate-fade-in">
            <p class="text-sm sm:text-base text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl shadow-sm animate-fade-in">
            <p class="text-sm sm:text-base text-rose-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Admin</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal Dibuat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                {{ $category->name }}
                            </td>

                            {{-- Jumlah User --}}
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $category->user_count ?? 0 }}
                            </td>

                            {{-- Jumlah Admin --}}
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $category->admin_count ?? 0 }}
                            </td>

                            {{-- Tanggal Dibuat --}}
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $category->created_at->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                        Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-all text-xs font-semibold shadow-sm hover:shadow-md">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-gray-500">
                                Belum ada kategori. Tambahkan kategori baru untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
