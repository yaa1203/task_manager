<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SuperAdminRegisterController extends Controller
{
    /**
     * Show the superadmin registration form
     */
    public function create()
    {
        return view('auth.register-superadmin');
    }

    /**
     * Handle the registration request
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'superadmin', // 👈 role superadmin
        ]);

        Auth::login($user);

        return redirect()->route('superadmin.dashboard');
    }
}
