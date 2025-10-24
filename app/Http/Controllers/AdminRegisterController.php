<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminRegisterController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('auth.register-admin', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'category_id' => ['required', 'exists:categories,id'], // validasi kategori
        ]);

        // ✅ Simpan dengan category_id
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'category_id' => $request->category_id, // <-- tambahkan ini
        ]);


        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }
}
