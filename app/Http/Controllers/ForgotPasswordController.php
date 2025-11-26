<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password-custom');
    }

    public function submitRequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'status'  => 'pending',
        ]);

        return back()->with('success', 'Permintaan reset telah dikirim ke SuperAdmin.');
    }
}
