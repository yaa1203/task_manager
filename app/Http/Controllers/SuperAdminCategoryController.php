<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class SuperAdminCategoryController extends Controller
{
    // Tampilkan daftar kategori
    public function index(Request $request)
    {
        // Ambil nilai sorting dari request, default 'created_at'
        $sortBy = $request->input('sort_by', 'created_at');
        
        // Ambil nilai pencarian dari request
        $search = $request->input('search');
        
        // Query dasar dengan eager loading untuk count
        $query = Category::withCount([
            'userUsers as user_count',
            'adminUsers as admin_count'
        ]);
        
        // Terapkan filter pencarian jika ada
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        
        // Terapkan sorting
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'admin_count':
                $query->orderBy('admin_count', 'desc');
                break;
            case 'user_count':
                $query->orderBy('user_count', 'desc');
                break;
            default: // created_at
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Pagination
        $categories = $query->paginate(10);
        
        // Kirim data ke view
        return view('superadmin.kategori.index', compact('categories', 'sortBy', 'search'));
    }

    // Tampilkan form buat kategori baru
    public function create()
    {
        return view('superadmin.kategori.create');
    }

    // Simpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Tampilkan form edit kategori
    public function edit(Category $category)
    {
        return view('superadmin.kategori.edit', compact('category'));
    }

    // Update kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate.');
    }

    // Hapus kategori
    public function destroy(Category $category)
    {
        // Cek apakah ada admin atau user yang terkait dengan kategori ini
        if ($category->adminUsers()->count() > 0 || $category->userUsers()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Tidak dapat menghapus kategori ini karena masih terdapat admin atau user yang terkait.');
        }
        
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}