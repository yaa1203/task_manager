<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi pengguna.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Cek apakah user ada dan diblokir sebelum autentikasi
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if ($user && $user->is_blocked) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah diblokir oleh administrator. Silakan hubungi admin untuk informasi lebih lanjut.',
            ]);
        }

        // Autentikasi pengguna
        $request->authenticate();

        // Regenerasi session agar lebih aman
        $request->session()->regenerate();

        // Pastikan user terdeteksi (double check setelah authenticate)
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal login. Silakan coba lagi.',
            ]);
        }

        // Redirect sesuai role pengguna
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect()->route('dashboard'),
        };
    }

    /**
     * Tentukan redirect otomatis (fallback)
     */
    protected function redirectTo(): string
    {
        $user = Auth::user();

        if (!$user) {
            return route('login');
        }

        return match ($user->role) {
            'superadmin' => route('superadmin.dashboard'),
            'admin'      => route('admin.dashboard'),
            default      => route('dashboard'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
