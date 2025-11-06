<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        // Autentikasi pengguna
        $request->authenticate();

        // Regenerasi session agar lebih aman
        $request->session()->regenerate();

        $user = Auth::user();

        // Pastikan user terdeteksi
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
     * Logout dan hapus session.
     */
    // Di AuthenticatedSessionController.php
    public function destroy(Request $request)
    {
        // Bersihkan cache PWA
        if ($request->header('User-Agent') && 
            (strpos($request->header('User-Agent'), 'wv') !== false || 
            strpos($request->header('User-Agent'), 'Android') !== false)) {
            
            // Kirim pesan ke Service Worker untuk membersihkan cache
            $response = [
                'message' => 'Logged out successfully',
                'clear_pwa_cache' => true
            ];
        }
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return isset($response) ? response()->json($response) : redirect('/');
    }
}
