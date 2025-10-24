<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class SuperAdminCategoryController extends Controller
{
    // Tampilkan daftar kategori
    public function index()
    {
         $categories = \App\Models\Category::withCount([
        'userUsers as user_count',
        'adminUsers as admin_count'
    ])->paginate(10);

    return view('superadmin.kategori.index', compact('categories'));
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

        $category->update(['name' => $request->name]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate.');
    }

    // Hapus kategori
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
